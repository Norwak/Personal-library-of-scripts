<?php
/* // */
// Add dashicons
/* // */
add_action('wp_enqueue_scripts', 'ww_load_dashicons', 999);
function ww_load_dashicons(){
  wp_enqueue_style('dashicons');
}


/* // */
// Check if post has category (even grandparent)
/* // */
function post_is_in_descendant_category( $cats, $_post = null ){
	foreach ( (array) $cats as $cat ) {
		// get_term_children() accepts integer ID only
		$descendants = get_term_children( (int) $cat, 'category' );
		if( $descendants && in_category( $descendants, $_post ) )
			return true;
	}
	return false;
}



/* // */
// disable srcset on frontend
/* // */
add_filter('max_srcset_image_width', 'disable_wp_responsive_images');
function disable_wp_responsive_images() {
	return 1;
}



/* // */
// Make raw phone number from beautiful
/* // */
function get_raw_phone($phone) {
	if (!$phone) {
		return;
	}
	if (substr($phone, 0, 2) === '{{') {
		return $phone;
	}

	preg_match_all('!\d+!', $phone, $phoneRaw);
	$phoneRaw = implode('', $phoneRaw[0]);
	if (($phoneRaw[0] != '+') AND ($phoneRaw[0] == '7') AND (strlen($phoneRaw) > '9')) {
		$phoneRaw = '+' . $phoneRaw;
	}
	if (($phoneRaw[0] == '8') AND (strlen($phoneRaw) == '11')) {
		$phoneRaw = preg_replace('/^[8]/i', '', $phoneRaw, 1);
		$phoneRaw = '+7' . $phoneRaw;
	}
	return $phoneRaw;
}



/* // */
// Highlight last part of phone number
/* // */
function get_formatted_phone($phone) {
	if (!$phone) {
		return;
	}
	if (substr($phone, 0, 2) === '{{') {
		return $phone;
	}
	
	$phone_formatted = $phone;
	if (strlen($phone_formatted) === 0) return '';

	if (strpos($phone, ')')) { // if number has parenthesis
		$phone_formatted = substr_replace($phone, '<span>', (strpos($phone, ')') + 1), 0);
		$phone_formatted .= '</span>';
	} else if (strpos($phone, '-') || strpos($phone, ' ')) { // if number has spaces or minuses
		$phone_formatted = substr_replace($phone, '<span>', -9, 0);
		$phone_formatted .= '</span>';
	} else if ($phone !== '') { // if number is just digits
		$phone_formatted = substr_replace($phone, '<span>', -7, 0);
		$phone_formatted .= '</span>';
	}

	return $phone_formatted;
}



