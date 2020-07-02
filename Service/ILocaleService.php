<?php

namespace BastSys\LocaleBundle\Service;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Entity\Language\Language;
use BastSys\LocaleBundle\Exception\UnknownLocaleException;

/**
 * Interface ILocaleService
 * @package BastSys\LocaleBundle\Service\Localisation
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
