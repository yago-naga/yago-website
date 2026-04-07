<?php
/**
 * Template helpers: navigation rendering, page routing, content loading, and the init() entry point.
 */

/**
 * Displays site name.
 */
function site_name() {
    echo config('name');
}

/**
 * Displays site url provided in config.
 */
function site_url() {
    echo config('site_url');
}

/**
 * Displays site version.
 */
function site_version() {
    echo config('version');
}

/**
 * Website navigation.
 */
function nav_menu() {
    $nav_menu = '';
    $nav_items = config('nav_menu');
    foreach ($nav_items as $uri => $name) {
        $class = str_replace('page=', '', $_SERVER['QUERY_STRING']) == $uri ? ' active' : '';
        $url = config('site_url') . '/' . (config('pretty_uri') || $uri == '' ? '' : '?page=') . $uri;

        if (array_key_exists('children', $name)) {
            $id = random_str(10);
            $nav_menu .= '<li class="' . $class . '"><a class="dropdown-trigger" href="' . $url . '" data-target="' . $id . '">' . $name['title'] . '<i class="material-icons right">arrow_drop_down</i></a></li>';
            $dropdown = '<ul id="' . $id . '" class="dropdown-content">';
            $children = $name['children'];
            foreach ($children as $sub_uri => $child) {
                $sub_url = config('site_url') . '/' . (config('pretty_uri') || $uri == '' ? '' : '?page=') . $sub_uri;
                $dropdown .= '<li><a href="' . $sub_url . '">' . $child . '</a></li>';
            }
            $dropdown .= '</ul>';
            $nav_menu .= $dropdown;

        } else {
            $nav_menu .= '<li class="' . $class . '"><a href="' . $url . '" title="' . $name['title'] . '" class="item">' . $name['title'] . '</a></li>';
        }
    }
    echo trim($nav_menu);
}

/**
 * Website mobile nav
 */
function nav_menu_mobile() {
    $menu = '';

    $nav_items = config('nav_menu');
    foreach ($nav_items as $uri => $name) {
        $class = str_replace('page=', '', $_SERVER['QUERY_STRING']) == $uri ? ' active' : '';
        $url = config('site_url') . '/' . (config('pretty_uri') || $uri == '' ? '' : '?page=') . $uri;
        $menu .= '<li class="' . $class . '"><a href="' . $url . '" title="' . $name['title'] . '" class="item">' . $name['title'] . '</a></li>';
    }

    echo $menu;
}


/**
 * Return the current page title (set by content pages via $GLOBALS['page_title'], or derived from page ID).
 */
function get_page_title() {
    if (!empty($GLOBALS['page_title'])) {
        return htmlspecialchars($GLOBALS['page_title']);
    }
    return ucwords(str_replace('-', ' ', htmlspecialchars(get_page_id())));
}

function page_title() {
    echo get_page_title();
}

/**
 * Page canonical name like "home"
 */
function get_page_id() {
    return isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 'home';
}

/**
 * Displays page content. It takes the data from
 * the static pages inside the pages/ directory.
 * When not found, display the 404 error page.
 */
function page_content() {
    $page = get_page_id();

    $path = getcwd() . '/' . config('content_path') . '/' . $page . '.php';

    if (!file_exists($path)) {
        $path = getcwd() . '/' . config('content_path') . '/404.php';
    }

    try {
        include($path);
    } catch( Exception $e ) {
        ob_end_clean(); // try to purge content sent so far
        header('HTTP/1.1 500 Internal Server Error');
        echo '<h1>Internal error</h1>';
        error_log('Page error (' . $page . '): ' . $e);
    }
}

/**
 * Starts everything and displays the template.
 */
function init() {
    require config('template_path') . '/template.php';
}

/** Include the header template partial. */
function get_header() {
    $path = getcwd() . '/' . config('template_path') . '/header.php';
    include($path);
}

/** Include the footer template partial. */
function get_footer() {
    $path = getcwd() . '/' . config('template_path') . '/footer.php';
    include($path);
}

/**
 * Generate a cryptographically secure random string (used for unique HTML element IDs).
 *
 * @param int    $length    Desired string length
 * @param string $keyspace  Characters to choose from
 * @return string
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces [] = $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

/**
 * Parse an HTTP Accept header into a priority-sorted list of MIME types.
 *
 * @param string $header  Raw Accept header value
 * @return array  MIME types sorted by quality factor (highest first)
 */
function parse_accept_header($header) {
    $accepts = [];
    foreach (explode(',', $header) as $i) {
        $parts = explode(';', $i);
        $type = trim($parts[0]);
        $priority = 1.;
        for ($i = 1; $i < count($parts); $i++) {
            $elements = explode('=', $parts[$i], 1);
            if (count($elements) === 2) {
                $key = trim($elements[0]);
                $value = trim($elements[1]);
                if ($key === 'q') {
                    $priority = floatval($value);
                }
            }
        }
        $accepts[$type] = max($priority, $accepts[$type] ?? 0);
    }
    arsort($accepts);
    return array_keys($accepts);
}
