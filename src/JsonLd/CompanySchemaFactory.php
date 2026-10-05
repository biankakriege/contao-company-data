<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\JsonLd;

use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;
use Contao\CoreBundle\Routing\ResponseContext\JsonLd\JsonLdManager;
use Contao\CoreBundle\Routing\ResponseContext\ResponseContext;
use Contao\CoreBundle\Routing\ResponseContext\ResponseContextAccessor;
use Contao\CoreBundle\String\HtmlDecoder;
use Contao\FilesModel;
use Contao\StringUtil;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\Corporation;
use Spatie\SchemaOrg\LocalBusiness;
use Spatie\SchemaOrg\Organization;
use Spatie\SchemaOrg\Person;
use Spatie\SchemaOrg\PostalAddress;
use Spatie\SchemaOrg\ProfessionalService;
use Spatie\SchemaOrg\PropertyValue;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class CompanySchemaFactory
{
    public const TYPES = ['Organization', 'Corporation', 'LocalBusiness', 'ProfessionalService'];

    public function __construct(
        private RequestStack $requestStack,
        private ResponseContextAccessor $responseContextAccessor,
        private HtmlDecoder $htmlDecoder,
    ) {
    }

    public function addToGraph(BaseType $schema, ResponseContext|null $responseContext = null): void
    {
        $responseContext ??= $this->responseContextAccessor->getResponseContext();

        if (!$responseContext?->has(JsonLdManager::class)) {
            return;
        }

        $responseContext
            ->get(JsonLdManager::class)
            ->getGraphForSchema(JsonLdManager::SCHEMA_ORG)
            ->set($schema, $schema->getProperty('@id'))
        ;
    }

    public function createOrganization(CompanyModel $company): BaseType
    {
        $schema = $this->createOrganizationType($company);

        $this->set($schema, 'name', $company->name);
        $this->set($schema, 'legalName', $company->imprintName);
        $this->set($schema, 'description', $company->schemaDescription);
        $this->set($schema, 'url', $this->normalizeUrl($company->website) ?? $this->getBaseUrl().'/');
        $this->set($schema, 'email', $company->email);
        $this->set($schema, 'telephone', $this->formatPhone($company->phone, $company->country));
        $this->set($schema, 'faxNumber', $this->formatPhone($company->fax, $company->country));
        $this->set($schema, 'vatID', $company->imprintVatIdentificationNumber);
        $this->set($schema, 'taxID', $company->imprintTaxIdentificationNumber);

        if ($logo = $this->getFileUrl($company->singleSRC)) {
            $schema->setProperty('logo', $logo);
            $schema->setProperty('image', $logo);
        }

        if ($address = $this->createAddress($company->street, $company->postal, $company->city, $company->country)) {
            $schema->setProperty('address', $address);
        }

        if ($company->imprintPublicRegistryNumber) {
            $registry = (new PropertyValue())->value($this->plain($company->imprintPublicRegistryNumber));
            $this->set($registry, 'propertyID', $company->imprintPublicRegistry);
            $schema->setProperty('identifier', $registry);
        }

        $sameAs = array_values(array_filter(array_map(
            $this->normalizeUrl(...),
            StringUtil::deserialize($company->sameAs, true),
        )));

        if ($sameAs) {
            $schema->setProperty('sameAs', $sameAs);
        }

        return $schema;
    }

    public function createPerson(PersonModel $person, array $fields, CompanyModel|null $company = null): Person
    {
        $schema = (new Person())->setProperty('@id', $this->getBaseUrl().'/#/schema/person/'.$person->id);
        $schema->setProperty('name', $this->plain($person->name));
        $has = static fn (string $field): bool => \in_array($field, $fields, true);

        if ($has('title')) {
            $this->set($schema, 'honorificPrefix', $person->title);
        }

        if ($has('position')) {
            $this->set($schema, 'jobTitle', $person->position);
        }

        if ($has('email')) {
            $this->set($schema, 'email', $person->email);
        }

        if ($has('phone')) {
            $this->set($schema, 'telephone', $this->formatPhone($person->phone, $person->country ?: $company?->country));
        }

        if ($has('fax')) {
            $this->set($schema, 'faxNumber', $this->formatPhone($person->fax, $person->country ?: $company?->country));
        }

        if ($has('website')) {
            $this->set($schema, 'url', $this->normalizeUrl($person->website));
        }

        if ($has('singleSRC')) {
            $this->set($schema, 'image', $this->getFileUrl($person->singleSRC));
        }

        $address = $this->createAddress(
            $has('street') ? $person->street : null,
            $has('postal') ? $person->postal : null,
            $has('city') ? $person->city : null,
            $has('country') ? $person->country : null,
        );

        if ($address) {
            $schema->setProperty('address', $address);
        }

        if ($company) {
            $schema->setProperty('worksFor', $this->createOrganizationType($company));
        }

        return $schema;
    }

    private function createOrganizationType(CompanyModel $company): BaseType
    {
        $schema = match ($company->schemaType) {
            'Corporation' => new Corporation(),
            'LocalBusiness' => new LocalBusiness(),
            'ProfessionalService' => new ProfessionalService(),
            default => new Organization(),
        };

        return $schema->setProperty('@id', $this->getBaseUrl().'/#/schema/organization/'.$company->id);
    }

    private function createAddress(mixed $street, mixed $postal, mixed $city, mixed $country): PostalAddress|null
    {
        if (!$street && !$postal && !$city && !$country) {
            return null;
        }

        $address = new PostalAddress();
        $this->set($address, 'streetAddress', $street);
        $this->set($address, 'postalCode', $postal);
        $this->set($address, 'addressLocality', $city);
        $this->set($address, 'addressCountry', $country ? strtoupper((string) $country) : null);

        return $address;
    }

    private function set(BaseType $schema, string $property, mixed $value): void
    {
        if (null !== $value && '' !== $value) {
            $schema->setProperty($property, $this->plain((string) $value));
        }
    }

    private function plain(string $value): string
    {
        return $this->htmlDecoder->inputEncodedToPlainText($value);
    }

    private function formatPhone(string|null $number, string|null $country): string|null
    {
        if (!$number) {
            return null;
        }

        try {
            $util = PhoneNumberUtil::getInstance();

            return $util->format($util->parse($number, strtoupper($country ?: 'DE')), PhoneNumberFormat::E164);
        } catch (NumberParseException) {
            return $number;
        }
    }

    private function normalizeUrl(string|null $url): string|null
    {
        $url = trim((string) $url);

        if ('' === $url) {
            return null;
        }

        if (!preg_match('~^https?://~i', $url)) {
            $url = 'https://'.$url;
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }

    private function getFileUrl(string|null $uuid): string|null
    {
        if (!$uuid || !$file = FilesModel::findByUuid($uuid)) {
            return null;
        }

        return $this->getBaseUrl().'/'.implode('/', array_map(rawurlencode(...), explode('/', $file->path)));
    }

    private function getBaseUrl(): string
    {
        return $this->requestStack->getMainRequest()?->getSchemeAndHttpHost() ?? '';
    }
}