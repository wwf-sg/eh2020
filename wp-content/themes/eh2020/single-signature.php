<?php get_template_part('/includes/header'); ?>
<?php include 'partials/translations.php'; ?>

<div id="primary">
    <?php
    // while (have_posts()) :
    //     the_post();

    // $country = strtolower(get_field('country'));

    $title = "Earth Hour 2020 - Live & Unplugged";
    $desc = "On 28 March, 5.30pm, join #nofilter conversations with changemakers about the planetary emergency, enjoy live music by some of Singapore’s top artistes.";
    $url = get_home_url() . "/?share_redirect";

    $img = 'https://www.earthhour.sg/wp-content/uploads/2020/03/openletter.png'; //get_field('image_url');
    $locale = get_field('locale') ? get_field('locale') : 'en';
    $feelings = get_field('feelings');
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

    <!-- For Google -->

    <meta name="description" content="<?= $desc ?>">

    <meta name="keywords" content="Earth Hour 2020" />

    <meta name="author" content="WWF" />

    <meta name="copyright" content="WWF @ 2020" />

    <meta name="application-name" content="Earth Hour 2020" />



    <!-- For Facebook -->

    <meta property="og:url" content="<?= get_permalink() ?>" />

    <meta property="og:type" content="article" />

    <meta property="og:image" content="<?= $img ? $img : get_stylesheet_directory_uri() . '/imgs/eh_share.jpg' ?>">

    <meta property="og:title" content="<?= $fb_title ?>" />

    <meta property="og:description" content="<?= $fb_desc ?>" />

    <!-- For Twitter -->

    <meta name="twitter:card" content="summary_large_image">

    <meta name="twitter:site" content="@wwf">

    <meta name="twitter:title" content="<?= $tw_title ?>">

    <meta name="twitter:description" content="<?= $tw_desc ?>">

    <meta name="twitter:image" content="<?= $img ? $img : get_stylesheet_directory_uri() . '/imgs/eh_share.jpg' ?>">

    <meta name="twitter:domain" content="earthhour.sg">

    <title>Earth Hour 2020 - Signature</title>


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
                <p>This is the year for action. Let’s bring nature back.</p>
                <p>With this letter, I am asking our decision makers - Singapore’s political leaders, our businesses, our schools and institutions - to fight for a better future. In our policies, our workplaces and our homes, we want systemic change that restores nature and stops its destruction. When we do so, we protect everything good that comes along with it: clean air, food, water and a future for everyone. </p>
                <p style="margin-top: 10px">Sincerely,</p>
                <p class="signature"><?= $user ?></p>

            </div>
        </div>


        <p class="text-center mt-5"><a class="btn btn-gradient" href="<?= $url ?>">Submit your Open Letter</a></p>
    </div>

    <?php // endwhile; 
    ?>
</div><!-- #primary -->

<?php get_template_part('/includes/footer'); ?>