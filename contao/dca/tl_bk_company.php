<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2025, Bianka Kriege
 * @package: company-data
 */

use Contao\DataContainer;
use Contao\DC_Table;
use Contao\System;

$table = 'tl_bk_company';

$GLOBALS['TL_DCA'][$table] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'ctable' => ['tl_bk_person'],
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTED,
            'fields' => ['name'],
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
            'panelLayout' => 'filter;search,limit',
            'defaultSearchField' => 'name',
        ],
        'label' => [
            'fields' => ['name', 'city', 'street'],
            'showColumns' => true,
        ],
    ],
    'palettes' => [
        'default' => 'name,singleSRC;'.
            '{address_legend},city,postal,street,country;'.
            '{contact_legend},email,phone,website;'.
            '{imprint_legend},imprintName,imprintRepresentative,imprintPublicRegistry,imprintPublicRegistryNumber,imprintFinancialDistrict,imprintVatIdentificationNumber,imprintTaxIdentificationNumber,imprintEconomyIdentificationNumber;',
    ],
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp' => [
            'sql' => 'int(10) unsigned NOT NULL default 0',
        ],
        'name' => [
            'inputType' => 'text',
            'eval' => [
                'mandatory' => true,
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'name',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'singleSRC' => [
            'inputType' => 'fileTree',
            'eval' => [
                'filesOnly' => true,
                'fieldType' => 'radio',
                'tl_class' => 'clr',
            ],
            'sql' => 'binary(16) NULL',
        ],
        'city' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'address',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'postal' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'address',
            ],
            'sql' => ['type' => 'string', 'length' => 32, 'default' => '', 'notnull' => false],
        ],
        'street' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'address',
                'itemprop' => 'streetAddress',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'country' => [
            'inputType' => 'select',
            'eval' => [
                'tl_class' => 'w50',
                'includeBlankOption' => true,
                'chosen' => true,
                'feGroup' => 'address',
            ],
            'options_callback' => static fn () => System::getContainer()->get('contao.intl.countries')->getCountries(),
            'sql' => ['type' => 'string', 'length' => 2, 'default' => '', 'notnull' => false],
        ],
        'phone' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'contact',
            ],
            'sql' => ['type' => 'string', 'length' => 64, 'default' => '', 'notnull' => false],
        ],
        'email' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'rgxp' => 'email',
                'decodeEntities' => true,
                'feGroup' => 'contact',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'website' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'contact',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'imprintName' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'name',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'imprintRepresentative' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'imprint',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'imprintPublicRegistry' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'imprint',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'imprintPublicRegistryNumber' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'imprint',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'imprintFinancialDistrict' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'imprint',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'imprintVatIdentificationNumber' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'imprint',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'imprintTaxIdentificationNumber' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'imprint',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
        'imprintEconomyIdentificationNumber' => [
            'inputType' => 'text',
            'eval' => [
                'tl_class' => 'w50',
                'decodeEntities' => true,
                'feGroup' => 'imprint',
            ],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => '', 'notnull' => false],
        ],
    ],
];