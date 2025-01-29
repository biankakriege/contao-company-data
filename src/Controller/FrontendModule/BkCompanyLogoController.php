<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2025, Bianka Kriege
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

#[AsFrontendModule(self::TYPE, category: 'bk_company')]
class BkCompanyLogoController extends AbstractFrontendModuleController
{
    public const TYPE = 'bk_company_logo';

    public function __construct(
        private readonly ImageHelper $imageHelper,
    )
    {
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $company = CompanyModel::findById($model->bkCompanyId);

        if (null !== $company) {
            $template->logo = $this->imageHelper->getImage($company->singleSRC, $model->imgSize);

            $objPageLink = PageModel::findById($model->rootPage);
            $template->link = $objPageLink->alias;
            $template->linkTitle = $objPageLink->title;

            if ($this->shouldResetLink($objPageLink)) {
                $template->link = '';
            }

            if ('index' === $this->getPageModel()->alias) {
                $template->link = 1;
            }
        }

        return $template->getResponse();
    }

    protected function shouldResetLink(object $objPageLink): bool
    {
        return 'root' === $objPageLink->type || 'index' === $objPageLink->alias;
    }
}
