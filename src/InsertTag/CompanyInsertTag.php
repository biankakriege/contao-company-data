<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\InsertTag;

use BiankaKriege\ContaoCompanyData\Helper\WebsiteCompanyFinder;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\Exception\InvalidInsertTagException;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\Intl\Countries;
use Contao\FilesModel;
use Contao\Model;

final readonly class CompanyInsertTag
{
    private const COMPANY_FIELDS = [
        'name', 'street', 'postal', 'city', 'phone', 'fax', 'email', 'website',
        'imprintName', 'imprintRepresentative', 'imprintPublicRegistry', 'imprintPublicRegistryNumber',
        'imprintFinancialDistrict', 'imprintVatIdentificationNumber', 'imprintTaxIdentificationNumber',
        'imprintEconomyIdentificationNumber', 'schemaDescription',
    ];

    private const PERSON_FIELDS = [
        'title', 'name', 'position', 'department', 'street', 'postal', 'city',
        'phone', 'mobile', 'fax', 'email', 'website',
    ];

    public function __construct(
        private WebsiteCompanyFinder $companyFinder,
        private Countries $countries,
    ) {
    }

    #[AsInsertTag('company')]
    public function company(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $field = $this->getField($insertTag);
        $id = $insertTag->getParameters()->get(1);
        $company = null !== $id ? CompanyModel::findByIdOrAlias($id) : $this->companyFinder->find();

        if (null === $company) {
            return new InsertTagResult('');
        }

        $value = match ($field) {
            'logo' => $this->getFilePath($company->singleSRC),
            default => $this->getValue($company, $field, self::COMPANY_FIELDS),
        };

        return new InsertTagResult($value, cacheTags: ['contao.db.'.CompanyModel::TABLE.'.'.$company->id]);
    }

    #[AsInsertTag('company_person')]
    public function person(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $field = $this->getField($insertTag);
        $id = $insertTag->getParameters()->get(1);

        if (null === $id) {
            throw new InvalidInsertTagException('Missing person alias or ID for company_person insert tag.');
        }

        $person = PersonModel::findByIdOrAlias($id);

        if (null === $person) {
            return new InsertTagResult('');
        }

        $cacheTags = ['contao.db.'.PersonModel::TABLE.'.'.$person->id];

        // Keep the cache tag, so the page is refreshed once the person gets published
        if (!$person->published) {
            return new InsertTagResult('', cacheTags: $cacheTags);
        }

        $value = match ($field) {
            'fullname' => trim($person->title.' '.$person->name),
            'image' => $this->getFilePath($person->singleSRC),
            default => $this->getValue($person, $field, self::PERSON_FIELDS),
        };

        return new InsertTagResult($value, cacheTags: $cacheTags);
    }

    private function getField(ResolvedInsertTag $insertTag): string
    {
        $field = $insertTag->getParameters()->get(0);

        if (null === $field) {
            throw new InvalidInsertTagException(\sprintf('Missing field for %s insert tag.', $insertTag->getName()));
        }

        return $field;
    }

    private function getValue(Model $model, string $field, array $allowed): string
    {
        return match ($field) {
            'address' => implode(', ', array_filter([$model->street, trim($model->postal.' '.$model->city)])),
            'country' => $model->country ? ($this->countries->getCountries()[strtoupper($model->country)] ?? '') : '',
            default => \in_array($field, $allowed, true) ? (string) $model->{$field} : '',
        };
    }

    private function getFilePath(string|null $uuid): string
    {
        return $uuid ? (FilesModel::findByUuid($uuid)?->path ?? '') : '';
    }
}