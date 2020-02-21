<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <?php get_template_part('/includes/meta'); ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <div id="page" class="site">
        <a class="skip-link screen-reader-text sr-only sr-only-focusable" href="#content"><?php esc_html_e('Skip to content', '_s'); ?></a>

        <header id="masthead" class="site-header position-fixed w-100">
            <div class="container-fluid">
                <div class="navbar navbar-expand-lg navbar-light px-0">
                    <a class="navbar-brand p-0" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/imgs/60+logo.png' ?>" width="80" height="65" class="" alt="">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/imgs/wwf-logo.png' ?>" width="70" height="80" class="ml-2 bg-white p-2" alt="wwf">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarToggler">
                        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Home<span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#about">About</a>
                            </li>
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
            </div>
        </header><!-- #masthead -->

        <div id="content" class="site-content">