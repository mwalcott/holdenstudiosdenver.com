<?php if(is_page(100)) { ?>
	<div class="row">
		<div class="col-sm-12">
			<p class="wholesale">Interested in selling our products? Contact us via email at <a href="mailto:holdenstudiosdenver@gmail.com">holdenstudiosdenver@gmail.com</a> or use the form below to enquire.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-5">
			<img src="<?php the_field('banner_background_image_mobile'); ?>" class="img-responsive"/>
		</div>
		<div class="col-sm-7">
			<?php the_content(); ?>			
		</div>
	</div>

<?php } else { ?>
	<?php the_content(); ?>
<?php } ?>


<?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
