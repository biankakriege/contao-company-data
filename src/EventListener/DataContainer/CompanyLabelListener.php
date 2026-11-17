<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\EventListener\DataContainer;

use BiankaKriege\ContaoCompanyData\Model\PersonModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

#[AsCallback(table: 'tl_bk_company', target: 'list.label.label')]
final class CompanyLabelListener
{
    /**
     * @var array<int, int>|null
     */
    private array|null $personCounts = null;

    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(array $row, string $label, DataContainer $dc, array $args): array
    {
        $this->personCounts ??= array_map('intval', $this->connection->fetchAllKeyValue(
            'SELECT pid, COUNT(*) FROM '.PersonModel::TABLE.' WHERE published = 1 GROUP BY pid',
        ));

        $key = array_search('personCount', $GLOBALS['TL_DCA'][$dc->table]['list']['label']['fields'], true);
        $args[$key] = (string) ($this->personCounts[(int) $row['id']] ?? '-');

        return $args;
    }
}