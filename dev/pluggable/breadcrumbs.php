<?php
/**
 * Breadcrumbs
 *
 * @package wprig
 */

/**
 * Main function. Create breadcrumbs.
 */
function the_breadcrumb() {

	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
	} else {
		$sep = __( ' > ', 'wprig' );

		if ( ! is_front_page() ) {
			// Start the breadcrumb with a link to your homepage.
			echo '<div class="breadcrumbs">';
			echo '<a href="';
			echo esc_url( get_option( 'home' ) );
			echo '">';
			bloginfo( 'name' );
			echo '</a> Test' . esc_attr( $sep );

			// Check if the current page is a category, an archive or a single page. If so show the category or archive name.
			if ( is_category() || is_single() ) {
				the_category( ', ' );
			} elseif ( is_archive() || is_single() ) {
				if ( is_day() ) {
					printf( esc_attr( '%s', 'wprig' ), get_the_date() );
				} elseif ( is_month() ) {
					printf( esc_attr( '%s', 'wprig' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'wprig' ) ) );
				} elseif ( is_year() ) {
					printf( esc_attr( '%s', 'wprig' ), get_the_date( _x( 'Y', 'yearly archives date format', 'wprig' ) ) );
				} else {
					esc_html_e( 'Blog Archives', 'wprig' );
				}
			}

			// If the current page is a single post, show its title with the separator.
			if ( is_single() ) {
				echo esc_attr( $sep );
				the_title();
			}

			// If the current page is a static page, show its title.
			if ( is_page() ) {
				echo the_title();
			}

			// if you have a static page assigned to be you posts list page. It will find the title of the static page and display it. i.e Home >> Blog.
			if ( is_home() ) {
				$page_for_posts_id = get_option( 'page_for_posts' );
				if ( $page_for_posts_id ) {
					$post = get_page( $page_for_posts_id );
					setup_postdata( $post );
					the_title();
					rewind_posts();
				}
			}
			echo '</div>';
		}
	}
}
