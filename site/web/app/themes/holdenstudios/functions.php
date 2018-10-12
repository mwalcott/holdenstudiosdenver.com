<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php' // Theme customizer
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

function woocommerce_shipping_content() {

global $post;
$terms = wp_get_post_terms( $post->ID, 'product_cat' );
foreach ( $terms as $term ) $categories[] = $term->slug;

if ( in_array( 'ornaments', $categories ) || in_array( 'coasters', $categories ) || in_array( 'magnets', $categories ) ) {
  echo '<p class="ships-free"><strong>Ships within 7-14 business days.</strong></p>';	
} elseif( in_array( 'cornhole', $categories ) ) {
	echo '';	
}	else {
  echo '<p>All artwork is ready to hang</p>';
  echo '<p class="ships-free"><strong>All orders over $40 ship free. Ships within 7-14 business days.</strong><br />';	
  echo '<i style="font-size: 1rem;">*Excludes Cornhole, Red Rocks, and Flatirons.</i></p>';
}
	
/*
	if ( in_array( 'ornaments', $categories ) ) { {
		echo '<p class="ships-free"><strong>Ships within 7-10 business days.</strong></p>';	
	} else {
		echo '<p class="ships-free"><strong>Ships FREE within 7-10 business days.</strong></p>';	
	}
*/

	
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );


//remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

//remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 8 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 6 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_shipping_content', 30 );

function open_product_wrapper() {
	echo '<div class="product-info">';
}
function close_product_wrapper() {
	echo '</div>';
}


add_action( 'woocommerce_shop_loop_item_title', 'open_product_wrapper', 9 );
add_action( 'woocommerce_after_shop_loop_item', 'close_product_wrapper',11 );

function remove_loop_button(){
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}
//add_action('init','remove_loop_button');

function ST4_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
        return $post_thumbnail_img[0];
    }
}

// ADD NEW COLUMN
function ST4_columns_head($defaults) {
    $defaults['featured_image'] = 'Featured Image';
    return $defaults;
}
 
// SHOW THE FEATURED IMAGE
function ST4_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
        $post_featured_image = ST4_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img style="max-width:100%; max-height: 100px;" src="' . $post_featured_image . '" />';
        }
    }
}

add_filter('manage_posts_columns', 'ST4_columns_head');
add_action('manage_posts_custom_column', 'ST4_columns_content', 10, 2);


add_filter( 'woocommerce_loop_add_to_cart_link', 'replace_default_button' );
function replace_default_button(){
    //list category slugs where button needs to be changed
    $selected_cats = array('coasters');
    //get current category object
    $current_cat = get_queried_object();
    //get category slug from category object
    $current_cat_slug = $current_cat->slug;
    //check if current category slug is in the selected category list
    if( in_array($current_cat_slug, $selected_cats) ){
        //replace default button code with custom code
        return '<a class="button product_type_simple add_to_cart_button ajax_add_to_cart" href="'. get_permalink() .'">Select</a>';
    }
}

/**
 * @snippet       Disable Free Shipping if Cart has Shipping Class (WooCommerce 2.6+)
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=19960
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.4.4
 */
   
function businessbloomer_hide_free_shipping_for_shipping_class( $rates, $package ) {
	$shipping_class_target = 53; // shipping class ID (to find it, see screenshot below)
	$in_cart = false;
	foreach( WC()->cart->cart_contents as $key => $values ) {
		if( $values[ 'data' ]->get_shipping_class_id() == $shipping_class_target ) {
			$in_cart = true;
			break;
		} 
	}
	if( $in_cart ) {
		unset( $rates['free_shipping:10'] ); // shipping method with ID (to find it, see screenshot below)
	}
	return $rates;
}
add_filter( 'woocommerce_package_rates', 'businessbloomer_hide_free_shipping_for_shipping_class', 10, 2 );
