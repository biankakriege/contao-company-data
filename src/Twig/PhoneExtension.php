<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\Twig;

use BiankaKriege\ContaoCompanyData\Phone\PhoneNumberFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class PhoneExtension extends AbstractExtension
{
    public function __construct(private readonly PhoneNumberFormatter $phoneNumberFormatter)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('tel_href', $this->phoneNumberFormatter->format(...)),
        ];
    }
}
