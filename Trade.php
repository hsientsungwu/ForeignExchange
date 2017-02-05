<?php

namespace Forex;

use Forex\Trader as Trader;

/**
 * Trade class that contains all the ticket related information
 */
class Trade {
    public $id = 0;
    public $transaction = 0;
    public $currency;
    public $action;
    public $value;
    public $caption;
    public $time;
    public $trader;
    public $return;
    public $pips;

    public function setId($id) {
        if (is_numeric($id)) {
            $this->id = $id;
        }
    }

    public function setTransaction($transaction) {
        if (is_numeric($transaction)) {
            $this->transaction = $transaction;
        }
    }

    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    public function setAction($action) {
        if (in_array($action, ['SELL', 'BUY'])) {
            $this->action = $action;
        }
    }

    public function setValue($value) {
        if (is_numeric($value)) {
            $this->value = $value;
        }
    }

    public function setCaption($caption) {
        if ($caption != '') {
            $this->caption = $caption;
        }
    }

    public function setTime($timeString = '') {
        if ($timeString == '') {
            $this->time = timestamp();
        } else {
            $this->time = strtotime('- ' . $timeString);
        }
    }

    public function setTrader(Trader $trader) {
        $this->trader = $trader;
    }

    public function setReturn($return) {
        if ($return != '') {
            $this->return = $return;
        }
    }

    public function setPips($pips) {
        if ($pips != '') {
            $this->pips = $pips;
        }
    }
}