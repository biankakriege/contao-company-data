<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\EventListener;

use BiankaKriege\ContaoCompanyData\Helper\WebsiteCompanyFinder;
use BiankaKriege\ContaoCompanyData\JsonLd\CompanySchemaFactory;
use Contao\CoreBundle\Event\JsonLdEvent;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\Routing\ResponseContext\JsonLd\JsonLdManager;
use Contao\CoreBundle\Util\LocaleUtil;
use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\WebPage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Enriches the empty WebPage node that Contao adds to every schema.org graph.
 */
#[AsEventListener]
final readonly class WebPageJsonLdListener
{
    public function __construct(
        private RequestStack $requestStack,
        private WebsiteCompanyFinder $companyFinder,
        private CompanySchemaFactory $schemaFactory,
    ) {
    }

    public function __invoke(JsonLdEvent $event): void
    {
        $context = $event->getResponseContext();
        $request = $this->requestStack->getMainRequest();

        if (!$request || !$context->has(JsonLdManager::class) || !$context->has(HtmlHeadBag::class)) {
            return;
        }

        $graph = $context->get(JsonLdManager::class)->getGraphForSchema(JsonLdManager::SCHEMA_ORG);

        if (!$graph->has(WebPage::class)) {
            return;
        }

        $webPage = $graph->get(WebPage::class);
        $headBag = $context->get(HtmlHeadBag::class);
        $url = $headBag->getCanonicalUriForRequest($request);

        $this->set($webPage, '@id', $url.'#webpage');
        $this->set($webPage, 'url', $url);
        $this->set($webPage, 'name', $headBag->getTitle());
        $this->set($webPage, 'description', $headBag->getMetaDescription());
        $this->set($webPage, 'inLanguage', LocaleUtil::formatAsLanguageTag($request->getLocale()));

        if ($company = $this->companyFinder->find()) {
            $this->set($webPage, 'publisher', $this->schemaFactory->createOrganizationReference($company));
        }
    }

    /**
     * Never overwrite values set by the Contao core or other extensions.
     */
    private function set(BaseType $schema, string $property, mixed $value): void
    {
        if (null !== $value && '' !== $value && null === $schema->getProperty($property)) {
            $schema->setProperty($property, $value);
        }
    }
}
