<?php
	global $post;
	//var_dump($post);
	$jumbotron = '';
	if( get_field('banner') ) {  
		$jumbotron = 'banner-top';	
	}
?>

<header class="banner <?php echo $jumbotron; ?>" id="myAffix">
  <div class="container-fluid">

		<div class="top-bar clearfix">
			<a class="hamburger visible-xs pull-left" href="#my-menu"><i class="fa fa-bars" aria-hidden="true"></i></a>
			<div class="pull-left header-contact">
			  <ul>
				  <li>
				  	<a href="tel:<?php the_field('phone_number', 'option'); ?>">
					  	<i class="fa fa-phone" aria-hidden="true"></i> 
					  	<span class="hidden-xs"><?php the_field('phone_number', 'option'); ?></span>
					  </a>
				  </li>
				  <li>
				  	<a href="mailto:info@holdenstudiosdenver.com">
					  	<i class="fa fa-envelope-o" aria-hidden="true"></i> 
					  	<span class="hidden-xs">info@holdenstudiosdenver.com</span>
					  </a>
				  </li>
			  </ul>
				
			</div>
			<div class="pull-right">
				
		    <?php
			    if (has_nav_menu('user_navigation')) :
			      wp_nav_menu(['theme_location' => 'user_navigation', 'menu_class' => 'nav user-nav pull-left']);
			    endif;
		    ?>
		    <?= Holden\cart_items(); ?>
			</div>
		</div>

		<div class="logo-content container-fluid">
			<a href="<?= esc_url(home_url('/')); ?>">
				<img class="img-responsive normal" src="<?= get_template_directory_uri(); ?>/dist/images/logo-new.png"/>
			</a>
		</div>
		  
    <nav class="nav-primary hidden-xs">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav-main']);
      endif;
      ?>
    </nav>
				  
  </div>
</header>

<?php if(is_page(100) || is_front_page()) { ?>

<?php } else { ?>
	<?= Holden\jumbotron(); ?>
<?php } ?>

<?php if( is_front_page() ) { ?>
	<?= Holden\carouselHome(); ?>
<?php } ?>

<?php //if( is_product_category( array( 'ornaments', 'magnets', 'coasters' ) ) || is_product() || is_cart() || is_page(319) ) { ?>
<?php 
/*
	if( $product->id == 1307 && is_product() ) {
		
	}
*/

	if( is_product_category( array( 'ornaments', 'magnets', 'coasters' ) ) || is_product() ) { 
	
?>
<!--
	<div class="free-shipping">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 text-center">
					<h2>
						<i class="fa fa-truck" aria-hidden="true"></i>
						<?php 
							if( has_term( 'ornaments' ) ) {
								echo 'All ornaments orders over $50 ship FREE';
							} else {
								echo 'All orders over $50 ship FREE';
							}
						?>
					</h2>
				</div>
			</div>
		</div>
	</div>
-->
<?php } else { } ?>