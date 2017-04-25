<div class="container acf-content">
	<div class="section-head">
		
		<?php if( get_sub_field('section_header_blog') ) { ?>
			<h2><?php the_sub_field('section_header_blog'); ?></h2>
		<?php	} ?>
		
		<?php if( get_sub_field('section_sub_header_blog') ) { ?>
			<em><?php the_sub_field('section_sub_header_blog'); ?></em>
		<?php	} ?>
		
	</div>
		
	<div class="row blog">
		<div class="col-sm-10 col-sm-offset-1">
			<?php
				
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => 2
				);
			
				// The Query
				$the_query = new WP_Query( $args );
				
				$i = 0;
				
				// The Loop
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) { 
						$the_query->the_post(); $i++ ?>
						
						<?php
							$colImg = '';
							$colContent = '';
							if( $i == 2 ) {
								$colImg = 'col-sm-push-7';
								$colContent = 'col-sm-pull-5';
							}
						?>
						
						<section class="blog-post">
							<div class="blog-post-inner row">
								<div class="col-sm-5 <?php echo $colImg; ?>">
									<?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
								</div>
								<div class="col-sm-7 <?php echo $colContent; ?>">
									<div class="blog-content">
										<h4><span><?php the_title(); ?></span></h4>
										<?php the_excerpt(); ?>
										<a href="<?php the_permalink(); ?>" class="btn btn-secondary">Read More</a>
									</div>
								</div>
							</div>
						</section>
					<?php }
					/* Restore original Post Data */
					wp_reset_postdata();
				} else {
					// no posts found
				}	
			?>
		</div>
	
	</div>
</div>