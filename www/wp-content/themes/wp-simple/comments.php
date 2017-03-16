<?php
if (comments_open()) {
    echo "<h2>";
    _e('Comments', 'wp-simple' );
    echo "</h2>";
}
if (post_password_required()) {
?>
    <p class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'wp-simple' ); ?></p>
    <?php
    return;
}
if (have_comments()) {
    $comments_by_type = separate_comments($comments);
    if (!empty($comments_by_type['comment'])) {
    ?>
        <ol id="comments">
            <?php wp_list_comments(array('type' => 'comment', 'callback' => 'nimbus_comment', 'avatar_size' => 75, 'reply_text' => __('Reply', 'wp-simple' ))); ?>
        </ol>
    <?php
    }
    ?>

    <div class="navigation">
        <div class="alignleft">
    <?php previous_comments_link() ?>
        </div>
        <div class="alignright">
    <?php next_comments_link() ?>
        </div>
    </div>

    <?php
    if (!empty($comments_by_type['pings'])) {
    ?>
        <ol id="pings">
            <?php wp_list_comments(array('type' => 'pings', 'callback' => 'nimbus_ping')); ?>
        </ol>
    <?php
    }
} else {
?>
    <div class="nocomments">
        <?php
        if ('open' == $post->comment_status) {
        ?>
            <p><?php _e('Be the first to comment.', 'wp-simple' ); ?></p>
        <?php
        } else {
            // If comments are closed.
        }
        ?>
    </div>
<?php
}
if ('open' == $post->comment_status) {

    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields = array(
        'author' => '<p class="comment-form-author"><label for="author">' . __('Name', 'wp-simple' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
        'email' => '<p class="comment-form-email"><label for="email">' . __('Email', 'wp-simple' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>',
        'url' => '<p class="comment-form-url"><label for="url">' . __('Website', 'wp-simple' ) . '</label><input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>'
    );

    $modified_defaults = array(
        'fields' => apply_filters('comment_form_default_fields', $fields),
        'comment_field' => '<div class="col-md-8"><p><label for="comment">Comment</label><br /><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p></div></div>',
        'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.', 'wp-simple' ), wp_login_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
        'logged_in_as' => '<div class="row"><div class="col-md-4"><p class="logged-in-as">' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'wp-simple' ), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))) . '</p></div>',
        'comment_notes_before' => '',
        'comment_notes_after' => '<p class="form_allowed_tags">' . sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'wp-simple' ), ' <code>' . allowed_tags() . '</code>') . '</p>',
        'id_form' => 'commentform',
        'id_submit' => 'submit',
        'title_reply' => __('Leave a Reply', 'wp-simple' ),
        'title_reply_to' => __('Leave a Reply to %s', 'wp-simple' ),
        'cancel_reply_link' => __('Cancel reply', 'wp-simple' ),
        'label_submit' => __('Submit', 'wp-simple' ),
    );
    ?>

    <?php comment_form($modified_defaults); ?>

<?php } ?>