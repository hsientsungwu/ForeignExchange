<?php

namespace Forex;

/**
 * Request object uses to centralize the GET, POST and FILE parameters
 */
class Request {
    public $get = [];
    public $post = [];
    public $files = [];

    public function __construct() {
        $get = $_GET;
        $post = $_POST;
        $files = $_FILES;
    }
}