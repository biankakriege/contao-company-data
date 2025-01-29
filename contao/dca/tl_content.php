<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2025, Bianka Kriege
 * @package: company-data
 */

use BiankaKriege\ContaoCompanyData\Controller\ContentElement\BkCompanyContactController;
use BiankaKriege\ContaoCompanyData\Controller\ContentElement\BkPersonSingleController;
use BiankaKriege\ContaoCompanyData\Controller\ContentElement\BkPersonListController;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;

$table = 'tl_content';

/*
 * Fields
 */
$GLOBALS['TL_DCA'][$table]['fields']['bkCompanyId'] = [
    'inputType' => 'select',
    'foreignKey' => CompanyModel::getTable().'.name',
    'eval' => [
        'mandatory' => true,
        'chosen' => true,
        'multiple' => false,
        'tl_class' => 'w100',
    ],
    'sql' => "int(10) unsigned NOT NULL default '0'",
    'relation' => [
        'type' => 'hasOne',
        'load' => 'eager',
    ],
];

$GLOBALS['TL_DCA'][$table]['fields']['bkPersonId'] = [
    'inputType' => 'select',
    'foreignKey' => PersonModel::getTable().'.name',
    'eval' => [
        'mandatory' => true,
        'chosen' => true,
        'multiple' => false,
        'tl_class' => 'w100',
    ],
    'sql' => "int(10) unsigned NOT NULL default '0'",
    'relation' => [
        'type' => 'hasOne',
        'load' => 'eager',
    ],
];

$GLOBALS['TL_DCA'][$table]['fields']['bkSelectable'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['multiple' => true, 'mandatory' => true],
    'sql' => "blob NULL"
];

$GLOBALS['TL_DCA'][$table]['palettes'][BkCompanyContactController::TYPE] =
    '{type_legend},type,headline,subline;
    {company_legend},bkCompanyId,bkSelectable,size;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID,space;
    {invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA'][$table]['palettes'][BkPersonListController::TYPE] =
    '{type_legend},type,headline;
    {company_legend},bkCompanyId,bkSelectable;
    {source_legend},size;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID,space;
    {invisible_legend:hide},invisible,start,stop';


$GLOBALS['TL_DCA'][$table]['palettes'][BkPersonSingleController::TYPE] =
    '{type_legend},type,headline;
    {company_legend},bkPersonId,bkSelectable;
    {source_legend},size;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID,space;
    {invisible_legend:hide},invisible,start,stop';