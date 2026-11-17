<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Phone;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

final readonly class PhoneNumberFormatter
{
    public function format(string|null $number, string|null $country = null): string|null
    {
        if (!$number) {
            return null;
        }

        try {
            $util = PhoneNumberUtil::getInstance();

            return $util->format($util->parse($number, $country ? strtoupper($country) : null), PhoneNumberFormat::E164);
        } catch (NumberParseException) {
            return $number;
        }
    }
}