<?php

declare(strict_types=1);

namespace Rixafy\Language;

use Rixafy\Language\Exception\LanguageNotProvidedException;

class LanguageStaticHolder
{
    /** @var Language */
    private static $language;

    public static function setLanguage(Language $language): void
    {
        self::$language = $language;
    }

    /**
     * @throws LanguageNotProvidedException
     */
    public static function getLanguage(): Language
    {
        if (self::$language === null) {
            throw LanguageNotProvidedException::neverSet();
        }

        return self::$language;
    }
}