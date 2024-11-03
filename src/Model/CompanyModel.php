<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Model;

use Contao\Model;

class CompanyModel extends Model
{
    public const TABLE = 'tl_kkt_company';

    protected static $strTable = 'tl_kkt_company';
}