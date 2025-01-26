<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2025, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Model;

use Contao\Model;

class CompanyModel extends Model
{
    public const TABLE = 'tl_bk_company';

    protected static $strTable = 'tl_bk_company';
}