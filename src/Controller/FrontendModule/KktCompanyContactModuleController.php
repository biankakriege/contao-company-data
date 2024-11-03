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
use Contao\ModuleModel;
use BiankaKriege\ContaoCompanyData\Helper\DataHelper;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(self::TYPE, category: 'kkt_company')]
class KktCompanyContactModuleController extends AbstractFrontendModuleController
{
    public const TYPE = 'kkt_company_contact';

    public function __construct(
        private readonly DataHelper $dataHelper,
    ) {
    }

    protected function getResponse($template, ModuleModel $model, Request $request): Response
    {
        $company = CompanyModel::findById($model->companyId);
        $this->dataHelper->getCompanyContact($company, $model, $template);

        return $template->getResponse();
    }
}