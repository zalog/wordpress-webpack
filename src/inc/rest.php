<?php

/**
 * Rest comments
 */
// activate anonymous comments
add_filter( 'rest_allow_anonymous_comments', '__return_true' );
// update cookies
function zatheme_rest_insert_comment($comment, $request) {
    $user = wp_get_current_user();
    do_action( 'set_comment_cookies', $comment, $user );
} add_action( 'rest_insert_comment', 'zatheme_rest_insert_comment', 10, 3 );
