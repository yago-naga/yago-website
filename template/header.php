<header>
    <div class="logo">
        <a href="/">
            <img src="<?php site_url(); ?>/assets/images/logo.png" alt="Yago Project - Select Knowledge"/ >
        </a>
    </div>

    <nav>
        <div class="wrap">
            <div class="nav-wrapper">
                <!--                <a href="#!" class="brand-logo">Logo</a>-->
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul class="hide-on-med-and-down nav-items">
                    <?php nav_menu(); ?>
                </ul>
            </div>
        </div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
        <?php nav_menu_mobile(); ?>
    </ul>
</header>