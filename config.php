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