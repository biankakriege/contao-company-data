<?php

declare(strict_types=1);

namespace BiankaKriege\ContaoCompanyData\EventListener\DataContainer\OptionsCallback;

use BiankaKriege\ContaoCompanyData\Model\PersonModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCallback('tl_content', 'fields.bkPersonSelection.options')]
class BkPersonSelectionOptionsCallback
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function __invoke(DataContainer|null $dc = null): array
    {
        $return = [];

        $people = PersonModel::findBy('published',true);

        if (null !== $people) {
            foreach ($people as $person) {
                $return[$person->id] = $person->name.($person->department ? ' ('.$person->department.')' : '');
            }
        }

        return $return;
    }
}
