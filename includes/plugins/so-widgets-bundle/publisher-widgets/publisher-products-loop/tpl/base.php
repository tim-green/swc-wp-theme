<?php
/**
 * Products Loop
 *
 * @package Publisher
 */

/*-----------------------------------------------------------------------------------*/
/*	Widget Settings
/*-----------------------------------------------------------------------------------*/

if ( class_exists( 'woocommerce' ) ) {

	$section_title = $instance['section_title'];
	$section_desc = $instance['section_desc'];
	$section_type = ! empty( $instance['section_type'] ) ? $instance['section_type'] : 'best_selling_products';
	$section_layout = ! empty( $instance['section_layout'] ) ? $instance['section_layout'] : 'grid';
	$post_columns = ! empty( $instance['number_of_columns'] ) ? $instance['number_of_columns'] : '4';
	$order = ! empty(  $instance['order'] ) ?  $instance['order'] : 'DESC';
	$orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : 'date';
	$product_id = $instance['product_id'];
	$number_of_items = ! empty( $instance['number_of_items'] ) ? $instance['number_of_items'] : get_option( 'posts_per_page' );
	$product_cats = is_array( $instance['terms'] ) ? $instance['terms'] : array( $instance['terms'] ) ;
	?>

	<div class="publisher-products-loop-widget" data-id="<?php echo esc_attr( $args['widget_id'] ) ?>" data-type="<?php echo esc_attr( $section_type ) ?>" data-layout="<?php echo esc_attr( $section_layout ) ?>">
		<div class="posts-index publisher-ext-posts-index woocommerce">

			<?php
				if ( '' != $instance['section_header']['headline'] || '' != $instance['section_header']['sub_headline'] ) {
					echo '<div class="publisher-header-section">';
					$this->sub_widget( 'SiteOrigin_Widget_Headline_Widget', $args, $instance['section_header'] );
					echo '</div><!-- .publisher-header-section -->';
				}
			?>

			<?php if ( ! in_array( $section_type, array( 'product', 'product_page' ) ) ) echo publisher_get_loader_icon(); ?>

			<div class="site-main <?php echo 'product' == $section_type ? 'columns-' . intval( $post_columns ) : ''; ?>">

				<?php if ( 'carousel' == $section_layout ) { ?>
					<div class="woocommerce products owl-carousel" data-columns="<?php echo esc_attr( $post_columns ) ?>">
				<?php } ?>

				<?php
					// Set Shortcode Atts
					$woo_shortcode_attributes = array();
					if ( 'product' == $section_type || 'product_page' == $section_type ) {
						if ( ! empty( $product_id ) ) $woo_shortcode_attributes['id'] = intval( $product_id );

					} else {

						if ( 'product_categories' == $section_type ) {
							if ( ! empty( $instance['number_of_items'] ) ) {
								$woo_shortcode_attributes['number'] = ! empty( $instance['number_of_items'] ) ? $instance['number_of_items'] : '';
							}
							$woo_shortcode_attributes['parent'] = ! empty( $instance['parent'] ) ? '0' : '';
						} else {
							$woo_shortcode_attributes['per_page'] = intval( $number_of_items );
						}
						if ( ! empty( $post_columns ) ) $woo_shortcode_attributes['columns'] = intval( $post_columns );
						if ( ! empty( $orderby ) ) $woo_shortcode_attributes['orderby'] = esc_attr( $orderby );
						if ( ! empty( $order ) ) $woo_shortcode_attributes['order'] = esc_attr( $order );

						if ( ( 'product_category' == $section_type ) && ! empty( $product_cats ) ) $woo_shortcode_attributes['category'] = implode( ", ", $product_cats );
					}

					// Build Shortcode Atts
					$woo_shortcode_atts = '';
					foreach( $woo_shortcode_attributes as $key => $val ) {
						$woo_shortcode_atts .= $key . '="' . $val . '" ';
					}

					$woo_shortcode = sprintf( '[%s %2$s]',
						$section_type,
						$woo_shortcode_atts
					);

					// Modify Actions
					if ( 'product_page' == $section_type ) {
						// Change Cart
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
						add_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_add_to_cart', 30 );

						// Change Rating
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
						add_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_rating', 8 );

						// Remove Product Summaries
						remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
						remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
						remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
					}

					// Display Shortcode
					echo do_shortcode( $woo_shortcode );

					// Re-Add Actions
					if ( 'product_page' == $section_type ) {
						// Change Cart
						add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_add_to_cart', 30 );

						// Change Rating
						add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_rating', 8 );

						// Remove Product Summaries
						add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
						add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
						add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
					}
				?>

				<?php if ( 'carousel' == $section_layout ) { ?>
					</div>
				<?php } ?>

			</div><!-- .site-main -->
		</div><!-- .posts-index -->
	</div><!-- .publisher-products-loop-widget -->
	<?php

} else {

	if ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
		printf( '<p class="alert info publisher-ext">' . __( 'This widget requires the <a href="%s">Woocommerce</a> plugin to be active.', 'publisher' ) . '</p>', esc_url( 'http://www.woothemes.com/woocommerce/' ) );
	};

}
