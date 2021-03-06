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


var isFrontPage = ( $body.hasClass('front-page') === true ) ? true : false;
var isMobile = ( $body.hasClass('mobile') === true ) ? true : false;
var isTablet = ( $body.hasClass('tablet') === true ) ? true : false;


/* ==========================================================================
	Let 'er rip... (DOM Ready)
   ========================================================================== */

(function($){

	$(document).ready(function(){
		rhdInit();

		$.slidebars({
			siteClose: false
		});

		toggleBurger();
	});

})(jQuery);


/* ==========================================================================
	Functions
   ========================================================================== */

function rhdInit() {
	wpadminbarPush();
}


function wpadminbarPush() {
	jQuery("#wpadminbar").css({
		top: '50px',
	});
}


// Adapted from Hamburger Icons: https://github.com/callmenick/Animating-Hamburger-Icons
function toggleBurger() {
	var toggles = jQuery(".cmn-toggle-switch");

	toggles.click(function(e){
		e.preventDefault();
		jQuery(this).toggleClass('active');
	});
}