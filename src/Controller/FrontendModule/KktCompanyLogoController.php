<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Contao\PageModel;
use BiankaKriege\ContaoCompanyData\Helper\ImageHelper;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(self::TYPE, category: 'kkt_company')]
class KktCompanyLogoController extends AbstractFrontendModuleController
{
    public const TYPE = 'kkt_company_logo';

    public function __construct(
        private readonly ImageHelper $imageHelper,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $company = CompanyModel::findById($model->companyId);
        if (null !== $company) {
            $template->logo = $this->imageHelper->getImage($company->singleSRC, $model->imgSize);
            // Verlinkung
            global $objPage;
            $objPageLink = PageModel::findById($model->rootPage);
            $template->link = $objPageLink->alias;
            $template->linkTitle = $objPageLink->title;
            if ($this->shouldResetLink($objPageLink)) {
                $template->link = '';
            }
            if ('index' === $objPage->alias) {
                $template->link = 1;
            }
        }

        return $template->getResponse();
    }

    /**
     * Determines whether the given page link should be reset.
     *
     * @param object $objPageLink the page link object to check
     *
     * @return bool returns true if the page link should be reset, false otherwise
     */
    protected function shouldResetLink(object $objPageLink): bool
    {
        return 'root' === $objPageLink->type || 'index' === $objPageLink->alias;
    }
}