<?php
namespace Forex;

require_once(__DIR__ . '/../system/config.php');

use Forex\Request as Request;

// retrieve request uri from $_SERVER (see .htaccess for detail)
$requestURI = $_SERVER['REQUEST_URI'];

// 0 is empty, 1 is the value we're looking for
$params = explode("/", $requestURI);

$success = false;

// exceptional case since a home view can have an empty request
if ($params[1] == '') $params[1] = 'homepage';

// if there is an url mapping found
if (in_array($params[1], array_keys($url_to_view_mappings))) {
    $view = $url_to_view_mappings[$params[1]];

    // verify if the view file exists
    if (file_exists($view['file'])) {
        require_once($view['file']);

        // verify if the view function exists
        if (function_exists(__NAMESPACE__ . '\\' . $view['function'])) {
            $success = true;
        }
    }
}

$request = new Request();

if ($success) {
    // if success, let's call the view function
    echo call_user_func(__NAMESPACE__ . '\\' . $view['function'], $request);
} else {
    // if fail, let's redirect the view to 404 page
    header('Location: ' . URL . '/404/');
}

