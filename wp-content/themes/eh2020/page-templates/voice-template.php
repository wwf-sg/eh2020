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
            </div>
        </div>

    <?php } ?>

</div><!-- #primary -->

<?php get_template_part('/includes/footer'); ?>