<?php

declare(strict_types=1);

namespace BiankaKriege\ContaoCompanyData\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Environment;
use Contao\Form;
use BiankaKriege\ContaoCompanyData\Helper\ImageHelper;
use BiankaKriege\ContaoCompanyData\Model\CompanyModel;
use Twig\Environment as TwigEnvironment;

#[AsHook('processFormData')]
class ProcessFormDataListener
{
    public function __construct(
        private readonly ImageHelper $imageHelper,
        private readonly TwigEnvironment $twig,
    ) {
    }

    public function __invoke(array &$submittedData, array $formData, array|null $files, array $labels, Form $form): void
    {
        $hbeData = CompanyModel::findById($form->companyId);

        if (1 === (int) $form->addSignatureAuto) {
            $submittedData['company'] = $this->generateSignature($hbeData, $form);
        }

        if (1 === (int) $form->generatePlaceholder) {
            $submittedData = $this->generatePlaceholder($hbeData, $submittedData, $form);
        }
    }

    private function generatePlaceholder($hbeData, $submittedData, $form)
    {
        foreach ($hbeData->row() as $key => $field) {
            $submittedData['company_'.$key] = $field;
            if ('singleSRC' === $key && null !== $hbeData->singleSRC) {
                $logo = $this->imageHelper->getImage($hbeData->singleSRC, $form->size);
                if ($logo) {
                    $submittedData['company_logo'] = '<img alt="'.$logo->getMetadata()->getAlt().'" src="'.Environment::get('url').$logo->getSchemaOrgData()['contentUrl'].'" style="border:0; outline:none; text-decoration:none; display:block;" >';
                }
            }
        }

        return $submittedData;
    }

    private function generateSignature($hbeData, $form): string
    {
        if ($hbeData->singleSRC) {
            $logo = $this->imageHelper->getImage($hbeData->singleSRC, $form->size);
            if ($logo) {
                $hbeData->imageSrc = Environment::get('url').$logo->getSchemaOrgData()['contentUrl'];
                $hbeData->imageAlt = $logo->getMetadata()->getAlt();
            }
        }

        $arrAddress = [];
        if ('' !== $hbeData->street) {
            $arrAddress[] = $hbeData->street;
        }
        if ($hbeData->postal && $hbeData->city) {
            $arrAddress[] = $hbeData->postal.' '.$hbeData->city;
        }
        $hbeData->address = [] !== $arrAddress ? implode(', ', $arrAddress) : '&nbsp;';

        return $this->twig->render('@Contao/mail_signature.html.twig', $hbeData->row());
    }
}