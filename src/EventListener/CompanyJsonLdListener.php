<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\EventListener;

use BiankaKriege\ContaoCompanyData\JsonLd\CompanySchemaFactory;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use Contao\CoreBundle\Event\JsonLdEvent;
use Contao\CoreBundle\Routing\PageFinder;
use Contao\PageModel;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final readonly class CompanyJsonLdListener
{
    public function __construct(
        private PageFinder $pageFinder,
        private CompanySchemaFactory $schemaFactory,
    ) {
    }

    public function __invoke(JsonLdEvent $event): void
    {
        $page = $this->pageFinder->getCurrentPage();

        if (null === $page) {
            return;
        }

        $rootPage = PageModel::findById($page->loadDetails()->rootId);

        if (null === $rootPage || !$rootPage->bkCompanyId) {
            return;
        }

        if ($company = CompanyModel::findById($rootPage->bkCompanyId)) {
            $this->schemaFactory->addToGraph($this->schemaFactory->createOrganization($company), $event->getResponseContext());
        }
    }
}