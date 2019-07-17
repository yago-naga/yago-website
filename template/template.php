<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php page_title(); ?> | <?php site_name(); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="<?php site_url(); ?>/assets/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php site_url(); ?>/assets/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php site_url(); ?>/assets/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="<?php site_url(); ?>/assets/images/favicons/site.webmanifest">
    <link rel="mask-icon" href="<?php site_url(); ?>/assets/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="<?php site_url(); ?>/assets/images/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config" content="<?php site_url(); ?>/assets/images/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <link href="//tools-static.wmflabs.org/cdnjs/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="//tools-static.wmflabs.org/cdnjs/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php site_url(); ?>/assets/css/normalize.css" rel="stylesheet" type="text/css"/>
    <link href="<?php site_url(); ?>/assets/css/style.css" rel="stylesheet" type="text/css"/>

    <link href="http://yago-knowledge.org/<?php echo get_page_id(); ?>" rel="canonical"/>
</head>
<body>

<?php get_header(); ?>

<div class="wrap">
    <div class="content">
        <?php page_content(); ?>
    </div>
</div>

<?php get_footer(); ?>
</body>
</html>