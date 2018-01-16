<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */

if ( ! is_active_sidebar( 'sidebar' ) ) return;

?>

<aside class="site-sidebar col-12 col-md-3"><?php dynamic_sidebar( 'sidebar' ); ?></aside>
