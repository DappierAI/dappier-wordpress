<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

/**
 * The endpoints class.
 *
 * @since 0.1.0
 */
class Dappier_Endpoints {
	protected $user;
	protected $request;
	protected $body;

	/**
	 * Construct the class.
	 */
	function __construct() {
		$this->hooks();
	}

	/**
	 * Add hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function hooks() {
		add_filter( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	/**
	 * Register the endpoints.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function register_endpoints() {
		/**
		 * /wp-json/dappier/v1/app
		 * /wp-json/dappier/v1/posts
		 */
		$routes = [
			'app'   => 'handle_app_request',
			'posts' => 'handle_posts_request',
		];

		// Loop through routes and register them.
		foreach ( $routes as $path => $callback ) {
			register_rest_route( 'dappier/v1', $path, [
				'methods'             => 'GET',
				'callback'            => [ $this, $callback ],
				'permission_callback' => [ $this, 'authenticate_request' ],
			] );
		}
	}

	/**
	 * Handle the site request.
	 *
	 * This method returns the site title and description.
	 *
	 * Currently unused.
	 *
	 * @example GET /wp-json/dappier/v1/app
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response
	 */
	function handle_app_request( $request ) {
		// Get the site title and description.
		$name = get_bloginfo( 'name' );
		$desc = get_bloginfo( 'description' );

		// Prepare the response.
		$response = [
			'feed'        => home_url( '/wp-json/dappier/v1/posts' ),
			'name'        => $name,
			'description' => $desc,
		];

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Handle the posts request.
	 *
	 * This method returns a paginated list of posts with specific custom data.
	 * You can control the pagination by passing `per_page` and `page` parameters in the request.
	 *
	 * @example GET /wp-json/dappier/v1/posts?per_page=10&page=1
	 * This will return the first 10 posts.
	 *
	 * @example GET /wp-json/dappier/v1/posts?per_page=5&page=2
	 * This will return the next 5 posts, starting from the 6th post.
	 *
	 * @example GET /wp-json/dappier/v1/posts?post_type=post,portfolio,lesson
	 * This will return posts, portfolio items, and lessons.
	 *
	 * @example GET /wp-json/dappier/v1/posts
	 * If `per_page` and `page` are not specified, it will default to 20 posts per page and start from the first page.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	function handle_posts_request( $request ) {
		// Get pagination parameters from the request.
		$post_types = $request->get_param( 'post_type' ) ? sanitize_text_field( $request->get_param( 'post_type' ) ) : null;
		$post_types = $post_types ? array_map( 'sanitize_key', explode( ',', $post_types ) ) : null;
		$post_types = $post_types ? $post_types : dappier_get_allowed_post_types();
		$per_page   = $request->get_param( 'per_page' ) ? absint( $request->get_param( 'per_page' ) ) : 20;
		$page       = $request->get_param( 'page' ) ? absint( $request->get_param( 'page' ) ) : 1;

		// Prepare the query arguments.
		$args = [
			'post_type'      => $post_types,
			'posts_per_page' => $per_page,
			'paged'          => $page,
		];

		// Add filters to the query.
		$args = apply_filters( 'dappier_api_query_args', $args, $request );

		// Query the posts.
		$query = new WP_Query( $args );

		// Prepare the response.
		$data = [];

		// Loop through the posts and add custom data.
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				// Get the post ID.
				global $post;
				$post_id = $post->ID;
				$content = $this->get_content( get_the_content( null, false, $post_id ) );
				$excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words( $content, 55, '...' );

				// Add custom data to each post.
				$data[] = [
					'id'             => $post_id,
					'url'            => get_permalink(),
					'title'          => get_the_title(),
					'post_type'      => get_post_type(),
					'date'           => get_the_date( 'c' ),
					'date_modified'  => get_the_modified_date( 'c' ),
					'author'         => get_the_author(),
					'featured_image' => (string) get_the_post_thumbnail_url( $post_id, 'full' ),
					'excerpt'        => $excerpt,
					'content'        => $content,
					'categories'     => $this->get_terms( $post_id, 'category' ),
					'tags'           => $this->get_terms( $post_id, 'post_tag' ),
					// 'custom_field'   => get_post_meta( get_the_ID(), 'custom_field_key', true ),
				];
			}
			wp_reset_postdata();
		}

		// $data = apply_filters( 'dappier_api_posts_data', $data, $query, $request );

		// Prepare the paginated response.
		$response = [
			'site'         => get_bloginfo( 'name' ),
			'posts'        => $data,
			'total'        => (int) $query->found_posts,
			'per_page'     => (int) $per_page,
			'current_page' => (int) $page,
			'total_pages'  => (int) $query->max_num_pages,
		];

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Process the content.
	 * Originally taken from Mai Theme's `mai_get_processed_content()` helper function.
	 *
	 * @since TBD
	 *
	 * @param string $content The content of the post.
	 *
	 * @return string
	 */
	function get_content( $content ) {
		/**
		 * Embed.
		 *
		 * @var WP_Embed $wp_embed Embed object.
		 */
		global $wp_embed;

		$blocks  = has_blocks( $content );
		$content = $wp_embed->autoembed( $content );            // WP runs priority 8.
		$content = $wp_embed->run_shortcode( $content );        // WP runs priority 8.
		$content = $blocks ? do_blocks( $content ) : $content;  // WP runs priority 9.
		$content = wptexturize( $content );                     // WP runs priority 10.
		$content = ! $blocks ? wpautop( $content ) : $content;  // WP runs priority 10.
		$content = shortcode_unautop( $content );               // WP runs priority 10.
		$content = do_shortcode( $content );                    // WP runs priority 11.
		$content = wp_filter_content_tags( $content );          // WP runs priority 12.
		$content = convert_smilies( $content );                 // WP runs priority 20.
		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;
	}

	/**
	 * Get the terms associated with a post.
	 *
	 * @since 0.1.0
	 *
	 * @param int    $post_id  The post ID.
	 * @param string $taxonomy The taxonomy name.
	 *
	 * @return array
	 */
	function get_terms( $post_id, $taxonomy ) {
		// Get the terms associated with the post.
		$terms = get_the_terms( $post_id, $taxonomy );

		// Check if terms were returned.
		if ( ! $terms || is_wp_error( $terms ) ) {
			return [];
		}

		// Extract the term names.
		$names = wp_list_pluck( $terms, 'name' );

		return $names;
	}

	/**
	 * Authenticate and validate the request.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function authenticate_request( $request ) {
		// Get the authorization header.
		$auth_header = $request->get_header( 'Authorization' );

		// Bail if no headers.
		if ( ! isset( $auth_header ) ) {
			// If authorization header is missing
			return new WP_Error( 'rest_forbidden', 'Authorization header missing.', [ 'status' => 403 ] );
		}

		// Extract the Bearer token from the Authorization header.
		list( $type, $token ) = explode( ' ', $auth_header, 2 );

		// Bearer token should start with 'Bearer'.
		if ( 'Bearer' !== $type ) {
			return new WP_Error( 'rest_forbidden', 'Invalid authentication method. Use Bearer token.', [ 'status' => 403 ] );
		}

		// Get Data Model ID key.
		$datamodel_id = dappier_get_option( 'datamodel_id' );

		// Bail if no API key.
		if ( ! $datamodel_id ) {
			return new WP_Error( 'rest_forbidden', 'Data Model API key is missing.', [ 'status' => 403 ] );
		}

		// Bail if token does not match the API key.
		if ( $token !== $datamodel_id ) {
			return new WP_Error( 'rest_forbidden', 'Token Mismatch.', [ 'status' => 403 ] );
		}

		return true;
	}
}