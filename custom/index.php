<?php
/**
 * The main template file
 *
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 */

get_header(); ?>

	<main id="main">

		<!-- Section: Description -->
		<section id="description">
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
