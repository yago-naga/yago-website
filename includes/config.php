<?php

define('SEARCH_RESULTS_PER_PAGE', 30);

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
				    "downloads/yago-4-5" => "Yago 4.5",
                    "downloads/yago-4" => "Yago 4",
                    "downloads/yago-3" => "Yago 3",
                    "downloads/yago-2s" => "Yago 2s",
                    "downloads/yago-2" => "Yago 2",
                    "downloads/yago-1" => "Yago 1",
                    "downloads/logo" => "Yago logo",

                ],
            ],
            'resource/Elvis_Presley' => [
                'title' => 'Browse/Query',
                'children' => [
                    "resource/Elvis_Presley" => "Browse",
                    "schema" => "Schema",
                    "sparql" => "SPARQL",
                ],
            ],
            'publications' => ['title' => 'Publications'],
            'contributors' => ['title' => 'Contributors'],
        ],
        'template_path' => 'template',
        'content_path' => 'content',
        'version' => 'v0.1',
        'sparql_endpoint' => 'http://localhost/sparql/qlever',
        // 'sparql_endpoint' => 'https://yago-knowledge.org/sparql/qlever',
        // Public-facing endpoint URLs (for YASQE editor and display)
        'sparql_endpoint_public' => 'https://yago-knowledge.org/sparql/qlever',
        // Excluded facts API (use production URL for local dev without the 18GB SQLite DB)
        'excluded_facts_api' => '/api_excluded_facts.php',
        // 'excluded_facts_api' => 'https://yago-knowledge.org/api_excluded_facts.php',
    ];

    return isset($config[$key]) ? $config[$key] : null;
}
