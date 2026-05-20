<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    public $baseURL = 'http://localhost:8080/';

    public $indexFile = 'index.php';

    public $uriProtocol = 'REQUEST_URI';

    public $defaultLocale = 'es';

    public $negotiateLocale = false;

    public $supportedLocales = ['es', 'en'];

    public $appTimezone = 'America/Guayaquil';

    public $charset = 'UTF-8';

    public $sessionDriver = 'File';

    public $sessionCookieName = 'pgpr_session';

    public $sessionSavePath = WRITEPATH . 'session';

    public $sessionMatchIP = false;

    public $sessionExpiration = 7200;

    public $session regenerate = true;

    public $session_regenerate = true;

    public $cookiePrefix = '';

    public $cookiePath = '/';

    public $cookieDomain = '';

    public $cookieSecure = false;

    public $cookieHTTPOnly = false;

    public $cookieSameSite = 'Lax';

    public $proxyIPs = '';

    public $CSRFProtection = true;

    public $CSRFTokenName = 'csrf_token';

    public $CSRFHeaderName = 'X-CSRF-TOKEN';

    public $CSRFCookieName = 'csrf_cookie_name';

    public $CSRFExpire = 7200;

    public $CSRFRegenerate = true;

    public $CSRFExcludeURIs = [];

    public $CSPEnabled = false;

    public $appNamespace = 'App';

    public $composerAutoload = APPPATH . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

    public $theme = 'default';

    public $controllerNamespace = 'App\Controllers';

    public $defaultController = 'Home';

    public $defaultMethod = 'index';

    public $middlewareNamespace = 'App\Filters';

    public $viewPath = APPPATH . 'Views/';
}