<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

use BiankaKriege\ContaoCompanyData\Controller\ContentElement\KktCompanyContactController;
use BiankaKriege\ContaoCompanyData\Controller\ContentElement\KktPersonSingleController;
use BiankaKriege\ContaoCompanyData\Controller\ContentElement\KktPersonListController;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;

$table = 'tl_content';

/*
 * Fields
 */
$GLOBALS['TL_DCA'][$table]['fields']['companyId'] = [
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

$GLOBALS['TL_DCA'][$table]['fields']['personId'] = [
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

$GLOBALS['TL_DCA'][$table]['fields']['name'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['showImage'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['showAddress'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['showCountry'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['email'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['phone'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['website'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['imprintName'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['imprintRepresentative'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['imprintPublicRegistry'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['imprintFinancialDistrict'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['imprintPublicRegistryNumber'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['imprintVatIdentificationNumber'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['imprintTaxIdentificationNumber'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['imprintEconomyIdentificationNumber'] = [
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['palettes'][KktCompanyContactController::TYPE] =
    '{type_legend},type,headline,subline;
    {company_legend},companyId;
    {company_information_legend},name,showAddress,showCountry,email,phone,website;
    {company_imprint_legend},imprintName,imprintRepresentative,imprintFinancialDistrict,imprintPublicRegistry,imprintPublicRegistryNumber,imprintVatIdentificationNumber,imprintTaxIdentificationNumber,imprintEconomyIdentificationNumber;
    {source_legend},showImage,size;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID,space;
    {invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA'][$table]['palettes'][KktPersonListController::TYPE] =
    '{type_legend},type,headline;
    {company_legend},companyId,showImage,showPosition,showAddress,email,phone,showMobile,showFax,showWebsite;
    {source_legend},size;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID,space;
    {invisible_legend:hide},invisible,start,stop';


$GLOBALS['TL_DCA'][$table]['palettes'][KktPersonSingleController::TYPE] =
    '{type_legend},type,headline;
    {company_legend},personId,showImage,showPosition,showAddress,email,phone,showMobile,showFax,showWebsite;
    {source_legend},size;
    {template_legend:hide},customTpl;
    {protected_legend:hide},protected;
    {expert_legend:hide},guests,cssID,space;
    {invisible_legend:hide},invisible,start,stop';