<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Controller\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use BiankaKriege\ContaoCompanyData\Helper\DataHelper;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(self::TYPE, category: 'kkt_company')]
class KktCompanyContactController extends AbstractContentElementController
{
    public const TYPE = 'kkt_company_contact';

    public function __construct(
        private readonly ScopeMatcher $scopeMatcher,
        private readonly DataHelper $dataHelper,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $company = CompanyModel::findById($model->companyId);

        if ($this->scopeMatcher->isBackendRequest($request)) {
            $template = new BackendTemplate('be_wildcard');
            $template->title = '';
            if (!empty($model->headline)) {
                $template->title .= StringUtil::deserialize($model->headline)['value'];
            }
            if (!empty($model->companyId)) {
                $template->title .= ' '.$GLOBALS['TL_LANG']['MOD']['company'][0].' '.$company->name;
            }

            return new Response($template->parse());
        }

        $this->dataHelper->getCompanyContact($company, $model, $template);

        return $template->getResponse();
    }
}