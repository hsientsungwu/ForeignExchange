<?php
namespace Forex;

require_once(__DIR__ . '/config.php');

use Forex\ForexCrawler as ForexCrawler;

$crawler = new ForexCrawler();

$result = $crawler->getTrades();

var_dump($result);
