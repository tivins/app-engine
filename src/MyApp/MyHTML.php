<?php

namespace Tivins\MyApp;

use Tivins\AppEngine\Cache\PublicCache;
use Tivins\Core\Http\ContentType;
use Tivins\Core\Http\Status;

class MyHTML extends \Tivins\AppEngine\HTMLPage
{
    public function __construct()
    {
        parent::__construct('');
        $this->addCSS(
            PublicCache::url('/css/engine/rules.css'),
            PublicCache::url('/css/engine/theme.css'),
        );
    }
}