<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

/**
 * Register a shortcode.
 *
 * @since TBD
 *
 * @return string
 */
add_shortcode( 'dappier_askai', function( $atts ) {
	// Atts.
	$atts = shortcode_atts(
		[
			'param' => '',
		],
		$atts,
		'shortcode_name'
	);

	// Sanitize.
	$atts = [
		'param' => esc_html( $atts['param'] ),
	];


});