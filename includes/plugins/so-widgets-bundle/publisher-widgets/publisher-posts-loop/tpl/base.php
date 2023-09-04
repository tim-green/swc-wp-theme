<?php
/**
 * Posts Loop Widget
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Widget Settings
/*-----------------------------------------------------------------------------------*/

$post_loop_query = publisher_build_posts_query( $instance['build_posts_query'] );

// Paged
$var = is_front_page() ? 'page' : 'paged';
global $paged;
$paged = ( get_query_var( $var ) ) ? get_query_var( $var ) : 1;

if ( 'none' != $instance['pagination'] ) {
	$post_loop_query['paged'] = $paged;
}

// Widget Settings
$section_title = $instance['section_title'];
$section_desc = $instance['section_desc'];
$section_style = $instance['section_style'];
$posts_page_layout = $instance['posts_page_layout'];
$post_columns = intval( $instance['post_columns'] );
$pagination = $instance['pagination'];
$ajax = $instance['ajax'] ? 'true' : 'false';
$history = ( $instance['ajax'] && $instance['history'] ) ? 'true' : '';
$unique_id = ( $instance['ajax'] && $instance['ajax_unique'] ) ? '-' . sanitize_title( $instance['ajax_unique'] ) : '';
$image_size = $instance['image_size'];
$post_format_icon = $instance['post_format_icon'];

// Slider Settings
$animate = $instance['slider_options']['animate'] ? 'fade' : 'slide';
$autoplay = $instance['slider_options']['autoplay'] ? 'true' : 'false';
$autoheight = $instance['slider_options']['autoheight'] ? 'true' : 'false';
$timeout = $instance['slider_options']['timeout'] ? intval( $instance['slider_options']['timeout'] ) : 5000;
$speed = $instance['slider_options']['speed'] ? intval( $instance['slider_options']['speed'] ) : 250;




/*-----------------------------------------------------------------------------------*/
/*	Print Template
/*-----------------------------------------------------------------------------------*/

/**
 * Get the posts
 */

$the_query = new WP_Query( apply_filters( 'publisher_ext_filter_posts_loop_query_' . $args['widget_id'], $post_loop_query ) );
$carousel_navigation = ( $the_query->post_count > $post_columns ) ? 'true' : 'false';

