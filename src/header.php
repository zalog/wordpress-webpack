<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?><link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"><?php endif; ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<a href="#content" class="sr-only sr-only-focusable"><?php _e( 'Skip to main content', 'zatheme' ); ?></a>

<header class="site-header">

    <?php if ( has_nav_menu( 'main' ) ) : ?><nav class="site-nav navbar navbar-expand-lg navbar-dark bg-dark fixed-top"><div class="container-fluid">

        <?php if ( is_front_page() && is_home() ) : ?>
            <h1><a class="navbar-brand" href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
        <?php else : ?>
            <a class="navbar-brand" href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
        <?php endif; ?>

        <div>
            <button type="button" data-toggle="collapse" data-target="#collapsing-search" aria-controls="collapsing-search" aria-expanded="false" class="navbar-toggler border-0" aria-label="Căutare">
                <?php the_icon('search'); ?>
            </button>
            <button type="button" data-toggle="collapse" data-target="#collapsing-navbar" aria-controls="collapsing-navbar" aria-expanded="false" class="navbar-toggler" aria-label="Meniu">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div id="collapsing-navbar" class="collapse navbar-collapse"><?php
            wp_nav_menu( array(
                'theme_location'  => 'main',
                'depth'           => 2,
                'container'       => false,
                'menu_class'      => 'nav navbar-nav',
                'walker'          => new WP_Bootstrap_Navwalker()
            ) );
        ?></div>

        <div id="collapsing-search" class="collapse navbar-collapse justify-content-lg-end">
            <form action="<?php echo home_url( '/' ); ?>" method="get" role="search">
                <input type="text" name="s" value="<?php the_search_query(); ?>" placeholder="<?php esc_attr_e( 'Caută&hellip;', 'placeholder' ); ?>" class="form-control">
            </form>
        </div>

    </div></nav><?php endif; ?>

</header>

<div id="content" class="site-wrap container-fluid" tabindex="-1"><div class="row">
