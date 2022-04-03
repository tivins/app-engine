<?php

namespace Tivins\AppEngine;

use Tivins\Database\Connectors\Connector;
use Tivins\I18n\Language;
use Tivins\UserPack\UserModule;

class MailSettings
{
    public function __construct(
        private string $host,
        private string $username,
        private string $password,
        private string $SMTPSecure/* = 'tls'*/,
        private int    $port)
    {
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return MailSettings
     */
    public function setHost(string $host): MailSettings
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return MailSettings
     */
    public function setUsername(string $username): MailSettings
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return MailSettings
     */
    public function setPassword(string $password): MailSettings
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getSMTPSecure(): string
    {
        return $this->SMTPSecure;
    }

    /**
     * @param string $SMTPSecure
     * @return MailSettings
     */
    public function setSMTPSecure(string $SMTPSecure): MailSettings
    {
        $this->SMTPSecure = $SMTPSecure;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return MailSettings
     */
    public function setPort(int $port): MailSettings
    {
        $this->port = $port;
        return $this;
    }

}

class AppSettings
{
    private bool         $isProd           = false;
    private ?Connector   $connector        = null;
    private ?string      $sessionName      = null;
    private string       $userModuleClass  = UserModule::class;
    private string       $htmlEngineClass  = HTMLPage::class;
    private string       $iconLetter       = 'E';
    private string       $primaryColor     = '#08c';
    private string       $title            = '';
    private string       $shortTitle       = '';
    private string       $punchLine        = '';
    private string       $themeColor       = '#ffffff';
    private string       $backgroundColor  = '#ffffff';
    private array        $allowedLanguages = [Language::English];
    private MailSettings $mailSettings;

    // ----- getters / settings -----

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return AppSettings
     */
    public function setTitle(string $title): AppSettings
    {
        $this->title = $title;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getSessionName(): ?string
    {
        return $this->sessionName;
    }

    /**
     * @param string|null $sessionName
     * @return static
     */
    public function setSessionName(?string $sessionName): static
    {
        $this->sessionName = $sessionName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isProd(): bool
    {
        return $this->isProd;
    }

    /**
     * @param bool $isProd
     * @return static
     */
    public function setIsProd(bool $isProd): static
    {
        $this->isProd = $isProd;
        return $this;
    }

    public function getConnector(): ?Connector
    {
        return $this->connector;
    }

    public function setConnector(Connector $connector): static
    {
        $this->connector = $connector;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserModuleClass(): string
    {
        return $this->userModuleClass;
    }

    /**
     * Specify which UserModule class to use.
     *
     * @param ?string $class
     * @return static
     */
    public function setUserModuleClass(?string $class = null): static
    {
        $this->userModuleClass = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlEngineClass(): string
    {
        return $this->htmlEngineClass;
    }

    /**
     * @param string $class
     * @return AppSettings
     */
    public function setHtmlEngineClass(string $class): AppSettings
    {
        $this->htmlEngineClass = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getPunchLine(): string
    {
        return $this->punchLine;
    }

    /**
     * @param string $punchLine
     * @return AppSettings
     */
    public function setPunchLine(string $punchLine): AppSettings
    {
        $this->punchLine = $punchLine;
        return $this;
    }

    /**
     * @return string
     */
    public function getIconLetter(): string
    {
        return $this->iconLetter;
    }

    /**
     * @param string $iconLetter
     * @return AppSettings
     */
    public function setIconLetter(string $iconLetter): AppSettings
    {
        $this->iconLetter = $iconLetter;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimaryColor(): string
    {
        return $this->primaryColor;
    }

    /**
     * @param string $primaryColor
     * @return AppSettings
     */
    public function setPrimaryColor(string $primaryColor): AppSettings
    {
        $this->primaryColor = $primaryColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortTitle(): string
    {
        return $this->shortTitle;
    }

    /**
     * @param string $shortTitle
     * @return AppSettings
     */
    public function setShortTitle(string $shortTitle): AppSettings
    {
        $this->shortTitle = $shortTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getThemeColor(): string
    {
        return $this->themeColor;
    }

    /**
     * @param string $themeColor
     * @return AppSettings
     */
    public function setThemeColor(string $themeColor): AppSettings
    {
        $this->themeColor = $themeColor;
        return $this;
    }

    /**
     * @return string
     */
    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     * @return AppSettings
     */
    public function setBackgroundColor(string $backgroundColor): AppSettings
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    /**
     * @return array
     */
    public function getAllowedLanguages(): array
    {
        return $this->allowedLanguages;
    }

    /**
     * @param Language ...$allowedLanguages
     * @return AppSettings
     */
    public function setAllowedLanguages(Language ...$allowedLanguages): AppSettings
    {
        $this->allowedLanguages = $allowedLanguages;
        return $this;
    }

    /**
     * @param Language ...$allowedLanguages
     * @return $this
     */
    public function addAllowedLanguages(Language ...$allowedLanguages): AppSettings
    {
        $this->allowedLanguages = array_merge($this->allowedLanguages, $allowedLanguages);
        return $this;
    }

    /**
     * @return MailSettings
     */
    public function getMailSettings(): MailSettings
    {
        return $this->mailSettings;
    }

    /**
     * @param MailSettings $mailSettings
     * @return AppSettings
     */
    public function setMailSettings(MailSettings $mailSettings): AppSettings
    {
        $this->mailSettings = $mailSettings;
        return $this;
    }


}