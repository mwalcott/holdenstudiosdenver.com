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

<!--
			<div class="text-center">
				<?php 
					$i = 1;
					$terms = get_terms( 'custom_categories', array(
						'exclude' => array(35,32)
					));
					$count = count( $terms );
					echo '<div class="filter-wrap">';
					echo 'Filters: ';
					if ( $count > 0 ) {
						foreach ( $terms as $term ) {
							$active = '';
							if( $i == 1 ) {
								$active = 'test';
							}
						  echo '<a class="filter btn btn-default btn-md '. $active .'" data-filter=".'. $term->slug .'">' . $term->name . '</a>';
						  
						  $i++;
						}
					}
					echo '</div>';			
				?>
			</div>	
-->
	    
			<div id="portfolio" class="container-fluid clearfix">
				<?php 
				$args = array(
					'post_type' => 'custom_work',
					'posts_per_page' => -1
				);
				// The Query
				$the_query = new WP_Query( $args );
				
				// The Loop
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post(); 
					?>
						<?php
							$filter = '';
							$term_list = wp_get_post_terms($post->ID, 'custom_categories', array("fields" => "all"));
							foreach($term_list as $term_single) {
								$filter = $term_single->slug; //do something here
							}
						?>
						<div class="mix <?php echo $filter; ?>">
							<?php the_post_thumbnail( 'portfolio', array( 'class' => 'img-responsive') ); ?>
						</div>
						
					<?php }
					/* Restore original Post Data */
					wp_reset_postdata();
				} else {
					// no posts found
				}
					
				?>
			</div>

	    <?php
	      do_action('get_footer');
	      get_template_part('templates/footer');
	      wp_footer();
	    ?>
    </div>
    
    <script type="text/javascript" src="<?= get_template_directory_uri() . '/assets/scripts/owl.carousel.min.js'; ?>"></script>
    
  </body>
</html>
