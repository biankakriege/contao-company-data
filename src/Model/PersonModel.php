<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Model;

use Contao\Model;

class PersonModel extends Model
{
    public const TABLE = 'tl_kkt_person';

    protected static $strTable = 'tl_kkt_person';
}