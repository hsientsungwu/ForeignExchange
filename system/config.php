<?php

namespace Forex;

// define global variables
define('FOREXFILEPATH', __DIR__ . '/../');
define("TWIG_TEMPLATE_FOLDER", FOREXFILEPATH . '/template/');
define("TWIG_TEMPLATE_CACHE_FOLDER", FOREXFILEPATH . '/cache/');
define('URL', 'http://forexfactory.app');


// class mapping for autloading
$mapping = array(
    'Forex\Crawler' => FOREXFILEPATH . '/model/Crawler.php',
    'Forex\ForexCrawler' => FOREXFILEPATH . '/model/ForexCrawler.php',
    'Forex\Request' => FOREXFILEPATH . '/model/Request.php',
    'Forex\Trade' => FOREXFILEPATH . '/model/Trade.php',
    'Forex\Trader' => FOREXFILEPATH . '/model/Trader.php',
);

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);

// require autload class from composer
require_once FOREXFILEPATH . '/vendor/autoload.php';

// require system file
require_once FOREXFILEPATH . '/system/url.php';

// require list of function files
require_once FOREXFILEPATH . '/support/frontend.func.php';