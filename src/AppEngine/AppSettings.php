<?php

namespace Tivins\AppEngine;

use Tivins\Core\Code\Singleton;
use Tivins\Database\Connectors\Connector;


class AppSettings extends Singleton
{
    private string $ROOT_DIR = '';

    public ?Connector $connector   = null;
    public ?string    $sessionName = null;
    public bool       $isProd      = false;

    public function setRootDir(string $dir): static {
        $this->ROOT_DIR = $dir;
        return $this;
    }
    public function getRootDir(): string {
        return $this->ROOT_DIR;
    }

//    public function getLocalesDir(): string {
//        return $this->ROOT_DIR . $this->I18N_DIR;
//    }
//    public function getSettingsDir(): string {
//        return $this->ROOT_DIR . $this->SETTINGS_DIR;
//    }
}