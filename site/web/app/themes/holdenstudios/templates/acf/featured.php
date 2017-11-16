<div class="container acf-content">
	<div class="section-head">
		
		<?php if( get_sub_field('section_heading_featured') ) { ?>
			<h2><?php the_sub_field('section_heading_featured'); ?></h2>
		<?php	} ?>
		
		<?php if( get_sub_field('section_sub_heading_featured') ) { ?>
			<em><?php the_sub_field('section_sub_heading_featured'); ?></em>
		<?php	} ?>
		
	</div>
	
	<?php if( get_sub_field('section_description_featured') ) { ?>
		<div class="row section-head-content">
			<div class="col-sm-8 col-sm-offset-2">
				<?php the_sub_field('section_description_featured'); ?>
			</div>
		</div>
	<?php } ?>
	
	<div id="" class="featured-products">
	
	<?php
		global $product;

$meta_query   = WC()->query->get_meta_query();
$meta_query[] = array(
    'key'   => '_featured',
    'value' => 'yes'
);	    
	    $i = 1;
	    
	    $q = new WP_Query([
	      'post_type'   =>  'product',
	      'stock'       =>  1,
	      'showposts'   =>  -1,
	      'meta_query'  =>  $meta_query
	    ]);
	    if ( $q->have_posts() ) :
	        while ( $q->have_posts() ) : $q->the_post(); ?>
	        
	        <?php if ($i%3 == 1) { 
		        echo '<div class="row">';
	        } ?>
	        
	            <div class="featured-product-wrap col-sm-4 <?php if($i == 1) { echo 'design-your-own'; } ?>">
			          <div class="featured-inner">
			            <h4><?php the_title(); ?></h4>
									<?php woocommerce_template_single_meta(); ?>
<!-- 			            <a href="<?php the_permalink(); ?>"> -->
				            <?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
<!-- 				          </a> -->
			            <p class="price"><?php woocommerce_get_template( 'loop/price.php' ); ?></p>
			            <?php woocommerce_template_loop_add_to_cart(); //ouptput the woocommerce loop add to cart button ?>
			          </div>
		          </div>
	        <?php 
						if ($i%3 == 0) {
							echo "</div>";
						}
		        
		        
		        $i++; 
		        endwhile;
		      
		      wp_reset_query();
	
	    endif;
	?>
	
	</div>
</div>