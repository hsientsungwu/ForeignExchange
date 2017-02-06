<?php

namespace Forex;

/**
 * Request object uses to centralize the GET, POST and FILES parameters
 */
class Request {
    /**
     * Retrieve data from $_GET
     * @var array
     */
    public $get = [];

    /**
     * Retrieve data from $_POST
     * @var array
     */
    public $post = [];

    /**
     * Retrieve data from $_FILES
     * @var array
     */
    public $files = [];

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
    }
}