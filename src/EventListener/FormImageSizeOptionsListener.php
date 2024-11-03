<?php

declare(strict_types=1);

/**
 * Bianka Kriege <bianka.kriege@web.de>
 * (c)2024, Bianka Kriege
 * @package: company-data
 */

namespace BiankaKriege\ContaoCompanyData\EventListener;

use Contao\BackendUser;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Image\ImageSizes;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsCallback('tl_form', 'fields.size.options')]
class FormImageSizeOptionsListener
{
    public function __construct(
        private readonly ImageSizes $imageSizes,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    public function __invoke(): array
    {
        if (!($user = $this->tokenStorage->getToken()?->getUser()) instanceof BackendUser) {
            return [];
        }

        $options = $user->admin ?
            $this->imageSizes->getAllOptions() :
            $this->imageSizes->getOptionsForUser($user);

        // Remove the "proportional" image resizing method
        if (false !== ($i = array_search('proportional', $options['custom'], true))) {
            unset($options['custom'][$i]);
        }

        return $options;
    }
}