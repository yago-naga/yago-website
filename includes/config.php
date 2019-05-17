<?php

/**
 * Used to store website configuration information.
 *
 * @var string or null
 * @return object
 */
function config($key = '')
{
    $config = [
        'name' => 'Yago Project',
        'site_url' => '',
        'pretty_uri' => true,
        'nav_menu' => [
            '' => 'Home',
            'getting-started' => 'Getting Started',
            'downloads' => 'Downloads',
            'sparql' => 'SPARQL',
            'publications' => 'Publications',
            'contributors' => 'Contributors',
        ],
        'template_path' => 'template',
        'content_path' => 'content',
        'version' => 'v3.0',
    ];

    return isset($config[$key]) ? $config[$key] : null;
}
