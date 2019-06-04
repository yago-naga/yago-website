<header>
    <div class="logo">
        <a href="/">
            <img src="<?php site_url(); ?>/assets/images/logo.png" alt="Yago Project - Select Knowledge"/ >
        </a>
    </div>
<!--    <div id="header">-->
<!--        <div class="wrap menu">-->
<!--            --><?php //nav_menu(); ?>
<!--        </div>-->
<!--    </div>-->


    <nav>
        <div class="wrap">
            <div class="nav-wrapper" >
<!--                <a href="#!" class="brand-logo">Logo</a>-->
                <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                <ul class="hide-on-med-and-down nav-items">
                    <?php nav_menu(); ?>
                </ul>
            </div>
        </div>
    </nav>


    <ul class="sidenav" id="mobile-demo">
        <li><a href="sass.html">Sass</a></li>
        <li><a href="badges.html">Components</a></li>
        <li><a href="collapsible.html">Javascript</a></li>
        <li><a href="mobile.html">Mobile</a></li>
    </ul>
</header>