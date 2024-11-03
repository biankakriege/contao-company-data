<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;

$table = 'tl_form';

/*
 * Fields
 */
$GLOBALS['TL_DCA'][$table]['fields']['companyId'] = [
    'inputType' => 'select',
    'foreignKey' => CompanyModel::getTable().'.name',
    'eval' => [
        'chosen' => true,
        'multiple' => false,
        'tl_class' => 'clr w50',
    ],
    'sql' => "int(10) unsigned NOT NULL default '0'",
    'relation' => [
        'type' => 'hasOne',
        'load' => 'eager',
    ],
];

$GLOBALS['TL_DCA'][$table]['fields']['addSignatureAuto'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'clr'],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['generatePlaceholder'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'clr'],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['size'] = [
    'inputType' => 'imageSize',
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval' => ['rgxp' => 'natural', 'includeBlankOption' => true, 'nospace' => true, 'helpwizard' => true, 'tl_class' => 'w50 clr'],
    'sql' => "varchar(128) COLLATE ascii_bin NOT NULL default ''",
];

PaletteManipulator::create()
    ->addLegend('company_legend', 'email_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('companyId', 'company_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('size', 'companyId')
    ->addField('generatePlaceholder', 'companyId')
    ->addField('addSignatureAuto', 'companyId')
    ->applyToPalette('default', $table)
;