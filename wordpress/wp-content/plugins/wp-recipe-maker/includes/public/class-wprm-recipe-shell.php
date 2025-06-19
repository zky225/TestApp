<?php
/**
 * Represents a recipe that doesn't have an associated post.
 *
 * @link       http://bootstrapped.ventures
 * @since      5.2.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 */

/**
 * Represents a recipe that doesn't have an associated post.
 *
 * @since      5.2.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Recipe_Shell {

	/**
	 * Data associated with this recipe.
	 *
	 * @since    5.2.0
	 * @access   private
	 * @var      array    $data    Recipe data.
	 */
	private $data = array();

	/**
	 * Get new recipe object from associated post.
	 *
	 * @since	5.2.0
	 * @param	object $data mixed Data for this recipe.
	 */
	public function __construct( $data = array() ) {
		$defaults = array(
			'type' => 'food',
			'image_id' => 0,
			'image_url' => '',
			'pin_image_id' => 0,
			'pin_image_url' => '',
			'pin_image_repin_id' => '',
			'video_id' => 0,
			'video_embed' => '',
			'video_thumb_url' => '',
			'name' => '',
			'summary' => '',
			'author_display' => 'default',
			'author_name' => 'custom' === WPRM_Settings::get( 'recipe_author_display_default' ) ? WPRM_Settings::get( 'recipe_author_custom_default' ) : '',
			'author_link' => '',
			'rating' => false,
			'servings' => 0,
			'servings_unit' => '',
			'servings_advanced_enabled' => false,
			'cost' => '',
			'my_emissions' => false,
			'prep_time' => 0,
			'prep_time_zero' => false,
			'cook_time' => 0,
			'cook_time_zero' => false,
			'total_time' => 0,
			'custom_time' => 0,
			'custom_time_zero' => false,
			'custom_time_label' => '',
			'tags' => array(),
			'equipment' => array(),
			'ingredients' => array(),
			'ingredients_flat' => array(),
			'ingredient_links_type' => 'global',
			'unit_system' => 'default',
			'instructions' => array(),
			'instructions_flat' => array(),
			'notes' => '',
			'nutrition' => array(),
			'custom_fields' => array(),
		);

		$this->data = array_merge( $defaults, $data );
	}

	/**
	 * Get recipe data.
	 *
	 * @since	5.2.0
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * Get recipe data for the manage page.
	 *
	 * @since	5.2.0
	 */
	public function get_data_manage() {
		return $this->data;
	}

	/**
	 * Get recipe data for the frontend.
	 *
	 * @since	8.10.0
	 */
	public function get_data_frontend() {
		$recipe = array();

		$recipe['type'] = $this->type();
		$recipe['name'] = $this->name();
		$recipe['slug'] = $this->slug();
		$recipe['image_url'] = $this->image_url( 'full' );

		// Servings related data.
		$recipe['originalServings'] = $this->servings();
		
		$parsed_servings = WPRM_Recipe_Parser::parse_quantity( $this->servings() );
		$recipe['originalServingsParsed'] = is_numeric( $parsed_servings ) && 0 < $parsed_servings ? $parsed_servings : 1;

		$recipe['currentServings'] = $recipe['originalServings'];
		$recipe['currentServingsParsed'] = $recipe['originalServingsParsed'];
		$recipe['currentServingsFormatted'] = $recipe['originalServings'];
		$recipe['currentServingsMultiplier'] = 1;

		$recipe['rating'] = $this->rating();

		return apply_filters( 'wprm_recipe_frontend_data', $recipe, $this );
	}

	/**
	 * Get metadata value.
	 *
	 * @since	5.2.0
	 * @param	mixed $field	Metadata field to retrieve.
	 * @param	mixed $default	Default to return if metadata is not set.
	 */
	public function meta( $field, $default ) {
		if ( isset( $this->data[ $field ] ) ) {
			return $this->data[ $field ];
		}

		return $default;
	}

	/**
	 * Get the recipe author.
	 *
	 * @since	6.0.0
	 */
	public function author() {
		$author = '';

		switch ( $this->author_display() ) {
			case 'same':
				$author = WPRM_Settings::get( 'recipe_author_same_name' );
				break;
			default:
				$author = $this->author_name();
		}

		return $author;
	}

	/**
	 * Get the recipe image HTML.
	 *
	 * @since	5.2.0
	 * @param	mixed $size Thumbnail name or size array of the image we want.
	 */
	public function image( $size = 'thumbnail' ) {
		$image_id = $this->image_id();

		if ( 'url' === $image_id ) {
			$style = '';

			$width = is_array( $size ) ? $size[0] : get_option( $size . '_size_w' );
			if ( $width ) {
				$style = ' style="max-width: ' . $width . 'px; height: auto;"';
			}
			
			$img = '<img src="' . esc_url( $this->image_url() ) . '" alt="' . esc_attr( $this->name() ) .'"' . $style . '/>';

			// Disable external recipe image pinning.
			if ( $this->parent_external() && WPRM_Settings::get( 'pinterest_nopin_external_roundup_image' ) ) {
				$img = str_ireplace( '<img ', '<img data-pin-nopin="true" ', $img );
			}

			return $img;
		}

		$img = wp_get_attachment_image( $image_id, $size );

		// Prevent stretching of recipe image in Gutenberg Preview.
		if ( WPRM_Context::is_gutenberg_preview() ) {
			$image_data = $this->image_data( $size );
			if ( $image_data[1] ) {
				$style = 'max-width: ' . $image_data[1] . 'px; height: auto;';
				$img = WPRM_Shortcode_Helper::add_inline_style( $img, $style );
			}
		}

		// Disable external recipe image pinning.
		if ( $this->parent_external() && WPRM_Settings::get( 'pinterest_nopin_external_roundup_image' ) ) {
			$img = str_ireplace( '<img ', '<img data-pin-nopin="true" ', $img );
		}

		// Clickable images (but not in Gutenberg Preview).
		if ( WPRM_Settings::get( 'recipe_image_clickable' ) && ! WPRM_Context::is_gutenberg_preview() ) {
			$full_image_url = $this->image_url( 'full' );
			if ( $full_image_url ) {
				$img = '<a href="' . esc_url( $full_image_url) . '" aria-label="' . __( 'Open recipe image in full size', 'wp-recipe-maker' ) . '">' . $img . '</a>';
			}
		}

		return $img;
	}

	/**
	 * Get the recipe image ID.
	 *
	 * @since	5.8.0
	 * @param	mixed $size Thumbnail name or size array of the image we want.
	 */
	public function image_id() {
		$image_id = $this->data['image_id'];

		if ( ! $image_id ) {
			$image_url = $this->data['image_url'];

			if ( $image_url ) {
				return 'url';
			}
		}

		return $image_id;
	}

	/**
	 * Get the recipe image data.
	 *
	 * @since	5.2.0
	 * @param	mixed $size Thumbnail name or size array of the image we want.
	 */
	public function image_data( $size = 'thumbnail' ) {
		$thumb = false;

		if ( function_exists( 'fly_get_attachment_image_src' ) ) {
			$thumb = fly_get_attachment_image_src( $this->image_id(), $size );
		}

		if ( ! $thumb ) {
			$thumb = wp_get_attachment_image_src( $this->image_id(), $size );
		}

		return $thumb;
	}

	/**
	 * Get the recipe ingredients without nested groups.
	 *
	 * @since    1.0.0
	 */
	public function ingredients_without_groups() {
		$ingredients = $this->ingredients();
		$ingredients_without_groups = array();

		foreach ( $ingredients as $ingredient_group ) {
			if ( isset( $ingredient_group['ingredients'] ) && is_array( $ingredient_group['ingredients'] ) ) {
				$ingredients_without_groups = array_merge( $ingredients_without_groups, $ingredient_group['ingredients'] );				
			}
		}

		return $ingredients_without_groups;
	}

	/**
	 * Get the recipe instructions without nested groups.
	 *
	 * @since    1.0.0
	 */
	public function instructions_without_groups() {
		$instructions = $this->instructions();
		$instructions_without_groups = array();

		if ( is_array( $instructions ) ) {
			foreach ( $instructions as $instruction_group ) {
				$instructions_without_groups = array_merge( $instructions_without_groups, $instruction_group['instructions'] );
			}
		}

		return $instructions_without_groups;
	}

	/**
	 * Get the recipe tags for a certain tag type.
	 *
	 * @since	5.8.0
	 * @param	mixed $taxonomy Taxonomy to get the tags for.
	 */
	public function tags( $taxonomy ) {
		$tags = $this->data['tags'];
		return isset( $tags[ $taxonomy] ) ? $tags[ $taxonomy ] : array();
	}

	/**
	 * Get the recipe video.
	 *
	 * @since	5.8.0
	 */
	public function video() {
		$output = '';
		if ( $this->video_embed() ) {
			$embed_code = $this->video_embed();

			// Check if it's a regular URL.
			$url = filter_var( $embed_code, FILTER_SANITIZE_URL );

			if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
				global $wp_embed;

				if ( isset( $wp_embed ) ) {
					$output = $wp_embed->run_shortcode( '[embed]' . $url . '[/embed]' );
				}
			} else {
				$output = $embed_code;
			}
		}

		return $output;
	}

	/**
	 * Replace placeholders in text with details from this recipe.
	 *
	 * @since	8.0.0
	 * @param	string $text Text to replace the placeholders in.
	 */
	public function replace_placeholders( $text ) {
		$text = str_ireplace( '%recipe_id%', $this->id(), $text );
		$text = str_ireplace( '%recipe_url%', $this->permalink(), $text );
		$text = str_ireplace( '%recipe_name%', $this->name(), $text );
		$text = str_ireplace( '%recipe_date%', date( get_option( 'date_format' ), strtotime( $this->date() ) ), $text );
		$text = str_ireplace( '%recipe_date_modified%', date( get_option( 'date_format' ), strtotime( $this->date_modified() ) ), $text );
		$text = str_ireplace( '%recipe_summary%', $this->summary(), $text );

		$http_host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$current_page = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://' . $http_host . $request_uri;

		$text = str_ireplace( '%recipe_current_url%', $current_page, $text );

		return $text;
	}

	/**
	 * Catch all other recipe function calls.
	 *
	 * @since	5.2.0
	 */
	public function __call( $name, $arguments ) {
		if ( isset( $this->data[ $name ] ) ) {
			return $this->data[ $name ];
		}

		return false;
	}
}
