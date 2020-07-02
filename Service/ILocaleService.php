<?php

namespace BastSys\LanguageBundle\Service;

use BastSys\LanguageBundle\Entity\Country\Country;
use BastSys\LanguageBundle\Entity\Language\Language;
use BastSys\LanguageBundle\Exception\UnknownLocaleException;

/**
 * Interface ILocaleService
 * @package BastSys\LanguageBundle\Service\Localisation
 * @author  mirkl
 */
interface ILocaleService
{
    /**
     * @return string
     */
    function getCurrentLocale(): string;

    /**
     * @return Language
     */
    function getCurrentLanguage(): Language;

    /**
     * @return Country
     */
    function getCurrentCountry(): Country;

    /**
     * @param string $locale
     *
     * @throws UnknownLocaleException
     */
    function setCurrentLocale(string $locale): void;

    /**
     * @return Language[]
     */
    function getAvailableLanguages(): array;

    /**
     * @return Country[]
     */
    function getAvailableCountries(): array;

    /**
     * @param string $locale
     *
     * @return bool
     */
    function isValidLocale(string $locale): bool;

    /**
     * @param string $languageCode
     *
     * @return bool
     */
    function isValidLanguageCode(string $languageCode): bool;

    /**
     * @param string $countryCode
     *
     * @return bool
     */
    function isValidCountryCode(string $countryCode): bool;

    /**
     * @return bool
     */
    function hasUpdatedCurrentLocale(): bool;
}
