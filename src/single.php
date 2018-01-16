<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?>

<?php get_header(); ?>

    <main class="site-content col-12 col-md-9"><?php while ( have_posts() ) : the_post();

        get_template_part( 'template-parts/content', 'single' );

        zatheme_the_post_navigation();

        if ( comments_open() || get_comments_number() ) comments_template();

    endwhile; ?></main>

    <?php get_sidebar(); ?>

<?php get_footer(); ?>
