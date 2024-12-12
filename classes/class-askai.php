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
		$this->args = wp_parse_args( $args, $this->get_default_attributes() );
	}

	/**
	 * Get an AskAI instance.
	 *
	 * @since TBD
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

		// If search.
		if ( is_search() ) {
			$attributes['initialSearchQuery'] = get_search_query();
		}

		// Allow filtering of attributes.
		$attributes = apply_filters( 'dappier_askai_attributes', $attributes );

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
	 * @since TBD
	 *
	 * @return array
	 */
	function get_default_attributes() {
		$api_key     = dappier_get_option( 'api_key' );
		$aimodel_id  = dappier_get_option( 'aimodel_id' );
		$widget_id   = dappier_get_option( 'widget_id' );
		$title       = __( 'Ask AI', 'dappier' );
		$widget_id   = $widget_id;
		$bg_color    = dappier_get_option( 'askai_bg_color' );
		// $bg_color    = is_null( $bg_color ) ? '#f8f9fa' : $bg_color;
		$bg_color    = $bg_color ?: 'inherit';
		$fg_color    = dappier_get_option( 'askai_fg_color' );
		$fg_color    = $fg_color ?: 'inherit';
		$theme_color = dappier_get_option( 'askai_theme_color' );
		// $theme_color = is_null( $theme_color ) ? '#674ad9' : $theme_color;
		// $theme_color = $theme_color ?: '#674ad9';
		$theme_color = $theme_color ?: 'inherit';

		// Set attributes.
		$attributes = [
			'widgetId'                     => $widget_id,
			'title'                        => $title,
			'mainBackgroundColor'          => $bg_color,
			'mainTextColor'                => $fg_color,
			'themeColor'                   => $theme_color,
			'mainLogoUrl'                  => 'https://assets.dappier.com/dappier_logo.png',
			'mainLogoWidth'                => '90',
			'chatIconUrl'                  => 'https://assets.dappier.com/dappier_logo_small.png',
			'chatIconWidth'                => '24',
			'enablePromptSuggestions'      => 'true',
			'enableContentRecommendations' => 'true',
			'showAttributionLinks'         => 'true',
			'enableSiteName'               => 'false',
			'enableTitle'                  => 'false',
			'initialSearchQuery'           => '',
		];

		// Filter default attributes.
		$attributes = apply_filters( 'dappier_askai_default_attributes', $attributes );

		return $attributes;
	}
}