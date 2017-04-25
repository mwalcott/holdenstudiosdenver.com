<?php
/**
 * Template Name: Business
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <div class="text-center">
  	<?php get_template_part('templates/content', 'page'); ?>
  </div>
<?php endwhile; ?>
