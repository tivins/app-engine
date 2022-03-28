<?php

namespace Tivins\AppEngine;

use Tivins\Core\Msg;
use Tivins\Database\Database;
use Tivins\I18n\Language;
use Tivins\UserPack\UserModule;

class AppData
{
    private static Msg         $msg;
    private static AppSettings $settings;
    private static HTMLPage    $htmlPage;
    private static Language    $language   = Language::English;
    private static ?Database   $db         = null;
    private static ?UserModule $userModule = null;

    public static function setDatabase(?Database $db): void
    {
        self::$db = $db;
    }

    public static function db(): ?Database
    {
        return self::$db;
    }

    /**
     * @return Msg
     */
    public static function msg(): Msg
    {
        return self::$msg;
    }

    /**
     * @param Msg $msg
     */
    public static function setMessenger(Msg $msg): void
    {
        self::$msg = $msg;
    }

    /**
     * @return AppSettings
     */
    public static function settings(): AppSettings
    {
        return self::$settings;
    }

    /**
     * @param AppSettings $settings
     */
    public static function setSettings(AppSettings $settings): void
    {
        self::$settings = $settings;
    }


    public static function setUserModule(?UserModule $userModule)
    {
        self::$userModule = $userModule;
    }

    public static function usermod(): ?UserModule
    {
        return self::$userModule;
    }

    /**
     * @return HTMLPage
     */
    public static function htmlPage(): HTMLPage
    {
        return self::$htmlPage;
    }

    /**
     * @param HTMLPage $htmlPage
     */
    public static function setHTMLPage(HTMLPage $htmlPage): void
    {
        self::$htmlPage = $htmlPage;
    }

    public static function getLanguageCode(): string
    {
        return self::getLanguage()->value;
    }

    /**
     * @return Language
     */
    public static function getLanguage(): Language
    {
        return self::$language;
    }

    /**
     * @param ?Language $language
     */
    public static function setLanguage(Language $language): void
    {
        self::$language = $language;
    }


}
