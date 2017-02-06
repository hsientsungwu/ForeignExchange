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
    $download = false;

    // if there is a post data
    if ($request->post) {
        // sanitize the post data
        $post = filter_var_array($request->post, FILTER_SANITIZE_STRING);

        $selectedCurrencies = $post['currency'];

        // set download flag to true if user wants to download
        if ($post['action'] == 'download') $download = true;
    }

    // initiate crawler
    $crawler = new ForexCrawler;

    // get trades from forexfactory.com
    $result = $crawler->getTrades($selectedCurrencies);

    $trades = ($result['success'] ? $result['trades'] : []);

    if ($download) {
        // predefined report columns
        $headers = [
            'Trade ID', 'Time', 'Currency', 'Action', 'Caption', 'Value', 'Trader', 'Return', 'Pips'
        ];

        $data = [];

        // iterate through trades to reformat into array
        foreach ($trades as $trade) {
            $data[] = [
                'id' => $trade->id, 
                'time' => $trade->getTime('m/d/Y H:i A'),
                'currency' => $trade->currency,
                'action' => $trade->action,
                'caption' => $trade->caption,
                'value' => $trade->value,
                'trader' => $trade->trader->getUsername(),
                'return' => $trade->return,
                'pips' => $trade->pips
            ];
        }

        $templateData = [
            'filename' => 'trades.csv',
            'headers' => $headers,
            'data' => $data
        ];

        // render it as csv download
        return renderView('csv', '', $templateData);
    } else {
        // if there is none selected, default USD/CAD will be pre-selected
        if (empty($selectedCurrencies)) $selectedCurrencies[] = 'USD/CAD';

        $templateData = [
            'trades' => $trades,
            'currencies' => Trade::getAvailableCurrencies(), // get list of available currency pairs forexfactory.com supported
            'selected' => [
                'currency' => $selectedCurrencies,
            ]
        ];

        // render it as html string
        return renderView('html', 'trade.html', $templateData);
    }    
}