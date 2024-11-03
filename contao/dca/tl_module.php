<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

use BiankaKriege\ContaoCompanyData\Controller\ContentElement\KktCompanyContactController;
use BiankaKriege\ContaoCompanyData\Controller\FrontendModule\KktCompanyLogoController;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;

$GLOBALS['TL_DCA']['tl_module']['fields']['link'] = [
    'inputType' => 'text',
    'eval' => [
        'tl_class' => 'clr w50', 'rgxp' => 'url', 'decodeEntities' => true, 'maxlength' => 255, 'dcaPicker' => true, 'addWizardClass' => false,
    ],
    'sql' => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['companyId'] = [
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

$GLOBALS['TL_DCA']['tl_module']['fields']['companyName'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['showImage'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['showAddress'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['showCountry'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['email'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['phone'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['fields']['website'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_module']['palettes'][KktCompanyLogoController::TYPE] = <<<'EOD'
        {type_legend},name,type;
        {company_legend},companyId,rootPage,imgSize;
    EOD;

$GLOBALS['TL_DCA']['tl_module']['palettes'][KktCompanyContactController::TYPE] = <<<'EOD'
        {title_legend},name,type,headline;
        {company_legend},companyId,companyName,showAddress,showCountry,email,phone,website,showImage,imgSize;
        {template_legend:hide},customTpl;
        {protected_legend:hide},protected;
        {expert_legend:hide},guests,cssID,space;
        {invisible_legend:hide},invisible,start,stop';
    EOD;