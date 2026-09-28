<?php

declare(strict_types=1);

namespace BiankaKriege\ContaoCompanyData\Helper;

use Contao\ContentModel;
use Contao\CoreBundle\Image\Studio\Figure;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\FilesModel;
use Contao\Model;
use Contao\ModuleModel;

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

    public function getImage(string|null $image, int|string|array|null $size = null, Model|null $model = null): Figure|null
    {
        $filesModel = FilesModel::findByUuid($image);

        if (null === $filesModel) {
            return null;
        }

        $builder = $this->getStudio()
            ->createFigureBuilder()
            ->fromFilesModel($filesModel)
            ->setSize($size)
        ;

        if (null !== $model) {
            $builder
                ->setLightboxGroupIdentifier('lb'.$model->id)
                ->enableLightbox((bool) $model->fullsize)
            ;
        }

        return $builder->buildIfResourceExists();
    }
}