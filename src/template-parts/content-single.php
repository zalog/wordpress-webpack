<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>

    <header class="entry-header card-body">
        <?php the_title( '<h1 class="entry-title card-title">', '</h1>' ); ?>
    </header>

    <div class="entry-content card-body"><?php the_content(); ?></div>

    <footer class="entry-footer card-body">
        <?php zatheme_the_entry_footer(); ?>
    </footer>

</article>
