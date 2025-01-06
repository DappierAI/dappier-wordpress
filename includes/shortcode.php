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
	$map = [
		'widget_id'        => 'widgetId',
		'title'            => 'title',
		'background_color' => 'mainBackgroundColor',
		'text_color'       => 'mainTextColor',
		'theme_color'      => 'themeColor',
		'logo_url'         => 'mainLogoUrl',
		'logo_width'       => 'mainLogoWidth',
		'icon_url'         => 'chatIconUrl',
		'icon_width'       => 'chatIconWidth',
		'suggestions'      => 'enablePromptSuggestions',
		'recommendations'  => 'enableContentRecommendations',
		'attribution'      => 'showAttributionLinks',
		'enable_site_name' => 'enableSiteName',
		'enable_title'     => 'enableTitle',
		'search_query'     => 'initialSearchQuery',
	];

	// Map attribute keys to the actual keys.
	foreach ( $atts as $key => $value ) {
		if ( isset( $map[ $key ] ) ) {
			$atts[ $map[ $key ] ] = $value;
			unset( $atts[ $key ] );
		}
	}

	// Instantiate the class.
	$askai = new Dappier_AskAi( $atts );

	// Return the HTML.
	return $askai->get();
});