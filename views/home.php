<?php
namespace Forex;

/**
 * View function for home page
 * @param  Request $request 
 * @return string          
 */
function home($request) {
    return renderView('html', 'index.html', []);
}
