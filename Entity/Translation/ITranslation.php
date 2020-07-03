<?php

namespace BastSys\LocaleBundle\Entity\Translation;

use BastSys\LocaleBundle\Entity\Language\Language;

/**
 * Interface ITranslation
 * @package BastSys\LocaleBundle\Entity
 * @author  mirkl
 */
interface ITranslation
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
}
