<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */

/**
 * Theme setup
 */
function zatheme_setup() {
    load_theme_textdomain( 'zatheme', get_template_directory() . '/languages' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    // add_image_size( 'facebook', 1200, 630, true ); // og:image
    add_editor_style( 'assets/css/editor-style.css' );
    register_nav_menus( array (
        'main' => __( 'Main menu', 'zatheme' ),
        'footer' => __( 'Footer menu', 'zatheme' )
    ) );
} add_action( 'after_setup_theme', 'zatheme_setup' );

// Set jpeg quality
add_filter('jpeg_quality', function() { return 78; } );

// Remove emojis actions
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

/**
 * Enqueue scripts and styles
 */
function zatheme_scripts_styles() {

    wp_deregister_script( 'jquery' );

    wp_enqueue_style( 'zatheme-style', get_stylesheet_uri() );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
    wp_enqueue_script( 'commons.js', get_template_directory_uri() . '/assets/js/commons.js', null, null, true );
    wp_enqueue_script( 'main.js', get_template_directory_uri() . '/assets/js/main.js', ['commons.js'], null, true );
    wp_localize_script( 'main.js', 'settings', array(
        'theme-path'    => get_template_directory_uri(),
        'nonce'         => wp_create_nonce( 'wp_rest' )
    ) );

} add_action( 'wp_enqueue_scripts', 'zatheme_scripts_styles' );
// script defer
function zatheme_script_tag_defer( $tag, $handle ) {
    if ( is_admin() ) return $tag;

    $output = str_replace( ' src',' defer src', $tag );

    return $output;
} add_filter( 'script_loader_tag', 'zatheme_script_tag_defer', 10, 2 );

/**
 * Theme header
 */
function zatheme_header() {

    // favicon & browser
    ?><link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/imgs/favicons/favicon.ico">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/imgs/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/imgs/favicons/android-icon-192x192.png" sizes="192x192" type="image/png">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/assets/imgs/favicons/apple-icon-180x180.png">
    <meta name="msapplication-square310x310logo" content="<?php echo get_template_directory_uri(); ?>/assets/imgs/favicons/ms-icon-310x310.png">
    <meta name="theme-color" content="#0275d8">
    <meta name="msapplication-navbutton-color" content="#0275d8"><?php

} add_action( 'wp_head', 'zatheme_header' );

/**
 * Theme footer
 */
function zatheme_footer() {

    // wp embed
    wp_deregister_script( 'wp-embed' );

    // developer mode
    if ( current_user_can( 'manage_options' ) ) :
        if ( isset( $_GET['debug'] ) ) : global $template; ?><div class="text-center"><?php echo get_num_queries() . ' / ' . timer_stop( 0, 2 ) . ' / ' . basename( $template ); ?></div><?php endif;
        ?><div style="position:fixed; left:0; top:50%; z-index:10000; width:14px; height:14px; overflow: hidden; margin-top:-7px; text-align:center; background:#000; color:#FFF; font:11px/14px sans; opacity:.9"><div class="d-none d-xl-block">xl</div><div class="d-none d-lg-block">lg</div><div class="d-none d-md-block">md</div><div class="d-none d-sm-block">sm</div><div class="d-sm-none">xs</div></div><?php
    endif;

} add_action( 'wp_footer', 'zatheme_footer' );

/**
 * Register widgetized areas
 */
function zatheme_widgets_init() {
    register_sidebar( array (
        'name' => 'Primary Sidebar',
        'id' => 'sidebar',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
} add_action( 'widgets_init', 'zatheme_widgets_init' );

/**
 * Custom archive title
 */
function zatheme_archive_title ( $title ) {
    if ( is_category() ) $title = single_cat_title( '', false );
    elseif ( is_author() ) $title = get_the_author();
    return $title;
} add_filter( 'get_the_archive_title', 'zatheme_archive_title' );

/**
 * Rest api
 */
require get_template_directory() . '/inc/rest.php';

/**
 * Register custom navigation walker
 */
require get_template_directory() . '/inc/nav-walker-bs.php';

/**
 * Register custom comment walker
 */
require get_template_directory() . '/inc/comment-walker.php';

/**
 * Register custom widgets
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Custom template tags
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Modify query
 */
function zatheme_search_filter( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_search ) $query->set( 'post_type', 'post' );
} add_action('pre_get_posts','zatheme_search_filter');

/**
 * Sets the post excerpt
 */
class Excerpt {
    public static $length = 55;
    public static $types = array(
        'short'   => 20,
        'regular' => 55,
        'long'    => 95
    );
    public static function length( $new_length = 55 ) {
        Excerpt::$length = $new_length;
        add_filter( 'excerpt_length', 'Excerpt::new_length' );
        Excerpt::output();
    }
    public static function new_length() {
        if ( isset( Excerpt::$types[Excerpt::$length] ) ) return Excerpt::$types[Excerpt::$length];
        else return Excerpt::$length;
    }
    public static function output() {
        the_excerpt();
    }
}
function custom_excerpt($length = 55) {
    Excerpt::length($length);
}
// change excerpt_more [...]
add_filter( 'excerpt_more', function() { return '&hellip;'; } );

/**
 * PhotoSwipe data-size
 */
function zatheme_attachment_link( $markup, $id ) {
    $attachment_metadata = wp_get_attachment_metadata($id, true);

    if ( ! $attachment_metadata ) return $markup;

    $data_size = $attachment_metadata['width'] . 'x' . $attachment_metadata['height'];
    return str_replace( "<a", "<a data-size='$data_size'", $markup );
} add_filter( 'wp_get_attachment_link', 'zatheme_attachment_link', 10, 2 );

/**
 * Comment spam protection
 */
function zatheme_check_referrer() {
    if ( !isset( $_SERVER['HTTP_REFERER'] ) || $_SERVER['HTTP_REFERER'] == "" ) wp_die( 'Please enable referrers in your browser!' );
} add_action( 'check_comment_flood', 'zatheme_check_referrer' );

/**
 * Generic login error
 */
add_filter('login_errors', function () {
    return 'Something is wrong!';
});

/**
 * Admin branding
 */
add_action( 'login_head', function() { echo '<style type="text/css">.login h1{height:70px;margin-bottom:25px;background: url(' . get_bloginfo( 'template_directory' ) . '/imgs/logo-login.png) no-repeat 50% 50% !important;}.login h1 a{display:none}</style>'; } );
add_filter( 'admin_footer_text', function() { echo 'Development by <a href="http://zalog.ro/" target="_blank" title="Developer Catalin Zalog">Catalin Zalog</a>'; } );
