<?php

namespace BastSys\LocaleBundle\Entity\Translation;

use BastSys\LocaleBundle\Entity\Language\Language;
use BastSys\UtilsBundle\Entity\Identification\AUuidEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ATranslation
 * @package BastSys\LocaleBundle\Entity\Translation
 * @author  mirkl
 *
 * @ORM\MappedSuperclass()
 */
abstract class ATranslation extends AUuidEntity implements ITranslation
{
    /**
     * @var ITranslatable
     */
    protected $translatable;

    /**
     * @var Language
     * @ORM\ManyToOne(targetEntity="BastSys\LocaleBundle\Entity\Language\Language", fetch="EXTRA_LAZY")
     */
    protected $language;

    /**
     * ATranslation constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }

    /**
     * @param ITranslatable $translatable
     */
    public function setTranslatable(ITranslatable $translatable): void
    {
        $this->translatable = $translatable;
    }

    /**
     * @return ITranslatable
     */
    public function getTranslatable(): ITranslatable
    {
        return $this->translatable;
    }

}
