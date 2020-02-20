<?php
while (have_posts()) :
    the_post();

    $country = strtolower(get_field('country'));

    $plastic_value = get_field('plastic_value');
    $plastic_name = get_field('plastic_name');

    $title = "Find out how much plastic you’re eating";
    $desc = "I consume approximately " . $plastic_value . " of plastic per week. That's like eating a " . $plastic_name . "!";
    $url = "https://yourplasticdiet.org/?share_redirect";

    switch ($country) {
        case 'ar':
        case 'br':
        case 'mx':
        case 'co':
        case 'pe':
        case 've':
        case 'cl':
        case 'gt':
        case 'ec':
            $title = "Descubre cuánto plástico estás comiendo";
            $desc = "¿Podrías estar consumiendo tanto plástico como el equivalente a una tarjeta de crédito por semana? Averígualo ahora en tudietaplastica.org";
            $url = "https://yourplasticdiet.org/es/?share_redirect";
            $fb_title = get_field('_yoast_wpseo_opengraph-title', 1043) ?: $title;
            $fb_desc = get_field('_yoast_wpseo_opengraph-description', 1043) ?: $desc;
            $tw_title = get_field('_yoast_wpseo_twitter-title', 1043) ?: $title;
            $tw_desc = get_field('_yoast_wpseo_twitter-description', 1043) ?: $desc;
            break;
        case 'jp':
            $url = "https://yourplasticdiet.org/jp/?share_redirect";
            $fb_title = get_field('_yoast_wpseo_opengraph-title', 1172) ?: $title;
            $fb_desc = get_field('_yoast_wpseo_opengraph-description', 1172) ?: $desc;
            $tw_title = get_field('_yoast_wpseo_twitter-title', 1172) ?: $title;
            $tw_desc = get_field('_yoast_wpseo_twitter-description', 1172) ?: $desc;
            break;
        case 'sg':
            $url = "https://yourplasticdiet.org/sg/?share_redirect";
            $fb_title = $title;
            $fb_desc = $desc;
            $tw_title = $title;
            $tw_desc = $desc;
            break;
        default:
            $fb_title = get_field('_yoast_wpseo_opengraph-title') ?: $title;
            $fb_desc = get_field('_yoast_wpseo_opengraph-description') ?: $desc;
            $tw_title = get_field('_yoast_wpseo_twitter-title') ?: $title;
            $tw_desc = get_field('_yoast_wpseo_twitter-description') ?: $desc;
            break;
    }
?>

    <!-- For Google -->

    <meta name="description" content="WWF Plastic Diet - Result">

    <meta name="keywords" content="Plastic Diet" />

    <meta name="author" content="WWF" />

    <meta name="copyright" content="WWF @ 2019" />

    <meta name="application-name" content="WWF Plastic Diet" />



    <!-- For Facebook -->

    <meta property="og:url" content="<?= get_permalink() ?>" />

    <meta property="og:type" content="article" />

    <meta property="og:image" content="<?= wp_get_attachment_url(get_field('image_url')) ?>">

    <meta property="og:title" content="<?= $fb_title ?>" />

    <meta property="og:description" content="<?= $fb_desc ?>" />



    <!-- For Twitter -->

    <meta name="twitter:card" content="summary_large_image">

    <meta name="twitter:site" content="@wwf">

    <meta name="twitter:title" content="<?= $tw_title ?>">

    <meta name="twitter:description" content="<?= $tw_desc ?>">

    <meta name="twitter:image" content="<?= wp_get_attachment_url(get_field('image_url')) ?>">

    <meta name="twitter:domain" content="yourplasticdiet.org">



    <title>WWF Plastic Diet - Image Result</title>







    <img src="<?= wp_get_attachment_url(get_field('image_url')) ?>" alt="Plastic diet result for <?= get_field('name') ?>" style="display: none;" />



    <p>Redirecting you to homepage...</p>





    <script>
        window.location.href = '<?= $url ?>';
    </script>


<?php endwhile; ?>