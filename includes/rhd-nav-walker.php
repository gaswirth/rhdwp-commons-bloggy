<?php
class Display_Projects_Sub_Menu extends Walker_Nav_Menu {
    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

		// Check if element as a 'current element' class
		$element_markers = array( 'menu-item-423' );
		$current_classes = array_intersect( $current_element_markers, $element->classes );

		// If element is found, print submenu
		if ( !empty($current_class) ) {
			// JS injectable "Projects" CPT post list
			$projects = get_posts( "post_type=project&posts_per_page=-1" );

			if ( $projects ) {

				echo "<ul class=\"sub-menu projects-sub-menu\">\n";

				foreach ( $projects as $project ) {
					setup_postdata( $GLOBALS['post'] =& $project );
					echo '<li class="menu-item menu-item-project sub-menu-item"><a href="' . get_the_permalink() .'">' .get_the_title() . '</a></li>';
				}
				wp_reset_postdata();

				echo "</ul>\n";

			}
		}

		parent::display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output );
    }
}
?>