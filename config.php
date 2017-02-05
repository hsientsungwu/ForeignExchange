<?php

namespace Forex;

$mapping = array(
    'Forex\Crawler' => __DIR__ . '/Crawler.php',
    'Forex\ForexCrawler' => __DIR__ . '/ForexCrawler.php',
    'Forex\Trade' => __DIR__ . '/Trade.php',
    'Forex\Trader' => __DIR__ . '/Trader.php',
);

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/support/frontend.func.php';

define("TWIG_TEMPLATE_FOLDER", __DIR__ . '/template/');
define("TWIG_TEMPLATE_CACHE_FOLDER", __DIR__ . '/cache/');