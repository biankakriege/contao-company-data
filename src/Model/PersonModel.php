<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2025, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Model;

use Contao\Model;

class PersonModel extends Model
{
    public const TABLE = 'tl_bk_person';

    protected static $strTable = 'tl_bk_person';
}