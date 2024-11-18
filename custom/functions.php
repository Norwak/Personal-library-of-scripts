<?
/* // */
// Add Supports
/* // */
add_theme_support('custom-logo');
add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('widgets');
add_theme_support('html5', array('search-form'));
add_theme_support('woocommerce');
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');
add_editor_style('css/editor-styles.css');



/**
 * Enable AJAX
 */
add_action('wp_enqueue_scripts', 'my_enqueue_function');
function my_enqueue_function() {
	wp_enqueue_script('wp-util');
}



require_once(get_stylesheet_directory() . '/inc/template-functions.php');


/* // */
// Enqueue styles and scripts
/* // */
function my_enqueuer($my_handle, $relpath, $type='script', $my_deps=array()) {
	if ($type === 'font') {
		wp_enqueue_style($my_handle, $relpath, $my_deps, false);
		return;
	}

	$uri = get_theme_file_uri($relpath);
	$vsn = filemtime(get_theme_file_path($relpath));
	if($type === 'script') wp_enqueue_script($my_handle, $uri, $my_deps, $vsn, true);
	else if($type === 'style') wp_enqueue_style($my_handle, $uri, $my_deps, $vsn);
}

add_action('wp_head', 'my_theme_assets', 1);
function my_theme_assets() {
	my_enqueuer('inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap', 'font');
	my_enqueuer('fontawesome', '/fonts/fontawesome-6.3.0/css/all.min.css', 'style');
	my_enqueuer('custom-theme', '/css/styles.css', 'style');
	my_enqueuer('custom-theme-hotfix', '/css/styles-after-deployment.css', 'style');

	my_enqueuer('swiper', '/js/vendor/swiper-bundle.min.js', 'script');
	my_enqueuer('swipebox', '/js/vendor/jquery.swipebox.js', 'script');
	my_enqueuer('masked-input', '/js/vendor/jquery.maskedinput.min.js', 'script');
	my_enqueuer('custom-theme', '/js/scripts.js', 'script');
}


/* // */
// Remove a security hole
/* // */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Remove wordpress's inline gallery styles
 */
add_filter( 'use_default_gallery_style', '__return_false' );


/* // */
// Register Menus
/* // */
add_action( 'after_setup_theme', function(){
	register_nav_menus( [
		'top-menu' => 'Меню в верху страницы',
		'social-menu' => 'Меню с ссылками на соцсети'
	] );
} );


$email = trim(get_option('email'));
$address = trim(get_option('address'));
$phone1 = trim(get_option('phone1'));
$phone2 = trim(get_option('phone2'));
$worktime = trim(get_option('worktime'));

$phone1_raw = get_raw_phone($phone1);
$phone2_raw = get_raw_phone($phone2);

$phone1_formatted = get_formatted_phone($phone1);
$phone2_formatted = get_formatted_phone($phone2);

define('EMAIL', $email);
define('ADDRESS', $address);
define('PHONE1_RAW', $phone1_raw);
define('PHONE1_FORMATTED', $phone1_formatted);
define('PHONE2_RAW', $phone2_raw);
define('PHONE2_FORMATTED', $phone2_formatted);
define('WORKTIME', $worktime);



// Create a new post type
// Okey. Go to this URL (yourdomain.com/wp-admin/options-permalink.php) and in the "common settings" click on "Post name" and click on save changes. if "Post name" is already selected then just for refresh permalinks, click on "plain" option and again select "Post name" and save the settings. Your custom post type links will be work. – Dinesh Aug 16, 2016 at 6:28 
add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type('photos', array(
		'labels' => array(
			'name' => __( 'Фото работ' ),
			'singular_name' => __( 'Фото' )
		),
		'public' => false,
		'publicly_queryable' => true,
		'show_ui' => true,
		'exclude_from_search' => true,
		'show_in_nav_menus' => false,
		'has_archive' => false,
		'hierarchial' => false,
		// 'menu_position' => null,
		// 'menu_icon' => null,
		'supports' => ['title', 'thumbnail', 'page-attributes'],
		'rewrite' => false
	));
}