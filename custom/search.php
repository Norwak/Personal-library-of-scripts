<?php
/**
 * The template for displaying search results pages
 *
 */

get_header();
?>

	<main id="main">
		
		<!-- Section: Breadcrumbs -->
		<section class="breadcrumbs mt15">
			<div class="container"> <?php
				if( function_exists('kama_breadcrumbs') ) kama_breadcrumbs(' <i class="fas fa-chevron-right"></i> '); ?>
			</div>
		</section>

		<!-- Section: Page Title -->
		<section id="page-title">
			<h1>
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'Результаты поиска: %s', 'custom' ), '<span>' . get_search_query() . '</span>' ); ?>
			</h1>
		</section>

		<?php if (have_posts()) { ?>

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				the_permalink();

			endwhile;

			the_posts_navigation();

		} else {

			echo "По заданному запросу нет результатов. Пожалуйста, попробуйте ввести другой запрос.";

		}
		?>

	</main>

<?php
get_sidebar();
get_footer();
