<?php

namespace Tivins\AppEngine\Controllers;

use Tivins\Core\Http\ContentType;
use Tivins\Core\Http\Method;
use Tivins\Core\Routing\Route;

class CacheController extends \Tivins\AppEngine\Controller
{
    public const SERVICE = 'cache';

    #[Route(
        methods: [Method::GET],
        permissions: ['public'],
        path: 'js',
        accept: [ContentType::JS],
    )]
    public function js()
    {

    }
}