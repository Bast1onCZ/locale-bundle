<?php

namespace BastSys\LocaleBundle\Entity\Translation;

use BastSys\LocaleBundle\Entity\Language\Language;
use BastSys\UtilsBundle\Entity\Identification\IIdentifiableEntity;

/**
 * Interface ITranslation
 * @package BastSys\LocaleBundle\Entity
 * @author  mirkl
 */
interface ITranslation extends IIdentifiableEntity
{
    /**
     * @return Language
     */
    function getLanguage(): Language;

    /**
     * @param Language $language
     */
    function setLanguage(Language $language): void;

    /**
     * @param ITranslatable $translatable
     */
    function setTranslatable(ITranslatable $translatable): void;

    /**
     * @return ITranslatable
     */
    function getTranslatable(): ITranslatable;
}
