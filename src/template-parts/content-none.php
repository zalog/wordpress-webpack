<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?>

<section>

    <header class="mb-4">
        <h1><?php _e( 'Nothing Found', 'zatheme' ); ?></h1>
        <?php if ( is_search() ) : ?>
            <p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'zatheme' ); ?></p>
        <?php else : ?>
            <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'zatheme' ); ?></p>
        <?php endif; ?>
    </header>

    <?php get_search_form(); ?>

</section>