/* // */
// Gallery modernizer
/* // */
function gallery_modernizer() {
	global $post;
	if (!is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active( 'elementor/elementor.php' ) && !(Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID))) {
		add_filter('post_gallery', 'customFormatGallery', 10, 3);
		function customFormatGallery($string, $attr, $instance){
			$columns = (isset($attr['columns'])) ? $attr['columns'] : 3;
			$image_ids = explode(',', $attr['ids']);
			$link = array_key_exists('link', $attr) ? $attr['link'] : '';
			$attr['size'] = array_key_exists('size', $attr) ? $attr['size'] : 'large';
			$output = '';

			switch ($columns) {
				case 1: $col_full = 1; $col_hdf = 1; $col_btb = 1; $col_stb = 1; $col_mbl = 1; break;
				case 2: $col_full = 2; $col_hdf = 2; $col_btb = 2; $col_stb = 1; $col_mbl = 1; break;
				case 3: $col_full = 3; $col_hdf = 3; $col_btb = 2; $col_stb = 1; $col_mbl = 1; break;
				case 4: $col_full = 4; $col_hdf = 3; $col_btb = 3; $col_stb = 2; $col_mbl = 1; break;
				case 5: $col_full = 5; $col_hdf = 4; $col_btb = 3; $col_stb = 2; $col_mbl = 1; break;
				case 6: $col_full = 6; $col_hdf = 5; $col_btb = 4; $col_stb = 3; $col_mbl = 2; break;
				case 7: $col_full = 7; $col_hdf = 6; $col_btb = 5; $col_stb = 3; $col_mbl = 2; break;
				case 8: $output .= '<p>8 колонок не поддерживаются</p>'; $col_full = 4; $col_hdf = 3; $col_btb = 1; $col_stb = 1; $col_mbl = 1; break;
				case 9: $output .= '<p>9 колонок не поддерживаются</p>'; $col_full = 4; $col_hdf = 3; $col_btb = 1; $col_stb = 1; $col_mbl = 1; break;
				default: $col_full = 4; $col_hdf = 3; $col_btb = 1; $col_stb = 1; $col_mbl = 1; break;
			}

			$output .= '<div class="gallery gallery-' . $instance . ' flex-gl gap30t cols' . $col_full . ' hdf-cols' . $col_hdf . ' btb-cols' . $col_btb . ' stb-cols' . $col_stb . ' mbl-cols' . $col_mbl. ' mt15">';

			foreach($image_ids as $image_id) {
				switch ($link) {
					case 'file':
						$output .= '<div class="gallery-slide">';
						$output .= '<a class="img-ar img-ar--4-3" href="' . wp_get_attachment_image_url($image_id, 'full') . '">';
						$output .= '<img src="' . wp_get_attachment_image_url($image_id, $attr['size']) . '">';
						$output .= '</a>';
						$output .= '</div>';
						break;

					case 'none':
						$output .= '<img src="' . wp_get_attachment_image_url($image_id, $attr['size']) . '">';
						break;
					
					default:
						// lightbox
						$output .= '<div class="gallery-slide">';
						$output .= '<a class="swipebox img-ar img-ar--4-3" rel="gal-' . $instance . '" href="' . wp_get_attachment_image_url($image_id, 'full') . '">';
						$output .= '<img src="' . wp_get_attachment_image_url($image_id, $attr['size']) . '">';
						$output .= '</a>';
						$output .= '</div>';
						break;
				}
				
			}

			$output .= '</div>';

			return $output;
		}
	}
}
add_filter('wp_head', 'gallery_modernizer');



/**
 * Get Image from post
 */
function get_image($post_id, $size) {

	if (!$post_id) return 'image error: ID поста не передан';

	if (!in_array($size, array('thumbnail', 'medium', 'large', 'full'))) {
		$size = 'large';
	}

	$image_url = get_the_post_thumbnail_url($post_id, $size);

	if (!$image_url) {
		$image_url = '/wp-content/themes/custom/img/no-image.png';
	}

	return $image_url;

}



// Wrap every table in a div
function table_wrap($content) {
  return preg_replace_callback('~<table.*?</table>~is', function($match) {
    return '<div class="table-wrapper">' . $match[0] . '</div>';
  }, $content);
}
add_filter('the_content', 'table_wrap');



/**
 * Make ACF uploaded image smaller in admin
 */
add_action('admin_head', 'my_custom_fonts');
function my_custom_fonts() { ?>
  <style>
		.acf-image-uploader .image-wrap img { width: 300px !important; }
  </style> <?php
}



/**
 * Allow uploading of SVG images
 */
function wp_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'wp_mime_types');

function wp_fix_svg() {
	echo '<style type="text/css">
	.attachment-266x266, .thumbnail img {
		width: 100% !important;
		height: auto !important;
	}
	</style>';
}
add_action( 'admin_head', 'wp_fix_svg' );

function my_wp_check_filetype($data, $file, $filename, $mimes) {
	global $wp_version;
	if( $wp_version !== '4.7.1' ){
		return $data;
	}

	$filetype = wp_check_filetype( $filename, $mimes );

	return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename']
	];
}
add_filter( 'wp_check_filetype_and_ext', 'my_wp_check_filetype', 10, 4 );



