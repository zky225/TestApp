<?php
/**
 * Handle compabitility with other plugins/themes.
 *
 * @link       http://bootstrapped.ventures
 * @since      3.2.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 */

/**
 * Handle compabitility with other plugins/themes.
 *
 * @since      3.2.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Compatibility {

	/**
	 * Register actions and filters.
	 *
	 * @since	3.2.0
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'yoast_seo' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'rank_math' ) );

		add_filter( 'wpseo_video_index_content', array( __CLASS__, 'yoast_video_seo' ) );

		// Caching plugins.
		add_filter( 'litespeed_optimize_js_excludes', array( __CLASS__, 'cache_js_excludes' ) );
		add_filter( 'rocket_exclude_js', array( __CLASS__, 'cache_js_excludes' ) );
		add_filter( 'wp-optimize-minify-default-exclusions', array( __CLASS__, 'cache_js_excludes' ) );
		add_filter( 'perfmatters_minify_js_exclusions', array( __CLASS__, 'cache_js_excludes' ) );
		add_filter( 'sgo_js_minify_exclude', array( __CLASS__, 'cache_js_excludes' ) );
		add_filter( 'js_do_concat', array( __CLASS__, 'jetpack_boost_exclude' ), 10, 2 );

		// Jupiter.
		add_action( 'wp_footer', array( __CLASS__, 'jupiter_assets' ) );

		// Emeals.
		add_filter( 'wprm_recipe_ingredients_shortcode', array( __CLASS__, 'emeals_after_ingredients' ), 9 );
		add_action( 'wp_footer', array( __CLASS__, 'emeals_assets' ) );

		// Smart With Food.
		add_filter( 'wprm_recipe_ingredients_shortcode', array( __CLASS__, 'smartwithfood_after_ingredients' ), 9 );
		add_action( 'wp_footer', array( __CLASS__, 'smartwithfood_assets' ) );

		// Chicory.
		add_filter( 'wprm_recipe_ingredients_shortcode', array( __CLASS__, 'chicory_after_ingredients' ), 9 );
		add_action( 'wp_footer', array( __CLASS__, 'chicory_assets' ) );

		// Divi.
		add_action( 'divi_extensions_init', array( __CLASS__, 'divi' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'divi_assets' ) );

		// Elementor.
		add_action( 'elementor/editor/before_enqueue_scripts', array( __CLASS__, 'elementor_assets' ) );
		add_action( 'elementor/controls/register', array( __CLASS__, 'elementor_controls' ) );
		add_action( 'elementor/preview/enqueue_styles', array( __CLASS__, 'elementor_styles' ) );
		add_action( 'elementor/widgets/register', array( __CLASS__, 'elementor_widgets' ) );
		add_action( 'elementor/elements/categories_registered', array( __CLASS__, 'elementor_categories' ) );
		add_action( 'ECS_after_render_post_footer', array( __CLASS__, 'wpupg_unset_recipe_id' ) );

		// WP Ultimate Post Grid.
		add_filter( 'wpupg_output_grid_post', array( __CLASS__, 'wpupg_set_recipe_id_legacy' ) );
		add_filter( 'wpupg_term_name', array( __CLASS__, 'wpupg_term_name' ), 10, 3 );

		add_filter( 'wpupg_set_current_item', array( __CLASS__, 'wpupg_set_recipe_id' ) );
		add_filter( 'wpupg_unset_current_item', array( __CLASS__, 'wpupg_unset_recipe_id' ) );
		add_filter( 'wpupg_template_editor_shortcodes', array( __CLASS__, 'wpupg_template_editor_shortcodes' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'wpupg_template_editor_styles' ) );

		// WP Ultimate Post Grid & WP Extended Search combination.
		add_filter( 'wpes_post_types', array( __CLASS__, 'wpupg_extended_search_post_types' ) );
		add_filter( 'wpes_tax', array( __CLASS__, 'wpupg_extended_search_taxonomies' ) );
	}

	/**
	 * Yoast SEO Compatibility.
	 *
	 * @since	3.2.0
	 */
	public static function yoast_seo() {
		if ( defined( 'WPSEO_VERSION' ) ) {
			wp_enqueue_script( 'wprm-yoast-compatibility', WPRM_URL . 'assets/js/other/yoast-compatibility.js', array( 'jquery' ), WPRM_VERSION, true );
		}
	}

	/**
	 * Yoast Video SEO Compatibility.
	 *
	 * @since	7.2.0
	 */
	public static function yoast_video_seo( $post_content ) {
		$recipes = WPRM_Recipe_Manager::get_recipe_ids_from_content( $post_content );

		if ( $recipes ) {
			foreach ( $recipes as $recipe_id ) {
				$recipe_id = intval( $recipe_id );
				$recipe = WPRM_Recipe_Manager::get_recipe( $recipe_id );

				if ( $recipe ) { 
					// This makes sure recipes are parsed and their videos are included.
					$post_content .= ' ' . do_shortcode( '[wprm-recipe id="' . $recipe_id . '"]' );
					$post_content .= ' ' . $recipe->video_embed();
				}
			}
		}

		return $post_content;
	}

	/**
	 * Caching plugin compatibility.
	 *
	 * @since	9.7.0
	 */
	public static function cache_js_excludes( $excludes ) {
		if ( WPRM_Settings::get( 'assets_prevent_caching_optimization' ) && is_array( $excludes ) ) {
			$excludes[] = 'wp-recipe-maker/dist/public-modern.js';
			if ( defined( 'WPRMP_BUNDLE' ) ) {
				$excludes[] = 'wp-recipe-maker-premium/dist/public-' . strtolower( WPRMP_BUNDLE ) . '.js';
			}
		}

		return $excludes;
	}

