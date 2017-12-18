<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?>

<?php get_header(); ?>

    <main class="site-content col-xs-12 col-md-9"><?php

    if ( have_posts() ) :

        while ( have_posts() ) : the_post();
            get_template_part( 'template-parts/content', get_post_format() );
        endwhile;

        zatheme_the_pagination();

    else :

        get_template_part( 'template-parts/content', 'none' );

    endif;

    ?></main>

    <?php get_sidebar(); ?>

<?php get_footer(); ?>
