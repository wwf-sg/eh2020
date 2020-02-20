<?php
/**
 * Template Name: Plastic diet
 * Template Post Type: post, page, event
 */
?>
<?php get_template_part('/includes/header'); ?>

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

<?php get_template_part('/includes/footer'); ?>