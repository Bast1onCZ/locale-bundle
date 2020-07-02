<?php

namespace BastSys\LocaleBundle\Entity\Language;

use BastSys\LocaleBundle\Entity\Translation\ATranslation;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class LanguageTranslation
 * @package BastSys\LocaleBundle\Entity\Language
 * @author mirkl
 *
 * @ORM\Entity()
 * @ORM\Table(name="bastsys_locale_bundle__language_translation")
 */
class LanguageTranslation extends ATranslation
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * LanguageTranslation constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
