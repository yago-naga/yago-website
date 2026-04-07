<?php
/**
 * Entry point for all page requests.
 * .htaccess rewrites /foo → index.php?page=foo. This file loads config + functions and renders the template.
 */

// Comment these lines to hide errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/functions.php';

init();