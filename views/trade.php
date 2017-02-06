<?php
namespace Forex;

use Forex\ForexCrawler as ForexCrawler;
use Forex\Trade as Trade;

/**
 * View function for home page
 * @param  Request $request 
 * @return string          
 */
function trade($request) {
    $selectedCurrencies = [];

    if ($request->post) {
        $post = filter_var_array($request->post, FILTER_SANITIZE_STRING);

        $selectedCurrencies = $post['currency'];
    }

    $crawler = new ForexCrawler;
    $result = $crawler->getTrades($selectedCurrencies);

    $trades = ($result['success'] ? $result['trades'] : []);

    if (empty($selectedCurrencies)) $selectedCurrencies[] = 'USD/CAD';

    $templateData = [
        'trades' => $trades,
        'currencies' => Trade::getAvailableCurrencies(),
        'selected' => [
            'currency' => $selectedCurrencies,
        ]
    ];

    return renderView('html', 'trade.html', $templateData);
}
