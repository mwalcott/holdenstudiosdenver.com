<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$config = '';
if (strpos($url,'2187') !== false) {
	$config = 'config-2187';
}
?>

<!doctype html>
<html <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class($config); ?>>
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
		        <?php 
			        if (strpos($url,'2187') !== false) {
		        ?>
		        <div class="owl-carousel owl-wedding">
			        
			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-9-1-640x430.jpeg"/>
			        </div>
			        
			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-8-1-640x430.jpeg"/>
			        </div>
			        
			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-6-1-640x430.jpeg"/>
			        </div>
			        
			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-5-1-640x430.jpeg"/>
			        </div>
			        
			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-3-1-640x430.jpeg"/>
			        </div>
			        
			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-640x430.jpeg"/>
			        </div>

			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-12-640x430.jpeg"/>
			        </div>

			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-9-640x430.jpeg"/>
			        </div>

			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-8-640x430.jpeg"/>
			        </div>

			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-8-640x430.jpeg"/>
			        </div>

			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-7-640x430.jpeg"/>
			        </div>

			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-5-640x430.jpeg"/>
			        </div>

			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-4-640x430.jpeg"/>
			        </div>

			        <div>
				        <img src="/app/uploads/2017/09/FullSizeRender.jpg-3-640x430.jpeg"/>
			        </div>
			        
		        </div>
		        <?php 
			        }
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
	    <?php
	      do_action('get_footer');
	      get_template_part('templates/footer');
	      wp_footer();
	    ?>
    </div>
    
    <script type="text/javascript" src="<?= get_template_directory_uri() . '/assets/scripts/owl.carousel.min.js'; ?>"></script>
    
  </body>
</html>
