<?php

namespace BastSys\LanguageBundle\Exception;

/**
 * Class UnknownLocaleException
 * @package BastSys\LanguageBundle\Exception
 * @author mirkl
 */
class UnknownLocaleException extends \Exception
{
    /**
     * UnknownLocaleException constructor.
     *
     * @param string $unknownLocale
     */
    public function __construct(string $unknownLocale)
    {
        parent::__construct("Unknown requested locale '$unknownLocale'", 404);
    }
}
