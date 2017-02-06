<?php
namespace Forex;

use Forex\ForexCrawler as ForexCrawler;

/**
 * View function for home page
 * @param  Request $request 
 * @return string          
 */
function trade($request) {
    $crawler = new ForexCrawler;
    $result = $crawler->getTrades();

    $trades = ($result['success'] ? $result['trades'] : []);
    $templateData = [
        'trades' => $trades
    ];
    
    return renderView('html', 'trade.html', $templateData);
}
