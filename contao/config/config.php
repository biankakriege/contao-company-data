<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;

$GLOBALS['BE_MOD']['content']['company'] = ['tables' => [CompanyModel::TABLE, PersonModel::TABLE]];
$GLOBALS['BE_MOD']['content']['person'] = ['tables' => [PersonModel::TABLE]];

/*
 * Register the Models
 */
$GLOBALS['TL_MODELS']['tl_kkt_company'] = CompanyModel::class;
$GLOBALS['TL_MODELS']['tl_kkt_person'] = PersonModel::class;