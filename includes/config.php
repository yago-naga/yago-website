<?php

/**
 * Used to store website configuration information.
 *
 * @return object
 * @var string or null
 */
function config($key = '')
{
    $config = [
        'name' => 'Yago Project',
        'site_url' => '',
        'pretty_uri' => true,
        'nav_menu' => [
            '' => ['title' => 'Home'],
            'getting-started' => ['title' => 'Getting Started'],
            'downloads' => [
                'title' => 'Downloads',
                'children' => [
                    "yago-4" => "Yago 4",
                    "yago-3" => "Yago 3",
                    "yago-2s" => "Yago 2s",
                    "yago-2" => "Yago 2",
                    "yago-1" => "Yago 1",
                    "logo" => "Yago logo",

                ],
            ],
            'sparql' => ['title' => 'SPARQL'],
            'publications' => ['title' => 'Publications'],
            'contributors' => ['title' => 'Contributors'],
        ],
        'template_path' => 'template',
        'content_path' => 'content',
        'version' => 'v0.1',
        'sparql_endpoint' => 'http://yago.r2.enst.fr/sparql/query'
    ];

    return isset($config[$key]) ? $config[$key] : null;
}
