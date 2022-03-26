<?php

namespace Tivins\AppEngine;

use Throwable;
use Tivins\Core\Code\Exception;
use Tivins\Core\Http\{HTTP, QueryString, Request};
use Tivins\Core\{HTML\FormSecurity, Msg, OptionArg, OptionsArgs};
use Tivins\Database\Database;
use Tivins\Database\Exceptions\ConnectionException;
use Tivins\UserPack\UserModule;

/**
 *
 * @see Boot::init()
 */
class Boot
{
    /**
     * Initialize the application environment, such as per-host settings, session, database connection, ...etc.
     *
     * Usages :
     *
     * From index.php (ex in ROOT/public):
     * ```php
     * require __dir__ . '/../vendor/autoload.php';
     * Boot::init(__dir__ . '/..'); // Project's dir.
     * ```
     *
     * From script (ex in ROOT/bin):
     * ```php
     * require __dir__ . '/../vendor/autoload.php';
     * $options = new OptionsArgs(); // and add some OptionArg...
     * $args = Boot::init(__dir__ . '/..', $options); // Project's dir.
     * ```
     *
     * @param string $rootDir
     *
     * @param ?OptionsArgs $optionsArgs
     *
     * @return array The result of OptionsArgs parsing (CLI Only) or an empty array.
     *
     * @see Boot::run()
     */
    public static function init(string $rootDir, ?OptionsArgs $optionsArgs = null): array
    {
        //
        // -- Exception handler
        //
        set_exception_handler([Engine::class, 'throwableHandler']);

        //
        // -- Main process
        //
        Env::setRootDir($rootDir);
        $options = self::readSettingsFromCLI($optionsArgs);

        // -- Create new Settings and store in AppData,
        // -- before to include settings file.
        $appSettings = new AppSettings;
        AppData::setSettings($appSettings);

        //
        // -- Load settings
        //
        $domain = str_replace(['.', ':'], '-', $_SERVER['HTTP_HOST']);
        $settingsFilename = Env::getPath('/settings/' . $domain . '.settings.php');
        if (! is_readable($settingsFilename)) {
            throw new Exception(
                "Settings not found",
                $settingsFilename . " not found");
        }
        require_once $settingsFilename;

        //
        // -- Init session
        //
        session_name($appSettings->getSessionName());
        session_start();
        FormSecurity::init();

        //
        // -- Query String
        //
        QueryString::parse();


        //
        // -- Database
        //
        $connector = $appSettings->getConnector();
        if ($connector) {
            try {
                AppData::setDatabase(new Database($connector));
            } catch (ConnectionException $e) {
                throw new Exception('Cannot access database', $e->getMessage());
            }
        }

        //
        // -- User Pack
        //
        if ($appSettings->getUserModuleClass() && AppData::db()) {
            // new UserModule(AppData::db())
            AppData::setUserModule(new ($appSettings->getUserModuleClass())(AppData::db()));
        }

        AppData::setMessenger(new Msg());
        AppData::setHTMLPage(new ($appSettings->getHtmlEngineClass())());

        //
        // -- Return options values (for CLI)
        //
        return $options;
    }


    /**
     * If the request come from CLI, to find the right settings,we need to know the requested domain.
     * The argument `-u` or `--uri` should be defined. So, the `$_SERVER` variables could be set accordingly.
     *
     * @param OptionsArgs|null $optionsArgs
     *      A specific OptionsArgs can be provided. If $optionsArgs is null, a new OptionsArgs will be used instead.
     */
    private static function readSettingsFromCLI(?OptionsArgs $optionsArgs = null): array
    {
        if (!Request::isCLI()) {
            return [];
        }
        $args = ($optionsArgs ?? new OptionsArgs)->add(new OptionArg('u', true, 'uri'))->parse();

        if (empty($args['uri'])) {
            die('uri argument is missing' . PHP_EOL);
        }
        $uri = parse_url($args['uri']);

        //
        // -- Override $_SERVER variables
        //
        $_SERVER['HTTP_HOST']   = $uri['host'] . (':' . $uri['port']);
        $_SERVER['HTTPS']       = $uri['scheme'] == 'https' ? 'ON' : '';
        $_SERVER['SERVER_PORT'] = $uri['port'];
        $_SERVER['REQUEST_URI'] = '/';
        return $args;
    }
}