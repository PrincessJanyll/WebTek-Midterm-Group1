<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Restaurant_and_Cafe
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
					esc_html_e( 'Comments', 'restaurant-and-cafe' );
			?>
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
          'callback'   => 'restaurant_and_cafe_comment',
          'avatar_size'=> 80,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'restaurant-and-cafe' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'restaurant-and-cafe' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'restaurant-and-cafe' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php
		endif; // Check for comment navigation.

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'restaurant-and-cafe' ); ?></p>
	<?php
	endif;
	?>

   <div class="comments-area">
    
    <?php
    //https://codex.wordpress.org/Function_Reference/comment_form
        $commenter = wp_get_current_commenter();
        $req = get_option( 'require_name_email' );
        $aria_req = ( $req ? " aria-required='true'" : '' );
        $required_text = esc_attr__( 'Required fields are marked', 'restaurant-and-cafe' );
        
        $fields =  array(

          'author' =>
            '<p class="comment-form-author"><input id="author" name="author" placeholder="' . esc_attr__( 'Name*', 'restaurant-and-cafe' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
            '" size="30"' . $aria_req . ' /></p>',
        
          'email' =>
            '<p class="comment-form-email"><input id="email" name="email" placeholder="' . esc_attr__( 'Email*', 'restaurant-and-cafe' ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
            '" size="30"' . $aria_req . ' /></p>',
        
          'url' =>
            '<p class="comment-form-url"><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'restaurant-and-cafe' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
            '" size="30" /></p>',
        );
        
        $args = array(
          'id_form'           => 'commentform',
          'class_form'      => 'comment-form',
          'id_submit'         => 'submit',
          'class_submit'      => 'submit',
          'name_submit'       => 'submit',
          'title_reply'       => esc_attr__( 'Leave a Reply', 'restaurant-and-cafe' ),
          'title_reply_to'    => esc_attr__( 'Leave a Reply to %s', 'restaurant-and-cafe' ),
          'cancel_reply_link' => esc_attr__( 'Cancel Reply', 'restaurant-and-cafe' ),
          'label_submit'      => esc_attr__( 'POST COMMENT', 'restaurant-and-cafe' ),
          'format'            => 'xhtml',
        
          'comment_field' =>  '<p class="comment-form-comment"><label for="comment">
            </label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'restaurant-and-cafe' ) . '" cols="45" rows="8" aria-required="true">' .
            '</textarea></p>',
        
          'must_log_in' => '<p class="must-log-in">' .
            sprintf(
              esc_html__( 'You must be <a href="%s">logged in</a> to post a comment.', 'restaurant-and-cafe' ),
              wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
            ) . '</p>',
        
          'logged_in_as' => '<p class="logged-in-as">' .
            sprintf(
            __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'restaurant-and-cafe' ),
              admin_url( 'profile.php' ),
              $user_identity,
              wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
            ) . '</p>',
        
          'comment_notes_before' => '<p class="comment-notes"><span class="email-notes">' .
            esc_html__( 'Your email address will not be published. ', 'restaurant-and-cafe' ) . '</span>' . ( $req ? $required_text : '' ) .
            '<span class="required">*</span></p>',
        
          'comment_notes_after' => '',
        
          'fields' => apply_filters( 'comment_form_default_fields', $fields ),
        );
        
        comment_form( $args ); 
	?>

</div><!-- #comments -->
