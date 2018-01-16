<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?>

<?php get_header(); ?>

    <main class="site-content col-12 col-md-9">

        <section>

            <header class="page-header">
                <h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'zatheme' ); ?></h1>
                <p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'zatheme' ); ?></p>
            </header>

            <?php get_search_form(); ?>

        </section>

    </main>

    <?php get_sidebar(); ?>

<?php get_footer(); ?>
