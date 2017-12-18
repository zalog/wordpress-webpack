<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?>
</div></div>

<footer class="site-footer bg-inverse"><div class="container-fluid">

    <?php if ( has_nav_menu( 'footer' ) ) :
        wp_nav_menu( array(
            'theme_location'  => 'footer',
            'depth'           => 1,
            'container'       => 'ul',
            'menu_class'      => 'nav justify-content-center',
            'walker'          => new bs4navwalker()
        ) ); ?>
        <hr>
    <?php endif; ?>

    <p>&copy; <?php echo date( 'Y', current_time( 'timestamp', 0 ) ); ?> <?php bloginfo( 'name' ); ?>.</p>

</div></footer>

<?php wp_footer(); ?>

</body>
</html>
