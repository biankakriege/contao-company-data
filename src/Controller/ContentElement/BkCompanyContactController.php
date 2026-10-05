<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Twig\FragmentTemplate;
use BiankaKriege\ContaoCompanyData\Helper\DataHelper;
use BiankaKriege\ContaoCompanyData\JsonLd\CompanySchemaFactory;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(self::TYPE, category: 'bk_company')]
class BkCompanyContactController extends AbstractContentElementController
{
    public const TYPE = 'bk_company_contact';

    public function __construct(
        private readonly DataHelper $dataHelper,
        private readonly CompanySchemaFactory $schemaFactory,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $company = CompanyModel::findById($model->bkCompanyId);

        if (null !== $company) {
            $this->dataHelper->getCompanyContact($company, $model, $template);
            $this->schemaFactory->addToGraph($this->schemaFactory->createOrganization($company));
        }

        return $template->getResponse();
    }
}