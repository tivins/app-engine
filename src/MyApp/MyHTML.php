<?php

namespace Tivins\MyApp;

use Tivins\Core\Http\ContentType;
use Tivins\Core\Http\Status;

class MyHTML extends \Tivins\AppEngine\HTMLPage
{
    public function __construct()
    {
        parent::__construct('');
        $this->addCSS('/assets/engine.css');
    }
}