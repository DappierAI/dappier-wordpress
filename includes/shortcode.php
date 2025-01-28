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
		'widget_id'                          => 'widgetId',
		'title'                              => 'title',
		'search_placeholder_text'            => 'searchPlaceholderText',
		'ask_button_text'                    => 'askButtonText',
		'main_logo_url'                      => 'mainLogoUrl',
		'chat_icon_url'                      => 'chatIconUrl',
		'main_background_color'              => 'mainBackgroundColor',
		'main_text_color'                    => 'mainTextColor',
		'theme_color'                        => 'themeColor',
		'prompt_suggestion_background_color' => 'promptSuggestionBackgroundColor',
		'prompt_suggestion_text_color'       => 'promptSuggestionTextColor',
		'message_background_color'           => 'messageBackgroundColor',
		'message_text_color'                 => 'messageTextColor',
		'title_color'                        => 'titleColor',
		'container_radius'                   => 'containerRadius',
		'element_radius'                     => 'elementRadius',
		'main_logo_width_mobile'             => 'mainLogoWidthMobile',
		'chat_icon_width_mobile'             => 'chatIconWidthMobile',
		'main_logo_width_desktop'            => 'mainLogoWidthDesktop',
		'chat_icon_width_desktop'            => 'chatIconWidthDesktop',
		'font_size_header_mobile'            => 'fontSizeHeaderMobile',
		'font_size_default_mobile'           => 'fontSizeDefaultMobile',
		'font_size_header_desktop'           => 'fontSizeHeaderDesktop',
		'font_size_default_desktop'          => 'fontSizeDefaultDesktop',
		'fixed_height'                       => 'fixedHeight',
		'max_height'                         => 'maxHeight',
		'enable_title'                       => 'enableTitle',
		'enable_prompt_suggestions'          => 'enablePromptSuggestions',
		'enable_content_recommendations'     => 'enableContentRecommendations',
		'enable_site_name'                   => 'enableSiteName',
		'referring_url'                      => 'referringUrl',
		'initial_search_query'               => 'initialSearchQuery',
		'disclaimer_link'                    => 'disclaimerLink',
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