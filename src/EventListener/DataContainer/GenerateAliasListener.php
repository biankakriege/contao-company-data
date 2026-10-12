<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024-2026, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Slug\Slug;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

#[AsCallback(table: 'tl_bk_company', target: 'fields.alias.save')]
#[AsCallback(table: 'tl_bk_person', target: 'fields.alias.save')]
final readonly class GenerateAliasListener
{
    public function __construct(
        private Slug $slug,
        private Connection $connection,
    ) {
    }

    public function __invoke(mixed $value, DataContainer $dc): string
    {
        $value = (string) $value;

        $aliasExists = fn (string $alias): bool => false !== $this->connection->fetchOne(
            'SELECT id FROM '.$dc->table.' WHERE alias = ? AND id != ?',
            [$alias, $dc->id],
        );

        if ('' === $value) {
            // getActiveRecord() contains the submitted name, it exists since Contao 5.7
            $record = method_exists($dc, 'getActiveRecord') ? $dc->getActiveRecord() : $dc->getCurrentRecord();

            return $this->slug->generate((string) ($record['name'] ?? ''), [], $aliasExists);
        }

        if (preg_match('/^[1-9]\d*$/', $value)) {
            throw new \RuntimeException(\sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $value));
        }

        if ($aliasExists($value)) {
            throw new \RuntimeException(\sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $value));
        }

        return $value;
    }
}