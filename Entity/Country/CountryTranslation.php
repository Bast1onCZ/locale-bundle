<?php

namespace BastSys\LocaleBundle\Entity\Country;

use BastSys\LocaleBundle\Entity\Translation\ATranslation;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class CountryTranslation
 * @package BastSys\LocaleBundle\Entity\Country
 * @author mirkl
 *
 * @ORM\Entity()
 * @ORM\Table(name="bastsys_locale_bundle__country_translation")
 */
class CountryTranslation extends ATranslation
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * CountryTranslationType constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CountryTranslation
     */
    public function setName(string $name): CountryTranslation
    {
        $this->name = $name;
        return $this;
    }
}
