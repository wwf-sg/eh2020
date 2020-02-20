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


    <style>
        .fest-ultra-blocks_block_timeline_heading-wrapper h5 {
            margin: 1px !important;
            font-size: 14px !important;
        }

        .fest-ultra-blocks_common_paragraph_paragraph p {
            margin: 0 !important;
        }

        .fest-ultra-blocks_block_timeline_heading-wrapper {
            height: 20px !important;
        }

        .fest-ultra-blocks_block_timeline_stage .has-inline-color:first-child {
            font-size: 20px;
            display: block;
        }

        .fest-ultra-blocks_block_timeline_stage .has-inline-color:last-child {
            display: block;
            margin-bottom: 2rem;
        }

        @media screen and (max-width: 650px) {
            .fest-ultra-blocks_common_heading_heading h5 {
                text-align: left !important;
            }

            .wp-block-fest-ultra-addons-gutenberg-timeline {
                margin-right: -20px;
                margin-left: -20px;
                border-radius: 0 !important;
            }

            .fest-ultra-blocks_block_timeline_wrapper .fest-ultra-blocks_block_timeline_block {
                border-radius: 0 !important;
            }

            /*  layout  */
            .fest-ultra-blocks_block_timeline_separator {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            .fest-ultra-blocks_block_timeline_stage .fest-ultra-blocks_block_timeline_separator {
                -webkit-box-ordinal-group: 1 !important;
                -webkit-order: 1 !important;
                -ms-flex-order: 1 !important;
                /* order: 1 !important; */
                /* padding-left: 10px !important; */
                /* padding-right: 10px !important; */
            }

            .fest-ultra-blocks_block_timeline_stage .fest-ultra-blocks_block_timeline_content {
                -webkit-box-ordinal-group: 2 !important;
                -webkit-order: 2 !important;
                -ms-flex-order: 2 !important;
                order: 2 !important;
                text-align: left !important;
                width: 85% !important;
            }

            .fest-ultra-blocks_block_timeline_management {
                display: none !important;
            }

            /* content */
            .fest-ultra-blocks_common_heading_heading h3 {
                text-align: left !important;
                margin: 0 !important;
            }

            .fest-ultra-blocks_common_paragraph_paragraph {
                position: relative;
            }

            .fest-ultra-blocks_common_paragraph_paragraph p:before {
                position: absolute;
                content: '';
                width: 10px;
                height: 10px;
            }

            .fest-ultra-blocks_common_paragraph_paragraph p {
                text-align: left !important;

            }
        }
    </style>
    <!-- End Google Tag Manager -->
</head>

<body <?php body_class(); ?>>
    <!-- Google Tag Manager (noscript) -->

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WZZ9B8T" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <!-- End Google Tag Manager (noscript) -->
    <div id="page" class="site">
        <a class="skip-link screen-reader-text sr-only sr-only-focusable" href="#content"><?php esc_html_e('Skip to content', '_s'); ?></a>
        <header id="masthead" class="site-header w-100">
            <div class="container-fluid">
                <div class="navbar navbar-light py-0">
                    <a class="navbar-brand p-0" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php if (get_field('logo')) { ?>
                            <img src="<?= get_field('logo') ?>" alt="<?= get_bloginfo('name') ?>">
                        <?php } else if (has_custom_logo()) { ?>
                            <?php
                            $custom_logo_id = get_theme_mod('custom_logo');
                            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                            ?>
                            <img src="<?= esc_url($logo) ?>" alt="<?= get_bloginfo('name') ?>">'
                        <?php } else { ?>
                            <img src="<?php echo get_stylesheet_directory_uri() . '/imgs/wwf-logo.png' ?>" alt="WWF">
                        <?php } ?>
                    </a>
                    <div class="nav-fixed">
                        <button id="country_selector" type="button" data-toggle="modal" data-target="#country_modal">
                            <svg enable-background="new 0 0 32 32" height="32px" id="Layer_1" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="globe-2">
                                    <polygon fill="#515151" points="13.179,6.288 13.179,6.315 13.195,6.295  " />
                                    <path d="M15.624,1.028c-7.811,0-14.167,6.355-14.167,14.167c0,7.812,6.356,14.167,14.167,14.167   c7.812,0,14.168-6.354,14.168-14.167C29.792,7.383,23.436,1.028,15.624,1.028z M28.567,15.195c0,0.248-0.022,0.49-0.037,0.735   c-0.091-0.23-0.229-0.53-0.262-0.659c-0.048-0.196-0.341-0.879-0.341-0.879s-0.293-0.39-0.488-0.488   c-0.194-0.098-0.341-0.342-0.683-0.536c-0.342-0.196-0.487-0.293-0.779-0.293c-0.294,0-0.585-0.391-0.928-0.586   c-0.342-0.194-0.39-0.097-0.39-0.097s0.39,0.585,0.39,0.731c0,0.146,0.438,0.39,0.879,0.292c0,0,0.292,0.537,0.438,0.683   c0.146,0.146-0.049,0.293-0.341,0.488c-0.293,0.194-0.244,0.146-0.392,0.292c-0.146,0.146-0.633,0.392-0.78,0.488   c-0.146,0.097-0.731,0.39-1.023,0.097c-0.143-0.141-0.099-0.438-0.195-0.634c-0.098-0.195-1.122-1.707-1.61-2.389   c-0.085-0.12-0.293-0.49-0.438-0.585c-0.146-0.099,0.342-0.099,0.342-0.099s0-0.342-0.049-0.585   c-0.05-0.244,0.049-0.585,0.049-0.585s-0.488,0.292-0.636,0.39c-0.145,0.098-0.292-0.194-0.486-0.439   c-0.195-0.243-0.391-0.537-0.439-0.781c-0.049-0.243,0.244-0.341,0.244-0.341l0.438-0.243c0,0,0.537-0.097,0.879-0.049   c0.341,0.049,0.877,0.098,0.877,0.098s0.146-0.342-0.049-0.488c-0.194-0.146-0.635-0.39-0.83-0.341   c-0.194,0.048,0.097-0.244,0.34-0.439l-0.54-0.098c0,0-0.491,0.244-0.638,0.293c-0.146,0.048-0.4,0.146-0.596,0.39   c-0.194,0.244,0.078,0.585-0.117,0.683c-0.194,0.098-0.326,0.146-0.473,0.194c-0.146,0.049-0.61,0-0.61,0   c-0.504,0-0.181,0.46-0.05,0.623l-0.39-0.476L18.564,8.88c0,0-0.416-0.292-0.611-0.389c-0.195-0.098-0.796-0.439-0.796-0.439   l0.042,0.439l0.565,0.572l0.05,0.013l0.294,0.39l-0.649,0.049V9.129c-0.612-0.148-0.452-0.3-0.521-0.347   c-0.145-0.097-0.484-0.342-0.484-0.342s-0.574,0.098-0.721,0.147c-0.147,0.049-0.188,0.195-0.479,0.292   c-0.294,0.098-0.426,0.244-0.523,0.39s-0.415,0.585-0.608,0.78c-0.196,0.196-0.558,0.146-0.704,0.146   c-0.147,0-0.851-0.195-0.851-0.195V9.173c0,0,0.095-0.464,0.047-0.61l0.427-0.072l0.713-0.147l0.209-0.147l0.3-0.39   c0,0-0.337-0.244-0.094-0.585c0.117-0.164,0.538-0.195,0.733-0.341c0.194-0.146,0.489-0.244,0.489-0.244s0.342-0.292,0.683-0.634   c0,0,0.244-0.147,0.536-0.245c0,0,0.83,0.732,0.977,0.732s0.683-0.341,0.683-0.341s0.146-0.438,0.098-0.585   c-0.049-0.146-0.293-0.634-0.293-0.634s-0.146,0.244-0.292,0.439s-0.244,0.439-0.244,0.439s-0.683-0.047-0.731-0.193   c-0.05-0.147-0.146-0.388-0.196-0.533c-0.047-0.147-0.438-0.142-0.729-0.044c-0.294,0.098,0.047-0.526,0.047-0.526   s0.294-0.368,0.488-0.368s0.635-0.25,0.828-0.298c0.196-0.049,0.783-0.272,1.025-0.272c0.244,0,0.537,0.105,0.684,0.105   s0.731,0,0.731,0l1.023-0.082c0,0,0.879,0.325,0.585,0.521c0,0,0.343,0.211,0.489,0.357c0.137,0.138,0.491-0.127,0.694-0.24   C26.127,6.525,28.567,10.576,28.567,15.195z M5.296,7.563c0,0.195-0.266,0.242,0,0.732c0.34,0.634,0.048,0.927,0.048,0.927   s-0.83,0.585-0.976,0.683c-0.146,0.098-0.536,0.634-0.293,0.487c0.244-0.146,0.536-0.292,0.293,0.098   c-0.244,0.391-0.683,1.024-0.78,1.269s-0.585,0.829-0.585,1.122c0,0.293-0.195,0.879-0.146,1.123   c0.033,0.17-0.075,0.671-0.16,0.877c0.066-2.742,0.989-5.269,2.513-7.336C5.26,7.55,5.296,7.563,5.296,7.563z M6.863,5.693   c1.193-1.101,2.591-1.979,4.133-2.573c-0.152,0.195-0.336,0.395-0.336,0.395s-0.341-0.001-0.976,0.683   C9.051,4.881,9.197,4.686,9.051,4.88S8.953,5.124,8.611,5.369C8.271,5.612,8.124,5.905,8.124,5.905L7.587,6.1L7.149,5.905   c0,0-0.392,0.147-0.343-0.049C6.82,5.804,6.841,5.75,6.863,5.693z M12.709,6.831l-0.194-0.292L12.709,6.1l0.47,0.188V5.417   l0.449-0.243l0.373,0.536l0.574,0.635l-0.381,0.292l-1.016,0.195V6.315L12.709,6.831z M19.051,11.416   c0.114-0.09,0.487,0.146,0.487,0.146s1.219,0.244,1.414,0.39c0.196,0.147,0.537,0.245,0.635,0.392   c0.098,0.146,0.438,0.585,0.486,0.731c0.05,0.146,0.294,0.684,0.343,0.878c0.049,0.195,0.195,0.683,0.341,0.927   c0.146,0.245,0.976,1.317,1.268,1.805l0.88-0.146c0,0-0.099,0.438-0.196,0.585c-0.097,0.146-0.39,0.536-0.536,0.731   c-0.147,0.195-0.341,0.488-0.634,0.731c-0.292,0.243-0.294,0.487-0.439,0.683c-0.146,0.195-0.342,0.634-0.342,0.634   s0.098,0.976,0.146,1.171s-0.341,0.731-0.341,0.731l-0.44,0.44l-0.588,0.779l0.048,0.731c0,0-0.444,0.343-0.689,0.537   c-0.242,0.194-0.204,0.341-0.399,0.537c-0.194,0.194-0.957,0.536-1.152,0.585s-1.271,0.195-1.271,0.195v-0.438l-0.022-0.488   c0,0-0.148-0.585-0.295-0.78s-0.083-0.489-0.327-0.732c-0.244-0.244-0.334-0.438-0.383-0.586c-0.049-0.146,0.053-0.584,0.053-0.584   s0.197-0.537,0.294-0.732c0.098-0.195,0.001-0.487-0.097-0.683s-0.145-0.684-0.145-0.829c0-0.146-0.392-0.391-0.538-0.537   c-0.146-0.146-0.097-0.342-0.097-0.535c0-0.197-0.146-0.635-0.098-0.977c0.049-0.341-0.438-0.098-0.731,0   c-0.293,0.098-0.487-0.098-0.487-0.391s-0.536-0.048-0.878,0.146c-0.343,0.195-0.732,0.195-1.124,0.342   c-0.389,0.146-0.583-0.146-0.583-0.146s-0.343-0.292-0.585-0.439c-0.245-0.146-0.489-0.438-0.685-0.682   c-0.194-0.245-0.683-0.977-0.73-1.268c-0.049-0.294,0-0.49,0-0.831s0-0.536,0.048-0.78c0.049-0.244,0.195-0.537,0.342-0.781   c0.146-0.244,0.683-0.536,0.828-0.634c0.146-0.097,0.488-0.389,0.488-0.585c0-0.195,0.196-0.292,0.292-0.488   c0.099-0.195,0.44-0.682,0.879-0.487c0,0,0.389-0.048,0.535-0.097s0.536-0.194,0.729-0.292c0.195-0.098,0.681-0.144,0.681-0.144   s0.384,0.153,0.53,0.153s0.622-0.085,0.622-0.085s0.22,0.707,0.22,0.854s0.146,0.292,0.391,0.39   C17.44,11.562,18.563,11.807,19.051,11.416z M24.66,20.977c0,0.146-0.049,0.537-0.098,0.732c-0.051,0.195-0.147,0.537-0.195,0.73   c-0.049,0.196-0.293,0.586-0.438,0.684c-0.146,0.098-0.391,0.391-0.536,0.439c-0.146,0.049-0.245-0.342-0.196-0.537   c0.05-0.195,0.293-0.731,0.293-0.731s0.049-0.292,0.097-0.488c0.05-0.194,0.635-0.438,0.635-0.438l0.391-0.732   C24.611,20.635,24.66,20.832,24.66,20.977z M3.015,18.071c0.063,0.016,0.153,0.062,0.28,0.175c0.184,0.16,0.293,0.242,0.537,0.341   c0.243,0.099,0.341,0.243,0.634,0.39c0.293,0.147,0.196,0.05,0.585,0.488c0.391,0.438,0.342,0.438,0.439,0.683   s0.244,0.487,0.342,0.635c0.098,0.146,0.39,0.243,0.536,0.341s0.39,0.195,0.536,0.195c0.147,0,0.586,0.439,0.83,0.487   c0.244,0.05,0.244,0.538,0.244,0.538l-0.244,0.682l-0.196,0.731l0.196,0.585c0,0-0.294,0.245-0.487,0.245   c-0.18,0-0.241,0.114-0.438,0.06C4.949,22.91,3.6,20.638,3.015,18.071z" fill="#515151" />
                                </g>
                            </svg>
                            <?php
                            // $icon = get_post_field('post_name', get_post()); 
                            ?>
                            <!-- <span class='flag-icon flag-icon-none'></span> -->
                            <!-- <span class='flag-icon flag-icon-<? /* $icon */ ?>'></span> -->
                        </button>
                        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon hamburg"></span>
                            <span class="navbar-toggler-icon cancel d-md-none "></span>

                            <div class="collapse navbar-collapse">
                                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                                    <?php if (have_rows('plastic_diet_menu')) : ?>

                                        <?php while (have_rows('plastic_diet_menu')) : the_row(); ?>

                                            <li class="nav-item active">
                                                <a class="nav-link" href="<?php echo the_sub_field('nav_url') ?>"><?php echo the_sub_field('nav_label') ?></a>
                                            </li>

                                        <?php endwhile; ?>

                                    <?php else : ?>

                                    <?php endif; ?>
                                </ul>
                            </div>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarToggler">
                            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                                <?php if (have_rows('plastic_diet_menu')) : ?>

                                    <?php while (have_rows('plastic_diet_menu')) : the_row(); ?>


                                        <li class="nav-item active">
                                            <a class="nav-link" href="<?php echo the_sub_field('nav_url') ?>"><?php echo the_sub_field('nav_label') ?></a>
                                        </li>

                                    <?php endwhile; ?>

                                <?php else : ?>

                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>


                </div>
            </div>
        </header><!-- #masthead -->

        <div id="content" class="site-content">