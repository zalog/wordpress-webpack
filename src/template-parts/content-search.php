<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card card-body'); ?>>

    <header class="entry-header">
        <?php the_title( sprintf( '<h2 class="entry-title card-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
    </header>

    <div class="entry-summary"><?php the_excerpt(); ?></div>

    <footer class="entry-footer">
        <?php zatheme_the_entry_footer(); ?>
    </footer>

</article>
