<?php

namespace BastSys\LanguageBundle\Entity\Translation;

use BastSys\LanguageBundle\Repository\LanguageRepository;
use BastSys\LanguageBundle\Service\ILocaleService;
use BastSys\UtilsBundle\Entity\Identification\IIdentifiableEntity;

/**
 * Interface ITranslatable
 * @package BastSys\LanguageBundle\Entity
 * @author  mirkl
 */
interface ITranslatable extends IIdentifiableEntity
{
    /**
     * @param ITranslation $translation
     */
    function addTranslation(ITranslation $translation): void;

    /**
     * @param string $languageCode
     *
     * @return ITranslation
     */
    function createTranslation(string $languageCode): ITranslation;

    /**
     * @param string $fieldName
     * @param string|null $preferredLanguageCode
     *
     * @return mixed
     */
    function getTranslatedField(string $fieldName, string $preferredLanguageCode = null);

    /**
     * @param string|null $languageCode
     *
     * @return ITranslation|null
     */
    function getTranslation(string $languageCode = null): ?ITranslation;

    /**
     * @return array
     */
    function getTranslations(): array;

    /**
     * @param ILocaleService $localeService
     * @param LanguageRepository $languageRepo
     */
    function feedTranslatable(ILocaleService $localeService, LanguageRepository $languageRepo): void;
}
