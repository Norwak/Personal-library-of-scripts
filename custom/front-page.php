<?php
/**
 * The home page
 */

get_header(); ?>

	<main id="main">
		<!-- Section: Content -->
		<section class="content user-content" id="content">
			<div class="container">
				<?= apply_filters('the_content', get_the_content(null, null, $post->ID)); ?>
			</div>
		</section>

		<!-- Section: Gallery -->
		<section class="gallery-514 mt50 pt30 pb20" id="gallery-514">
			<div class="container-narrow">
				<h3 class="gallery-514__title">Наши работы:</h3>
				<div class="mt15 flex-gl cols6 gap20b lpt-cols4 stb-cols2"> <?php
					$my_posts = get_posts( array(
						'numberposts' => -1,
						'orderby'     => 'menu_order',
						'order'       => 'DESC',
						'post_type'   => 'photos',
					) );
				
					foreach ($my_posts as $post) {
						setup_postdata( $post ); ?>
						<div class="gallery-514__item">
							<a href="<?=get_image($post->ID, 'full')?>" class="gallery-514__link swipebox img-ar img-ar--4-3" rel="works">
								<img src="<?=get_image($post->ID, 'thumbnail')?>">
							</a>
							<span class="gallery-514__caption"><?=get_the_title($post->ID)?></span>
						</div> <?php
						}
					wp_reset_postdata(); ?>
				</div>
				<h3 class="gallery-514__title">Наши работы:</h3>
				<div class="mt15 flex-gl cols6 gap20b lpt-cols4 stb-cols2"> <?php
					$my_posts = get_posts( array(
						'numberposts' => -1,
						'orderby'     => 'menu_order',
						'order'       => 'DESC',
						'post_type'   => 'photos',
					) );
				
					foreach ($my_posts as $post) {
						setup_postdata( $post ); ?>
						<div class="gallery-514__item">
							<a href="<?=get_image($post->ID, 'full')?>" class="gallery-514__link swipebox img-ar img-ar--4-3" rel="works">
								<img src="<?=get_image($post->ID, 'thumbnail')?>">
							</a>
							<span class="gallery-514__caption"><?=get_the_title($post->ID)?></span>
						</div> <?php
						}
					wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	</main>

	<?php
	get_sidebar();

get_footer();
