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
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\System;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;

class DataHelper
{
    public function __construct(
        private readonly Studio $studio,
        private readonly ImageHelper $imageHelper,
    ) {
    }

    public function getPersonData(mixed $person, ContentModel $model): array
    {
        $arrName = [];
        $arrName[] = $person->title;
        $arrName[] = $person->name;

        $data['name'] = implode(' ', $arrName);
        $data['position'] = !empty($person->position) ? $person->position : '';
        $data['phone'] = $model->phone ? $person->phone : '';
        $data['email'] = $model->email ? $person->email : '';
        $data['image'] = $model->showImage ? $this->imageHelper->getImage($person->singleSRC, $model->size) : '';

        System::loadLanguageFile(PersonModel::TABLE);
        $data['translation'] = $GLOBALS['TL_LANG'][PersonModel::TABLE];
        return $data;
    }

    public function getCompanyContact(CompanyModel $company, $model, FragmentTemplate $template): void
    {
        System::loadLanguageFile(CompanyModel::TABLE);
        Controller::loadDataContainer(CompanyModel::TABLE);

        // get categories from dca
        $arrCategory = [];
        $dcaMember = $GLOBALS['TL_DCA'][CompanyModel::TABLE]['fields'];

        foreach ($dcaMember as $field => $item) {
            if (!empty($item['eval']['feGroup'])) {
                $arrCategory[$field] = $item['eval']['feGroup'];
            }
        }

        $arrCompany = [];

        // get company data
        foreach ($company->row() as $key => $field) {
            if (!empty($company->$key) && \in_array($key, (array) $model, true) && 1 === (int) $model->$key && !empty($arrCategory[$key])) {
                $arrCompany[$arrCategory[$key]][$key] = $company->$key;
            }
            // add name form module
            if (1 === (int) $model->companyName && 'name' === $key) {
                $arrCompany[$arrCategory[$key]][$key] = $field;
            }
            // add address
            if (1 === (int) $model->showAddress && ('street' === $key || 'postal' === $key || 'city' === $key)) {
                $arrCompany[$arrCategory[$key]][$key] = $field;
            }
            // get country
            if (1 === (int) $model->showCountry && 'country' === $key) {
                $arrCompany[$arrCategory[$key]][$key] = System::getContainer()->get('contao.intl.countries')->getCountries()[strtoupper($field)];
            }
        }

        // add logo
        if (1 === (int) $model->showImage) {
            $size = !empty($model->size) ? $model->size : $model->imgSize;
            $template->logo = $this->imageHelper->getImage($company->singleSRC, $size);
        }

        $template->company = $arrCompany;
        $template->translation = $GLOBALS['TL_LANG'][CompanyModel::TABLE];
    }
}