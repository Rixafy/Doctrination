<?php

declare(strict_types=1);

namespace Rixafy\Doctrination;

use Rixafy\Doctrination\Exception\UnsetLanguageException;
use Rixafy\Doctrination\Language\Language;

class Doctrination
{
    /** @var Language */
    private static $language;

    public static function setLanguage(Language $language): void
    {
        self::$language = $language;
    }

    /**
     * @return Language
     * @throws UnsetLanguageException
     */
    public static function getLanguage(): Language
    {
        if (self::$language === null) {
            throw new UnsetLanguageException('Language was never set');
        }

        return self::$language;
    }
}