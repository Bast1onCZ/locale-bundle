<?php

namespace BastSys\LanguageBundle\Service;

use BastSys\LanguageBundle\Entity\Country\Country;
use BastSys\LanguageBundle\Entity\Language\Language;
use BastSys\LanguageBundle\Exception\UnknownLocaleException;
use BastSys\LanguageBundle\Repository\CountryRepository;
use BastSys\LanguageBundle\Repository\LanguageRepository;
use BastSys\LanguageBundle\Structure\Locale;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * Class LocaleService
 * @package App\CoreBundle\Service\Localisation
 * @author  mirkl
 */
class LocaleService implements ILocaleService
{
    /** @var LanguageRepository */
    private $languageRepo;
    /** @var CountryRepository */
    private $countryRepo;
    /** @var Translator */
    private $translator;

    /** @var Locale */
    private $currentLocale;
    /** @var bool */
    private $hasUpdatedCurrentLocale = false;
    /** @var Language|null */
    private $currentLanguage = null;
    /** @var Country|null */
    private $currentCountry = null;

    /**
     * LocaleService constructor.
     *
     * @param LanguageRepository $languageRepo
     * @param CountryRepository $countryRepo
     * @param string $defaultLocale
     * @param Translator $translator
     */
    public function __construct(LanguageRepository $languageRepo, CountryRepository $countryRepo, string $defaultLocale, Translator $translator)
    {
        $this->languageRepo = $languageRepo;
        $this->countryRepo = $countryRepo;
        $this->currentLocale = new Locale($defaultLocale);
        $this->translator = $translator;
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
        return $this->currentLocale;
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
