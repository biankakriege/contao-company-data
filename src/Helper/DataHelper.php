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
use Contao\StringUtil;
use Contao\System;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

class DataHelper
{
    public function __construct(private readonly ImageHelper $imageHelper)
    {
    }

    public function getPersonData(mixed $person, ContentModel $model): array
    {
        $data = [];
        $selected = StringUtil::deserialize($model->bkSelectable);

        foreach ($selected as $value) {
            if (!empty($person->{$value})) {

                if ($value === 'singleSRC') {
                    $data['image'] = $this->imageHelper->getImage($person->singleSRC, $model->size);
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
                    $phoneUtil = PhoneNumberUtil::getInstance();
                    $phoneNumberProto = $phoneUtil->parse($person->{$value}, "DE");

                    $data['phoneFormatted'] = $phoneUtil->format($phoneNumberProto, PhoneNumberFormat::E164);
                    $data['phone'] = $person->{$value};
                }
            }
        }

        System::loadLanguageFile(PersonModel::TABLE);

        $data['translation'] = $GLOBALS['TL_LANG'][PersonModel::TABLE];

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
        $template->translation = $GLOBALS['TL_LANG'][CompanyModel::TABLE];
    }
}