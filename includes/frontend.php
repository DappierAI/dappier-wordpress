<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

add_action( 'init', 'dappier_register_scripts' );
/**
 * Register scripts.
 *
 * @since TBD
 *
 * @return void
 */
function dappier_register_scripts() {
	// Bail if not configured.
	if ( ! dappier_is_configured() ) {
		return;
	}

	wp_register_script( 'dappier-loader', 'https://assets.dappier.com/widget/dappier-loader.min.js', [], null, [] );
}

add_action( 'wp_enqueue_scripts', 'dappier_enqueue_scripts' );
/**
 * Enqueue scripts and styles.
 *
 * @since  0.1.0
 */
function dappier_enqueue_scripts() {
	// Bail if not a single post.
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	// Bail not displaying.
	if ( ! ( dappier_is_configured() && in_array( dappier_get_option( 'askai_location' ), [ 'before', 'after' ], true ) ) ) {
		return;
	}

	// Enqueue the script.
	dappier_enqueue_loader();
}

add_filter( 'script_loader_tag', 'dappier_add_script_attributes', 10, 2 );
/**
 * Add attributes to script tag.
 *
 * @since  0.1.0
 *
 * @param  string $tag    The script tag.
 * @param  string $handle The script handle.
 *
 * @return string
 */
function dappier_add_script_attributes( $tag, $handle ) {
	// Bail if not configured.
	if ( ! dappier_is_configured() ) {
		return $tag;
	}

	// Bail if not the Dappier script.
	if ( 'dappier-loader' !== $handle ) {
		return $tag;
	}

	// Get widget ID.
	$widget_id = dappier_get_option( 'widget_id' );

	// Bail if no widget ID.
	if ( ! $widget_id ) {
		return $tag;
	}

	// Set up tag processor.
	$tags = new WP_HTML_Tag_Processor( $tag );

	// Loop through tags.
	while ( $tags->next_tag( [ 'tag_name' => 'script' ] ) ) {
		$tags->set_attribute( 'widget-id', esc_attr( $widget_id ) );
		// $tags->set_attribute( 'env', 'dev' );
	}

	// Get updated tag.
	$tag = $tags->get_updated_html();

	return $tag;
}

add_action( 'the_content', 'dappier_add_askai' );
/**
 * Add Dappier Ask AI askai to the content.
 *
 * @since  0.1.0
 *
 * @param  string $content The content.
 *
 * @return string
 */
function dappier_add_askai( $content ) {
	// Bail if in the Dashboard or not the main query.
	if ( is_admin() || ! is_main_query() ) {
		return $content;
	}

	// Bail if not a single post.
	if ( ! is_singular( 'post' ) ) {
		return $content;
	}

	// Get plugin location.
	$location = dappier_get_option( 'askai_location' );

	// Bail if not a valid location.
	if ( ! in_array( $location, [ 'before', 'after' ], true ) ) {
		return $content;
	}

	// Get askai.
	$askai = new Dappier_AskAi;
	$html   = $askai->get();

	// If before.
	if ( 'before' === $location ) {
		return $html . $content;
	} else {
		return $content . $html;
	}
}
