<?php

namespace BastSys\LocaleBundle\Entity\Language;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Entity\Translation\TTranslatable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Language
 * @package BastSys\LocaleBundle\Entity\Language
 * @author mirkl
 *
 * @ORM\Entity()
 * @ORM\Table(name="bastsys_locale_bundle__language")
 */
class Language
{
    use TTranslatable {
        __construct as initTranslatable;
    }

    /**
     * @var string language code
     *
     * @ORM\Column(name="id", type="string", length=10)
     * @ORM\Id()
     */
    private $id;

    /**
     * @var ArrayCollection|Country[]
     * @ORM\OneToMany(targetEntity="BastSys\LocaleBundle\Entity\Country\Country", mappedBy="mainLanguage")
     */
    private $mainSpeakingCountries;

    /**
     * @var string[]
     * @ORM\Column(type="simple_array", length=255)
     */
    private $platforms;

    /**
     * Language constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->initTranslatable();
        $this->mainSpeakingCountries = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setCode(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string[]
     */
    public function getPlatforms(): array
    {
        return $this->platforms;
    }

    /**
     * @param string[] $platforms
     */
    public function setPlatforms(array $platforms): void
    {
        $this->platforms = $platforms;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getName(): string
    {
        /** @var LanguageTranslation $translation */
        $translation = $this->getTranslation();
        return $translation->getName();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getOwnTranslationName(): string
    {
        /** @var LanguageTranslation $translation */
        $translation = $this->getTranslation($this->getCode());
        return $translation->getName();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->id;
    }

    /**
     * @return Country[]
     */
    public function getMainSpeakingCountries(): array
    {
        return $this->mainSpeakingCountries->toArray();
    }
}
