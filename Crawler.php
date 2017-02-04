<?php

namespace Forex;

/**
 * Crawler class serves as the crawler that retrieve html of a given url.
 */
class Crawler {
    /**
     * Current history ID
     * @var integer
     */
    private $history = 0;

    /**
     * Array of responses from curl result
     * @var array
     */
    private $response = [];

    /**
     * Array of curl information from curl result
     * @var array
     */
    private $curlInfo = [];

    /**
     * Function uses as the main property that crawls the page of a given url with provided configuration
     * @param  string $url    url to crawl
     * @param  array  $config configuration for curl
     * @return integer         history id
     */
    public function crawl($url, $config = []) {
        // increment of history id to make sure we have the updated id
        $this->history++;

        // curl initiation
        $ch = curl_init();

        // configuration for url target
        curl_setopt($ch, CURLOPT_URL, $url);

        // configuration for post fields
        if (array_key_exists('CURLOPT_POSTFIELDS', $config)) {
            $postFields = http_build_query($config['CURLOPT_POSTFIELDS']);
            
            curl_setopt($ch, CURLOPT_POST, 1); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        }
        
        // configuration for port
        if (array_key_exists('CURLOPT_PORT', $config)) {
            curl_setopt($ch, CURLOPT_PORT , $config['CURLOPT_PORT']);
        }
        
        // configuration of return transfer
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        // store curl response into response array and tag it with current history id
        $this->response[$this->history] = curl_exec($ch);

        // store curl information into curlInfo array and tag it with current history id
        $this->curlInfo[$this->history] = curl_getinfo($ch);

        return $this->history;
    }

    /**
     * Function uses to retrieve curl response from a given history id
     * @param  integer $historyId history id
     * @return array             response
     */
    public function getResponse($historyId = 0) {
        if ($historyId && array_key_exists($historyId, $this->response)) {
            return $this->response[$historyId];
        }

        return [];
    }

    /**
     * Function uses to retrieve curl information from a given history id
     * @param  integer $historyId history id
     * @return array             curl information
     */
    public function getCurlInfo($historyId = 0) {
        if ($historyId && array_key_exists($historyId, $this->curlInfo)) {
            return $this->curlInfo[$historyId];
        }

        return [];
    }
}