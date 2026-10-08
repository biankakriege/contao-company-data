<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Helper;

use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use Contao\CoreBundle\Routing\PageFinder;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class WebsiteCompanyFinder
{
    public function __construct(
        private PageFinder $pageFinder,
        private RequestStack $requestStack,
    ) {
    }

    public function find(): CompanyModel|null
    {
        $page = $this->getCurrentPage();

        if (null === $page) {
            return null;
        }

        $rootPage = PageModel::findById($page->loadDetails()->rootId);

        return $rootPage?->bkCompanyId ? CompanyModel::findById($rootPage->bkCompanyId) : null;
    }

    private function getCurrentPage(): PageModel|null
    {
        // Contao 5.7+
        if (method_exists($this->pageFinder, 'getCurrentPage')) {
            return $this->pageFinder->getCurrentPage();
        }

        // TODO: remove once Contao 5.3 is no longer supported
        $page = $this->requestStack->getCurrentRequest()?->attributes->get('pageModel');

        return $page instanceof PageModel ? $page : null;
    }
}