if ( $the_query->have_posts() ) :

	// Attributes
	if ( ! empty( $args['widget_id'] ) ) $div_attributes['data-id'] = $args['widget_id'] . $unique_id;
	if ( ! empty( $posts_page_layout ) ) $div_attributes['data-layout'] = $posts_page_layout;
	if ( ! empty( $pagination ) ) $div_attributes['data-pagination'] = $pagination;
	if ( ! empty( $ajax ) ) $div_attributes['data-ajax'] = $ajax;
	if ( ! empty( $history ) ) $div_attributes['data-history'] = $history;

	$setup_posts_page_layout = $posts_page_layout ? $posts_page_layout . '-layout' : '';
	$setup_post_columns = ( TRUE === in_array( $posts_page_layout, array( 'grid', 'cover', 'mini-grid', 'slider' ) ) ) ? 'post-column-' . $post_columns : '';
	?>

	<div class="publisher-posts-loop-widget" <?php foreach( $div_attributes as $key => $val ) echo esc_attr( $key ) . '="' . esc_attr( $val ) . '" ' ?>>

		<div class="posts-index publisher-ext-posts-index">
			<div class="publisher-widget-site-content <?php echo esc_attr( $setup_posts_page_layout ); ?> <?php echo esc_attr( $setup_post_columns ); ?>">

				<?php
					if ( '' != $instance['post_loop_section_header']['headline'] || '' != $instance['post_loop_section_header']['sub_headline'] ) {
						echo '<div class="publisher-header-section ' . $instance['post_loop_section_header']['publisher_headline_style'] . '">';
							$this->sub_widget( 'SiteOrigin_Widget_Headline_Widget', $args, $instance['post_loop_section_header'] );

							// Navigation
							if ( 'header-prev-next' == $pagination && is_page() ) {

								if ( is_rtl() ) {
									$header_prev_next_text = array(
										'prev_text' => _x( '<i class="fa fa-angle-right"></i>', 'RTL: Header Previous posts', 'publisher' ),
										'next_text' => _x( '<i class="fa fa-angle-left"></i>', 'RTL: Header Next posts', 'publisher' ),
									);
								} else {
									$header_prev_next_text = array(
										'prev_text' => __( '<i class="fa fa-angle-left"></i>', 'publisher' ),
										'next_text' => __( '<i class="fa fa-angle-right"></i>', 'publisher' )
									);
								}
								$header_prev_next = apply_filters( 'publisher_ext_filter_posts_loop_pagination_header_prev_next_text', $header_prev_next_text );
								?>

								<nav class="navigation <?php echo esc_attr( $pagination ) ?>">
									<div class="navigation-wrap">
										<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'publisher' ); ?></h2>

										<div class="nav-links">
											<?php
												if ( 'true' == $ajax ) {
													echo publisher_get_loader_icon();
												}

												printf( '<div class="nav-previous">%1$s</div>', get_next_posts_link( $header_prev_next['prev_text'], $the_query->max_num_pages ) );
												printf( '<div class="nav-next">%1$s</div>',	get_previous_posts_link( $header_prev_next['next_text'] ) );
											?>
										</div><!-- .nav-links -->
									</div><!-- .navigation-wrap -->
								</nav><!-- .navigation -->
								<?php
							}

						echo '</div><!-- .publisher-header-section -->';
					}
				?>

				<?php
					// Grid Loader Icon
					if ( 'grid' == $posts_page_layout ) {
						echo publisher_get_loader_icon();
					}
				?>

				<div class="site-main">
					<?php
						if ( 'listing-h' == $posts_page_layout || 'listing-v' == $posts_page_layout ) { ?>

							<div class="listing-posts">
								<?php
									$i = 0;
									while ( $the_query->have_posts() ) : $the_query->the_post();

										// Pass $instance to templates
										set_query_var( 'instance', $instance );

										if ( $i == 0 ) {
											echo '<div class="listing-featured-container">';
												get_template_part( 'includes/partials/posts-loop-listing-featured' );
											echo '</div><!-- .listing-featured-container -->';
										} else {
											if ( $i == 1 ) echo '<div class="listing-container">';
											get_template_part( 'includes/partials/posts-loop-listing' );
										}
										$i++;

									endwhile;

									if ( $i > 0 ) echo '</div><!-- .listing-container -->';
								?>
							</div><!-- .listing-posts -->

							<?php
						} elseif ( 'carousel' == $posts_page_layout || 'mini-carousel' == $posts_page_layout ) {

							// Attributes
							if ( 'mini-carousel' == $posts_page_layout ) {
								$slider_attributes['data-items'] = $post_columns;
								$slider_attributes['data-mobile-items'] = 2;
								$slider_attributes['data-tablet-items'] = 3;
								$slider_attributes['data-margin'] = 16;
							} else {
								$slider_attributes['data-items'] = $post_columns;
								$slider_attributes['data-mobile-items'] = 1;
								$slider_attributes['data-tablet-items'] = ( $post_columns == 1 ) ? 1 : 2;
								$slider_attributes['data-margin'] = ( $post_columns == 1 ) ? 2 : 16;
							}

							if ( ! empty( $carousel_navigation ) ) $slider_attributes['data-navigation'] = $carousel_navigation;
							if ( ! empty( $animate ) ) $slider_attributes['data-animation'] = $animate;
							if ( ! empty( $speed ) ) $slider_attributes['data-speed'] = $speed;
							if ( ! empty( $autoplay ) ) $slider_attributes['data-autoplay'] = $autoplay;
							if ( ! empty( $timeout ) ) $slider_attributes['data-timeout'] = $timeout;
							if ( ! empty( $autoheight ) ) $slider_attributes['data-autoheight'] = $autoheight;
							?>

							<?php echo publisher_get_loader_icon(); ?>

							<div class="owl-carousel" <?php foreach( $slider_attributes as $key => $val ) echo esc_attr( $key ) . '="' . esc_attr( $val ) . '" ' ?>>
								<?php
									while ( $the_query->have_posts() ) : $the_query->the_post();

										// Pass $instance to templates
										set_query_var( 'instance', $instance );

										if ( 'mini-carousel' == $posts_page_layout ) {
											get_template_part( 'includes/partials/posts-loop-mini-grid' );
										} else {
											get_template_part( 'includes/partials/posts-loop-cover' );
										}

									endwhile;
								?>
							</div><!-- .owl-carousel -->

							<?php
						} elseif ( 'slider' == $posts_page_layout ) {

							// Attributes
							$slider_attributes['data-items'] = 1;
							$slider_attributes['data-mobile-items'] = 1;
							$slider_attributes['data-tablet-items'] = 1;
							$slider_attributes['data-margin'] = 2;

							if ( ! empty( $carousel_navigation ) ) $slider_attributes['data-navigation'] = $carousel_navigation;
							if ( ! empty( $animate ) ) $slider_attributes['data-animation'] = $animate;
							if ( ! empty( $speed ) ) $slider_attributes['data-speed'] = $speed;
							if ( ! empty( $autoplay ) ) $slider_attributes['data-autoplay'] = $autoplay;
							if ( ! empty( $timeout ) ) $slider_attributes['data-timeout'] = $timeout;
							if ( ! empty( $autoheight ) ) $slider_attributes['data-autoheight'] = $autoheight;
							if ( ! empty( $post_columns ) ) $slider_attributes['data-grid'] = $post_columns;
							?>

							<?php echo publisher_get_loader_icon(); ?>

							<div class="owl-carousel" <?php foreach( $slider_attributes as $key => $val ) echo esc_attr( $key ) . '="' . esc_attr( $val ) . '" ' ?>>
								<?php
									$i = 0;
									while ( $the_query->have_posts() ) : $the_query->the_post();

										// Open Container
										if ( $i % $post_columns == 0 ) {
											echo $i > 0 ? '</div><!-- .slider-posts-slide -->' : ''; // close div if it's not the first
											echo '<div class="slider-posts-slide">';
										}

										// Open Slide Wrap
										if ( 3 == $post_columns ) {
											echo $i % $post_columns == 1 ? '<div class="slide-column-wrap">' : '';
										} elseif ( 4 == $post_columns ) {
											echo $i % $post_columns == 1 ? '<div class="slide-column-wrap">' : '';
											echo $i % $post_columns == 2 ? '<div class="slide-wrap">' : '';
										} elseif ( 5 == $post_columns ) {
											echo $i % $post_columns == 0 ? '<div class="slide-wrap featured">' : '';
											echo $i % $post_columns == 2 ? '<div class="slide-wrap list">' : '';
										}

										include( locate_template( 'includes/partials/posts-loop-slider.php' ) );

										// Close Slide Wrap
										if ( 3 == $post_columns ) {
											echo $i % $post_columns == 2 ? '</div><!-- .slide-column-wrap -->' : '';
										} elseif ( 4 == $post_columns ) {
											echo $i % $post_columns == 3 ? '</div><!-- .slide-wrap --></div><!-- .slide-column-wrap -->' : '';
										} elseif ( 5 == $post_columns ) {
											echo $i % $post_columns == 1 ? '</div><!-- .slide-wrap featured -->' : '';
											echo $i % $post_columns == 4 ? '</div><!-- .slide-wrap -->' : '';
										}

										$i++;

									endwhile;

									// Close Slide Wrap
									if ( 3 == $post_columns ) {
										echo ( $i % $post_columns > 1 ) && ( $i % $post_columns < 3 ) ? '</div><!-- .slide-column-wrap -->' : '';
									} elseif ( 4 == $post_columns ) {
										if ( ( $i % $post_columns > 1 ) && ( $i % $post_columns < 3 ) ) {
											echo '</div><!-- .slide-column-wrap -->';
										} elseif ( ( $i % $post_columns > 2 ) && ( $i % $post_columns < 4 ) ) {
											echo '</div><!-- .slide-wrap --></div><!-- .slide-column-wrap -->';
										}
									} elseif ( 5 == $post_columns ) {
										echo ( $i % $post_columns > 0 ) && ( $i % $post_columns < 3 ) ? '</div><!-- .slide-wrap-featured -->' : '';
										echo ( $i % $post_columns > 2 ) && ( $i % $post_columns < 5 ) ? '</div><!-- .slide-wrap -->' : '';
									}

									// Close Container
									echo '</div><!-- .slider-posts-slide -->';
								?>
							</div>

							<?php
						} else {

							while ( $the_query->have_posts() ) : $the_query->the_post();

								// Pass $instance to templates
								set_query_var( 'instance', $instance );

								if ( 'mini-grid' == $posts_page_layout ) {
									get_template_part( 'includes/partials/posts-loop-mini-grid' );

								} elseif ( 'mini-listing' == $posts_page_layout ) {
									get_template_part( 'includes/partials/posts-loop-listing' );

								} elseif ( 'cover' == $posts_page_layout ) {
									get_template_part( 'includes/partials/posts-loop-cover' );

								} else {

									if ( publisher_is_custom_post_type() ) :
										if ( get_post_format() && ! in_array( get_post_format(), array( 'gallery', 'audio', 'video' ) ) ) {
											get_template_part( 'includes/partials/content-' . get_post_format(), get_post_type() );
										} else {
											get_template_part( 'includes/partials/content', get_post_type() );
										}
									else :
										get_template_part( 'includes/partials/content', get_post_format() );
									endif;

								}

							endwhile;

						}
					?>
				</div><!-- .site-main -->


				<?php if ( TRUE === in_array( $pagination, array( 'prev-next', 'posts-navigation', 'pagination', 'load-more' ) ) && $the_query->max_num_pages > 1 && is_page() ) { ?>

					<nav class="navigation <?php echo esc_attr( $pagination ) ?>">
						<div class="navigation-wrap">
							<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'publisher' ); ?></h2>

							<div class="nav-links">
								<?php
									if ( 'true' == $ajax ) {
										echo publisher_get_loader_icon();
									}

									if ( 'load-more' == $pagination ) {

										$load_more_text = apply_filters( 'publisher_ext_filter_posts_loop_pagination_load_more_text', __( 'Load More', 'publisher' ) );
										$no_more_posts_text = apply_filters( 'publisher_ext_filter_posts_loop_pagination_no_more_posts_text', __( 'No More Posts', 'publisher' ) );

										$pages = $the_query->max_num_pages;
										printf( '<div class="nav-previous">%1$s</div>',
											( $paged == $pages ) ? '<span class="end-of-posts">' . $no_more_posts_text . '</span>' : get_next_posts_link( $load_more_text, $pages )
										);

									} elseif ( 'pagination' == $pagination ) {

										$pages = $the_query->max_num_pages;
										$range = apply_filters( 'publisher_ext_filter_posts_loop_pagination_numeric_range', 1 );
										if ( is_rtl() ) {
											$numeric_text_args = array(
												'prev_text' => _x( '<i class="fa fa-angle-right"></i>', 'RTL: Numeric Previous posts', 'publisher' ),
												'next_text' => _x( '<i class="fa fa-angle-left"></i>', 'RTL: Numeric Next posts', 'publisher' ),
											);
										} else {
											$numeric_text_args = array(
												'prev_text' => __( '<i class="fa fa-angle-left"></i>', 'publisher' ),
												'next_text' => __( '<i class="fa fa-angle-right"></i>', 'publisher' )
											);
										}
										$numeric_text = apply_filters( 'publisher_ext_filter_posts_loop_pagination_numeric_text', $numeric_text_args );

										$showitems = ( $range * 2 ) + 1;

										if ( 1 != $pages ) {
											// Previous Page of Posts
											if ( $paged > 1 && $showitems < $pages ) echo '<a href="' . esc_url( get_pagenum_link( $paged - 1 ) ) . '">' . $numeric_text['prev_text'] . '</a>';

											// First Page
											if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) echo '<a href="' . get_pagenum_link( 1 ) . '">1</a>';

											// Dots
											if ( ( $paged - ($range+1) ) > 1 ) echo '<span class="page-numbers dots">&hellip;</span>';

											// Pages
											for ( $i=1; $i <= $pages; $i++ ) {
												if ( 1 != $pages && ( !( $i >= $paged+$range+1 || $i <= $paged-$range-1 ) || $pages <= $showitems ) ) {
													echo ( $paged == $i ) ? '<span class="page-numbers current">' . $i . '</span>' : '<a class="page-numbers" href="' . esc_url( get_pagenum_link( $i ) ) . '">' . $i . '</a>';
												}
											}

											// Dots
											if ( ( $paged + ($range+1) ) < $pages ) echo '<span class="page-numbers dots">&hellip;</span>';

											// Last Page
											if ( $paged < $pages-1 && $paged + $range - 1 < $pages && $showitems < $pages )
												echo '<a class="page-numbers" href="' . esc_url( get_pagenum_link( $pages ) ) .'">' . $pages . '</a>';

											// Next Page of Posts
											if ( $paged < $pages && $showitems < $pages )
												echo '<a href="' . esc_url( get_pagenum_link( $paged + 1 ) ) . '">' . $numeric_text['next_text'] . '</a>';
										}

									} else {

										if ( is_rtl() ) {
											$text = array(
												'prev_text' => __( '&rarr; Older posts', 'publisher' ),
												'next_text' => __( 'Newer posts &larr;', 'publisher' )
											);
										} else {
											$text = array(
												'prev_text' => __( '&larr; Older posts', 'publisher' ),
												'next_text' => __( 'Newer posts &rarr;', 'publisher' )
											);
										}
										$pagination_text = apply_filters( 'publisher_ext_filter_posts_loop_pagination_text', $text );

										printf( '<div class="nav-previous">%1$s</div>', get_next_posts_link( $pagination_text['prev_text'], $the_query->max_num_pages ) );
										printf( '<div class="nav-next">%1$s</div>',	get_previous_posts_link( $pagination_text['next_text'] ) );

									}
								?>
							</div><!-- .nav-links -->
						</div><!-- .navigation-wrap -->
					</nav><!-- .navigation -->

				<?php }	?>

			</div><!-- .publisher-widget-site-content -->
		</div><!-- .posts-index -->

		<?php wp_reset_postdata(); // Reset the global $the_post as this query will have stomped on it ?>

	</div><!-- .publisher-posts-loop-widget -->

<?php
else :

	if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
		printf( '<p class="alert info publisher-ext">' . __( 'No posts found in the query. Please adjust your query and try again.', 'publisher' ) . '</p>' );
	};

endif;
