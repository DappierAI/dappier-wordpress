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
		'location'                           => 'location',
		'widget_id'                          => 'widgetId',
		'title'                              => 'title',
		'search_placeholder_text'            => 'searchPlaceholderText',
		'ask_button_text'                    => 'askButtonText',
		'main_logo_url'                      => 'mainLogoUrl',
		'chat_icon_url'                      => 'chatIconUrl',
		'user_chat_icon_color'               => 'userChatIconColor',
		'ask_button_text_color'              => 'askButtonTextColor',
		'ask_button_background_color'        => 'askButtonBackgroundColor',
		'main_background_color'              => 'mainBackgroundColor',
		'theme_color'                        => 'themeColor',
		'prompt_suggestion_background_color' => 'promptSuggestionBackgroundColor',
		'prompt_suggestion_text_color'       => 'promptSuggestionTextColor',
		'message_background_color'           => 'messageBackgroundColor',
		'message_text_color'                 => 'messageTextColor',
		'search_box_background_color'        => 'searchBoxBackgroundColor',
		'search_box_text_color'              => 'searchBoxTextColor',
		'search_placeholder_text_color'      => 'searchPlaceholderTextColor',
		'title_color'                        => 'titleColor',
		'content_rec_site_name_color'        => 'contentRecSiteNameColor',
		'container_margin_desktop'           => 'containerMarginDesktop',
		'container_margin_mobile'            => 'containerMarginMobile',
		'container_padding_desktop'          => 'containerPaddingDesktop',
		'container_padding_mobile'           => 'containerPaddingMobile',
		'container_radius'                   => 'containerRadius',
		'prompt_suggestion_radius'           => 'promptSuggestionRadius',
		'search_box_radius'                  => 'searchBoxRadius',
		'ask_button_radius'                  => 'askButtonRadius',
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
		'max_height_mobile'                  => 'maxHeightMobile',
		'height_mode_mobile'                 => 'heightModeMobile',
		'enable_title'                       => 'enableTitle',
		'enable_prompt_suggestions'          => 'enablePromptSuggestions',
		'enable_content_recommendations'     => 'enableContentRecommendations',
		'enable_related_content_new_window'  => 'enableRelatedContentNewWindow',
		'enable_site_name'                   => 'enableSiteName',
		'referring_url'                      => 'referringUrl',
		'initial_search_query'               => 'initialSearchQuery',
		'show_initial_search_query'          => 'showInitialSearchQuery',
		'disclaimer_link'                    => 'disclaimerLink',
	];

	// Get the location.
	$location = isset( $atts['location'] ) ? $atts['location'] : 'shortcode';

	// Unset the location.
	unset( $atts['location'] );

	// Map attribute keys to the actual keys.
	foreach ( $atts as $key => $value ) {
		if ( isset( $map[ $key ] ) ) {
			$atts[ $map[ $key ] ] = $value;
			unset( $atts[ $key ] );
		}
	}

	// Instantiate the class.
	$askai = new Dappier_AskAi( $atts );

	// Set the location.
	$askai->set_location( $location );

	// Return the HTML.
	return $askai->render();
});