<?php

namespace Forex;

use Forex\Crawler as Crawler;
use Forex\Trade as Trade;

/**
 * ForexCrawler class specialize in crawling and reorganize the data from forexfactory.com
 */
class ForexCrawler extends Crawler {
    /**
     * Function uses to retrieve trades from forexfactory.com
     * @param  array  $currencies array of currency pairs to filter
     * @return array             success falg and array of trades
     */
    public function getTrades($currencies = []) {
        $success = false;
        $trades = [];

        if (empty($currencies)) {
            // default currency pair if nothing was filtered
            $currencies[] = 'USDCAD';
        }

        // url to retrieve forexfactory trades
        $url = "https://www.forexfactory.com/flex.php?more=0";

        // post data - mimicing what's from the network call
        $postData = [
            's' => '',
            'securitytoken' => 'guest',
            'do' => 'saveoptions',
            'setdefault' => 'no',
            'ignoreinput' => 'no',
            'flex' => [
                'Trades/Activity_tradesActivity' => [
                    'idSuffix' => '',
                    '_flexForm_' => 'flexForm',
                    'modelData' => $this->getModelData(),
                    'trades/tradetype' => 'all',
                    'trades/accounttype' => 'live',
                    'mirs' => 0,
                    'trades/instruments' => $currencies
                ]
            ]
        ];

        // curl configuration
        $curlConfig = [
            'CURLOPT_POSTFIELDS' => $postData,
            'CURLOPT_PORT' => 443,
        ];

        // execute the crawl and obtain the history id
        $historyId = $this->crawl($url, $curlConfig);

        if ($this->curlInfo[$historyId]['http_code'] == 200) {
            // if everything goes well, http code will return 200
            $success = true;

            // initiate a new DOMDocument object
            $dom = new \DOMDocument;

            // load HTML from the curl response string
            @$dom->loadHTML($this->response[$historyId]);

            // base on the html returns from curl, there are multiple tables and only one we're interested ie. table with trades_activity__table class
            $tables = $dom->getElementsByTagName('table');

            foreach ($tables as $table) {
                $classes = $table->getAttribute('class');

                // only start the translation when correct table is iterated
                if ($classes == 'activity trades_activity__table') {
                    // every tr tag here represents each trade/activity/ticket
                    $trs = $table->getElementsByTagName('tr');

                    // iterate through the tr tags
                    foreach ($trs as $tr) {
                        // tr's id consisted of trader id, ticket id and transaction id
                        $tag_tr_attribute_id = $tr->getAttribute('id');
                        
                        if ($tag_tr_attribute_id == '') continue;

                        // 0 => flexActivity, 1 => trader id, 2 => trade id, 3 => transaction id
                        $tag_tr_attribute_id_exploded = explode('_', $tag_tr_attribute_id);

                        // each td represent block of information about this trade
                        // 0 => trade, 1 => trader, 2 => return, 3 => pips
                        $tds = $tr->getElementsByTagName('td');

                        // initiate a new ticket
                        $trade = new Trade;
                        $trade->setId($tag_tr_attribute_id_exploded[2]);
                        $trade->setTransaction($tag_tr_attribute_id_exploded[3]);

                        foreach ($tds as $index => $d) {
                            if ($index == 0) {
                                // trade information
                                $str = str_replace(["\n", "  "], " ", trim($d->nodeValue));
                                $raw = explode(' ', $str);

                                $trade->setCurrency($raw[0]);
                                $trade->setAction($raw[1]);
                                $trade->setValue($raw[2]);
                                $trade->setCaption(trim($raw[4]) . " " . trim($raw[5]));

                                $hour = str_replace('~', '', $raw[7]);
                                $trade->setTime($hour . ' hours');
                            } elseif ($index == 1) {
                                // trader information
                                $imgs = $d->getElementsByTagName('img');

                                // initiate trader object
                                $trader = new Trader([
                                    'id' => $tag_tr_attribute_id_exploded[1], 
                                    'avatar' => $imgs[0]->getAttribute('src'), 
                                    'username' => trim($d->nodeValue)]
                                );
                                $trade->setTrader($trader);
                            } elseif ($index == 2) {
                                // return information - requires some clean up (explode and trim) in order to retrieve the return value
                                $return = '';

                                $raw = str_replace("\n", '', trim($d->nodeValue));

                                if (strstr($raw, 'MTM')) {
                                    $mtm = explode("MTM", trim($raw));

                                    if (count($mtm) > 1) {
                                        $return = trim($mtm[count($mtm) - 2]) . "MTM";
                                    } else {
                                        $return = $raw;
                                    }
                                } else {
                                    $raw = explode(' ', str_replace('%', '% ', str_replace(" ", '', $raw)));
                                    $return = trim($raw[count($raw)-2]);
                                }

                                $trade->setReturn($return);
                            } elseif ($index == 3) {
                                // pips information - requires some clean up (explode and trim) in order to retrieve the pips value
                                $pips = '';

                                if (strstr($d->nodeValue, 'MTM')) {
                                    $mtm = explode("MTM", trim($d->nodeValue));

                                    if (count($mtm) > 1) {
                                        $pips = trim($mtm[count($mtm) - 2]) . "MTM";
                                    } else {
                                        $pips = $raw;
                                    }
                                } else {
                                    $raw = explode("\n", trim($d->nodeValue));
                                    $pips = (count($raw) == 1 ? $raw[0] : trim(array_pop($raw)));
                                }

                                $trade->setPips($pips);
                            }
                        }

                        // add the trade into trades array
                        $trades[] = $trade;
                    }

                    // we can break out of the table iteration since we have accomplished the scraping of data at this point.
                    break;
                }
            }
        }

        return ['success' => $success, 'trades' => $trades];
    }

