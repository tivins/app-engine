<?php

namespace Tivins\AppEngine;

use Tivins\Core\Code\Singleton;
use Tivins\Database\Connectors\Connector;


class AppSettings extends Singleton
{
    public bool        $isProd      = false;
    private string     $ROOT_DIR    = '';
    private ?Connector $connector   = null;
    private ?string    $sessionName = null;

    /**
     * @return string|null
     */
    public function getSessionName(): ?string
    {
        return $this->sessionName;
    }

    /**
     * @param string|null $sessionName
     * @return AppSettings
     */
    public function setSessionName(?string $sessionName): AppSettings
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
     * @return AppSettings
     */
    public function setIsProd(bool $isProd): AppSettings
    {
        $this->isProd = $isProd;
        return $this;
    }

    public function getConnector(): ?Connector
    {
        return $this->connector;
    }

    public function setConnector(Connector $connector)
    {
        $this->connector = $connector;
    }

    public function getRootDir(): string
    {
        return $this->ROOT_DIR;
    }

    public function setRootDir(string $dir): static
    {
        $this->ROOT_DIR = $dir;
        return $this;
    }

//    public function getLocalesDir(): string {
//        return $this->ROOT_DIR . $this->I18N_DIR;
//    }
//    public function getSettingsDir(): string {
//        return $this->ROOT_DIR . $this->SETTINGS_DIR;
//    }
}