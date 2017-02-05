<?php

namespace Forex;

// define global variables
define('FOREXFILEPATH', __DIR__ . '/../');
define("TWIG_TEMPLATE_FOLDER", FOREXFILEPATH . '/template/');
define("TWIG_TEMPLATE_CACHE_FOLDER", FOREXFILEPATH . '/cache/');
define('URL', 'http://forexfactory.app');


// class mapping for autloading
$mapping = array(
    'Forex\Crawler' => FOREXFILEPATH . '/Crawler.php',
    'Forex\ForexCrawler' => FOREXFILEPATH . '/ForexCrawler.php',
    'Forex\Request' => FOREXFILEPATH . '/Request.php',
    'Forex\Trade' => FOREXFILEPATH . '/Trade.php',
    'Forex\Trader' => FOREXFILEPATH . '/Trader.php',
);

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);

// require autload class from composer
require_once FOREXFILEPATH . '/vendor/autoload.php';

// require list of function files
require_once FOREXFILEPATH . '/support/frontend.func.php';

// url mappings
$url_to_view_mappings = [
    'homepage' => [
        'file' => FOREXFILEPATH . '/views/home.php',
        'function' => 'home'
    ],
    "404" => [
        'file' => FOREXFILEPATH . '/views/404.php',
        'function' => 'FourZeroFour'
    ],
];