<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <?php get_template_part('/includes/meta'); ?>

    <?php wp_head(); ?>
    <?php if (defined('PROD') && PROD && !isset($_GET['test'])) : ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114909992-13"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-114909992-13');
        </script>
    <?php endif; ?>

</head>

<body <?php body_class(); ?>>

    <div id="page" class="site">
        <a class="skip-link screen-reader-text sr-only sr-only-focusable" href="#content"><?php esc_html_e('Skip to content', '_s'); ?></a>

        <header id="masthead" class="site-header position- w-100">
            <div class="container">
                <div class="navbar navbar-expand-lg navbar-dark px-0">
                    <a class="navbar-brand p-0" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/imgs/60+logo.png' ?>" width="80" height="" class="eh60plus" alt="">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/imgs/wwf-logo.png' ?>" width="70" height="" class="ml-2 bg-white p-2 wwfsg" alt="wwf">
                    </a>
                    <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                    <ul class="nav ml-auto">
                        <li class="nav-item active">
                            <a class="text-white nav-link" href="#">Home<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="text-white nav-link" href="#about">About</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="text-white nav-link" href="https://www.earthhour.sg/lights-out/">Lights Out</a>
                        </li> -->
                        <!-- <li class="nav-item">
                                <a class="nav-link" href="#LightsOut">Lights Out</a>
                            </li> -->
                        <?php if (false) { ?>
                            <?php if (have_rows('plastic_diet_menu')) : ?>

                                <?php while (have_rows('plastic_diet_menu')) : the_row(); ?>

                                    <li class="nav-item active">
                                        <a class="nav-link" href="<?php echo the_sub_field('nav_url') ?>"><?php echo the_sub_field('nav_label') ?></a>
                                    </li>

                                <?php endwhile; ?>

                            <?php else : ?>

                            <?php endif; ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </header><!-- #masthead -->

        <div id="content" class="site-content">