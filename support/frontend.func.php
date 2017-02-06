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
        // render it as html string
        $loader = new Twig_Loader_Filesystem(TWIG_TEMPLATE_FOLDER);
        
        $twig = new Twig_Environment($loader, array(
            //'cache' => TWIG_TEMPLATE_CACHE_FOLDER,
        ));

        return $twig->render($template, $data);
    } elseif ($type == 'csv') {
        // render it as csv download
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=" . $data['filename']);

        // initiate the output
        $output = fopen("php://output", "w");

        // print headers as first row in the first row
        fputcsv($output, $data['headers']);

        // iterate through the data to print them row by row
        foreach ($data['data'] as $row) {
            fputcsv($output, $row);
        }

        // close the file
        fclose($output);
        exit();
    }
}