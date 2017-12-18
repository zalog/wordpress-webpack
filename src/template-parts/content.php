<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>

    <?php if ( has_post_thumbnail() ) : ?><div class="card-img-top">
        <figure class="img-responsive img-responsive-16by9"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a></figure>
    </div><?php endif; ?>

    <div class="card-body">

        <header class="entry-header">
            <?php the_title( sprintf( '<h2 class="entry-title card-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        </header>

        <div class="entry-summary"><?php the_excerpt(); ?></div>

        <footer class="entry-footer">
            <?php zatheme_the_entry_footer(); ?>
        </footer>

    </div>

</article>
