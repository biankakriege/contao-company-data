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
use Contao\ModuleModel;
use BiankaKriege\ContaoCompanyData\Helper\DataHelper;
use BiankaKriege\ContaoCompanyData\JsonLd\CompanySchemaFactory;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(self::TYPE, category: 'bk_company')]
class BkCompanyContactModuleController extends AbstractFrontendModuleController
{
    public const TYPE = 'bk_company_contact';

    public function __construct(
        private readonly DataHelper $dataHelper,
        private readonly CompanySchemaFactory $schemaFactory,
    ) {
    }

    protected function getResponse($template, ModuleModel $model, Request $request): Response
    {
        $company = CompanyModel::findById($model->bkCompanyId);

        if (null !== $company) {
            $this->dataHelper->getCompanyContact($company, $model, $template);
            $this->schemaFactory->addToGraph($this->schemaFactory->createOrganization($company));
        }

        return $template->getResponse();
    }
}