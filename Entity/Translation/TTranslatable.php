<?php

namespace BastSys\LocaleBundle\Entity\Translation;

use BastSys\LocaleBundle\Entity\Language\Language;
use BastSys\LocaleBundle\Repository\LanguageRepository;
use BastSys\LocaleBundle\Service\ILocaleService;
use BastSys\LocaleBundle\Structure\Locale;
use BastSys\UtilsBundle\Exception\TraitNotImplementingInterfaceException;
use BastSys\UtilsBundle\Model\Strings;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trait TTranslatable
 * @package BastSys\LocaleBundle\Entity
 */
trait TTranslatable
{
    /**
     * @var ArrayCollection
     */
    private $translations;

    /** @var ILocaleService */
    private $localeService;

    /** @var LanguageRepository */
    private $languageRepository;

    /**
     * TTranslatable constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!$this instanceof ITranslatable) {
            throw new TraitNotImplementingInterfaceException($this, TTranslatable::class, ITranslatable::class);
        }

        $this->translations = new ArrayCollection();
    }

    /**
     * Gets translated field. First tries preferredLocale (if not defined currentLocale is used). If field value is not
     * defined for this translation or translation is not found, searches for any valid value (non empty string).
     *
     * @param string $fieldName
     * @param string|null $preferredLanguageCode
     *
     * @return string|null
     */
    function getTranslatedField(string $fieldName, string $preferredLanguageCode = null): ?string
    {
        $preferredLanguageCode = $preferredLanguageCode ?? (new Locale($this->localeService->getCurrentLocale()))->getLanguageCode();

        $fieldGetter = Strings::getGetterName($fieldName);
        $translatedValue = null;

        $preferredTranslation = $this->translations->get($preferredLanguageCode);
        if ($preferredTranslation) {
            // try assign preferred translation field value
            $translatedValue = $preferredTranslation->$fieldGetter();
        }
        if (!$translatedValue) {
            // try every translation for a valid field value
            foreach ($this->translations as $translation) {
                $translatedValue = $translation->$fieldGetter();
                if ($translatedValue) {
                    break;
                }
            }
        }

        return $translatedValue;
    }

    /**
     * Gets ITranslation for requestedLocale (if specified) or currentLocale. If currentLocale is used, can also return
     * any translation. If no ITranslation found, a new one is created.
     *
     * @param string|null $requestedLanguageCode
     *
     * @return ITranslation
     * @throws \Exception
     */
    public function getTranslation(string $requestedLanguageCode = null): ITranslation
    {
        $languageCode = $requestedLanguageCode ?? (new Locale($this->localeService->getCurrentLocale()))->getLanguageCode();

        $translation = $this->translations->get($languageCode);
        if (!$translation && !$requestedLanguageCode) {
            // when languageCode not specified and currentLocale translation not found, gets first translation
            $translation = $this->translations->first();
        }

        if (!$translation) {
            // when translation not found, is created in requestedLocale or currentLocale
            $translation = $this->createTranslation($languageCode);

            $this->addTranslation($translation);
        }

        return $translation;
    }

    /**
     * @param string $languageCode
     *
     * @return ITranslation
     * @throws \Exception
     */
    public function createTranslation(string $languageCode): ITranslation
    {
        /** @var Language $language */
        $language = $this->languageRepository->findById($languageCode, true);
        $translationClass = self::class . 'Translation';

        /** @var ITranslation $translation */
        $translation = new $translationClass();
        $translation->setLanguage($language);

        return $translation;
    }

    /**
     * Adds a translation to the translatable. Replaces old translation if new locale is set
     *
     * @param ITranslation $translation
     */
    public function addTranslation(ITranslation $translation): void
    {
        if ($this instanceof ITranslatable) {
            $this->translations->set($translation->getLanguage()->getId(), $translation);

            $translation->setTranslatable($this);
        } else {
            throw new TraitNotImplementingInterfaceException($this, TTranslatable::class, ITranslatable::class);
        }
    }

    /**
     * @return ITranslation[]
     */
    public function getTranslations(): array
    {
        return $this->translations->toArray();
    }

    /**
     * @param ILocaleService $localeService
     * @param LanguageRepository $languageRepository
     */
    public function feedTranslatable(ILocaleService $localeService, LanguageRepository $languageRepository): void
    {
        $this->localeService = $localeService;
        $this->languageRepository = $languageRepository;
    }
}
