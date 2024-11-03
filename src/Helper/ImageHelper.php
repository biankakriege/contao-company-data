<?php

declare(strict_types=1);

namespace BiankaKriege\ContaoCompanyData\Helper;

use Contao\CoreBundle\Image\Studio\Figure;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\FilesModel;

readonly class ImageHelper
{
    public function __construct(
        private Studio $studio)
    {
    }

    public function getStudio(): Studio
    {
        return $this->studio;
    }

    public function getImage($image, $size): Figure|null
    {
        $file = FilesModel::findByUuid($image);

        if (null !== $file) {
            $figureBuilder = $this->getStudio()
                ->createFigureBuilder()
                ->fromUuid($file->uuid)
                ->setSize($size)
            ;

            return $figureBuilder->buildIfResourceExists();
        }

        return null;
    }
}