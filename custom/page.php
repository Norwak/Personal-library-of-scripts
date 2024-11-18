<?php
/**
 * The template for typical text page
 */
get_header(); ?>

	<main id="main">

		<!-- Section: Breadcrumbs -->
		<section class="breadcrumbs mt15">
			<div class="container"> <?php
				if( function_exists('kama_breadcrumbs') ) kama_breadcrumbs(' <i class="fas fa-chevron-right"></i> '); ?>
			</div>
		</section>

		<!-- Section: Post -->
		<section id="post">
			<?php
			if (have_posts()) {
				while (have_posts()) {
					the_post(); ?>

					<?= apply_filters('the_content', get_the_content()); ?>

					<?php
				}
			} ?>
		</section>

	</main>

	<?php
	get_sidebar();

get_footer();
