<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2025, Bianka Kriege
 * @package: company-data
 */

use BiankaKriege\ContaoCompanyData\Controller\ContentElement\BkCompanyContactController;
use BiankaKriege\ContaoCompanyData\Controller\FrontendModule\BkCompanyLogoController;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;

$table = 'tl_module';

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

$GLOBALS['TL_DCA'][$table]['fields']['bkSelectable'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['multiple' => true, 'mandatory' => true],
    'sql' => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_module']['palettes'][BkCompanyLogoController::TYPE] = <<<'EOD'
        {type_legend},name,type;
        {company_legend},bkCompanyId,rootPage,imgSize;
    EOD;

$GLOBALS['TL_DCA']['tl_module']['palettes'][BkCompanyContactController::TYPE] = <<<'EOD'
        {title_legend},name,type,headline;
        {company_legend},bkCompanyId,bkSelectable,imgSize;
        {template_legend:hide},customTpl;
        {protected_legend:hide},protected;
        {expert_legend:hide},guests,cssID,space;
        {invisible_legend:hide},invisible,start,stop';
    EOD;