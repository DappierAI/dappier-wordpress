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
	$askai = new Dappier_AskAi( $atts );

	return $askai->get();
});