<?php
	$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
?>
<?php if(is_page(100)) { ?>
	<div class="row">
		<div class="col-sm-12">
			<img src="<?php the_field('banner_background_image_mobile'); ?>" class="img-responsive"/>
<!-- 			<p class="wholesale">Interested in selling our products? Contact us via email at <a href="mailto:holdenstudiosdenver@gmail.com">holdenstudiosdenver@gmail.com</a> or use the form below to enquire.</p> -->
		</div>
	</div>
	
	<?php the_content(); ?>

<?php } else { ?>
	<?php 
	if (strpos($url,'2187') !== false) {
		echo '<p class="wedding-text" style="text-align: center;"><strong>Wedding season is upon us!!</strong><br /><br />We love weddings and marriage! Let Holden Studios help with a stunning hand burned guestbook for your special day. Have your guests sign the back for memories that last a lifetime.</p>';
		the_content();
	} else {
		the_content();
	}
 ?>
<?php } ?>


<?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