/**
  * Excludes WPRM assets from Jetpack Boost's concatenation.
  *
  * @param bool   $do_concat Whether Jetpack Boost should concatenate the asset.
  * @param string $handle    The handle of the JavaScript asset.
  * @return bool  Whether the asset should be excluded from concatenation.
  */
 public static function jetpack_boost_exclude( $do_concat, $handle ) {
		if ( WPRM_Settings::get( 'assets_prevent_caching_optimization' ) ) {
			if ( 'wprm-public' === $handle || 'wprmp-public' === $handle ) {
				$do_concat = false;
			}
		}

		return $do_concat;
	}

	/**
	 * Rank Math Compatibility.
	 *
	 * @since	6.6.0
	 */
	public static function rank_math() {
		// wp_enqueue_script( 'wprm-rank-math-compatibility', WPRM_URL . 'assets/js/other/rank-math-compatibility.js', array( 'wp-hooks', 'rank-math-analyzer' ), WPRM_VERSION, true );
	}

	/**
	 * Divi Builder Compatibility.
	 *
	 * @since	5.1.0
	 */
	public static function divi() {
		require_once( WPRM_DIR . 'templates/divi/includes/extension.php' );
	}

	/**
	 * Divi Builder assets.
	 *
	 * @since	9.7.0
	 */
	public static function divi_assets() {
		if ( isset( $_GET['et_fb'] ) && '1' === $_GET['et_fb'] ) {
			WPRM_Assets::load();
		}
	}


	/**
	 * Elementor Compatibility.
	 *
	 * @since	5.0.0
	 */
	public static function elementor_assets() {
		WPRM_Modal::add_modal_content();
		WPRM_Assets::enqueue_admin();
		WPRM_Modal::enqueue();

		if ( class_exists( 'WPRMP_Assets' ) ) {
			WPRMP_Assets::enqueue_admin();
		}

		wp_enqueue_script( 'wprm-admin-elementor', WPRM_URL . 'assets/js/other/elementor.js', array( 'wprm-admin', 'wprm-admin-modal' ), WPRM_VERSION, true );
	}
	public static function elementor_controls( $controls_manager ) {
		include( WPRM_DIR . 'templates/elementor/control.php' );
		include( WPRM_DIR . 'templates/elementor/control-list.php' );

		$controls_manager->register( new WPRM_Elementor_Control() );
		$controls_manager->register( new WPRM_Elementor_Control_List() );
	}
	public static function elementor_styles() {
		// Make sure default assets load.
		WPRM_Assets::load();
	}
	public static function elementor_widgets( $widgets_manager ) {
		include( WPRM_DIR . 'templates/elementor/widget-recipe.php' );
		include( WPRM_DIR . 'templates/elementor/widget-list.php' );
		include( WPRM_DIR . 'templates/elementor/widget-roundup.php' );

		$widgets_manager->register( new WPRM_Elementor_Recipe_Widget() );
		$widgets_manager->register( new WPRM_Elementor_List_Widget() );
		$widgets_manager->register( new WPRM_Elementor_Roundup_Widget() );
	}

	/**
	 * Add custom widget categories to Elementor.
	 *
	 * @since 8.6.0
	 */
	public static function elementor_categories( $elements_manager ) {
		$elements_manager->add_category(
			'wp-recipe-maker',
			array(
				'title' => __( 'WP Recipe Maker', 'wp-recipe-maker' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Recipes in WP Ultimate Post Grid Compatibility (after 3.0.0).
	 *
	 * @since	5.9.0
	 * @param	mixed $post Post getting shown in the grid.
	 */
	public static function wpupg_set_recipe_id( $item ) {
		if ( WPRM_POST_TYPE === $item->post_type() ) {
			WPRM_Template_Shortcodes::set_current_recipe_id( $item->id() );
		} else {
			$recipes = WPRM_Recipe_Manager::get_recipe_ids_from_post( $item->id() );

			if ( isset( $recipes[0] ) ) {
				WPRM_Template_Shortcodes::set_current_recipe_id( $recipes[0] );
			}
		}

		return $item;
	}
	public static function wpupg_unset_recipe_id( $item ) {
		WPRM_Template_Shortcodes::set_current_recipe_id( false );
		return $item;
	}

	/**
	 * Recipes in WP Ultimate Post Grid Compatibility (before 3.0.0).
	 *
	 * @since	4.2.0
	 * @param	mixed $post Post getting shown in the grid.
	 */
	public static function wpupg_set_recipe_id_legacy( $post ) {
		if ( WPRM_POST_TYPE === $post->post_type ) {
			WPRM_Template_Shortcodes::set_current_recipe_id( $post->ID );
		}

		return $post;
	}

	/**
	 * Alter term names in WP Ultimate Post Grid.
	 *
	 * @since	7.3.0
	 * @param	mixed $name Name for the term.
	 * @param	mixed $id Term ID.
	 * @param	mixed $taxonomy Taxonomy of the term.
	 */
	public static function wpupg_term_name( $name, $id, $taxonomy ) {
		if ( 'wprm_suitablefordiet' === $taxonomy ) {
			$diet_label = get_term_meta( $id, 'wprm_term_label', true );
			
			if ( $diet_label ) {
				$name = $diet_label;
			}
		}

		return $name;
	}

	/**
	 * Add recipe shortcodes to grid template editor.
	 *
	 * @since	5.9.0
	 * @param	mixed $shortcodes Current template editor shortcodes.
	 */
	public static function wpupg_template_editor_shortcodes( $shortcodes ) {
		$shortcodes = array_merge( $shortcodes, WPRM_Template_Shortcodes::get_shortcodes() );
		return $shortcodes;
	}
	
	/**
	 * Add recipe shortcode styles to grid template editor.
	 *
	 * @since	5.9.0
	 */
	public static function wpupg_template_editor_styles( $shortcodes ) {
		$screen = get_current_screen();
		if ( 'grids_page_wpupg_template_editor' === $screen->id  ) {
			wp_enqueue_style( 'wprm-admin-template', WPRM_URL . 'dist/admin-template.css', array(), WPRM_VERSION, 'all' );
		}
	}
	
	/**
	 * Compatibility with WP Extended Search when WP Ultimate Post Grid is activated.
	 *
	 * @since	8.0.0
	 */
	public static function wpupg_extended_search_post_types( $post_types ) {
		if ( class_exists( 'WP_Ultimate_Post_Grid' ) ) {
			$post_types[ WPRM_POST_TYPE ] = get_post_type_object( WPRM_POST_TYPE );
		}

		return $post_types;
	}

	/**
	 * Compatibility with WP Extended Search when WP Ultimate Post Grid is activated.
	 *
	 * @since	8.0.0
	 */
	public static function wpupg_extended_search_taxonomies( $taxonomies ) {
		if ( class_exists( 'WP_Ultimate_Post_Grid' ) ) {
			$wprm_taxonomies = get_object_taxonomies( WPRM_POST_TYPE, 'objects' );
			$taxonomies = array_merge( $taxonomies, $wprm_taxonomies );
		}

		return $taxonomies;
	}

	/**
	 * Jupiter assets in footer.
	 *
	 * @since    8.2.0
	 */
	public static function jupiter_assets() {
		if ( WPRM_Settings::get( 'integration_jupiter' ) ) {
			echo '<script defer src="https://scripts.jupiter.shop/wp-recipe-maker/bundle.min.js"></script>';
		}
	}

	/**
	 * Add eMeals Walmart button after the ingredients.
	 *
	 * @since	9.4.0
	 * @param	mixed $output Current ingredients output.
	 */
	public static function emeals_after_ingredients( $output ) {
		if ( WPRM_Settings::get( 'emeals_walmart_button' ) ) {
			$output = $output . do_shortcode( '[wprm-spacer][wprm-recipe-emeals]' );
		}

		return $output;
	}
	
	/**
	 * Emeals assets in footer.
	 *
	 * @since    9.0.0
	 */
	public static function emeals_assets() {
		if ( apply_filters( 'wprm_load_emeals', false ) ) {
			// Optional partner ID.
			$partner_id = WPRM_Settings::get( 'emeals_partner_id' );

			if ( ! $partner_id ) {
				$partner_id = 'wprecipemaker';
			}
			
			echo '<script type="text/javascript" src="https://emeals.com/shopping/button/bundle.min.js" partner="' . esc_attr( $partner_id ) . '"></script>';
		}
	}

	/**
	 * Add Smart with Food button after the ingredients.
	 *
	 * @since	8.10.0
	 * @param	mixed $output Current ingredients output.
	 */
	public static function smartwithfood_after_ingredients( $output ) {
		if ( WPRM_Settings::get( 'integration_smartwithfood_token' ) && WPRM_Settings::get( 'integration_smartwithfood' ) ) {
			$output = $output . do_shortcode( '[wprm-spacer][wprm-recipe-smart-with-food]' );
		}

		return $output;
	}

	/**
	 * Smart with Food assets in footer.
	 *
	 * @since    8.10.0
	 */
	public static function smartwithfood_assets() {
		if ( apply_filters( 'wprm_load_smartwithfood', false ) ) {
			// Make sure to only load JS if a token is set up.
			if ( WPRM_Settings::get( 'integration_smartwithfood_token' ) ) {
				echo '<script src="https://unpkg.com/@smartwithfood/js-sdk@2.0.0/dist/index.min.js"></script>';
				echo '<script src="' . WPRM_URL . 'assets/js/other/smart-with-food.js?ver=' . WPRM_VERSION .'"></script>';
				echo '<script>window.wprm_smartwithfood_token = "' . esc_attr( WPRM_Settings::get( 'integration_smartwithfood_token' ) ).'";</script>';
			}
		}
	}

	/**
	 * Add Chicory button after the ingredients.
	 *
	 * @since	9.3.0
	 * @param	mixed $output Current ingredients output.
	 */
	public static function chicory_after_ingredients( $output ) {
		if ( WPRM_Settings::get( 'integration_chicory_activate' ) && WPRM_Settings::get( 'integration_chicory_shoppable_button' ) ) {
			$output = $output . do_shortcode( '[wprm-spacer][wprm-recipe-chicory]' );
		}

		return $output;
	}

	/**
	 * Chicory assets in footer.
	 *
	 * @since    9.3.0
	 */
	public static function chicory_assets() {
		if ( apply_filters( 'wprm_load_chicory', false ) ) {
			// Make sure to only load JS if they actually agree to the terms.
			if ( WPRM_Settings::get( 'integration_chicory_activate' ) ) {
				$ads_enabled = WPRM_Settings::get( 'integration_chicory_premium_ads' );
				$chicory_config = array(
					'desktop' => array(
						'pairingAdsEnabled' => $ads_enabled,
						'inlineAdsEnabled' => $ads_enabled,
						'inlineAdsRefresh' => $ads_enabled,
						'pairingAdsRefresh' => $ads_enabled,
					),
					'mobile' => array(
						'pairingAdsEnabled' => $ads_enabled,
						'inlineAdsEnabled' => $ads_enabled,
						'inlineAdsRefresh' => $ads_enabled,
						'pairingAdsRefresh' => $ads_enabled,
					),
				);

				echo '<script>window.ChicoryConfig = ' . json_encode( $chicory_config ) . ';</script>';
				echo '<script defer src="//www.chicoryapp.com/widget_v2/"></script>';
			}
		}
	}

	/**
	 * Check if and what multilingual plugin is getting used.
	 *
	 * @since	6.9.0
	 */
	public static function multilingual() {
		$plugin = false;
		$languages = array();
		$current_language = false;
		$default_language = false;

		// WPML.
		$wpml_languages = apply_filters( 'wpml_active_languages', false );

		if ( $wpml_languages ) {
			$plugin = 'wpml';

			foreach ( $wpml_languages as $code => $options ) {
				$languages[ $code ] = array(
					'value' => $code,
					'label' => $options['native_name'],
				);
			}

			$current_language = ICL_LANGUAGE_CODE;
			$default_language = apply_filters( 'wpml_default_language', false );
		}

		// Polylang.
		if ( function_exists( 'pll_home_url' ) ) {
			$plugin = 'polylang';
			$slugs = pll_languages_list( array(
				'fields' => 'slug',
			) );

			$names = pll_languages_list( array(
				'fields' => 'name',
			) );

			$languages = array();
			foreach ( $slugs as $index => $slug ) {
				$languages[ $slug ] = array(
					'value' => $slug,
					'label' => isset( $names[ $index ] ) ? $names[ $index ] : $slug,
				);
			}
		}

		// Return either false (no multilingual plugin) or an array with the plugin and activated languages.
		return ! $plugin ? false : array(
			'plugin' => $plugin,
			'languages' => $languages,
			'current' => $current_language,
			'default' => $default_language,
		);
	}

	/**
	 * Get the language of a specific post ID.
	 *
	 * @since	7.7.0
	 * @param	int $post_id Post ID to get the language for.
	 */
	public static function get_language_for( $post_id ) {
		$language = false;

		if ( $post_id ) {
			$multilingual = self::multilingual();

			if ( $multilingual ) {
				// WPML.
				if ( 'wpml' === $multilingual['plugin'] ) {
					$wpml = apply_filters( 'wpml_post_language_details', false, $post_id );

					if ( $wpml && is_array( $wpml ) ) {
						$language = $wpml['language_code'];
					}
				}

				// Polylang.
				if ( 'polylang' === $multilingual['plugin'] ) {
					$polylang = pll_get_post_language( $post_id, 'slug' );

					if ( $polylang && ! is_wp_error( $polylang ) ) {
						$language = $polylang;
					}
				}
			}
		}

		// Use false instead of null.
		if ( ! $language ) {
			$language = false;
		}

		return $language;
	}

	/**
	 * Set the language for a specific recipe ID.
	 *
	 * @since	7.7.0
	 * @param	int 	$recipe_id	Recipe ID to set the language for.
	 * @param	mixed 	$language	Language to set the recipe to.
	 */
	public static function set_language_for( $recipe_id, $language ) {
		if ( $recipe_id ) {
			$multilingual = self::multilingual();

			if ( $multilingual ) {
				// WPML.
				if ( 'wpml' === $multilingual['plugin'] ) {
					$element_type = 'post_' . WPRM_POST_TYPE;
					$translation_group_id = apply_filters( 'wpml_element_trid', NULL, $recipe_id, $element_type );

					do_action( 'wpml_set_element_language_details', array(
						'element_id' => $recipe_id,
						'trid' => $translation_group_id ? $translation_group_id : false,
						'element_type' => $element_type,
						'language_code' => $language ? $language : null,
					) );
				}
			}
		}
	}

	/**
	 * Compatibility with multilingual plugins for home URL.
	 *
	 * @since	5.7.0
	 */
	public static function get_home_url() {
		$home_url = home_url();

		// Polylang Compatibility.
		if ( function_exists( 'pll_home_url' ) ) {
			$home_url = pll_home_url();
		}

		// Add trailing slash unless there are query parameters.
		if ( false === strpos( $home_url, '?' ) ) {
			$home_url = trailingslashit( $home_url );
		}

		// Add index.php if that's used in the permalinks.
		$structure = get_option( 'permalink_structure' );
		if ( '/index.php' === substr( $structure, 0, 10 ) && false === strpos( $home_url, '?' ) ) {
			$home_url .= 'index.php/';
		}

		return $home_url;
	}
}

WPRM_Compatibility::init();
