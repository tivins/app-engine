<?php

use Tivins\AppEngine\AppData;
use Tivins\AppEngine\Boot;
use Tivins\Core\OptionArg;
use Tivins\Core\OptionsArgs;

require __dir__ . '/../vendor/autoload.php';

$opts = (new OptionsArgs())
    ->add(new OptionArg('n', true, 'name'))
    ->add(new OptionArg('p', true, 'password'))
    ->add(new OptionArg('m', true, 'mail'));

$args = Boot::init(__dir__ . '/..');

$usermod = (AppData::usermod())
    ?->install()
    ->createUser(
        $args['name'] ?? 'admin',
        $args['mail'] ?? 'admin@example.com',
        $args['password'] ?? 'aStrongPassword'
    );