/**
 * Add default the_content filter, so elementor won't load everywhere
 */
add_filter( 'se152488_the_content', 'wptexturize'        );
add_filter( 'se152488_the_content', 'convert_smilies'    );
add_filter( 'se152488_the_content', 'convert_chars'      );
add_filter( 'se152488_the_content', 'wpautop'            );
add_filter( 'se152488_the_content', 'shortcode_unautop'  );
add_filter( 'se152488_the_content', 'prepend_attachment' );



/* // */
// ADD CONTACTS PAGE
/* // */
add_action('admin_menu', function() {
	add_menu_page('Контакты на сайте', 'Контакты на сайте', 'manage_options', 'site-contacts', 'site_contacts_setting');
});

function site_contacts_setting() {
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields("contacts_group");
				do_settings_sections("contacts_page");
				submit_button();
			?>
		</form>
	</div>
	<?php

}

add_action('admin_init', 'plugin_settings');
function plugin_settings() {

	// main settings
	$settings_main = array(
		'phone1' => array('Телефон', 'text'),
		'phone2' => array('Телефон 2', 'text'),
		'address' => array('Адрес', 'text'),
		'email' => array('E-mail', 'text'),
	);
	$settings_social = array(
		'vkontakte' => array('Вконтакте', 'text'),
		'odnoklassniki' => array('Одноклассники', 'text'),
		'instagram' => array('Instagram', 'text'),
		'whatsapp' => array('Whatsapp', 'text'),
		'telegram' => array('Telegram', 'text'),
		'youtube' => array('Youtube', 'text'),
		// 'twitter' => array('Twitter', 'text'),
		// 'facebook' => array('Facebook', 'text'),
	);

	// параметры: $option_group, $option_name, $sanitize_callback
	foreach ($settings_main as $slug => $data) {
		register_setting('contacts_group', $slug);
	}
	foreach ($settings_social as $slug => $data) {
		register_setting('contacts_group', $slug);
	}

	// параметры: $id, $title, $callback, $page
	add_settings_section( 'contacts', 'Контакты', '', 'contacts_page');
	add_settings_section( 'social-links', 'Социальные сети', '', 'contacts_page');

	// параметры: $id, $title, $callback, $page, $section, $args
	foreach ($settings_main as $slug => $data) {
		add_settings_field($slug, $data[0], 'output_'.$data[1].'_option', 'contacts_page', 'contacts', array($slug));
	}
	foreach ($settings_social as $slug => $data) {
		add_settings_field($slug, $data[0], 'output_'.$data[1].'_option', 'contacts_page', 'social-links', array($slug));
	}

}

## Заполняем текстовую опцию
function output_text_option($args){
	$val = get_option($args[0]);
	?>
	<style>input[type=text] { width: 100%; }</style>
	<input type="text" name="<?=$args[0]?>" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}



/* // */
// KAMA Breadcrumbs
/* // */
/**
 * Хлебные крошки для WordPress (breadcrumbs)
 *
 * @param  string [$sep  = '']      Разделитель. По умолчанию ' » '
 * @param  array  [$l10n = array()] Для локализации. См. переменную $default_l10n.
 * @param  array  [$args = array()] Опции. См. переменную $def_args
 * @return string Выводит на экран HTML код
 *
 * version 3.3.2
 */
function kama_breadcrumbs( $sep = ' » ', $l10n = array(), $args = array() ){
	$kb = new Kama_Breadcrumbs;
	echo $kb->get_crumbs( $sep, $l10n, $args );
}

class Kama_Breadcrumbs {

	public $arg;

