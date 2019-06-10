<?php

/**
 * Displays site name.
 */
function site_name()
{
    echo config('name');
}

/**
 * Displays site url provided in conig.
 */
function site_url()
{
    echo config('site_url');
}

/**
 * Displays site version.
 */
function site_version()
{
    echo config('version');
}

/**
 * Website navigation.
 */
function nav_menu($sep = '')
{
    $nav_menu = '';
    $nav_items = config('nav_menu');
    foreach ($nav_items as $uri => $name) {
        $class = str_replace('page=', '', $_SERVER['QUERY_STRING']) == $uri ? ' active' : '';
        $url = config('site_url') . '/' . (config('pretty_uri') || $uri == '' ? '' : '?page=') . $uri;

        if (array_key_exists('children', $name)) {
            $id = random_str(10);
            $nav_menu .= '<li class="'. $class .'"><a class="dropdown-trigger" href="' . $url . '" data-target="' . $id . '">' . $name['title'] . '<i class="material-icons right">arrow_drop_down</i></a></li>';
            $dropdown = '<ul id="' . $id . '" class="dropdown-content">';
            $children = $name['children'];
            foreach ($children as $sub_uri => $child) {
                $sub_url = $url . '/'.(config('pretty_uri') || $sub_uri == '' ? '' : '?page=') . $sub_uri;
                $dropdown .= '<li><a href="' . $sub_url . '">' . $child . '</a></li>';
            }
            $dropdown .= '</ul>';
            $nav_menu .= $dropdown;

        } else {
            $nav_menu .= '<li class="'.$class.'"><a href="' . $url . '" title="' . $name['title'] . '" class="item">' . $name['title'] . '</a></li>' . $sep;
        }
    }

    echo trim($nav_menu, $sep);
}

/**
 * Displays page title. It takes the data from
 * URL, it replaces the hyphens with spaces and
 * it capitalizes the words.
 */
function page_title()
{
    $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'Home';

    echo ucwords(str_replace('-', ' ', $page));
}

/**
 * Displays page content. It takes the data from
 * the static pages inside the pages/ directory.
 * When not found, display the 404 error page.
 */
function page_content()
{
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';

    $path = getcwd() . '/' . config('content_path') . '/' . $page . '.php';

    if (!file_exists($path)) {
        $path = getcwd() . '/' . config('content_path') . '/404.phtml';
    }

    include($path);
}

/**
 * Starts everything and displays the template.
 */
function init()
{
    require config('template_path') . '/template.php';
}

function get_header()
{
    $path = getcwd() . '/' . config('template_path') . '/header.php';
    include($path);
}

function get_footer()
{
    $path = getcwd() . '/' . config('template_path') . '/footer.php';
    include($path);
}

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces [] = $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}