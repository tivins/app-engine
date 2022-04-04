# Engine

* Data
* Boot
* Engine
* Env -> paths
  * -> Path/URL
* Settings
---

# app-engine

## Configuration

### Automatic

```shell
vendor/bin/ae.php 
```

### Manual

See also [CHANGES.md](CHANGES.md).

#### Directories

```text
root
├── bin          # CLI scripts
├── files        # private files
│   └── cache    # private cache
├── public       # HTTP accessible scripts
│   └── cache    # public cache
├── settings     # application configurations
├── templates    # HTML data
└── vendor       # composer
```

See also [`Tivins\AppEngine\Env`](src/AppEngine/Env.php) class

#### Basic files

Append to `.gitignore`:

```gitignore
/files/cache
*.settings.php
```

### Controllers

* Controllers are able to answer to an HTTP request.
* Controllers classes should extends [`Tivins\AppEngine\Controller`](src/AppEngine/Controller.php)
* Controllers' public methods should have `#[APIAccess]` attribute (see tivins/php-common repository).
* Controllers' public methods should be `static` and should never return.
* Controllers' public methods should finally call `HTTP::sendResponse()`, by example, by using [`HTMLPage`](src/AppEngine/HTMLPage.php).

Minimal example:
```php
class MyController extends Controller 
{
    #[APIAccess]
    public static function index(): never 
    { 
        HTTP::send('hello', ContentType::TEXT, Status::OK);
    }
}
```

see #default-controllers




### Index

`public/index.php` should define available controllers, then call `Engine::start()`. Example:

```php
<?php
use \Tivins\AppEngine\Engine; 
require __dir__ . '/../vendor/autoload.php';
Engine::addController(MyController1::class);
Engine::addController(MyController2::class);
// ... add more controllers
Engine::start(__dir__ . '/..'); # never return
```

### Default controllers

#### Basic

#### UserController

#### CacheController

### Extending default controllers

### Meta data and Cache Controller

