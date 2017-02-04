<?php

namespace Forex;

class Trader {
  	private $id;
	private $username;

	public function __construct($trader) {
		$this->id = (array_key_exists('id', $trader) ? $trader['id'] : false);
		$this->username = (array_key_exists('username', $trader) ? $trader['username'] : false);
	}

	public function getUsername() {
		if ($this->id && $this->username) {
			return $this->username;
		}

		return 'guest';
	}
}