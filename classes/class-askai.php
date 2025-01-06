<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

/**
 * Adds settings page.
 */
class Dappier_AskAi {
	protected $args;

	/**
	 * Construct the class.
	 */
	function __construct( $args = [] ) {
		$this->args = wp_parse_args( $args, $this->get_default_attributes(), 'dappier_askai' );
	}

	/**
	 * Get an AskAI instance.
	 *
	 * @since 0.7.0
	 *
	 * @return void
	 */
	function get() {
		$html = '';

		// Bail if not configured.
		if ( ! dappier_is_configured() ) {
			return $html;
		}

		// Enqueue the instance.
		dappier_enqueue_loader();

		// Start attributes.
		$attributes = $this->args;

		// Start attributes.
		$attr = '';

		// Build attributes.
		foreach ( $attributes as $key => $value ) {
			$attr .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}

		// Add the HTML.
		$html .= sprintf( '<dappier-ask-ai-widget%s></dappier-ask-ai-widget>', $attr );

		// Allow filtering of HTML.
		$html = apply_filters( 'dappier_askai_html', $html, $attr );

		// Return the instance.
		return $html;
	}

	/**
	 * Get default attributes.
	 *
	 * @since 0.7.0
	 *
	 * @return array
	 */
	function get_default_attributes() {
		$api_key     = dappier_get_option( 'api_key' );
		$aimodel_id  = dappier_get_option( 'aimodel_id' );
		$widget_id   = dappier_get_option( 'widget_id' );
		$widget_id   = $widget_id;
		$bg_color    = dappier_get_option( 'askai_bg_color' );
		$bg_color    = $bg_color ?: 'inherit';
		$fg_color    = dappier_get_option( 'askai_fg_color' );
		$fg_color    = $fg_color ?: 'inherit';
		$theme_color = dappier_get_option( 'askai_theme_color' );
		$theme_color = $theme_color ?: 'inherit';
		$branding    = dappier_get_option( 'askai_branding' );
		$branding    = is_null( $branding ) ? 'logo' : $branding;
		$image_size  = has_image_size( 'medium' ) ? 'medium' : 'full';
		$logo_id     = dappier_get_option( 'askai_logo' );
		$logo_id     = is_null( $logo_id ) ? (int) get_theme_mod( 'custom_logo' ) : $logo_id;
		$logo_url    = $logo_id ? wp_get_attachment_image_url( $logo_id, $image_size ) : 'https://assets.dappier.com/dappier_logo.png';
		$logo_url    = 'logo' === $branding ? $logo_url : '';
		$logo_width  = dappier_get_option( 'askai_logo_width' );
		$logo_width  = $logo_width ?: '90';
		$title_text  = dappier_get_option( 'askai_title' );
		$icon_id     = dappier_get_option( 'askai_icon' );
		$icon_id     = is_null( $icon_id ) ? (int) get_option( 'site_icon' ) : $icon_id;
		$icon_url    = $icon_id ? wp_get_attachment_image_url( $icon_id, $image_size ) : 'https://assets.dappier.com/dappier_logo_small.png';
		$icon_width  = dappier_get_option( 'askai_icon_width' );
		$icon_width  = $icon_width ?: '24';

		// Set attributes.
		$attributes = [
			'widgetId'                     => $widget_id,
			'title'                        => $title_text,
			'mainBackgroundColor'          => $bg_color,
			'mainTextColor'                => $fg_color,
			'themeColor'                   => $theme_color,
			'mainLogoUrl'                  => $logo_url,
			'mainLogoWidth'                => $logo_width,
			'chatIconUrl'                  => $chat_url,
			'chatIconWidth'                => $icon_width,
			'enablePromptSuggestions'      => 'true',
			'enableContentRecommendations' => 'true',
			'showAttributionLinks'         => 'true',
			'enableSiteName'               => 'true',
			'enableTitle'                  => 'title' === $branding ? 'true' : 'false',
			'initialSearchQuery'           => is_search() ? get_search_query() : '',
		];

		// Allow filtering of attributes.
		$attributes = apply_filters( 'dappier_askai_attributes', $attributes );

		return $attributes;
	}
}