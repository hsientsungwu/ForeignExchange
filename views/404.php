<?php
namespace Forex;

/**
 * View function for 404 page
 * @param Request $request 
 * @return  string 
 */
function FourZeroFour($request) {
    return renderView('html', '404.html', []);
}
