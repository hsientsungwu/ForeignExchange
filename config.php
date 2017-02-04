<?php

namespace Forex;

$mapping = array(
    'Forex\Crawler' => __DIR__ . '/Crawler.php'
);

spl_autoload_register(function ($class) use ($mapping) {
    if (isset($mapping[$class])) {
        require $mapping[$class];
    }
}, true);