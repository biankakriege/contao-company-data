<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2025, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Helper;

use Contao\ContentModel;
use Contao\Controller;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;
use BiankaKriege\ContaoCompanyData\Phone\PhoneNumberFormatter;
use Symfony\Contracts\Translation\TranslatorInterface;

class DataHelper
{
    public function __construct(
        private readonly ImageHelper $imageHelper,
        private readonly TranslatorInterface $translator,
        private readonly PhoneNumberFormatter $phoneNumberFormatter
    )
    {
    }

    public function getPersonData(mixed $person, ContentModel $model): array
    {
        $data = [];
        $selected = StringUtil::deserialize($model->bkSelectable);

        $countryCode = $person->country ?: CompanyModel::findById($person->pid)?->country;
        $data['countryCode'] = $countryCode;

        foreach ($selected as $value) {
            if ($value === 'companyName') {
                $data['companyName'] = CompanyModel::findById($person->pid)->name;
            }

            if (!empty($person->{$value})) {
                if ($value === 'singleSRC') {
                    $data['image'] = $this->imageHelper->getImage($person->singleSRC, $model->size, $model);
                } else {
                    $data[$value] = $person->{$value};
                }

                if ($value === 'country') {
                    $data[$value] = System::getContainer()
                        ->get('contao.intl.countries')
                        ->getCountries()[strtoupper($person->{$value})]
                    ;
                }

                if ($value === 'officeHours') {
                    $data[$value] =  StringUtil::deserialize($person->{$value});
                }

                if ($value === 'phone') {
                    $data['phoneFormatted'] = $this->phoneNumberFormatter->format($person->{$value}, $countryCode);
                }

                if (true === $person->isOfficeRestricted) {
                    $data['isOfficeRestricted'] = $person->isOfficeRestricted;
                }

                if ($value === 'jumpTo') {
                    if ($objTarget = PageModel::findById($person->{$value})) {
                        $data['href'] = System::getContainer()
                            ->get('contao.routing.content_url_generator')
                            ->generate($objTarget)
                        ;
                    }
                }
            }
        }

        System::loadLanguageFile(PersonModel::TABLE);

        $data['translation'] = $GLOBALS['TL_LANG'][PersonModel::TABLE];
        $data['href_title'] = $this->translator->trans('MSC.more', [], 'contao_default');
        $data['href_text'] = $this->translator->trans('MSC.more', [], 'contao_default');

        return $data;
    }

    public function getCompanyContact(CompanyModel $company, $model, FragmentTemplate $template): void
    {
        System::loadLanguageFile(CompanyModel::TABLE);
        Controller::loadDataContainer(CompanyModel::TABLE);
        $arrFields = $GLOBALS['TL_DCA'][CompanyModel::TABLE]['fields'];

        $selected = StringUtil::deserialize($model->bkSelectable);
        $data = [];

        foreach ($selected as $value) {
            if (!empty($company->{$value})) {
                if ($value === 'singleSRC') {
                    $size = !empty($model->size) ? $model->size : $model->imgSize;
                    $template->logo = $this->imageHelper->getImage($company->$value, $size);
                } else {
                    $data[$arrFields[$value]['eval']['feGroup']][$value] = $company->{$value};
                }

                if ($value === 'country') {
                    $data[$arrFields[$value]['eval']['feGroup']][$value] = System::getContainer()->get('contao.intl.countries')->getCountries()[strtoupper($company->{$value})];
                }
            }
        }

        $template->company = $data;
        $template->countryCode = $company->country;
        $template->translation = $GLOBALS['TL_LANG'][CompanyModel::TABLE];
    }
}