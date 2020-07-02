<?php

namespace BastSys\LocaleBundle\Entity\Country;

use BastSys\LocaleBundle\Entity\Currency\Currency;
use BastSys\LocaleBundle\Entity\Language\Language;
use BastSys\LocaleBundle\Entity\Translation\ITranslatable;
use BastSys\LocaleBundle\Entity\Translation\TTranslatable;
use BastSys\LocaleBundle\Service\CountryFlagService;
use BastSys\UtilsBundle\Entity\Identification\IIdentifiableEntity;
use BastSys\UtilsBundle\Model\IEquatable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Country
 * @package BastSys\LocaleBundle\Entity\Country
 * @author mirkl
 *
 * @ORM\Entity()
 * @ORM\Table(name="bastsys_locale_bundle__country")
 */
class Country implements IIdentifiableEntity, IEquatable, ITranslatable
{
    use TTranslatable {
        __construct as initTranslatable;
    }

    /**
     * @var string - two letter code (e.g. 'CZ')
     * @ORM\Column(name="id", type="string", length=2)
     * @ORM\Id()
     */
    private $alpha2;

    /**
     * @var string - three letter code (e.g. 'CZE')
     * @ORM\Column(type="string", length=3)
     */
    private $alpha3;

    /**
     * @var string - three letter numeric code (e.g. '203')
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @var Currency
     * @ORM\ManyToOne(targetEntity="BastSys\LocaleBundle\Entity\Currency\Currency", fetch="EXTRA_LAZY")
     */
    private $currency;

    /**
     * @var CountryFlagService|null injected on postLoad or postPersist
     */
    private ?CountryFlagService $flagService;

    /**
     * @var Language
     * @ORM\ManyToOne(targetEntity="BastSys\LocaleBundle\Entity\Language\Language", inversedBy="mainSpeakingCountries", fetch="EXTRA_LAZY")
     */
    private $mainLanguage;

    /**
     * CountryType constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->initTranslatable();
    }

    /**
     * @param CountryFlagService $flagService
     */
    public function feed(CountryFlagService $flagService)
    {
        $this->flagService = $flagService;
    }

    /**
     * @return string
     */
    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    /**
     * @param string $alpha2
     *
     * @return Country
     */
    public function setAlpha2(string $alpha2): Country
    {
        $this->alpha2 = $alpha2;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlpha3(): string
    {
        return $this->alpha3;
    }

    /**
     * @param string $alpha3
     *
     * @return Country
     */
    public function setAlpha3(string $alpha3): Country
    {
        $this->alpha3 = $alpha3;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Country
     */
    public function setCode(string $code): Country
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getFlagLink(): string
    {
        return $this->flagService->generateFlagUrl($this);
    }

    /**
     * @return Language
     */
    public function getMainLanguage(): Language
    {
        return $this->mainLanguage;
    }

    /**
     * @param Language $mainLanguage
     *
     * @return Country
     */
    public function setMainLanguage(Language $mainLanguage): Country
    {
        $this->mainLanguage = $mainLanguage;
        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getName()
    {
        /** @var CountryTranslation $translation */
        $translation = $this->getTranslation();
        return $translation->getName();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getOwnTranslationName()
    {
        /** @var CountryTranslation $translation */
        $translation = $this->getTranslation($this->mainLanguage->getCode());
        return $translation->getName();
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param IEquatable $comparable
     * @return bool
     */
    function equals($comparable): bool
    {
        return $comparable instanceof Country && $this->getId() === $comparable->getId();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->alpha2;
    }
}