	// Локализация
	static $l10n = array(
		'home'       => 'Главная',
		'paged'      => 'Страница %d',
		'_404'       => 'Ошибка 404',
		'search'     => 'Результаты поиска по запросу - <b>%s</b>',
		'author'     => 'Архив автора: <b>%s</b>',
		'year'       => 'Архив за <b>%d</b> год',
		'month'      => 'Архив за: <b>%s</b>',
		'day'        => '',
		'attachment' => 'Медиа: %s',
		'tag'        => 'Записи по метке: <b>%s</b>',
		'tax_tag'    => '%1$s из "%2$s" по тегу: <b>%3$s</b>',
		// tax_tag выведет: 'тип_записи из "название_таксы" по тегу: имя_термина'.
		// Если нужны отдельные холдеры, например только имя термина, пишем так: 'записи по тегу: %3$s'
	);

	// Параметры по умолчанию
	static $args = array(
		'on_front_page'   => true,  // выводить крошки на главной странице
		'show_post_title' => true,  // показывать ли название записи в конце (последний элемент). Для записей, страниц, вложений
		'show_term_title' => true,  // показывать ли название элемента таксономии в конце (последний элемент). Для меток, рубрик и других такс
		'title_patt'      => '<span class="kb_title">%s</span>', // шаблон для последнего заголовка. Если включено: show_post_title или show_term_title
		'last_sep'        => true,  // показывать последний разделитель, когда заголовок в конце не отображается
		'markup'          => 'schema.org', // 'markup' - микроразметка. Может быть: 'rdf.data-vocabulary.org', 'schema.org', '' - без микроразметки
										   // или можно указать свой массив разметки:
										   // array( 'wrappatt'=>'<div class="kama_breadcrumbs">%s</div>', 'linkpatt'=>'<a href="%s">%s</a>', 'sep_after'=>'', )
		'priority_tax'    => array('category'), // приоритетные таксономии, нужно когда запись в нескольких таксах
		'priority_terms'  => array(), // 'priority_terms' - приоритетные элементы таксономий, когда запись находится в нескольких элементах одной таксы одновременно.
									  // Например: array( 'category'=>array(45,'term_name'), 'tax_name'=>array(1,2,'name') )
									  // 'category' - такса для которой указываются приор. элементы: 45 - ID термина и 'term_name' - ярлык.
									  // порядок 45 и 'term_name' имеет значение: чем раньше тем важнее. Все указанные термины важнее неуказанных...
		'nofollow' => false, // добавлять rel=nofollow к ссылкам?

		// служебные
		'sep'             => '',
		'linkpatt'        => '',
		'pg_end'          => '',
	);

