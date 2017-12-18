<?php

/**
 * Prints HTML with meta information for the current post-date/time and author
 */
function zatheme_get_the_entry_date() {
    $time_string = ( get_the_time('U') !== get_the_modified_time('U') ) ? '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s" hidden>%4$s</time>' : '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    return sprintf(
        $time_string,
        esc_attr( get_the_date('c') ), esc_html( get_the_date() ),
        esc_attr( get_the_modified_date('c') ), esc_html( get_the_modified_date() )
    );
}
function zatheme_the_entry_date() {
    echo zatheme_get_the_entry_date();
}

/**
 * Prints HTML with meta information
 */
function zatheme_the_entry_footer() {
    // author
    if ( is_multi_author() ) {
        $author_html = sprintf(
            '<li class="list-inline-item">%3$s %4$s <a href="%2$s">%1$s</a></li>',
            get_the_author(),
            esc_url( get_author_posts_url( get_the_author_meta('ID') ) ),
            get_the_icon('author'),
            __('by')
        );
    }
    // datetime
    $time_html = '<li class="list-inline-item">' . get_the_icon('date') . ' ' . zatheme_get_the_entry_date() . '</li>';
    // comments
    $comments_number = number_format_i18n( get_comments_number() );
    if ( $comments_number ) $comments_html = '<li class="list-inline-item">' . get_the_icon('comment') . $comments_number . '</li>';
    // tags
    $tags_html = get_the_tag_list('<ul class="list-inline entry-meta"><li class="list-inline-item">', '</li><li class="list-inline-item">', '</li></ul>');

    echo '<ul class="list-inline entry-meta">' . $author_html . $time_html . $comments_html . '</ul>';
    if ( is_single() ) echo $tags_html;
}

/**
 * Prints HTML Pagination
 */
function zatheme_the_pagination( $args = array() ) {
    $default = array(
        'prev_text' => get_the_icon('arrow-left'),
        'next_text' => get_the_icon('arrow-right'),
        'mid_size' => 1,
        'type' => 'array'
    );
    $args = wp_parse_args( $args, $default );

    if ( $args['total'] ) {
        $big = 999999999;
        $custom = array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $args['total']
        );
        $args = wp_parse_args( $args, $custom );
    }

    $links = paginate_links( $args );

    if ( $links ) {
        foreach( $links as $link ) {
            $link = str_replace('page-numbers', 'page-link', $link);
            $class = ( strpos( $link, 'current' ) !== false ) ? ' class="page-item active"' : ' class="page-item"';
            $links_li .= '<li' . $class . '>' . $link . '</li>';
        }
        echo '<nav><ul class="pagination justify-content-center flex-wrap">' . $links_li . '</ul></nav>';
    }
}

/**
 * Prints HTML Post Navigation: Prev Title, Next Title
 */
function zatheme_the_post_navigation( $args = array() ) {
    $args = wp_parse_args( $args, array(
        'prev_text' => '<span aria-hidden="true">' . get_the_icon('arrow-left') . '</span> ' .
            '<span>%title</span>' .
            '<span class="sr-only">' . __('Previous') . '</span> ',
        'next_text' => '<span>%title</span>' .
            ' <span aria-hidden="true">' . get_the_icon('arrow-right') . '</span>' .
            '<span class="sr-only">' . __('Next') . '</span> '
    ) );
    $prev = get_previous_post_link('%link', $args['prev_text']);
    $next = get_next_post_link('%link', $args['next_text']);

    if ( $prev || $next ) echo '<nav><ul class="pagination justify-content-between align-items-center">' .
        sprintf('<li class="page-item">%s</li>', $prev) .
        sprintf('<li class="page-item">%s</li>', $next) .
    '</ul></nav>';
}
function zatheme_post_link_attributes($output) {
    $code = 'class="page-link"';
    return str_replace('<a href=', '<a '.$code.' href=', $output);
}
add_filter('next_post_link', 'zatheme_post_link_attributes');
add_filter('previous_post_link', 'zatheme_post_link_attributes');

/**
 * Prints HTML Comment Navigation: Prev, Next
 */
function zatheme_the_comment_navigation() {
    if ( get_comment_pages_count() > 1 && get_option('page_comments') ) :

        $args = wp_parse_args( $args, array(
            'prev_text' => __('Previous', 'zatheme'),
            'next_text' => __('Next', 'zatheme')
        ) );
        $prev = sprintf('<li class="page-item">%s</li>', get_previous_comments_link($args['prev_text']));
        $next = sprintf('<li class="page-item">%s</li>', get_next_comments_link($args['next_text']));

        echo '<nav><ul class="pagination justify-content-center">' . $prev . $next . '</ul></nav>';

        endif;
}
add_filter('previous_comments_link_attributes', function() { return 'class="page-link"'; });
add_filter('next_comments_link_attributes', function() { return 'class="page-link"'; });

