<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\Service;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Entity\Language\Language;
use BastSys\LocaleBundle\Exception\UnknownLocaleException;
use BastSys\LocaleBundle\Repository\CountryRepository;
use BastSys\LocaleBundle\Repository\LanguageRepository;
use BastSys\LocaleBundle\Structure\Locale;

/**
 * Class LocaleService
 * @package BastSys\LocaleBundle\Service
 * @author mirkl
 */
class LocaleService implements ILocaleService
{
    /** @var LanguageRepository */
    private $languageRepo;
    /** @var CountryRepository */
    private $countryRepo;

    private Locale $defaultLocale;
    /** @var Locale */
    private Locale $currentLocale;
    /** @var bool */
    private bool $hasUpdatedCurrentLocale = false;
    /** @var Language|null */
    private ?Language $currentLanguage = null;
    /** @var Country|null */
    private ?Country $currentCountry = null;

    /**
     * LocaleService constructor.
     *
     * @param LanguageRepository $languageRepo
     * @param CountryRepository $countryRepo
     * @param string $defaultLocale
     */
    public function __construct(LanguageRepository $languageRepo, CountryRepository $countryRepo, string $defaultLocale)
    {
        $this->languageRepo = $languageRepo;
        $this->countryRepo = $countryRepo;
        $this->defaultLocale = new Locale($defaultLocale);
        $this->currentLocale = $this->defaultLocale;
    }

    /**
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return (string) $this->defaultLocale;
    }

    /**
     * @return Language
     * @throws \BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException
     */
    function getCurrentLanguage(): Language
    {
        $this->currentLanguage = $this->currentLanguage ?? $this->languageRepo->findById($this->currentLocale->getLanguageCode());
        return $this->currentLanguage;
    }

    /**
     * @return Country
     */
    function getCurrentCountry(): Country
    {
        $this->currentCountry = $this->currentCountry ?? $this->countryRepo->findByAlpha2Code($this->currentLocale->getCountryCode());
        return $this->currentCountry;
    }

    /**
     * @return string
     */
    public function getCurrentLocale(): string
    {
        return (string) $this->currentLocale;
    }

    /**
     * @param string $locale
     *
     * @throws UnknownLocaleException
     * @throws \BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException
     */
    public function setCurrentLocale(string $locale): void
    {
        if (!$this->isValidLocale($locale)) {
            throw new UnknownLocaleException($locale);
        }

        $newLocale = new Locale($locale);
        $this->hasUpdatedCurrentLocale = true;
        if (!$newLocale->equals($this->currentLocale)) {
            $this->currentLocale = $newLocale;
            $this->currentLanguage = null;
            $this->currentCountry = null;
        }
    }

    /**
     * @param string $locale
     *
     * @return bool
     * @throws \BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException
     */
    public function isValidLocale(string $locale): bool
    {
        $parsedLocale = null;
        try {
            $parsedLocale = new Locale($locale);
        } catch (\InvalidArgumentException $ex) {
            return false;
        }

        $language = $this->languageRepo->findById($parsedLocale->getLanguageCode());
        $country = $this->countryRepo->findByAlpha2Code($parsedLocale->getCountryCode());

        return $language && $country;
    }

    /**
     * @return Language[]
     */
    public function getAvailableLanguages(): array
    {
        return $this->languageRepo->findAll();
    }

    /**
     * @return Country[]
     */
    public function getAvailableCountries(): array
    {
        return $this->countryRepo->findAll();
    }

    /**
     * @param string $languageCode
     *
     * @return bool
     * @throws \BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException
     */
    function isValidLanguageCode(string $languageCode): bool
    {
        return !!$this->languageRepo->findById($languageCode);
    }

    /**
     * @param string $countryCode
     *
     * @return bool
     */
    function isValidCountryCode(string $countryCode): bool
    {
        return !!$this->countryRepo->findByAlpha2Code($countryCode);
    }

    /**
     * @return bool
     */
    public function hasUpdatedCurrentLocale(): bool
    {
        return $this->hasUpdatedCurrentLocale;
    }
}
