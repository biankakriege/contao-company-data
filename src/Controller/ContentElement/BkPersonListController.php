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
use Contao\StringUtil;
use BiankaKriege\ContaoCompanyData\Helper\DataHelper;
use BiankaKriege\ContaoCompanyData\JsonLd\CompanySchemaFactory;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

#[AsContentElement(self::TYPE, category: 'bk_company')]
class BkPersonListController extends AbstractContentElementController
{
    public const TYPE = 'bk_person_list';

    public function __construct(
        private readonly TwigEnvironment $twig,
        private readonly DataHelper $dataHelper,
        private readonly CompanySchemaFactory $schemaFactory,
    )
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $list = [];
        $people = PersonModel::findBy(
            ['pid=?', 'published=?'],
            [$model->bkCompanyId, 1],
        );

        if (null !== $people) {
            $company = CompanyModel::findById($model->bkCompanyId);
            $fields = StringUtil::deserialize($model->bkSelectable, true);

            if (null !== $company) {
                $this->schemaFactory->addToGraph($this->schemaFactory->createOrganization($company));
            }

            foreach ($people as $person) {
                $data = $this->dataHelper->getPersonData($person, $model);
                $this->schemaFactory->addToGraph($this->schemaFactory->createPerson($person, $fields, $company));

                $list[] = $this->twig->render('@Contao/content_element/content-person-list.html.twig', $data);
            }
            $template->list = $list;
        }

        return $template->getResponse();
    }
}