<?php
/**
 * Load more posts.
 *
 * @package wprig
 */

/**
 * Main function. Runs everything.
 */
function wprig_loadmore_posts() {

	// If this is the admin page, do nothing.
	if ( is_admin() ) {
		return;
	}
	add_action( 'wp_enqueue_scripts', 'wprig_enqueue_loadmore_assets' );
}
add_action( 'wp', 'wprig_loadmore_posts' );

/**
 * Enqueue and defer lazyload script.
 */
function wprig_enqueue_loadmore_assets() {
	wp_enqueue_script( 'wprig-load-more-posts', get_theme_file_uri( '/pluggable/loadmore/js/loadmore.js' ), array('jquery'), '20181225', false );
	wp_script_add_data( 'wprig-load-more-posts', 'defer', true );

	wp_localize_script( 'wprig-load-more-posts', 'loadmoreBtnParams', array(
		'ajax_url'      => admin_url( 'admin-ajax.php' ),
		'home_url'      => home_url( '/' ),
		'current_page'  => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
	) );
}

/**
 * Ajax handler
 */
function wprig_loadmore_ajax_handler() {
	// prepare our arguments for the query.

	if ( isset( $_POST['page'] ) ) {
		$page = wp_unslash( $_POST['page'] ) + 1;
	} else {
		$page = 2;
	}
	// it is always better to use WP_Query but not here.
	$args = array(
		'post_type' => 'post',
		'paged'     => $page,
	);

	$the_query = new WP_Query( $args );
	ob_start();

	if ( $the_query->have_posts() ) :
		/* Start the Loop. */
		while ( $the_query->have_posts() ) :
			$the_query->the_post();

			/*
			 * Include the Post-Type-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
			 */
			get_template_part( 'template-parts/content' );

		endwhile;

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	$output['more'] = ob_get_clean();
	wp_send_json( $output );
}
add_action( 'wp_ajax_loadmore', 'wprig_loadmore_ajax_handler' );
add_action( 'wp_ajax_nopriv_loadmore', 'wprig_loadmore_ajax_handler' );
