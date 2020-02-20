<?php

/**
 * Template Name: EH 2020 Homepage
 * Template Post Type: page
 */

?>

<?php get_template_part('/includes/header'); ?>

<div id="primary">
    <article itemtype="https://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header">

            <?php // astra_the_title('<h1 class="entry-title" itemprop="headline">', '</h1>'); 
            ?>
        </header><!-- .entry-header -->

        <div class="entry-content clear" itemprop="text">

            <?php the_content(); ?>

        </div><!-- .entry-content .clear -->

    </article><!-- #post-## -->
</div><!-- #primary -->

<?php get_template_part('/includes/footer'); ?>