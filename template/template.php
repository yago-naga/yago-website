<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?php page_title(); ?> | <?php site_name(); ?></title>

    <link href="<?php site_url(); ?>/template/normalize.css" rel="stylesheet" type="text/css"/>
    <link href="<?php site_url(); ?>/template/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>

<header>
    <div class="logo">
        <img src="<?php site_url(); ?>/assets/images/logo.png" alt="Yago Project - Select Knowledge"/>
    </div>
    <nav>
        <div class="wrap menu">
            <?php nav_menu(); ?>
        </div>
    </nav>
</header>

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
</body>
</html>