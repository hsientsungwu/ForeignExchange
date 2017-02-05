<?php

namespace Forex;

use Forex\Trader as Trader;

/**
 * Trade class that contains all the ticket related information
 */
class Trade {
    /**
     * trade id
     * @var integer
     */
    public $id = 0;

    /**
     * trade's transaction id
     * @var integer
     */
    public $transaction = 0;

    /**
     * trade's currency pair
     * @var string
     */
    public $currency;

    /**
     * trade's action - BUY or SELL
     * @var string
     */
    public $action;

    /**
     * trade's value
     * @var double
     */
    public $value;

    /**
     * trade's caption
     * @var string
     */
    public $caption;

    /**
     * trade's time
     * @var bigint
     */
    public $time;

    /**
     * trader's information
     * @var Forex\Trader
     */
    public $trader;

    /**
     * trader's return
     * @var string
     */
    public $return;

    /**
     * trader's pips
     * @var string
     */
    public $pips;

    /**
     * Function uses to set trade id
     * @param integer $id 
     */
    public function setId($id) {
        if (is_numeric($id)) {
            $this->id = $id;
        }
    }

    /**
     * Function uses to set trade transaction id
     * @param integer $transaction 
     */
    public function setTransaction($transaction) {
        if (is_numeric($transaction)) {
            $this->transaction = $transaction;
        }
    }

    /**
     * Function uses to set trade currency pair
     * @param string $currency 
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    /**
     * Function uses to set trade action
     * @param string $action 
     */
    public function setAction($action) {
        if (in_array($action, ['SELL', 'BUY'])) {
            $this->action = $action;
        }
    }

    /**
     * Function uses to set trade value
     * @param double $value 
     */
    public function setValue($value) {
        if (is_numeric($value)) {
            $this->value = $value;
        }
    }

    /**
     * Function uses to set trade caption
     * @param string $caption 
     */
    public function setCaption($caption) {
        if ($caption != '') {
            $this->caption = $caption;
        }
    }

    /**
     * Function uses to set trade time - take string and translate into timestamp
     * @param string $timeString 
     */
    public function setTime($timeString = '') {
        if ($timeString == '') {
            $this->time = timestamp();
        } else {
            $this->time = strtotime('- ' . $timeString);
        }
    }

    /**
     * Function uses to set Trader object
     * @param Trader $trader 
     */
    public function setTrader(Trader $trader) {
        $this->trader = $trader;
    }

    /**
     * Function uses to set trade return
     * @param string $return 
     */
    public function setReturn($return) {
        if ($return != '') {
            $this->return = $return;
        }
    }

    /**
     * Function uses to set trade pips
     * @param string $pips 
     */
    public function setPips($pips) {
        if ($pips != '') {
            $this->pips = $pips;
        }
    }
}