	function get_crumbs( $sep, $l10n, $args ){
		global $post, $wp_query, $wp_post_types;

		self::$args['sep'] = $sep;

		// Фильтрует дефолты и сливает
		$loc = (object) array_merge( apply_filters('kama_breadcrumbs_default_loc', self::$l10n ), $l10n );
		$arg = (object) array_merge( apply_filters('kama_breadcrumbs_default_args', self::$args ), $args );

		$arg->sep = '<span class="kb_sep">'. $arg->sep .'</span>'; // дополним

		// упростим
		$sep = & $arg->sep;
		$this->arg = & $arg;

		// микроразметка ---
		if(1){
			$mark = & $arg->markup;

			// Разметка по умолчанию
			if( ! $mark ) $mark = array(
				'wrappatt'  => '<div class="kama_breadcrumbs">%s</div>',
				'linkpatt'  => '<a href="%s">%s</a>',
				'sep_after' => '',
			);
			// rdf
			elseif( $mark === 'rdf.data-vocabulary.org' ) $mark = array(
				'wrappatt'   => '<div class="kama_breadcrumbs" prefix="v: http://rdf.data-vocabulary.org/#">%s</div>',
				'linkpatt'   => '<span typeof="v:Breadcrumb"><a href="%s" rel="v:url" property="v:title">%s</a>',
				'sep_after'  => '</span>', // закрываем span после разделителя!
			);
			// schema.org
			elseif( $mark === 'schema.org' ) $mark = array(
				'wrappatt'   => '<div class="kama_breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">%s</div>',
				'linkpatt'   => '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="%s" itemprop="item"><span itemprop="name">%s</span></a></span>',
				'sep_after'  => '',
			);

			elseif( ! is_array($mark) )
				die( __CLASS__ .': "markup" parameter must be array...');

			$wrappatt  = $mark['wrappatt'];
			$arg->linkpatt  = $arg->nofollow ? str_replace('<a ','<a rel="nofollow"', $mark['linkpatt']) : $mark['linkpatt'];
			$arg->sep      .= $mark['sep_after']."\n";
		}

		$linkpatt = $arg->linkpatt; // упростим

		$q_obj = get_queried_object();

		// может это архив пустой таксы?
		$ptype = null;
		if( empty($post) ){
			if( isset($q_obj->taxonomy) )
				$ptype = & $wp_post_types[ get_taxonomy($q_obj->taxonomy)->object_type[0] ];
		}
		else $ptype = & $wp_post_types[ $post->post_type ];

		// paged
		$arg->pg_end = '';
		if( ($paged_num = get_query_var('paged')) || ($paged_num = get_query_var('page')) )
			$arg->pg_end = $sep . sprintf( $loc->paged, (int) $paged_num );

		$pg_end = $arg->pg_end; // упростим

		$out = '';

		if( is_front_page() ){
			return $arg->on_front_page ? sprintf( $wrappatt, ( $paged_num ? sprintf($linkpatt, get_home_url(), $loc->home) . $pg_end : $loc->home ) ) : '';
		}
		// страница записей, когда для главной установлена отдельная страница.
		elseif( is_home() ) {
			$out = $paged_num ? ( sprintf( $linkpatt, get_permalink($q_obj), esc_html($q_obj->post_title) ) . $pg_end ) : esc_html($q_obj->post_title);
		}
		elseif( is_404() ){
			$out = $loc->_404;
		}
		elseif( is_search() ){
			$out = sprintf( $loc->search, esc_html( $GLOBALS['s'] ) );
		}
		elseif( is_author() ){
			$tit = sprintf( $loc->author, esc_html($q_obj->display_name) );
			$out = ( $paged_num ? sprintf( $linkpatt, get_author_posts_url( $q_obj->ID, $q_obj->user_nicename ) . $pg_end, $tit ) : $tit );
		}
		elseif( is_year() || is_month() || is_day() ){
			$y_url  = get_year_link( $year = get_the_time('Y') );

			if( is_year() ){
				$tit = sprintf( $loc->year, $year );
				$out = ( $paged_num ? sprintf($linkpatt, $y_url, $tit) . $pg_end : $tit );
			}
			// month day
			else {
				$y_link = sprintf( $linkpatt, $y_url, $year);
				$m_url  = get_month_link( $year, get_the_time('m') );

				if( is_month() ){
					$tit = sprintf( $loc->month, get_the_time('F') );
					$out = $y_link . $sep . ( $paged_num ? sprintf( $linkpatt, $m_url, $tit ) . $pg_end : $tit );
				}
				elseif( is_day() ){
					$m_link = sprintf( $linkpatt, $m_url, get_the_time('F'));
					$out = $y_link . $sep . $m_link . $sep . get_the_time('l');
				}
			}
		}
		// Древовидные записи
		elseif( is_singular() && $ptype->hierarchical ){
			$out = $this->_add_title( $this->_page_crumbs($post), $post );
		}
		// Таксы, плоские записи и вложения
		else {
			$term = $q_obj; // таксономии

			// определяем термин для записей (включая вложения attachments)
			if( is_singular() ){
				// изменим $post, чтобы определить термин родителя вложения
				if( is_attachment() && $post->post_parent ){
					$save_post = $post; // сохраним
					$post = get_post($post->post_parent);
				}

				// учитывает если вложения прикрепляются к таксам древовидным - все бывает :)
				$taxonomies = get_object_taxonomies( $post->post_type );
				// оставим только древовидные и публичные, мало ли...
				$taxonomies = array_intersect( $taxonomies, get_taxonomies( array('hierarchical' => true, 'public' => true) ) );

				if( $taxonomies ){
					// сортируем по приоритету
					if( ! empty($arg->priority_tax) ){
						usort( $taxonomies, function($a,$b)use($arg){
							$a_index = array_search($a, $arg->priority_tax);
							if( $a_index === false ) $a_index = 9999999;

							$b_index = array_search($b, $arg->priority_tax);
							if( $b_index === false ) $b_index = 9999999;

							return ( $b_index === $a_index ) ? 0 : ( $b_index < $a_index ? 1 : -1 ); // меньше индекс - выше
						} );
					}

					// пробуем получить термины, в порядке приоритета такс
					foreach( $taxonomies as $taxname ){
						if( $terms = get_the_terms( $post->ID, $taxname ) ){
							// проверим приоритетные термины для таксы
							$prior_terms = & $arg->priority_terms[ $taxname ];
							if( $prior_terms && count($terms) > 2 ){
								foreach( (array) $prior_terms as $term_id ){
									$filter_field = is_numeric($term_id) ? 'term_id' : 'slug';
									$_terms = wp_list_filter( $terms, array($filter_field=>$term_id) );

									if( $_terms ){
										$term = array_shift( $_terms );
										break;
									}
								}
							}
							else
								$term = array_shift( $terms );

							break;
						}
					}
				}

				if( isset($save_post) ) $post = $save_post; // вернем обратно (для вложений)
			}

			// вывод

			// все виды записей с терминами или термины
			if( $term && isset($term->term_id) ){
				$term = apply_filters('kama_breadcrumbs_term', $term );

				// attachment
				if( is_attachment() ){
					if( ! $post->post_parent )
						$out = sprintf( $loc->attachment, esc_html($post->post_title) );
					else {
						if( ! $out = apply_filters('attachment_tax_crumbs', '', $term, $this ) ){
							$_crumbs    = $this->_tax_crumbs( $term, 'self' );
							$parent_tit = sprintf( $linkpatt, get_permalink($post->post_parent), get_the_title($post->post_parent) );
							$_out = implode( $sep, array($_crumbs, $parent_tit) );
							$out = $this->_add_title( $_out, $post );
						}
					}
				}
				// single
				elseif( is_single() ){
					if( ! $out = apply_filters('post_tax_crumbs', '', $term, $this ) ){
						$_crumbs = $this->_tax_crumbs( $term, 'self' );
						$out = $this->_add_title( $_crumbs, $post );
					}
				}
				// не древовидная такса (метки)
				elseif( ! is_taxonomy_hierarchical($term->taxonomy) ){
					// метка
					if( is_tag() )
						$out = $this->_add_title('', $term, sprintf( $loc->tag, esc_html($term->name) ) );
					// такса
					elseif( is_tax() ){
						$post_label = $ptype->labels->name;
						$tax_label = $GLOBALS['wp_taxonomies'][ $term->taxonomy ]->labels->name;
						$out = $this->_add_title('', $term, sprintf( $loc->tax_tag, $post_label, $tax_label, esc_html($term->name) ) );
					}
				}
				// древовидная такса (рибрики)
				else {
					if( ! $out = apply_filters('term_tax_crumbs', '', $term, $this ) ){
						$_crumbs = $this->_tax_crumbs( $term, 'parent' );
						$out = $this->_add_title( $_crumbs, $term, esc_html($term->name) );
					}
				}
			}
			// влоежния от записи без терминов
			elseif( is_attachment() ){
				$parent = get_post($post->post_parent);
				$parent_link = sprintf( $linkpatt, get_permalink($parent), esc_html($parent->post_title) );
				$_out = $parent_link;

				// вложение от записи древовидного типа записи
				if( is_post_type_hierarchical($parent->post_type) ){
					$parent_crumbs = $this->_page_crumbs($parent);
					$_out = implode( $sep, array( $parent_crumbs, $parent_link ) );
				}

				$out = $this->_add_title( $_out, $post );
			}
			// записи без терминов
			elseif( is_singular() ){
				$out = $this->_add_title( '', $post );
			}
		}

		// замена ссылки на архивную страницу для типа записи
		$home_after = apply_filters('kama_breadcrumbs_home_after', '', $linkpatt, $sep, $ptype );

		if( '' === $home_after ){
			// Ссылка на архивную страницу типа записи для: отдельных страниц этого типа; архивов этого типа; таксономий связанных с этим типом.
			if( $ptype && $ptype->has_archive && ! in_array( $ptype->name, array('post','page','attachment') )
				&& ( is_post_type_archive() || is_singular() || (is_tax() && in_array($term->taxonomy, $ptype->taxonomies)) )
			){
				$pt_title = $ptype->labels->name;

				// первая страница архива типа записи
				if( is_post_type_archive() && ! $paged_num )
					$home_after = sprintf( $this->arg->title_patt, $pt_title );
				// singular, paged post_type_archive, tax
				else{
					$home_after = sprintf( $linkpatt, get_post_type_archive_link($ptype->name), $pt_title );

					$home_after .= ( ($paged_num && ! is_tax()) ? $pg_end : $sep ); // пагинация
				}
			}
		}

		$before_out = sprintf( $linkpatt, home_url(), $loc->home ) . ( $home_after ? $sep.$home_after : ($out ? $sep : '') );

		$out = apply_filters('kama_breadcrumbs_pre_out', $out, $sep, $loc, $arg );

		$out = sprintf( $wrappatt, $before_out . $out );

		return apply_filters('kama_breadcrumbs', $out, $sep, $loc, $arg );
	}

