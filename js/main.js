/* ==========================================================================
	Setup
   ========================================================================== */

var $window = jQuery(window),
	$body = jQuery('body'),
	$mast = jQuery('#masthead'),
	$branding = jQuery('#branding'),
	$navCont = jQuery('#site-navigation-container'),
	$nav = jQuery('#site-navigation'),
	$hamburger = jQuery('#hamburger'),
	$content = jQuery('#content'),
	$main = jQuery('#main');

var isSingle = ( $body.hasClass('single') ) ? true : false,
	isGrid = ( $main.hasClass('grid') === true ) ? true : false,
	isPaged = $body.hasClass('paged');

// wp_data object
var homeUrl = wp_data.home_url,
	themeDir = wp_data.theme_dir,
	imgDir = wp_data.img_dir;

var isFrontPage = ( $body.hasClass('front-page') === true ) ? true : false;
var isMobile = ( $body.hasClass('mobile') === true ) ? true : false;
var isTablet = ( $body.hasClass('tablet') === true ) ? true : false;


/* ==========================================================================
	Let 'er rip... (DOM Ready)
   ========================================================================== */

(function($){
	rhdInit();

	if ( wp_data.inc_slidebars > 0 )
		$.slidebars({
			siteClose: false
		});

	if ( wp_data.inc_packery > 0 ) {
		// Packery Initialization + Options
    }

    $(".projects-sub-menu").appendTo("li.menu-item-423");

	$(".menu-item-423 > a").click(function(e){
		e.preventDefault();
		$(this)
			.siblings(".sub-menu")
			.slideToggle();
	});

})(jQuery);


/* ==========================================================================
	Functions
   ========================================================================== */

function rhdInit() {
	wpadminbarPush();
	//rhd_youtube_responsivizer();
}


function wpadminbarPush() {
	jQuery("#wpadminbar").css({
		top: '50px',
	});
}