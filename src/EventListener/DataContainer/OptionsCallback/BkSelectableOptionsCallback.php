<?php

declare(strict_types=1);

namespace BiankaKriege\ContaoCompanyData\EventListener\DataContainer\OptionsCallback;

use Contao\Controller;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCallback('tl_content', 'fields.bkSelectable.options')]
#[AsCallback('tl_module', 'fields.bkSelectable.options')]
class BkSelectableOptionsCallback
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function __invoke(DataContainer|null $dc = null): array
    {
        $return = [];

        if ($dc && ('bk_person_single' === $dc->getCurrentRecord()['type'] ||'bk_person_list' === $dc->getCurrentRecord()['type'])) {
            $return = $this->fetchSelectableFields($return, 'tl_bk_person');
        }

        if ($dc && ('bk_company_contact' === $dc->getCurrentRecord()['type'] )) {
            $return = $this->fetchSelectableFields($return, 'tl_bk_company');
        }

        return $return;
    }

    public function fetchSelectableFields(array $return, $table): array
    {
        Controller::loadDataContainer($table);

        foreach ($GLOBALS['TL_DCA'][$table]['fields'] as $k => $v) {
            if ($v['eval']['bkSelectable'] ?? null) {
                $return[$k] = $this->translator->trans($table.'.'.$k.'.0', [], 'contao_'.$table);
            }
        }
        return $return;
    }

}