	function _page_crumbs( $post ){
		$parent = $post->post_parent;

		$crumbs = array();
		while( $parent ){
			$page = get_post( $parent );
			$crumbs[] = sprintf( $this->arg->linkpatt, get_permalink($page), esc_html($page->post_title) );
			$parent = $page->post_parent;
		}

		return implode( $this->arg->sep, array_reverse($crumbs) );
	}

	function _tax_crumbs( $term, $start_from = 'self' ){
		$termlinks = array();
		$term_id = ($start_from === 'parent') ? $term->parent : $term->term_id;
		while( $term_id ){
			$term       = get_term( $term_id, $term->taxonomy );
			$termlinks[] = sprintf( $this->arg->linkpatt, get_term_link($term), esc_html($term->name) );
			$term_id    = $term->parent;
		}

		if( $termlinks )
			return implode( $this->arg->sep, array_reverse($termlinks) ) /*. $this->arg->sep*/;
		return '';
	}

	// добалвяет заголовок к переданному тексту, с учетом всех опций. Добавляет разделитель в начало, если надо.
	function _add_title( $add_to, $obj, $term_title = '' ){
		$arg = & $this->arg; // упростим...
		$title = $term_title ? $term_title : esc_html($obj->post_title); // $term_title чиститься отдельно, теги моугт быть...
		$show_title = $term_title ? $arg->show_term_title : $arg->show_post_title;

		// пагинация
		if( $arg->pg_end ){
			$link = $term_title ? get_term_link($obj) : get_permalink($obj);
			$add_to .= ($add_to ? $arg->sep : '') . sprintf( $arg->linkpatt, $link, $title ) . $arg->pg_end;
		}
		// дополняем - ставим sep
		elseif( $add_to ){
			if( $show_title )
				$add_to .= $arg->sep . sprintf( $arg->title_patt, $title );
			elseif( $arg->last_sep )
				$add_to .= $arg->sep;
		}
		// sep будет потом...
		elseif( $show_title )
			$add_to = sprintf( $arg->title_patt, $title );

		return $add_to;
	}

}