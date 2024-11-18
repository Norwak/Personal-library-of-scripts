<?php
/**
 * The template for displaying categories
 */
get_header(); ?>

	<main id="main">

		<!-- Section: Breadcrumbs -->
		<section class="breadcrumbs mt15">
			<div class="container"> <?php
				if( function_exists('kama_breadcrumbs') ) kama_breadcrumbs(' <i class="fas fa-chevron-right"></i> '); ?>
			</div>
		</section>

		<!-- Section: Page Title -->
		<section id="page-title">
			<div class="container">

				<div class="category-title">
					<?= single_term_title(); ?>
				</div>

				<div class="category-description">
					<?= get_the_archive_description(); ?>
				</div>

			</div><!-- /.container -->
		</section>
		


		<!-- Section: Posts -->
		<section id="posts">
			<?php 
			if (have_posts()) {

				while (have_posts()) {
					the_post();

					// write here loop itself
					the_content();

				}
				the_posts_navigation();

			} else {
				echo '<p>Категория пуста</p>';
			} ?>
		</section>

	</main>

	<?php
	get_sidebar();

get_footer();