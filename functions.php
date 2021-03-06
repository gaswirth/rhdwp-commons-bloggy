<?php
/**
 * RHD Base
 *
 * ROUNDHOUSE DESIGNS
 *
 * @package WordPress
 * @subpackage rhd
 **/


/* ==========================================================================
   Initialization
   ========================================================================== */

function rhd_init() {
	// Constants
	define( "RHD_THEME_DIR", get_template_directory_uri() );
	define( "RHD_IMG_DIR", get_template_directory_uri() . '/img' );
}
add_action( 'after_setup_theme', 'rhd_init' );

/* Disable Editor */
define( 'DISALLOW_FILE_EDIT', true );

/* Theme Options */
$theme_opts = get_option( 'rhd_theme_settings' );


/* ==========================================================================
   Scripts + Styles
   ========================================================================== */

function rhd_enqueue_styles(){
	global $theme_opts;

	wp_register_style( 'rhd-sitewide', content_url() . '/global/sitewide.css', array(), '1', 'all' );
	wp_register_style( 'rhd-main', RHD_THEME_DIR . '/css/main.css', array(), '1', 'all' );
	wp_register_style( 'rhd-enhanced', RHD_THEME_DIR . '/css/enhanced.css', array(), '1', 'all' );
	wp_register_style( 'Slidebars', RHD_THEME_DIR . '/js/vendor/Slidebars/dist/slidebars.min.css', array(), null, 'all' );

	$normalize_deps = array();
	$normalize_deps[] = 'Slidebars';

	if ( !rhd_is_mobile() ) {
		wp_enqueue_style( 'rhd-enhanced' );
	}

	wp_register_style( 'normalize', RHD_THEME_DIR . '/css/normalize.css', $normalize_deps, null, 'all' );

	wp_enqueue_style( 'rhd-main' );
	wp_enqueue_style( 'rhd-sitewide' );
	wp_enqueue_style( 'normalize' );
	wp_enqueue_style( 'google-fonts' );
}
add_action( 'wp_enqueue_scripts', 'rhd_enqueue_styles' );

function rhd_enqueue_scripts() {
	global $theme_opts;

	wp_register_script( 'modernizr', RHD_THEME_DIR . '/js/vendor/modernizr/modernizr-custom.js', null, '2.8.3', true );
	wp_register_script( 'rhd-plugins', RHD_THEME_DIR . '/js/plugins.js', array( 'jquery' ), null, true );
	wp_register_script( 'Slidebars', RHD_THEME_DIR . '/js/vendor/Slidebars/dist/slidebars.min.js', array( 'jquery' ), '0.10.3', true );
	wp_register_script( 'packery', RHD_THEME_DIR . '/js/vendor/packery/dist/packery.pkgd.min.js', array( 'jquery', 'imagesloaded' ), null, true );
	wp_register_script( 'imagesloaded', RHD_THEME_DIR . '/js/vendor/imagesloaded/imagesloaded.pkgd.min.js', array( 'jquery' ), null, true );

	$main_deps = array( 'rhd-plugins', 'jquery' );

	if ( $theme_opts['rhd_include_slidebars'] == '1' ) {
		wp_enqueue_script( 'Slidebars' );
		$main_deps[] = 'Slidebars';
	}

	if ( $theme_opts['rhd_include_packery']) {
		wp_enqueue_script( 'packery' );
		$main_deps[] = 'packery';
	}

	wp_register_script( 'rhd-main', RHD_THEME_DIR . '/js/main.js', $main_deps, null, true );

	wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'rhd-plugins' );
	wp_enqueue_script( 'rhd-main' );

	if ( is_singular() )
		wp_enqueue_script( 'comment-reply' );


			// Localize data for client-side use
	global $wp_query;
	$data = array(
		'inc_slidebars' => ( $theme_opts['rhd_include_slidebars'] == '1' ) ? true : false
	);
	wp_localize_script( 'rhd-main', 'wp_data', $data );
}
add_action('wp_enqueue_scripts', 'rhd_enqueue_scripts');


/* ==========================================================================
   Sidebars + Menus
   ========================================================================== */

// Sidebars
function rhd_register_sidebars() {
	register_sidebar(array(
		'name'			=> __( 'Sidebar', 'rhd' ),
		'id'			=> 'sidebar',
		'description'	=> 'Menu hard-coded. Additional widgets will show below nav menu.',
		'before_title'	=> '<h2 class="widget-title">',
		'after_title'	=> '</h2>',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>'
	));

	register_sidebar(array(
		'name'			=> __( 'Footer Widget Area', 'rhd' ),
		'id'			=> 'footer',
		'before_title'	=> '<h2 class="widget-title">',
		'after_title'	=> '</h2>',
		'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</div>'
	));
}
add_action( 'widgets_init', 'rhd_register_sidebars' );

// Menus
register_nav_menu( 'primary', 'Main Site Navigation' );

