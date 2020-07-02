<?php

namespace BastSys\LocaleBundle\Structure;

use BastSys\UtilsBundle\Model\IEquatable;

/**
 * Class Locale
 * @package BastSys\UtilsBundle\Model
 * @author  mirkl
 */
class Locale implements IEquatable
{
    /**
     *
     */
    private const LOCALE_REGEXP = '/^([a-z]{2})_([A-Z]{2})$/';

    /** @var string */
    private $languageCode;

    /** @var string */
    private $countryCode;

    /**
     * Locale constructor.
     *
     * @param string $locale
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $locale)
    {
        $matches = [];
        if (!preg_match(self::LOCALE_REGEXP, $locale, $matches)) {
            throw new \InvalidArgumentException("Wrong locale '$locale' argument, locale must match '" . self::LOCALE_REGEXP . "'");
        }

        $this->languageCode = $matches[1];
        $this->countryCode = $matches[2];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->languageCode . '_' . $this->countryCode;
    }

    /**
     * @return string
     */
    public function toDashString(): string
    {
        return $this->languageCode . '-' . $this->countryCode;
    }

    /**
     * @param mixed $comparable
     *
     * @return bool
     */
    function equals($comparable): bool
    {
        return $comparable instanceof self &&
            $comparable->languageCode === $this->languageCode &&
            $comparable->countryCode === $this->countryCode;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     *
     * @return Locale new instance
     */
    public function setCountryCode(string $countryCode): Locale
    {
        return new Locale($this->languageCode . '_' . $countryCode);
    }

    /**
     * @return string
     */
    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    /**
     * @param string $languageCode
     *
     * @return Locale new instance
     */
    public function setLanguageCode(string $languageCode): Locale
    {
        return new Locale($languageCode . '_' . $this->countryCode);
    }

}
