<?php

namespace Tivins\AppEngine;

use Tivins\Database\Connectors\Connector;
use Tivins\UserPack\UserModule;

class AppSettings
{
    public bool        $isProd          = false;
    private ?Connector $connector       = null;
    private ?string    $sessionName     = null;
    private string     $userModuleClass = UserModule::class;
    private string     $htmlEngineClass = HTMLPage::class;
    private string     $iconLetter      = 'E';
    private string     $primaryColor    = '#08c';
    private string     $title           = '';
    private string     $shortTitle      = '';
    private string $punchLine       = '';
    private string $themeColor      = '#ffffff';
    private string $backgroundColor = '#ffffff';

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


}