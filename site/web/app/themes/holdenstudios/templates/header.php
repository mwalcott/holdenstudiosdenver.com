<?php
	$jumbotron = '';
	if( get_field('banner') ) {  
		$jumbotron = 'banner-top';	
	}
?>

<header class="banner <?php echo $jumbotron; ?>" id="myAffix">
  <div class="container-fluid">

		<div class="top-bar clearfix">
			<a class="hamburger visible-xs pull-left" href="#my-menu"><i class="fa fa-bars" aria-hidden="true"></i></a>
			<div class="pull-right">
		    <?php
			    if (has_nav_menu('user_navigation')) :
			      wp_nav_menu(['theme_location' => 'user_navigation', 'menu_class' => 'nav user-nav pull-left']);
			    endif;
		    ?>
		    <?= Holden\cart_items(); ?>
			</div>
		</div>

		<div class="logo-content container-fluid">
			<img class="img-responsive normal" src="<?= get_template_directory_uri(); ?>/dist/images/logo.png"/>
		</div>
		  
    <nav class="nav-primary hidden-xs">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav-main']);
      endif;
      ?>
    </nav>
				  
  </div>
</header>


<?= Holden\jumbotron(); ?>