<?php
/**
 * Responsible for returning recipes.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 */

/**
 * Responsible for returning recipes.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Recipe_Manager {

	/**
	 * Recipes that have already been requested for easy subsequent access.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $recipes    Array containing recipes that have already been requested for easy access.
	 */
	private static $recipes = array();

	/**
	 * Array of posts with the recipes in them.
	 *
	 * @since    4.2.0
	 * @access   private
	 * @var      array    $posts    Array containing posts with recipes in them.
	 */
	private static $posts = array();

	/**
	 * Register actions and filters.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		add_action( 'wp_ajax_wprm_get_recipe', array( __CLASS__, 'ajax_get_recipe' ) );
		add_action( 'wp_ajax_wprm_search_recipes', array( __CLASS__, 'ajax_search_recipes' ) );
		add_action( 'wp_ajax_wprm_search_posts', array( __CLASS__, 'ajax_search_posts' ) );

		add_action( 'wp_footer', array( __CLASS__, 'recipe_data_in_footer' ) );
	}

	/**
	 * Get all recipes. Should generally not be used.
	 *
	 * @since    1.2.0
	 */
	public static function get_recipes() {
		$recipes = array();

		$limit = 200;
		$offset = 0;

		while ( true ) {
			$args = array(
					'post_type' => WPRM_POST_TYPE,
					'post_status' => 'any',
					'orderby' => 'date',
					'order' => 'DESC',
					'posts_per_page' => $limit,
					'offset' => $offset,
					'suppress_filters' => true,
					'lang' => '',
			);

			$query = new WP_Query( $args );

			if ( ! $query->have_posts() ) {
				break;
			}

			$posts = $query->posts;

			foreach ( $posts as $post ) {
				$recipes[ $post->ID ] = array(
					'name' => $post->post_title,
				);

				wp_cache_delete( $post->ID, 'posts' );
				wp_cache_delete( $post->ID, 'post_meta' );
			}

			$offset += $limit;
			wp_cache_flush();
		}

		return $recipes;
	}

	/**
	 * Get the x latest recipes.
	 *
	 * @since	4.0.0
	 * @param	int $limit Number of recipes to get, defaults to 10.
	 * @param	mixed $display How to display the recipes.
	 */
	public static function get_latest_recipes( $limit = 10, $display = 'name' ) {
		$recipes = array();

		$args = array(
				'post_type' => WPRM_POST_TYPE,
				'post_status' => 'any',
				'orderby' => 'date',
				'order' => 'DESC',
				'posts_per_page' => $limit,
				'offset' => 0,
				'suppress_filters' => true,
				'lang' => '',
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$posts = $query->posts;

			foreach ( $posts as $post ) {
				// Special case.
				if ( 'manage' === $display ) {
					$recipe = self::get_recipe( $post );

					if ( $recipe ) {
						$recipes[] = $recipe->get_data_manage();
					}

					continue;
				}

				switch ( $display ) {
					case 'id':
						$text = $post->ID . ' ' . $post->post_title;
						break;
					default:
						$text = $post->post_title;
				}

				$recipes[] = array(
					'id' =>  $post->ID,
					'text' => $text,
				);
			}
		}

		return $recipes;
	}

	/**
	 * Search for recipes by keyword.
	 *
	 * @since    1.8.0
	 */
	public static function ajax_search_recipes() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : ''; // Input var okay.

			$recipes = array();
			$recipes_with_id = array();

			$args = array(
				'post_type' => WPRM_POST_TYPE,
				'post_status' => 'any',
				'posts_per_page' => 100,
				's' => $search,
				'suppress_filters' => true,
				'lang' => '',
			);

			$query = new WP_Query( $args );

			$posts = $query->posts;

			// If searching for number, include exact result first.
			if ( is_numeric( $search ) ) {
				$id = abs( intval( $search ) );

				if ( $id > 0 ) {
					$args = array(
						'post_type' => WPRM_POST_TYPE,
						'post_status' => 'any',
						'posts_per_page' => 100,
						'post__in' => array( $id ),
					);
	
					$query = new WP_Query( $args );
	
					$posts = array_merge( $query->posts, $posts );
				}
			}

			foreach ( $posts as $post ) {
				$recipes[] = array(
					'id' => $post->ID,
					'text' => $post->post_title,
				);

				$recipes_with_id[] = array(
					'id' => $post->ID,
					'text' => $post->ID . ' - ' . $post->post_title,
				);
			}

			wp_send_json_success( array(
				'recipes' => $recipes,
				'recipes_with_id' => $recipes_with_id,
			) );
		}

		wp_die();
	}

	/**
	 * Search for posts by keyword.
	 *
	 * @since    9.0.0
	 */
	public static function ajax_search_posts() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$search = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : ''; // Input var okay.

			$found_posts = array();
			$found_posts_with_id = array();

			$args = array(
				'post_type' => 'any',
				'post_status' => 'any',
				'posts_per_page' => 100,
				's' => $search,
				'suppress_filters' => true,
				'lang' => '',
			);

			$query = new WP_Query( $args );

			$posts = $query->posts;

			// If searching for number, include exact result first.
			if ( is_numeric( $search ) ) {
				$id = abs( intval( $search ) );

				if ( $id > 0 ) {
					$args = array(
						'post_type' => 'any',
						'post_status' => 'any',
						'posts_per_page' => 100,
						'post__in' => array( $id ),
					);
	
					$query = new WP_Query( $args );
	
					$posts = array_merge( $query->posts, $posts );
				}
			}

			foreach ( $posts as $post ) {
				$ignore_post_types = array(
					WPRM_POST_TYPE,
					'attachment',
				);
				if ( in_array( $post->post_type, $ignore_post_types ) ) {
					continue;
				}

				$found_posts[] = array(
					'id' => $post->ID,
					'text' => $post->post_title,
				);

				// Get post type name.
				$post_type_object = get_post_type_object( $post->post_type );
				$post_type_label = $post_type_object ? $post_type_object->labels->singular_name : $post->post_type;

				$found_posts_with_id[] = array(
					'id' => $post->ID,
					'text' => $post_type_label . ' - ' . $post->ID . ' - ' . $post->post_title,
				);
			}

			wp_send_json_success( array(
				'posts' => $found_posts,
				'posts_with_id' => $found_posts_with_id,
			) );
		}

		wp_die();
	}

	/**
	 * Get recipe data by ID through AJAX.
	 *
	 * @since    1.0.0
	 */
	public static function ajax_get_recipe() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			$recipe_id = isset( $_POST['recipe_id'] ) ? intval( $_POST['recipe_id'] ) : 0; // Input var okay.

			$recipe = self::get_recipe( $recipe_id );
			$recipe_data = $recipe ? $recipe->get_data() : array();

			wp_send_json_success( array(
				'recipe' => $recipe_data,
			) );
		}

		wp_die();
	}

	/**
	 * Get recipe object by ID.
	 *
	 * @since    1.0.0
	 * @param		 mixed $post_or_recipe_id ID or Post Object for the recipe we want.
	 */
	public static function get_recipe( $post_or_recipe_id ) {
		if ( 'demo' === $post_or_recipe_id ) {
			return self::get_demo_recipe();
		} else {
			$recipe_id = is_object( $post_or_recipe_id ) && $post_or_recipe_id instanceof WP_Post ? $post_or_recipe_id->ID : intval( $post_or_recipe_id );
		}

		// Only get new recipe object if it hasn't been retrieved before.
		if ( ! array_key_exists( $recipe_id, self::$recipes ) ) {
			$post = is_object( $post_or_recipe_id ) && $post_or_recipe_id instanceof WP_Post ? $post_or_recipe_id : get_post( intval( $post_or_recipe_id ) );

			if ( $post instanceof WP_Post && WPRM_POST_TYPE === $post->post_type ) {
				$recipe = new WPRM_Recipe( $post );
			} else {
				$recipe = false;
			}

			self::$recipes[ $recipe_id ] = $recipe;
		}

		return self::$recipes[ $recipe_id ];
	}

	/**
	 * Get demo recipe.
	 *
	 * @since	5.8.0
	 */
	public static function get_demo_recipe() {
		ob_start();
		include( WPRM_DIR . 'templates/admin/demo-recipe.json' );
		$json = ob_get_contents();
		ob_end_clean();

		$json_recipe = json_decode( $json, true );
		$json_recipe = apply_filters( 'wprm_demo_recipe', $json_recipe );

		$sanitized_recipe = WPRM_Recipe_Sanitizer::sanitize( $json_recipe );

		// Fix technical fields.
		$sanitized_recipe['id'] = 'demo';
		$sanitized_recipe['parent_url'] = '#';
		$sanitized_recipe['post_author'] = $json_recipe['post_author'];
		$sanitized_recipe['ingredients_flat'] = $json_recipe['ingredients_flat'];
		$sanitized_recipe['instructions_flat'] = $json_recipe['instructions_flat'];

		// Set some additional fields.
		$sanitized_recipe['image_url'] = WPRM_URL . 'assets/images/demo-recipe.jpg';
		$sanitized_recipe['pin_image_url'] = WPRM_URL . 'assets/images/demo-recipe.jpg';
		$sanitized_recipe['rating'] = array(
			'count' => 8,
			'total' => 30,
			'average' => 3.75,
		);
		$sanitized_recipe['permalink'] = home_url() . '/demo-recipe/';

		$demo_recipe = new WPRM_Recipe_Shell( $sanitized_recipe );
		WPRM_Template_Shortcodes::set_current_recipe_shell( $demo_recipe );

		return $demo_recipe;
	}

	/**
	 * Get an array of recipe IDs that are in a specific post.
	 *
	 * @since	4.2.0
	 * @param	mixed	$post_id Optional post ID. Uses current post if not set.
	 * @param	boolean	$ignore_cache Whether the cache should be ignored.
	 */
	public static function get_recipe_ids_from_post( $post_id = false, $ignore_cache = false ) {
		// Default to current post ID and sanitize.
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$post_id = intval( $post_id );

		// Search through post content if not in cache only.
		if ( $ignore_cache || ! isset( self::$posts[ $post_id ] ) ) {
			$post = get_post( $post_id );

			if ( $post ) {
				if ( WPRM_POST_TYPE === $post->post_type ) {
					self::$posts[ $post_id ] = array( $post_id );
				} else {
					$recipe_ids = self::get_recipe_ids_from_content( $post->post_content );

					// Thrive Architect compatibility.
					if ( function_exists( 'tve_get_post_meta' ) ) {
						$content = tve_get_post_meta( get_the_ID(), 'tve_updated_post', true );
						$thrive_recipe_ids = self::get_recipe_ids_from_content( $content );

						$recipe_ids = array_unique( $recipe_ids + $thrive_recipe_ids );
					}

					// Themify Builder compatibility.
					if ( '<!-- wp:themify-builder/canvas /-->' === substr( $post->post_content, 0, 38 ) ) {
						$ThemifyBuilder = isset( $GLOBALS['ThemifyBuilder'] ) ? $GLOBALS['ThemifyBuilder'] : false;

						if ( $ThemifyBuilder ) {
							$content = $ThemifyBuilder->get_builder_output( $post->ID );
						
							if ( $content ) {
								preg_match_all( '/id="wprm-recipe-container-(\d+)"/m', $content, $matches );
								$recipe_ids = array_unique( $recipe_ids + $matches[1] );
							}
						}
					}

					self::$posts[ $post_id ] = $recipe_ids;
				}
			} else {
				// Fail now and give another chance to find ids later.
				return false;
			}
		}

		return self::$posts[ $post_id ];
	}

	/**
	 * Get an array of recipe IDs that are in the content.
	 *
	 * @since    1.0.0
	 * @param		 mixed $content Content we want to check for recipes.
	 */
	public static function get_recipe_ids_from_content( $content ) {
		// Gutenberg.
		$gutenberg_matches = array();
		$gutenberg_patern = '/<!--\s+wp:(wp\-recipe\-maker\/recipe)(\s+(\{.*?\}))?\s+(\/)?-->/';
		preg_match_all( $gutenberg_patern, $content, $matches );

		if ( isset( $matches[3] ) ) {
			foreach ( $matches[3] as $block_attributes_json ) {
				if ( ! empty( $block_attributes_json ) ) {
					$attributes = json_decode( $block_attributes_json, true );
					if ( ! is_null( $attributes ) ) {
						if ( isset( $attributes['id'] ) ) {
							$gutenberg_matches[] = intval( $attributes['id'] );
						}
					}
				}
			}
		}

		// Classic Editor.
		preg_match_all( WPRM_Fallback_Recipe::get_fallback_regex(), $content, $matches );
		$classic_matches = isset( $matches[1] ) ? array_map( 'intval', $matches[1] ) : array();

		// Site Origin Page Builder Compatibility.
		$content = str_ireplace( '\&quot;', '"', $content );

		// Match shortcodes (need for Site Origin Page Builder, for example).
		$shortcode_pattern = '/\[wprm-recipe\s.*?id=\"?\'?(\d+)\"?\'?.*?\]/mi';
		preg_match_all( $shortcode_pattern, $content, $matches );
		$shortcode_matches = isset( $matches[1] ) ? array_map( 'intval', $matches[1] ) : array();

		// Divi Builder.
		$divi_matches = array();
		if ( function_exists( 'et_core_is_builder_used_on_current_request' ) ) {
			$pattern = get_shortcode_regex( array( 'divi_wprm_recipe' ) );

			if ( preg_match_all( '/' . $pattern . '/s', $content, $matches ) && array_key_exists( 2, $matches ) ) {
				foreach ( $matches[2] as $key => $value ) {
					if ( 'divi_wprm_recipe' === $value ) {
						$divi_atts = shortcode_parse_atts( stripslashes( $matches[3][ $key ] ) );

						if ( isset( $divi_atts['recipe_id'] ) ) {
							$divi_matches[] = intval( $divi_atts['recipe_id'] );
						}
					}
				}
			}
		}

		return $gutenberg_matches + $classic_matches + $shortcode_matches + $divi_matches;
	}

	/**
	 * Invalidate cached recipe.
	 *
	 * @since    1.0.0
	 * @param		 int $recipe_id ID of the recipe to invalidate.
	 */
	public static function invalidate_recipe( $recipe_id ) {
		if ( array_key_exists( $recipe_id, self::$recipes ) ) {
			unset( self::$recipes[ $recipe_id ] );
		}
	}

	/**
	 * Recipe data to pass along in footer.
	 *
	 * @since	8.10.0
	 */
	public static function recipe_data_in_footer( $recipe_ids = array() ) {
		// add_action will pass along empty string, so make sure we have an array.
		$recipe_ids = is_array( $recipe_ids ) ? $recipe_ids : array();

		$recipes = apply_filters( 'wprm_recipes_on_page', $recipe_ids );

		if ( $recipes ) {
			$recipe_data = array();
			$recipes = array_unique( $recipes );

			foreach( $recipes as $recipe_id ) {
				$recipe = self::get_recipe( $recipe_id );

				if ( $recipe ) {
					$recipe_data[ 'recipe-' . $recipe_id ] = $recipe->get_data_frontend();
				}
			}

			if ( $recipe_data ) {
				echo '<script>window.wprm_recipes = ' . wp_json_encode( $recipe_data ) . '</script>';
			}
		}
	}
}

WPRM_Recipe_Manager::init();
