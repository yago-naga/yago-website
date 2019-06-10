<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php page_title(); ?> | <?php site_name(); ?></title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="<?php site_url(); ?>/template/normalize.css" rel="stylesheet" type="text/css"/>
    <link href="<?php site_url(); ?>/template/style.css" rel="stylesheet" type="text/css"/>
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