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
use BiankaKriege\ContaoCompanyData\Model\PersonModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

#[AsContentElement(self::TYPE, category: 'bk_company')]
class BkPersonSingleController extends AbstractContentElementController
{
    public const TYPE = 'bk_person_single';

    public function __construct(
        private readonly TwigEnvironment $twig,
        private readonly DataHelper $dataHelper,
    )
    {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $person = PersonModel::findById($model->personId);

        if (null !== $person && 1 === $person->published) {


            $data = $this->dataHelper->getPersonData($person, $model);

            $template->list = $this->twig->render('@Contao/content_element/content-person-list.html.twig', $data);
        }

        return $template->getResponse();
    }
}