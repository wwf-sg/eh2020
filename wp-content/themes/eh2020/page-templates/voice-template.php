<?php

/**
 * Template Name: voice
 * Template Post Type: page
 */

global $post;

?>

<?php get_template_part('/includes/header'); ?>

<div id="primary" class="bg-black entry">

    <?php if (post_password_required($post)) { ?>
        <style>
            body {
                background: #000 !important;
            }
        </style>
        <div style="margin-top: 150px" class="text-center text-white">
            <?php echo get_the_password_form($post); ?>
        </div>
    <?php } else { ?>

        <!-- Form -->
        <div class="entry-content">
            <div id="voice" class="alignfull">
                <?php include(__DIR__ . '/../partials/voice.php'); ?>

                <!-- <svg class="wave" viewBox="0 0 1024 307" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <filter x="-1.2%" y="-4.0%" width="102.3%" height="108.0%" filterUnits="objectBoundingBox" id="filter-1">
                            <feOffset dx="0" dy="2" in="SourceAlpha" result="shadowOffsetOuter1"></feOffset>
                            <feGaussianBlur stdDeviation="2" in="shadowOffsetOuter1" result="shadowBlurOuter1"></feGaussianBlur>
                            <feColorMatrix values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0  0 0 0 0.5 0" type="matrix" in="shadowBlurOuter1" result="shadowMatrixOuter1"></feColorMatrix>
                            <feMerge>
                                <feMergeNode in="shadowMatrixOuter1"></feMergeNode>
                                <feMergeNode in="SourceGraphic"></feMergeNode>
                            </feMerge>
                        </filter>
                        <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="linearGradient-2">
                            <stop stop-color="#D756B5" offset="0%"></stop>
                            <stop stop-color="#000000" offset="100%"></stop>
                        </linearGradient>
                        <path d="M0,65.8418637 L42.6666667,78.8976438 C85.3333333,92.1986029 170.666667,117.942395 256,127.933438 C341.333333,137.556712 426.666667,131.427238 512,105.070499 C597.333333,78.7137596 682.666667,33.3556502 768,13.5574484 C853.333333,-5.8729849 938.666667,0.256489342 981.333333,3.75028966 L1024,6.998911 L1024,299 L0,299 L0,65.8418637 Z" id="path-3"></path>
                    </defs>
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="EH-Website" transform="translate(0.000000, -1418.000000)">
                            <g id="wave-(1)" filter="url(#filter-1)" transform="translate(0.000000, 1420.000000)">
                                <mask id="mask-4" fill="white">
                                    <use xlink:href="#path-3"></use>
                                </mask>
                                <use id="Mask-Copy" fill="url(#linearGradient-2)" fill-rule="nonzero" transform="translate(512.000000, 149.500000) scale(1, -1) translate(-512.000000, -149.500000) " xlink:href="#path-3"></use>
                            </g>
                        </g>
                    </g>
                </svg> -->
            </div>
        </div>

    <?php } ?>

</div><!-- #primary -->

<?php get_template_part('/includes/footer'); ?>