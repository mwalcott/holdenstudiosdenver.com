<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->

    <nav id="my-menu" class="hidden-sm hidden-md hidden-lg">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => '']);
      endif;
      ?>
    </nav>

    <div>
	    <?php
	      do_action('get_header');
	      get_template_part('templates/header');
	    ?>
<!--
	    <h1 style="text-align: center;">Colorado Customs</h1>
	    <p style="text-align: center;">Call <a href="tel:720-360-5090">720-360-5090</a> for a quick quote.</p>
-->			
			<div id="business" class="container clearfix">
				<?php 

				$args = [
				  'taxonomy'     	=> 'custom_categories',
				  'number'        => 0,
				  'parent'        => 0,
				  'hide_empty'    => true
				];
				$terms = get_terms( $args );
				
				foreach ( $terms as $term ) {
					echo '<h3>'. $term->name .'</h3>';

					echo '<div class="row custom-work">';
						$args = array(
							'post_type' => 'custom_work',
							'posts_per_page' => -1,
							'tax_query' => array(
								array(
									'taxonomy' => 'custom_categories',
									'field'    => 'slug',
									'terms'    => $term->slug,
								),
							),
						);
						$the_query = new WP_Query( $args );
						$i = 0;
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post(); $i++;
							
								if( $i == 1 ) {
									echo '<div class="col-sm-4">';
										the_post_thumbnail( 'full', array( 'class' => 'img-responsive') );
									echo '</div>';
								} elseif( $i == 2 ) {
									echo '<div class="col-sm-8">';
										echo '<div class="row">';
											echo '<div class="col-sm-6">';
												the_post_thumbnail( 'portfolio', array( 'class' => 'img-responsive') );
											echo '</div>';
								} elseif( $i == 5 ) {
											echo '<div class="col-sm-6">';
												the_post_thumbnail( 'portfolio', array( 'class' => 'img-responsive') );
											echo '</div>';
										echo '</div>';
									echo '</div>';
								} else {
									echo '<div class="col-sm-6">';
										the_post_thumbnail( 'portfolio', array( 'class' => 'img-responsive') );
									echo '</div>';
								}
								
								if( $i == 5 ) {
									$i = 0;
								}
							
							
							?>
							
							<?php }
							wp_reset_postdata();
						} else {
		
						}
					
					echo '</div>';

				}


/*
				$args = array(
					'post_type' => 'custom_work',
					'posts_per_page' => -1
				);
				$the_query = new WP_Query( $args );
				
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post(); 
					?>
					
						<div class="">
							<?php the_post_thumbnail( 'portfolio', array( 'class' => 'img-responsive') ); ?>
						</div>
						
					<?php }
					wp_reset_postdata();
				} else {

				}
*/
					
				?>
			</div>

	    <div class="wrap container" role="document">
	      <div class="content row">
	        <main class="main">
	          <?php include Wrapper\template_path(); ?>
	        </main><!-- /.main -->
	        <?php if (Setup\display_sidebar()) : ?>
	          <aside class="sidebar">
	            <?php include Wrapper\sidebar_path(); ?>
	          </aside><!-- /.sidebar -->
	        <?php endif; ?>
	      </div><!-- /.content -->
	    </div><!-- /.wrap -->
	    <?= Holden\content_acf(); ?>

	    <?php
	      do_action('get_footer');
	      get_template_part('templates/footer');
	      wp_footer();
	    ?>
    </div>
    
    <script type="text/javascript" src="<?= get_template_directory_uri() . '/assets/scripts/owl.carousel.min.js'; ?>"></script>
    
  </body>
</html>
