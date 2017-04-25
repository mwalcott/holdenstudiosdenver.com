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
	    <?php
	      do_action('get_footer');
	      get_template_part('templates/footer');
	      wp_footer();
	    ?>
    </div>
    
    <script type="text/javascript" src="<?= get_template_directory_uri() . '/assets/scripts/owl.carousel.min.js'; ?>"></script>
    
  </body>
</html>
