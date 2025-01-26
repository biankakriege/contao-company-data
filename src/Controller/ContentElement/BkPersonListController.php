<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2025, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Twig\FragmentTemplate;
use BiankaKriege\ContaoCompanyData\Helper\DataHelper;
use BiankaKriege\ContaoCompanyData\Helper\ImageHelper;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

#[AsContentElement(self::TYPE, category: 'bk_company')]
class BkPersonListController extends AbstractContentElementController
{
    public const TYPE = 'bk_person_list';

    public function __construct(
        private readonly ImageHelper     $imageHelper,
        private readonly TwigEnvironment $twig,
        private readonly DataHelper      $dataHelper,
    )
    {

    }

    /**
     * @return ImageHelper
     */
    public function getImageHelper(): ImageHelper
    {
        return $this->imageHelper;
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $list = [];
        $people = PersonModel::findBy(
            ['pid=?', 'published=?'],
            [$model->companyId, 1],
        );

        if (null !== $people) {
            foreach ($people as $person) {
                $data = $this->dataHelper->getPersonData($person, $model);

                $list[] = $this->twig->render('@Contao/content_element/content-person-list.html.twig', $data);
            }
            $template->list = $list;
        }

        return $template->getResponse();
    }
}