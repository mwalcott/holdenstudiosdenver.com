<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

namespace Holden;

add_image_size( 'cta-size', 500, 648, true );
add_image_size( 'portfolio', 640, 430, true );

/**
 * Register Global Options Page - ACF
 */
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

/**
 * Jumbotron - Main Banner at the top of pages
 */
function jumbotron() { ?>

	<?php if( get_field('banner') ) { ?>
		<img src="<?php the_field('banner_background_image_mobile'); ?>" class="img-responsive"/>
	
	


	<?php } ?>


<?php }

/**
 * Jumbotron - Main Banner at the top of pages
 */
function carouselHome() { ?>

	<?php if( is_front_page() ) { ?>
		
	
		<div class="owl-carousel owl-home">

			<?php
			
			// check if the repeater field has rows of data
			if( have_rows('slides') ):
			
			 	// loop through the rows of data
			    while ( have_rows('slides') ) : the_row(); ?>
			
			        <div style="background-image: url(<?php the_sub_field('slide_image'); ?>)">
			        	<div class="content">
				        	<h2><?php the_sub_field('heading'); ?></h2>
				        	<h3><?php the_sub_field('sub_heading'); ?></h3>
				        	<a class="btn btn-primary" href="<?php the_sub_field('button_url'); ?>">
					        	<?php the_sub_field('button_text'); ?>
				        	</a>
			        	</div>
			        </div>
			
			    <?php endwhile;
			
			else :
			
			    // no rows found
			
			endif;
			
			?>
			
		</div>


	<?php } ?>


<?php }

/**
 * Cart Items
 */
function cart_items() {
	
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$count = WC()->cart->cart_contents_count;
	?>
	
		<a class="cart-contents pull-left" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
			<?php 
				if ( $count > 0 ) { 
					echo '<span>' . $count . '</span><i class="fa fa-shopping-basket" aria-hidden="true"></i>'; 
				}
				else {
					
				}
			?>
		</a>
	
	<?php 
	}
}

/**
 * Add Login / Logout Links
 */
function add_loginout_link( $items, $args ) {
	if (is_user_logged_in() && $args->theme_location == 'user_navigation') {
		$items .= '<li class="menu-item menu-logout"><a href="/my-account/customer-logout"><i class="fa fa-user" aria-hidden="true"></i> Logout</a></li>';
	}
	elseif (!is_user_logged_in() && $args->theme_location == 'user_navigation') {
		$items .= '<li class="menu-item menu-login"><a href="/my-account"><i class="fa fa-user" aria-hidden="true"></i> Login</a></li>';
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', __NAMESPACE__ . '\\add_loginout_link', 10, 2 );

/**
 * Get credit card logos
 */
function cc_gallery() { ?>

	<ul class="cc_gallery">
		<li><i class="fa fa-cc-visa" aria-hidden="true"></i></li>
		<li><i class="fa fa-cc-mastercard" aria-hidden="true"></i></li>
		<li><i class="fa fa-cc-amex" aria-hidden="true"></i></li>
		<li><i class="fa fa-cc-discover" aria-hidden="true"></i></li>
	</ul>
	
<?php }

/**
 * Footer Column 1
 */
function footer_column_1() { ?>
	
	<h4><?php the_field('column_1_heading', 'option'); ?></h4>
	<p><?php the_field('column_1_content', 'option'); ?></p>

	<?php
	
		// check if the repeater field has rows of data
		if( have_rows('social_media_accounts', 'option') ):
			echo '<ul class="social">';
			// loop through the rows of data
			while ( have_rows('social_media_accounts', 'option') ) : the_row(); ?>
				<li>
					<a href="<?php the_sub_field('account_url'); ?>" target="_blank" rel="nofollow">
						<i class="fa fa-<?php the_sub_field('account_provider'); ?>" aria-hidden="true"></i>
					</a>
				</li>
			
			<?php endwhile;
			echo '</ul>';
		
		else :
		
			// no rows found
		
		endif;
	
	?>
	
<?php }

/**
 * Footer Column 2
 */
function footer_column_2() { ?>
	
	<h4><?php the_field('column_2_heading', 'option'); ?></h4>

	<?php
	
		// check if the repeater field has rows of data
		if( have_rows('store_hours', 'option') ):
			echo '<ul>';
			// loop through the rows of data
			while ( have_rows('store_hours', 'option') ) : the_row(); ?>
			
				<li>
					<?php the_sub_field('hours'); ?>
				</li>
						
			<?php endwhile;
			echo '</ul>';
		
		else :
		
			// no rows found
		
		endif;
	
	?>
	
<?php }
	
/**
 * Change from Price Range to Lowest Price
 */
/*
function custom_variation_price( $price, $product ) {
	$price = '';
	if ( !$product->min_variation_price || $product->min_variation_price !== $product->max_variation_price ) {
		$price .= '<span class="from">' . _x('From ', 'min_price', 'woocommerce') . ' </span>';
		$price .= woocommerce_price($product->get_price());
	}
	
	return $price;
}
add_filter('woocommerce_variable_price_html', __NAMESPACE__ . '\\custom_variation_price', 10, 2);
*/

function wpa83367_price_html( $price, $product ){
  //var_dump($product);
	$price = '';
		
		
	if($product->product_type == 'variable') {
	  $price .= '<span class="from">' . _x('From ', 'min_price', 'woocommerce') . ' </span>';
	  $price .= woocommerce_price($product->get_price());
	} elseif($product->product_type == 'simple') {
		if($product->regular_price == 95) {
		  $price .= '<span class="from">' . _x('From ', 'min_price', 'woocommerce') . ' </span>';
		  $price .= woocommerce_price($product->get_price());
		} else {
		  $price .= '<span class="from">' . _x('', 'woocommerce') . ' </span>';
		  $price .= woocommerce_price($product->get_price());
		}
	} else {
	  $price .= '<span class="from">' . _x('', 'woocommerce') . ' </span>';
	  $price .= woocommerce_price($product->get_price());
	}
	  
  return $price;
  
}
add_filter( 'woocommerce_get_price_html', __NAMESPACE__ . '\\wpa83367_price_html', 100, 2 );

/**
 * Remove product count from category
 */
function woo_remove_category_products_count() {
  return;
}
add_filter( 'woocommerce_subcategory_count_html', __NAMESPACE__ . '\\woo_remove_category_products_count' );


/**
 * Remove product tabs
 */
function woo_remove_product_tabs( $tabs ) {
    unset( $tabs['description'] );
    //unset( $tabs['reviews'] );
    unset( $tabs['additional_information'] );
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', __NAMESPACE__ . '\\woo_remove_product_tabs', 98 );

/**
 * Content Builder ACF
 */
function content_acf() { 

	// check if the flexible content field has rows of data
	if( have_rows('sections') ):
	
		// loop through the rows of data
		while ( have_rows('sections') ) : the_row();
		
			if( get_row_layout() == 'category_list' )
			
				get_template_part('templates/acf/category');

			if( get_row_layout() == 'featured_products' )
			
				get_template_part('templates/acf/featured');

			if( get_row_layout() == 'call_to_action' )
			
				get_template_part('templates/acf/cta');

			if( get_row_layout() == 'blog' )
			
				get_template_part('templates/acf/blog');

			if( get_row_layout() == 'buttons' )
			
				get_template_part('templates/acf/buttons');
									
		endwhile;
	
	else :
	
		// no layouts found
	
endif;

}
