<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use Contao\CoreBundle\DataContainer\PaletteManipulator;

$table = 'tl_page';

$GLOBALS['TL_DCA'][$table]['fields']['bkCompanyId'] = [
    'inputType' => 'select',
    'foreignKey' => CompanyModel::getTable().'.name',
    'eval' => [
        'includeBlankOption' => true,
        'chosen' => true,
        'tl_class' => 'w50',
    ],
    'sql' => "int(10) unsigned NOT NULL default '0'",
    'relation' => [
        'type' => 'hasOne',
        'load' => 'lazy',
    ],
];

PaletteManipulator::create()
    ->addLegend('bk_company_legend', 'url_legend', PaletteManipulator::POSITION_BEFORE, true)
    ->addField('bkCompanyId', 'bk_company_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('root', $table)
    ->applyToPalette('rootfallback', $table)
;