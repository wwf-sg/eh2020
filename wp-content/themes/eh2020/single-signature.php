<?php include 'partials/translations.php'; ?>

<?php
// while (have_posts()) :
//     the_post();

// $country = strtolower(get_field('country'));

$title = "#DearSingapore, the time to shape our future is now.";
$desc = "Sign this open letter to Singapore's decision makers and join our fight for a better future.";
$url = get_home_url() . "/?share_redirect";

$img = 'https://www.earthhour.sg/wp-content/uploads/2020/03/openletter.png'; //get_field('image_url');
$locale = get_field('locale') ? get_field('locale') : 'en';
$feelings = "Hopeful"; // get_field('feelings');
$health_1 = get_field('health_1');
$health_2 = get_field('health_2');
$qualityOfLiving_1 = get_field('qualityOfLiving_1');
$qualityOfLiving_2 = get_field('qualityOfLiving_2');
$future_1 = get_field('future_1');
$future_2 = get_field('future_2');
$custom_issue = get_field('custom_issue');
$user = get_field('first_name') . ' ' . get_field('last_name');

switch ($country) {
    default:
        $fb_title = $title;
        $fb_desc = $desc;
        $tw_title = $title;
        $tw_desc = $desc;
        break;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <?php get_template_part('/includes/meta'); ?>
    <!-- For Google -->

    <meta name="description" content="<?= $desc ?>">

    <meta name="keywords" content="Earth Hour 2020" />

    <meta name="author" content="WWF" />

    <meta name="copyright" content="WWF @ 2020" />

    <meta name="application-name" content="Earth Hour 2020" />



    <!-- For Facebook -->

    <meta property="og:url" content="<?= get_permalink() ?>" />

    <meta property="og:type" content="article" />

    <meta property="og:image" content="<?= $img ? $img : get_stylesheet_directory_uri() . '/imgs/openletter.png' ?>">

    <meta property="og:title" content="<?= $fb_title ?>" />

    <meta property="og:description" content="<?= $fb_desc ?>" />

    <!-- For Twitter -->

    <meta name="twitter:card" content="summary_large_image">

    <meta name="twitter:site" content="@wwf">

    <meta name="twitter:title" content="<?= $tw_title ?>">

    <meta name="twitter:description" content="<?= $tw_desc ?>">

    <meta name="twitter:image" content="<?= $img ? $img : get_stylesheet_directory_uri() . '/imgs/openletter.png' ?>">

    <meta name="twitter:domain" content="earthhour.sg">

    <title>Earth Hour 2020 - Signature</title>

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
                            <a class="text-white nav-link" href="/">Home<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="text-white nav-link" href="/#about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="text-white nav-link" href="/lights-out">Lights Out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </header><!-- #masthead -->

        <div id="content" class="site-content">


            <div id="primary">

                <!-- <img src="<?= wp_get_attachment_url(get_field('image_url')) ?>" alt="Image from <?= get_field('first_name') ?>" style="display: none;" /> -->

                <div class="container" style="max-width: 800px">

                    <div class="openletter-wrapper">
                        <div class="bg"></div>
                        <div class="openletter w-100 bg-white text-dark" style="max-height: unset; min-height: auto;">
                            <p><?= $translations[$locale]['ol']['line1'] ?></p>
                            <p><?= $translations[$locale]['ol']['line2'] ?></p>

                            <p><?= $translations[$locale]['ol']['line3'] ?> <span style="text-transform: lowercase;"><?= $feelings ?></span>.</p>
                            <p><?= $translations[$locale]['ol']['line4'] ?></p>
                            <p><?= $translations[$locale]['ol']['line5'] ?></p>
                            <p><?= $translations[$locale]['ol']['line6'] ?></p>

                            <ul>
                                <?php if ($health_1 || $health_2) : ?>
                                    <li>
                                        <strong><?= $translations[$locale]['ol']['health'] ?></strong>
                                        <ul>
                                            <?php if ($health_1) { ?>
                                                <li><?= $translations[$locale]['ol']['health1'] ?></li>
                                            <?php } ?>
                                            <?php if ($health_2) { ?>
                                                <li><?= $translations[$locale]['ol']['health2'] ?></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if ($qualityOfLiving_1 || $qualityOfLiving_2) : ?>
                                    <li>
                                        <strong><?= $translations[$locale]['ol']['quality'] ?></strong>
                                        <ul>
                                            <?php if ($qualityOfLiving_1) { ?>
                                                <li><?= $translations[$locale]['ol']['quality1'] ?></li>
                                            <?php } ?>
                                            <?php if ($qualityOfLiving_2) { ?>
                                                <li><?= $translations[$locale]['ol']['quality2'] ?></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if ($future_1 || $future_2) : ?>
                                    <li>
                                        <strong><?= $translations[$locale]['ol']['health'] ?></strong>
                                        <ul>
                                            <?php if ($future_1) { ?>
                                                <li><?= $translations[$locale]['ol']['health1'] ?></li>
                                            <?php } ?>
                                            <?php if ($future_2) { ?>
                                                <li><?= $translations[$locale]['ol']['health2'] ?></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>

                                <?php if ($custom_issue) { ?>
                                    <li><?= $custom_issue ?></li>
                                <?php } ?>
                            </ul>
                            <p><?= $translations[$locale]['ol']['line7'] ?></p>
                            <p><?= $translations[$locale]['ol']['line8'] ?></p>
                            <p style="margin-top: 10px"><?= $translations[$locale]['ol']['signature'] ?> <br><?= $user ?></p>

                        </div>
                    </div>


                    <p class="text-center mt-5"><a class="btn btn-gradient" href="<?= $url ?>">Submit your Open Letter</a></p>
                </div>

                <?php // endwhile; 
                ?>
            </div><!-- #primary -->

            <?php get_template_part('/includes/footer'); ?>