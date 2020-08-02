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
    public function getDefaultLocale(): string;
    /**
     * @return string
     */
    public function getCurrentLocale(): string;

    /**
     * @return Language
     */
    public function getCurrentLanguage(): Language;

    /**
     * @return Country
     */
    public function getCurrentCountry(): Country;

    /**
     * @param string $locale
     *
     * @throws UnknownLocaleException
     */
    public function setCurrentLocale(string $locale): void;

    /**
     * @return Language[]
     */
    public function getAvailableLanguages(): array;

    /**
     * @return Country[]
     */
    public function getAvailableCountries(): array;

    /**
     * @param string $locale
     *
     * @return bool
     */
    public function isValidLocale(string $locale): bool;

    /**
     * @param string $languageCode
     *
     * @return bool
     */
    public function isValidLanguageCode(string $languageCode): bool;

    /**
     * @param string $countryCode
     *
     * @return bool
     */
    public function isValidCountryCode(string $countryCode): bool;

    /**
     * @return bool
     */
    public function hasUpdatedCurrentLocale(): bool;
}
