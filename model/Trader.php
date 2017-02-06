<?php

namespace Forex;

/**
 * Trader class that is use to store trader information
 */
class Trader {
	/**
	 * trader id
	 * @var integer
	 */
  	private $id;

  	/**
  	 * trader username
  	 * @var string
  	 */
	private $username;

	/**
	 * trader avatar url
	 * @var string
	 */
	private $avatarUrl;

	/**
	 * construct function to initiate the trader information from an array provided
	 * @param array $trader [description]
	 */
	public function __construct($trader = []) {
		$this->id = (array_key_exists('id', $trader) ? $trader['id'] : false);
		$this->username = (array_key_exists('username', $trader) ? $trader['username'] : false);
		$this->avatarUrl = (array_key_exists('avatar', $trader) ? urldecode($trader['avatar']) : false);
	}

	/**
	 * Function uses to retrieve trader's username
	 * @return string username
	 */
	public function getUsername() {
		if ($this->id && $this->username) {
			return $this->username;
		}

		return 'guest';
	}

	/**
	 * Function uses to retrieve trader's avatar url
	 * @return string url
	 */
	public function getAvatarUrl() {
		if ($this->id && $this->avatarUrl) {
			return $this->avatarUrl;
		}

		return 'https://cdn-resources.forexfactory.net/images/misc/avatar_default_16x16.png';
	}
}