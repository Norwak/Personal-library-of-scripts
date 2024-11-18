<?php
/**
 * The template for displaying all single posts
 */
get_header(); ?>

	<main id="main">

		<!-- Section: Breadcrumbs -->
		<section class="breadcrumbs mt15">
			<div class="container"> <?php
				if( function_exists('kama_breadcrumbs') ) kama_breadcrumbs(' <i class="fas fa-chevron-right"></i> '); ?>
			</div>
		</section>

		<?php
		while (have_posts()) {
			the_post(); ?>

			<?= apply_filters('the_content', get_the_content()); ?>

			<?php
			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if (comments_open() || get_comments_number()) {
				comments_template();
			}
		} ?>

	</main>

	<?php
	get_sidebar();

get_footer();