// Includes and Requires
include_once( 'includes/rhd-admin-panel.php' );


/* ==========================================================================
   Registrations, Theme Support, Thumbnails
   ========================================================================== */

// Theme Support
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
}

// Enable themes auto-update
add_filter( 'allow_minor_auto_core_updates', '__return_true' );

// Content Width
if ( ! isset( $content_width ) ) {
	$content_width = 620;
}

// Adds RSS feed links to for posts and comments.
add_theme_support( 'automatic-feed-links' );

/*
function rhd_image_sizes(){
	add_image_size( 'square', 200, 200 );
}
add_action( 'after_setup_theme', 'rhd_image_sizes' );
*/


/* ==========================================================================
   Roundhouse Admin
   ========================================================================== */

// Remove 'Editor' panel
function rhd_remove_editor_menu() {
  remove_action('admin_menu', '_add_themes_utility_last', 101);
}
add_action('_admin_menu', 'rhd_remove_editor_menu', 1);


/* ==========================================================================
   Helpers
   ========================================================================== */

/**
 * rhd_is_mobile function.
 *
 * @access public
 * @return void
 */
function rhd_is_mobile() {
	$mobile_browser = 0;

	if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
	    $mobile_browser++;
	}

	if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
	    $mobile_browser++;
	}

	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
	    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
	    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
	    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
	    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
	    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
	    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
	    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
	    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
	    'wapr','webc','winw','winw','xda ','xda-');

	if (in_array($mobile_ua,$mobile_agents)) {
	    $mobile_browser++;
	}

	if ( array_key_exists( 'ALL_HTTP', $_SERVER ) ) {
		if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
		    $mobile_browser++;
		}
	}

	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
	    $mobile_browser = 0;
	}

	if ( $mobile_browser > 0 ) {
		$mobile_browser = TRUE;
	} else {
		$mobile_browser = FALSE;
	}

	return $mobile_browser;
}


/**
 * get_the_slug function.
 *
 * @access public
 * @return void
 */
function get_the_slug() {
	$post_data = get_post( $post->ID, ARRAY_A );
	$slug = $post_data['post_name'];
	return $slug;
}


/**
 * Function: rhd_strip_thumbnail_dimensions
 *
 * Strip WP inline image dimensions
 *
 * @param $html
 **/
add_filter( 'post_thumbnail_html', 'rhd_strip_thumbnail_dimensions', 10 );
add_filter( 'image_send_to_editor', 'rhd_strip_thumbnail_dimensions', 10 );

function rhd_strip_thumbnail_dimensions($html) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}


/**
 * rhd_add_async function.
 *
 * @access public
 * @param mixed $url
 * @return void
 */
function rhd_add_async( $url ) {
	if ( strpos( $url, '#async') === false )
		return $url;
	elseif ( is_admin() )
		return str_replace( '#async', '', $url );
	else
		return str_replace( '#async', '', $url ) . "' async";
}
add_filter( 'clean_url', 'rhd_add_async', 11, 1 );


/**
 * rhd_gallery_atts function.
 *
 * @access public
 * @param mixed $out
 * @param mixed $pairs
 * @param mixed $atts
 * @return void
 */
function rhd_gallery_atts( $out, $pairs, $atts ) {
	$atts = shortcode_atts(
		array(
			'link' => 'file'
		), $atts );

	$out['link'] = $atts['link'];

/*
	Other example defaults:
	$out['columns'] = $atts['columns'];
	$out['size'] = $atts['size'];
*/

	return $out;
}
add_filter( 'shortcode_atts_gallery', 'rhd_gallery_atts', 10, 3 );


/**
 * rhd_enhance_excerpts function.
 *
 * @access public
 * @param mixed $text
 * @return void
 */
function rhd_enhance_excerpts( $text ) {
	global $post;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace('\]\]\>', ']]&gt;', $text);
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
		$text = strip_tags($text, '<a>');
		$excerpt_length = 80;
		$words = explode(' ', $text, $excerpt_length + 1);
		if ( count( $words ) > $excerpt_length) {
			array_pop( $words );
			array_push( $words, '... <a class="readmore" href="'. get_permalink($post->ID) . '">Continue reading &rarr;</a>' );
			$text = implode(' ', $words);
        }
	}
	return $text;
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'rhd_enhance_excerpts');


/**
 * rhd_archive_pagination function.
 *
 * @access public
 * @return void
 */
function rhd_archive_pagination() {
	$sep = ( get_previous_posts_link() != '' ) ? '<div class="pag-sep"></div>' : null; ?>

	<div class="pagination">
		<?php next_posts_link( '&larr; Older', null ); ?>
		<?php if ( $sep ) : ?>
			<div class="pag-sep"></div>
			<?php previous_posts_link( 'Newer &rarr;', null ); ?>
		<?php endif; ?>
	</div> <?php
}