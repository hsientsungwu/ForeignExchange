<?php

/**
 * Function uses to render output result for frontend ('html', 'csv')
 * @param  string $type     ['html', 'csv']
 * @param  string $template template file if uses twig
 * @param  array  $data     data to pass to view
 * @return mix           
 */
function renderView($type, $template = '', $data = []) {
    if ($type == 'html') {
        $loader = new Twig_Loader_Filesystem(TWIG_TEMPLATE_FOLDER);
        
        $twig = new Twig_Environment($loader, array(
            //'cache' => TWIG_TEMPLATE_CACHE_FOLDER,
        ));

        return $twig->render($template, $data);
    }
}