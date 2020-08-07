<?php

namespace BastSys\LocaleBundle\Entity\Translation;

use BastSys\LocaleBundle\Repository\LanguageRepository;
use BastSys\LocaleBundle\Service\ILocaleService;
use BastSys\UtilsBundle\Entity\Identification\IIdentifiableEntity;

/**
 * Interface ITranslatable
 * @package BastSys\LocaleBundle\Entity
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
     * Gets translated field. First tries preferredLocale (if not defined currentLocale is used). If field value is not
     * defined for this translation or translation is not found, searches for any valid value (non empty string).
     *
     * @param string      $fieldName
     * @param string|null $preferredLanguageCode
     *
     * @return string|string[]|null
     */
    function getTranslatedField(string $fieldName, string $preferredLanguageCode = null);

    /**
     * @param string|null $languageCode
     *
     * @return ITranslation|null
     */
    function getTranslation(string $languageCode = null): ?ITranslation;

    /**
     * @return ITranslation[]
     */
    function getTranslations(): array;

    /**
     * @param ILocaleService $localeService
     * @param LanguageRepository $languageRepo
     */
    function feedTranslatable(ILocaleService $localeService, LanguageRepository $languageRepo): void;
}
