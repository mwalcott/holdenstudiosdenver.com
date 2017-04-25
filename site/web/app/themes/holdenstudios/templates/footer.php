<footer class="content-info clearfix">
  <div class="container">
    <?php dynamic_sidebar('sidebar-footer'); ?>
    
    <div class="row footer-info">
	   
	    <div class="col-xs-7 col-sm-3 col">
		    <?= Holden\footer_column_1();  ?>
	    </div>
	    
	    <div class="col-sm-1 hidden-xs"></div>
	    
	    <div class="col-xs-5 col-sm-2 col">
		    <h4><?php the_field('column_3_heading', 'option'); ?></h4>
	      <?php
	      if (has_nav_menu('about_navigation')) :
	        wp_nav_menu(['theme_location' => 'about_navigation', 'menu_class' => 'about-nav']);
	      endif;
	      ?>

<!-- 		    <?= Holden\footer_column_2();  ?> -->
	    </div>

<!-- 	    <div class="col-sm-1 hidden-xs"></div> -->
	    
	    <div class="col-xs-6 col-sm-2 col">
		    <h4><?php the_field('column_4_heading', 'option'); ?></h4>
	      <?php
	      if (has_nav_menu('account_navigation')) :
	        wp_nav_menu(['theme_location' => 'account_navigation', 'menu_class' => 'about-nav']);
	      endif;
	      ?>

<!--
		    <h4><?php the_field('column_3_heading', 'option'); ?></h4>
	      <?php
	      if (has_nav_menu('about_navigation')) :
	        wp_nav_menu(['theme_location' => 'about_navigation', 'menu_class' => 'about-nav']);
	      endif;
	      ?>
-->
	    </div>

	    <div class="col-sm-1 hidden-xs"></div>

	    <div class="col-xs-6 col-sm-2 col">
		    
<!--
		    <h4><?php the_field('column_4_heading', 'option'); ?></h4>
	      <?php
	      if (has_nav_menu('account_navigation')) :
	        wp_nav_menu(['theme_location' => 'account_navigation', 'menu_class' => 'about-nav']);
	      endif;
	      ?>
-->
	    </div>
	    
    </div>
    
    <div class="row copy">
	    <div class="col-sm-12 text-center">
<!-- 		    <?= Holden\cc_gallery(); ?>			   -->
		    Copyright &copy; <?php echo date('Y'); ?> Holden Studios. All Rights Reserved		    
	    </div>
    </div>
  </div>
</footer>
