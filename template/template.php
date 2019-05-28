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

<footer>
    <div class="affiliation-logos">
        <img src="<?php site_url(); ?>/assets/images/affiliations.png" alt="Affiliations"/>
    </div>
    <p>&copy; <?php echo date('Y'); ?> - <?php site_name(); ?>. Web design by <a href="https://alab-ux.com"
                                                                                 target="_blank"
                                                                                 rel="noopener noreferrer">ALAB UX</a>
    </p>
</footer>

<script
        src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script type="text/javascript" src="<?php site_url(); ?>/js/scripts.js"></script>
</body>
</html>