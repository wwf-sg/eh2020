<?php
/**
 * Template Name: Plastic diet iframe
 * Template Post Type: post, page, event
 */
?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <?php get_template_part('/includes/meta'); ?>

    <?php wp_head(); ?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114909992-11"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-114909992-11');
    </script>
    <script src="<?= get_template_directory_uri() ?>/dist/iframeResizer.contentWindow.min.js"></script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':

                    new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],

                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =

                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);

        })(window, document, 'script', 'dataLayer', 'GTM-WZZ9B8T');
    </script>

    <!-- End Google Tag Manager -->
</head>

<body <?php body_class(); ?>>
    <!-- Google Tag Manager (noscript) -->

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WZZ9B8T" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <!-- End Google Tag Manager (noscript) -->
    <div id="page" class="site">
        <div id="content" class="site-content">
            <div id="primary">
                <?php
                while (have_posts()) :
                    the_post(); ?>
                    <article itemtype="https://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
                        <div class="entry-content clear container" itemprop="text">
                            <?php the_content(); ?>
                        </div><!-- .entry-content .clear -->
                    </article><!-- #post-## -->
                <?php endwhile; ?>
            </div><!-- #primary -->
        </div><!-- #content -->
    </div><!-- #page -->

    <script>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var iframe = true;
    </script>
    <?php wp_footer(); ?>
</body>

</html>