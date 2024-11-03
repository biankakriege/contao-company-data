<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

use Contao\DataContainer;
use Contao\DC_Table;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use BiankaKriege\ContaoCompanyData\Model\PersonModel;

$GLOBALS['TL_DCA'][PersonModel::TABLE] =
[
    // Config
    'config' => [
        'dataContainer' => DC_Table::class,
        'ptable' => CompanyModel::TABLE,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'email' => 'index',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTABLE,
            'fields' => ['sorting'],
            'defaultSearchField' => 'name',
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
            'panelLayout' => 'filter;sort,search,limit',
            'renderAsGrid'            => true,
            'limitHeight'             => 160
        ],
        'label' => [
            'fields' => ['pid', 'name', 'position', 'email', 'phone'],
            'showColumns' => true,
        ],
        'operations' => [
            'edit',
            'copy',
            'toggle',
            'delete',
        ],
    ],
    // Palettes
    'palettes' => [
        'default' => 'pid;{personal_legend},title,name,position,singleSRC;{contact_legend},phone,mobile,email,website;{address_legend},street,postal,city;{published_legend},published;',
    ],
    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => 'int(10) unsigned NOT NULL default 0',
        ],
        'sorting' => [
            'sql' => 'int(10) unsigned NOT NULL default 0'
        ],
        'pid' => [
            'filter' => true,
            'sorting' => true,
            'inputType' => 'select',
            'foreignKey' => 'tl_kkt_company.name',
            'eval' => ['mandatory' => true],
            'sql' => 'int(10) unsigned NOT NULL default 0',
        ],
        'title' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'personal', 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'name' => [
            'search' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'personal', 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'position' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'personal', 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'street' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'address', 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'postal' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 32, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'address', 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 32, 'default' => '', 'notnull' => false],
        ],
        'city' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'address', 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'phone' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'mobile' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'email' => [
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'rgxp' => 'email', 'unique' => false, 'decodeEntities' => true, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'contact', 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'website' => [
            'inputType' => 'text',
            'eval' => ['mandatory' => false, 'rgxp' => 'url', 'maxlength' => 255, 'decodeEntities' => true, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'contact', 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'singleSRC' => [
            'inputType' => 'fileTree',
            'eval' => ['tl_class' => 'clr', 'fieldType' => 'radio', 'filesOnly' => true, 'extensions' => '%contao.image.valid_extensions%'],
            'sql' => 'binary(16) NULL',
        ],
        'published' => [
            'toggle' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => ['type' => 'boolean', 'default' => false],
        ],
    ],
];