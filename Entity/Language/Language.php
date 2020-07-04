<?php

namespace BastSys\LocaleBundle\Entity\Language;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Entity\Translation\ITranslatable;
use BastSys\LocaleBundle\Entity\Translation\TTranslatable;
use BastSys\UtilsBundle\Entity\Identification\IIdentifiableEntity;
use BastSys\UtilsBundle\Model\IEquatable;
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
class Language implements IIdentifiableEntity, IEquatable, ITranslatable
{
    use TTranslatable {
        __construct as initTranslatable;
    }

    /**
     * @var string language code
     *
     * @ORM\Column(name="id", length=10)
     * @ORM\Id()
     */
    private $code;

    /**
     * @var ArrayCollection|Country[]
     * @ORM\OneToMany(targetEntity="BastSys\LocaleBundle\Entity\Country\Country", mappedBy="mainLanguage")
     */
    private $mainSpeakingCountries;

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
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
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
        return $this->code;
    }

    /**
     * @return Country[]
     */
    public function getMainSpeakingCountries(): array
    {
        return $this->mainSpeakingCountries->toArray();
    }

    /**
     * @param IEquatable $comparable
     * @return bool
     */
    public function equals($comparable): bool
    {
        return $comparable instanceof Language && $this->code === $comparable->getId();
    }
}
