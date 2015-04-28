<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage rhd
 */
?>


<aside id="secondary" class="widget-area" role="complementary">
	<div class="widget sidebar-menu">
		<?php
			// JS injectable "Projects" CPT post list
			$projects = get_posts( "post_type=project&posts_per_page=-1" );
		?>

		<?php if ( $projects ) : ?>

			<ul id="projects-sub-menu" class="sub-menu projects-sub-menu">

			<?php foreach ( $projects as $project ) : setup_postdata( $GLOBALS['post'] =& $project ); ?>
				<li class="menu-item menu-item-project sub-menu-item"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; ?>

			<?php wp_reset_postdata(); ?>

			</ul>

		<?php endif; ?>

		<?php
			$nav_args = array(
				'menu_location' => 'primary',
				'menu_id' => 'site-navigation-sidebar',
				'container' => 'nav',
				'container_id' => 'site-navigation-sidebar-container'
			);
			wp_nav_menu( $nav_args );
		?>
	</div>

	<?php if ( is_active_sidebar( 'sidebar' ) ) dynamic_sidebar( 'sidebar' ); ?>
</aside><!-- #secondary -->