/**
 * Alter comment form
 */
function zablog_comment_form( $defaults ) {
    $commenter = wp_get_current_commenter();
    $user = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';

    $req = get_option('require_name_email');
    $html_req = ( $req ? " required='required'" : '' );

    $required_text = sprintf(' ' . __('Required fields are marked %s'), '<span class="required">*</span>');

    if ( function_exists('subscribe_reloaded_show') ) {
        ob_start();
        subscribe_reloaded_show();
        $subscribe_reloaded_show = ob_get_clean();
        $defaults['comment_notes_after'] .= '<fieldset class="form-group">'.$subscribe_reloaded_show.'</fieldset>';
    }

    $defaults['class_form'] = 'form-comment';
    $defaults['class_submit'] = 'btn btn-primary';
    $defaults['title_reply'] = '';
    $defaults['title_reply_to'] = '';
    $defaults['title_reply_before'] = '';
    $defaults['title_reply_after'] = '';
    $defaults['cancel_reply_before'] = '<div class="float-right">';
    $defaults['cancel_reply_after'] = '</div>';
    $defaults['cancel_reply_link'] = get_the_icon('close');
    $defaults['comment_notes_before'] = '<p class="text-muted"><span id="email-notes">' . __('Your email address will not be published.') . '</span>' . ( $req ? $required_text : '' ) . '</p>';
    $defaults['logged_in_as'] = '<p class="text-muted">' . sprintf(__('Logged in as %1$s.'), $user_identity) . '</p>';
    $defaults['comment_field'] = '<fieldset class="form-group">' .
        '<label for="comment" class="sr-only">' . _x('Comment', 'noun') . '</label>' .
        '<textarea id="comment" name="comment" placeholder="' . __('Comment') . '" cols="45" rows="8" maxlength="65525" required="required" class="form-control"></textarea>' .
        '</fieldset>';
    $defaults['fields'] = array(
        'author' => '<div class="row">' .
            '<fieldset class="form-group col-xs-12 col-sm-4">' .
                '<label for="author" class="sr-only">' . __('Name') . ( $req ? ' *' : '' ) . '</label>' .
                '<input type="text" id="author" name="author" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . __('Name') . ( $req ? '*' : '' ) . '" size="30" maxlength="245" class="form-control"' . $html_req . '>' .
            '</fieldset>',
        'email' => '<fieldset class="form-group col-xs-12 col-sm-4">' .
                '<label for="email" class="sr-only">' . __('Email') . ( $req ? ' *' : '' ) . '</label>' .
                '<input type="email" id="email" name="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" placeholder="' . __('Email') . ( $req ? '*' : '' ) . '" size="30" maxlength="100" class="form-control" aria-describedby="email-notes"' . $html_req . '>' .
            '</fieldset>',
        'url' => '<fieldset class="form-group col-xs-12 col-sm-4">' .
                '<label for="url" class="sr-only">' . __('Website') . '</label>' .
                '<input type="url" id="url" name="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . __('Website') . '" size="30" maxlength="200" class="form-control">' .
            '</fieldset>' .
            '</div>'
    );
    $defaults['submit_field'] = '%1$s %2$s';
    return $defaults;
} add_filter('comment_form_defaults', 'zablog_comment_form', 10, 1);
add_action('comment_form_before', function() { echo '<div class="card-body">'; });
add_action('comment_form_after', function() { echo '</div>'; });

/**
 * Alter get_search_form();
 */
function zatheme_search_form( $form ) {
    return '<form action="' . home_url('/') . '" method="get" role="search" class="form-search">' .
        '<label class="sr-only" for="s">Caută</label>' .
        '<div class="input-group">' .
            '<input value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_attr( 'Caută&hellip;', 'placeholder' ) . '" class="form-control">' .
            '<span class="input-group-btn"><button type="submit" class="btn btn-secondary">' . get_the_icon('search') . '</button></span>' .
        '</div>' .
    '</form>';
} add_filter('get_search_form', 'zatheme_search_form');

/**
 * Prints HTML icons
 */
function get_the_icon( $classes = '', $filename = 'icons.svg' ) {
    $classesArr = explode(' ', $classes);
    return '<span class="icon icon-' . $classes . '" role="img"><svg>' .
        '<title>' . $classesArr[0] . ' icon</title>' .
        '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="' . get_template_directory_uri() . '/assets/svg/' . $filename . '#icon-' . $classesArr[0] . '"></use>' .
    '</svg></span>';
}
function the_icon( $classes = '', $filename = 'icons.svg' ) {
    echo get_the_icon( $classes, $filename );
}

/**
 * Alter wrap oembed
 */
function zatheme_oembed_html( $html, $url, $attr, $post_id ) {
    return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
} add_filter('embed_oembed_html', 'zatheme_oembed_html', 99, 4);
