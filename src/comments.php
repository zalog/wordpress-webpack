<?php
/**
 * @package WordPress
 * @subpackage zatheme
 */
?>

<?php if ( post_password_required() ) return;

?><div id="comments" class="card card-comments"><?php

    if ( have_comments() ) :

        ?><div class="card-header">
            <h3 class="card-title h6"><?php
                printf(
                _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$d thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'zatheme' ),
                number_format_i18n( get_comments_number() ),
                get_the_title()
                );
            ?></h3>
        </div>

        <div class="card-body">

            <ol class="list-comments"><?php wp_list_comments(array(
                'style' => 'ol',
                'avatar_size' => 48,
                'short_ping' => true,
                'walker' => new Bootstrap_Comment_Walker()
            )); ?></ol>

            <?php zatheme_the_comment_navigation(); ?>

        </div><?php

    endif;

    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) )
        echo '<div class="card-body"><p>' . __( 'Comments are closed.', 'zatheme' ) . '</p></div>';

    comment_form();

?></div>
