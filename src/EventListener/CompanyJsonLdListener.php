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
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final readonly class CompanyJsonLdListener
{
    public function __construct(
        private WebsiteCompanyFinder $companyFinder,
        private CompanySchemaFactory $schemaFactory,
    ) {
    }

    public function __invoke(JsonLdEvent $event): void
    {
        if ($company = $this->companyFinder->find()) {
            $this->schemaFactory->addToGraph($this->schemaFactory->createOrganization($company), $event->getResponseContext());
        }
    }
}