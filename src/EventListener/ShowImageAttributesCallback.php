<?php

declare(strict_types=1);

namespace BiankaKriege\ContaoCompanyData\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsCallback('tl_content', 'fields.showImage.attributes')]
class ShowImageAttributesCallback
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke(array $attributes, DataContainer|null $dc = null): array
    {
        if ($dc && ('kkt_person_single' === $dc->getCurrentRecord()['type'] ||'kkt_person_list' === $dc->getCurrentRecord()['type'])) {
            $attributes['options'][0]['label'] = $this->translator->trans('tl_content.showPersonImage.0', [], 'contao_tl_content');
        }

        return $attributes;
    }
}