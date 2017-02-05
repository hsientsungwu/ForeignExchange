<?php
namespace Forex;

require_once(__DIR__ . '/config.php');

use Forex\ForexCrawler as ForexCrawler;

$crawler = new ForexCrawler();

$result = $crawler->getActivity();

var_dump($result);
