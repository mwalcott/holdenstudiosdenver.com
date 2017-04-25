<div class="container acf-content">
	<div class="section-head">
		
		<?php if( get_sub_field('section_sub_heading_cat') ) { ?>
			<h2><?php the_sub_field('section_heading_cat'); ?></h2>
		<?php	} ?>
		
		<?php if( get_sub_field('section_sub_heading_cat') ) { ?>
			<em><?php the_sub_field('section_sub_heading_cat'); ?></em>
		<?php	} ?>
		
	</div>
	
	<?php if( get_sub_field('section_description_cat') ) { ?>
		<div class="row section-head-content">
			<div class="col-sm-8 col-sm-offset-2">
				<?php the_sub_field('section_description_cat'); ?>
			</div>
		</div>
	<?php } ?>
	
	<div class="row product-categories">
		<?php
			$taxonomy     = 'product_cat';
			$orderby      = 'name';  
			$show_count   = 0;      // 1 for yes, 0 for no
			$pad_counts   = 0;      // 1 for yes, 0 for no
			$hierarchical = 1;      // 1 for yes, 0 for no  
			$title        = '';  
			$empty        = 0;
			
			$args = array(
				'taxonomy'     => $taxonomy,
				'orderby'      => $orderby,
				'show_count'   => $show_count,
				'pad_counts'   => $pad_counts,
				'hierarchical' => $hierarchical,
				'title_li'     => $title,
				'hide_empty'   => $empty
			);
			$all_categories = get_categories( $args );
			foreach ($all_categories as $cat) {
				if($cat->category_parent == 0) {
					$category_id = $cat->term_id;  
					$thumbnail_id = get_woocommerce_term_meta( $category_id, 'thumbnail_id', true );     
					$image = wp_get_attachment_url( $thumbnail_id );
					echo '<div class="col-sm-3 cat">';
						echo '<div class="cat-inner">';
							echo '<a href="'. get_term_link($cat->slug, 'product_cat') .'">';
								echo '<h2>'. $cat->name .'</h2>';
								echo '<img class="img-responsive" src="'. $image .'"/>';
							echo '</a>';
						echo '</div>';
					echo '</div>';
					$args2 = array(
						'taxonomy'     => $taxonomy,
						'child_of'     => 0,
						'parent'       => $category_id,
						'orderby'      => $orderby,
						'show_count'   => $show_count,
						'pad_counts'   => $pad_counts,
						'hierarchical' => $hierarchical,
						'title_li'     => $title,
						'hide_empty'   => $empty
					);
					$sub_cats = get_categories( $args2 );
					if($sub_cats) {
						foreach($sub_cats as $sub_category) {
							echo  $sub_category->name ;
						}   
					}
				}       
			}
		?>
	</div>
</div>