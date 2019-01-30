<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Publisher
 * @since Publisher 1.0
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

	<h2 class="comments-header section-title"><?php _e( 'Discussion', 'publisher' ); ?></h2>

	<div class="comments-wrap">
		<?php if ( have_comments() ) : ?>

			<h3 class="comments-title"><?php comments_number( __( 'No Responses', 'publisher' ), __( '1 Response', 'publisher' ), __( '% Responses', 'publisher' ) ); ?></h3>

			<ol class="comment-list">
				<?php
					wp_list_comments( array(
						'style'       => 'ol',
						'short_ping'  => true,
						'avatar_size' => 60,
						'callback'    => 'publisher_get_comment'
					) );
				?>
			</ol><!-- .comment-list -->

		<?php endif; // have_comments() ?>

		<?php if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : // If comments are closed and there are comments ?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'publisher' ); ?></p>
		<?php endif; ?>

		<?php
			// Get Current User
			global $current_user;
			get_currentuserinfo();

			$comments_args = apply_filters( 'publisher_filter_comment_form_args', array(
				'cancel_reply_link'	  	=> '<i class="fa fa-times-circle"> <span>' . __( 'Cancel Reply', 'publisher' ) . '</span></i>',
				'title_reply'		  	=> '<span>' . __( 'Leave A Reply', 'publisher' ) . '</span>',
				'comment_notes_after' 	=> '',
				'logged_in_as'			=> sprintf( '<p class="logged-in-as">' . __( '<span class="logged-in-as-text">Logged in as</span>%1$s<a class="user-identity" href="%2$s">%3$s</a><a class="log-out-text" href="%4$s" title="%5$s">%6$s</a>', 'publisher' ) . '</p>',
					get_avatar( $current_user->ID, apply_filters( 'publisher_filter_comment_form_avatar_size', 26 ) ),
					admin_url( 'profile.php' ),
					$user_identity,
					wp_logout_url( apply_filters( 'publisher_filter_comment_form_permalink', esc_url( get_permalink() ) ) ),
					__( 'Log out of this account', 'publisher' ),
					'<span>' . __( 'Log Out ?', 'publisher' ) . '</span>'
				)
			) );
			comment_form( $comments_args );
		?>

	</div><!-- .comments-wrap -->

	<?php if ( have_comments() ) : ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>

			<nav id="comment-navigation" class="comment-navigation clearfix">
				<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'publisher' ); ?></h1>

				<?php if ( 'prev-next' == get_option( 'publisher_options_comment_pagination' ) ) { ?>

					<?php if ( '' != get_previous_comments_link() ) { ?>
						<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'publisher' ) ); ?></div>
					<?php } ?>

					<?php if ( '' != get_next_comments_link() ) { ?>
						<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'publisher' ) ); ?></div>
					<?php } ?>

				<?php } else { ?>

					<div class="pagination">
						<?php
							if ( is_rtl() ) {
								$paginate_comments_args = array(
									'prev_text' => '&rarr;',
									'next_text' => '&larr;'
								);
							} else {
								$paginate_comments_args = array(
									'prev_text' => '&larr;',
									'next_text' => '&rarr;'
								);
							}
							paginate_comments_links( apply_filters( 'publisher_filter_paginate_comments_args', $paginate_comments_args ) );
						?>
					</div>

				<?php }	?>
			</nav><!-- .comment-navigation -->

		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

</div><!-- #comments -->
