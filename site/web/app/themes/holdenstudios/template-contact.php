<?php
/**
 * Template Name: Contact Template
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  
  <div class="row">
	  <div class="col-sm-8">
		  <?php get_template_part('templates/content', 'page'); ?>
	  </div>
	  <div class="col-sm-4 contact-info">
		  <h3>Contact Information</h3>
		  <ul>
			  <li>
			  	<a href="tel:<?php the_field('phone_number', 'option'); ?>"><i class="fa fa-phone" aria-hidden="true"></i> <?php the_field('phone_number', 'option'); ?></a>
			  </li>
			  <li>
			  	<a href="mailto:holdenstudiosdenver@gmail.com"><i class="fa fa-envelope-o" aria-hidden="true"></i> holdenstudiosdenver@gmail.com</a>
			  </li>
		  </ul>

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
		  
		  
	  </div>
  </div>
 
<?php endwhile; ?>		