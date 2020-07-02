<?php

namespace BastSys\LanguageBundle\Entity\Translation;

use BastSys\LanguageBundle\Entity\Language\Language;

/**
 * Interface ITranslation
 * @package BastSys\LanguageBundle\Entity
 * @author  mirkl
 */
interface ITranslation
{
    /**
     * @return string
     */
    function getLocale(): string;

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
