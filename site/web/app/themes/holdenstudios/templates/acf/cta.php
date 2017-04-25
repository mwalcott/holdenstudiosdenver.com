<div class="container-fluid acf-content cta-top" style="background-image: url(<?php the_sub_field('background_image_cta'); ?>);">
	<div class="container">
		<div class="section-head">
			
			<?php if( get_sub_field('section_header_cta') ) { ?>
				<h2><?php the_sub_field('section_header_cta'); ?></h2>
			<?php	} ?>
			
			<?php if( get_sub_field('section_sub_header_cta') ) { ?>
				<em><?php the_sub_field('section_sub_header_cta'); ?></em>
			<?php	} ?>
			
		</div>
		
		<?php if( get_sub_field('section_description_cta') ) { ?>
			<div class="row section-head-content">
				<div class="col-sm-8 col-sm-offset-2">
					<?php the_sub_field('section_description_cta'); ?>
				</div>
			</div>
		<?php } ?>
		
	
	</div>
</div>
<div class="container acf-content cta-bottom">
	<div class="row cta">
	
		<div class="col-sm-10 col-sm-offset-1">
			<div class="cta-lower-inner">
				<div class="row">
					<div class="col-sm-4 hidden-xs">
						<?php 
						
							$image = get_sub_field('block_image_cta');
							
							if( !empty($image) ) { 
								
								// vars
								$url = $image['url'];
								$title = $image['title'];
								$alt = $image['alt'];
								$caption = $image['caption'];
								
								// thumbnail
								$size = 'cta-size';
								$thumb = $image['sizes'][ $size ];
								$width = $image['sizes'][ $size . '-width' ];
								$height = $image['sizes'][ $size . '-height' ];
						?>
							<img src="<?php echo $thumb; ?>" class="img-responsive" alt="<?php echo $alt; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
						<?php } ?>
					</div>
					<div class="col-sm-8 cta-content">
						<div class="cta-content-inner">
							<h3><span><?php the_sub_field('block_heading_cta'); ?></span></h3>
							<?php the_sub_field('block_content_cta'); ?>
		
							<?php					
								// check if the nested repeater field has rows of data
								if( have_rows('buttons_cta') ):
								
									// loop through the rows of data
									while ( have_rows('buttons_cta') ) : the_row(); ?>
									
									<a href="<?php the_sub_field('button_url_cta'); ?>" class="btn btn-primary">
										<?php the_sub_field('button_text_cta'); ?>
									</a>
									
									
									<?php endwhile;
									
								endif;					
							?>
						</div>										
					</div>
				</div>
			</div>
		</div>
	
	</div>
</div>