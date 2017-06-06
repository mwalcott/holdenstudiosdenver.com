<?php if(is_page(100)) { ?>
	<div class="row">
		<div class="col-sm-12">
			<img src="<?php the_field('banner_background_image_mobile'); ?>" class="img-responsive"/>
<!-- 			<p class="wholesale">Interested in selling our products? Contact us via email at <a href="mailto:holdenstudiosdenver@gmail.com">holdenstudiosdenver@gmail.com</a> or use the form below to enquire.</p> -->
		</div>
	</div>
	
	<?php the_content(); ?>

<?php } else { ?>
	<?php the_content(); ?>
<?php } ?>


<?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
