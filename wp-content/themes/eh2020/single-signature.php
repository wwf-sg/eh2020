<?php
while (have_posts()) :
    the_post();

    // $country = strtolower(get_field('country'));

    $title = "Earth Hour 2020 - Live & Unplugged";
    $desc = "On 28 March, 5.30pm, join #nofilter conversations with changemakers about the planetary emergency, enjoy live music by some of Singapore’s top artistes.";
    $url = get_home_url() . "/?share_redirect";

    $img = get_field('image_url');

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


    <img src="<?= wp_get_attachment_url(get_field('image_url')) ?>" alt="Image from <?= get_field('first_name') ?>" style="display: none;" />


    <p>Redirecting you to <a href="<?= $url ?>">homepage</a>...</p>



    <script>
        window.location.href = '<?= $url ?>';
    </script>


<?php endwhile; ?>