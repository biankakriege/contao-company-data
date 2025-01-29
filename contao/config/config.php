<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2025, Bianka Kriege
 * @package: company-data
 */

use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;

$GLOBALS['BE_MOD']['content']['company'] = ['tables' => [CompanyModel::TABLE, PersonModel::TABLE]];

/*
 * Register the Models
 */
$GLOBALS['TL_MODELS']['tl_bk_company'] = CompanyModel::class;
$GLOBALS['TL_MODELS']['tl_bk_person'] = PersonModel::class;