    private function getModelData() {
        return "YTo4OntzOjEzOiJwcmVsb2FkT25WaWV3IjtiOjE7czoxODoiZGVmYXVsdEluc3RydW1lbnRzIjthOjk3OntpOjA7czo2OiJBVURDQUQiO2k6MTtzOjY6IkFVRENIRiI7aToyO3M6NjoiQVVEREtLIjtpOjM7czo2OiJBVURKUFkiO2k6NDtzOjY6IkFVRE5aRCI7aTo1O3M6NjoiQVVEUExOIjtpOjY7czo2OiJBVURTR0QiO2k6NztzOjY6IkFVRFVTRCI7aTo4O3M6NjoiQlRDVVNEIjtpOjk7czo2OiJDQURDSEYiO2k6MTA7czo2OiJDQURKUFkiO2k6MTE7czo2OiJDSEZKUFkiO2k6MTI7czo2OiJDSEZQTE4iO2k6MTM7czo2OiJDSEZTR0QiO2k6MTQ7czo2OiJFVVJBVUQiO2k6MTU7czo2OiJFVVJDQUQiO2k6MTY7czo2OiJFVVJDSEYiO2k6MTc7czo2OiJFVVJDWksiO2k6MTg7czo2OiJFVVJES0siO2k6MTk7czo2OiJFVVJHQlAiO2k6MjA7czo2OiJFVVJIS0QiO2k6MjE7czo2OiJFVVJIVUYiO2k6MjI7czo2OiJFVVJKUFkiO2k6MjM7czo2OiJFVVJNWE4iO2k6MjQ7czo2OiJFVVJOT0siO2k6MjU7czo2OiJFVVJOWkQiO2k6MjY7czo2OiJFVVJQTE4iO2k6Mjc7czo2OiJFVVJSVUIiO2k6Mjg7czo2OiJFVVJTRUsiO2k6Mjk7czo2OiJFVVJTR0QiO2k6MzA7czo2OiJFVVJUUlkiO2k6MzE7czo2OiJFVVJVU0QiO2k6MzI7czo2OiJFVVJaQVIiO2k6MzM7czo2OiJHQlBBVUQiO2k6MzQ7czo2OiJHQlBCR04iO2k6MzU7czo2OiJHQlBDQUQiO2k6MzY7czo2OiJHQlBDSEYiO2k6Mzc7czo2OiJHQlBES0siO2k6Mzg7czo2OiJHQlBKUFkiO2k6Mzk7czo2OiJHQlBOT0siO2k6NDA7czo2OiJHQlBOWkQiO2k6NDE7czo2OiJHQlBQTE4iO2k6NDI7czo2OiJHQlBTRUsiO2k6NDM7czo2OiJHQlBTR0QiO2k6NDQ7czo2OiJHQlBUUlkiO2k6NDU7czo2OiJHQlBVU0QiO2k6NDY7czo2OiJHQlBaQVIiO2k6NDc7czo2OiJIS0RKUFkiO2k6NDg7czo2OiJOT0tTRUsiO2k6NDk7czo2OiJOWkRDQUQiO2k6NTA7czo2OiJOWkRDSEYiO2k6NTE7czo2OiJOWkRKUFkiO2k6NTI7czo2OiJOWkRTR0QiO2k6NTM7czo2OiJOWkRVU0QiO2k6NTQ7czo2OiJQTE5KUFkiO2k6NTU7czo2OiJTR0RKUFkiO2k6NTY7czo2OiJVU0RDQUQiO2k6NTc7czo2OiJVU0RDSEYiO2k6NTg7czo2OiJVU0RDTkgiO2k6NTk7czo2OiJVU0RES0siO2k6NjA7czo2OiJVU0RIS0QiO2k6NjE7czo2OiJVU0RIVUYiO2k6NjI7czo2OiJVU0RKUFkiO2k6NjM7czo2OiJVU0RNWE4iO2k6NjQ7czo2OiJVU0ROT0siO2k6NjU7czo2OiJVU0RQTE4iO2k6NjY7czo2OiJVU0RSVUIiO2k6Njc7czo2OiJVU0RTRUsiO2k6Njg7czo2OiJVU0RTR0QiO2k6Njk7czo2OiJVU0RUUlkiO2k6NzA7czo2OiJVU0RaQVIiO2k6NzE7czo2OiJBVURHQlAiO2k6NzI7czo2OiJBVURIS0QiO2k6NzM7czo2OiJBVURIVUYiO2k6NzQ7czo2OiJBVURNWE4iO2k6NzU7czo2OiJBVUROT0siO2k6NzY7czo2OiJBVURTRUsiO2k6Nzc7czo2OiJDQURDWksiO2k6Nzg7czo2OiJDQUROT0siO2k6Nzk7czo2OiJDQURTR0QiO2k6ODA7czo2OiJDQURaQVIiO2k6ODE7czo2OiJDSEZIS0QiO2k6ODI7czo2OiJDSEZIVUYiO2k6ODM7czo2OiJDSEZTRUsiO2k6ODQ7czo2OiJDSEZaQVIiO2k6ODU7czo2OiJHQlBIS0QiO2k6ODY7czo2OiJHQlBNWE4iO2k6ODc7czo2OiJOT0tKUFkiO2k6ODg7czo2OiJOWkRDWksiO2k6ODk7czo2OiJTR0RDSEYiO2k6OTA7czo2OiJUUllES0siO2k6OTE7czo2OiJUUllKUFkiO2k6OTI7czo2OiJVU0RDTlkiO2k6OTM7czo2OiJVU0RJTlIiO2k6OTQ7czo2OiJVU0RUSEIiO2k6OTU7czo2OiJaQVJKUFkiO2k6OTY7czo2OiJzdG9ja3MiO31zOjExOiJwYV9jb250cm9scyI7czoxNjoidHJhZGVzfFRyYWRlRmVlZCI7czoxNjoicGFfaW5qZWN0cmV2ZXJzZSI7YjowO3M6MTY6InBhX2hhcmRpbmplY3Rpb24iO2I6MDtzOjExOiJwYV9pbmplY3RhdCI7YjowO3M6ODoic2hvd0xpdmUiO2I6MTtzOjE0OiJzdHJlYW1DaGVja2JveCI7czozMjM6IjxzcGFuIGNsYXNzPSJmbGV4RXh0ZXJuYWxPcHRpb24iIGRhdGEtdHlwZT0iSW5saW5lQ2hlY2tib3giPjxpbnB1dCB0eXBlPSJjaGVja2JveCIgY2xhc3M9ImNoZWNrYm94IiBuYW1lPSJmbGV4W1RyYWRlcy9BY3Rpdml0eV90cmFkZXNBY3Rpdml0eV1bdHJhZGVzL3N0cmVhbV1bXSIgaWQ9ImZsZXhbVHJhZGVzL0FjdGl2aXR5X3RyYWRlc0FjdGl2aXR5XVt0cmFkZXMvc3RyZWFtXVsxXSIgdmFsdWU9IjEiIC8";
    }
}