<?php
/**
 * The core plugin class.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WP_Recipe_Maker {

	/**
	 * Define any constants to be used in the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_constants() {
		define( 'WPRM_VERSION', '9.8.3' );
		define( 'WPRM_PREMIUM_VERSION_RECOMMENDED', '9.8.0' );
		define( 'WPRM_PREMIUM_VERSION_REQUIRED', '7.0.0' );
		define( 'WPRM_POST_TYPE', 'wprm_recipe' );
		define( 'WPRM_LIST_POST_TYPE', 'wprm_list' );
		define( 'WPRM_DIR', plugin_dir_path( dirname( __FILE__ ) ) );
		define( 'WPRM_URL', plugin_dir_url( dirname( __FILE__ ) ) );
	}

	/**
	 * Make sure all is set up for the plugin to load.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->define_constants();
		$this->load_dependencies();
		add_action( 'plugins_loaded', array( $this, 'wprm_init' ), 1 );
		add_filter( 'wprm_admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Init WPRM for Premium add-ons.
	 *
	 * @since    1.21.0
	 */
	public function wprm_init() {
		do_action( 'wprm_init' );
	}

	/**
	 * Load all plugin dependencies.
	 *
	 * @since    1.0.0
	 */
	private function load_dependencies() {
		// General.
		require_once( WPRM_DIR . 'includes/class-wprm-i18n.php' );

		// Priority.
		require_once( WPRM_DIR . 'includes/public/class-wprm-settings.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-debug.php' );
		require_once( WPRM_DIR . 'includes/public/shortcodes/class-wprm-shortcode-helper.php' );

		// API.
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-analytics.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-comments.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-custom-taxonomies.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-dashboard.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-equipment.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-ingredient-units.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-ingredients.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-integrations.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-list.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-manage-analytics.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-manage-changelog.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-manage-lists.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-manage-ratings.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-manage-recipes.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-manage-revisions.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-manage-taxonomies.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-manage-trash.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-modal.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-notices.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-rating.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-recipe.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-settings.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-taxonomies.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-templates.php' );
		require_once( WPRM_DIR . 'includes/public/api/class-wprm-api-utilities.php' );

		// Public.
		require_once( WPRM_DIR . 'includes/public/class-wprm-access.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-addons.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-admin-bar.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-analytics.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-analytics-database.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-analytics-csv.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-changelog-database.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-changelog-track.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-changelog.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-assets.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-blocks.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-cache.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-comment-moderation.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-comment-rating.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-comment-review.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-compatibility.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-context.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-cron.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-custom-hash.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-fallback-recipe.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-icon.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-instacart.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-list-manager.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-list-post-type.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-list-saver.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-list-shell.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-list-shortcode.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-list.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-metadata-rank-math.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-metadata-video.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-metadata.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-migrations.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-notices.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-nutrition.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-popup.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-post-type.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-preview.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-print.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-rating-database.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-rating.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-recipe-manager.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-recipe-parser.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-recipe-revisions.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-recipe-roundup.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-recipe-sanitizer.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-recipe-saver.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-recipe-shell.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-recipe.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-seo-checker.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-shortcode-other.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-shortcode-snippets.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-shortcode.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-taxonomies.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-template-editor.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-template-manager.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-template-shortcode.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-template-shortcodes.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-tooltip.php' );
		require_once( WPRM_DIR . 'includes/public/class-wprm-version.php' );

		// Deprecated.
		require_once( WPRM_DIR . 'includes/public/deprecated/class-wprm-template-helper.php' );

		// Admin.
		if ( is_admin() ) {
			// Classic Editor.
			require_once( WPRM_DIR . 'includes/admin/classic-editor/class-wprm-button.php' );
			require_once( WPRM_DIR . 'includes/admin/classic-editor/class-wprm-shortcode-preview.php' );

			// Import.
			require_once( WPRM_DIR . 'includes/admin/import/class-wprm-import.php' );

			// Menu.
			require_once( WPRM_DIR . 'includes/admin/menu/class-wprm-admin-menu-addons.php' );
			require_once( WPRM_DIR . 'includes/admin/menu/class-wprm-admin-menu-faq.php' );
			require_once( WPRM_DIR . 'includes/admin/menu/class-wprm-admin-menu-preview.php' );
			require_once( WPRM_DIR . 'includes/admin/menu/class-wprm-admin-menu.php' );

			// Reports.
			require_once( WPRM_DIR . 'includes/admin/reports/class-wprm-reports-recipe-interactions.php' );

			// Tools.
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-anonymize-ratings.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-create-lists.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-create-reviews.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-find-ingredient-units.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-find-parents.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-find-ratings.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-fix-comment-ratings.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-health-check.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-refresh-video-metadata.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-wpurp-ingredients.php' );
			require_once( WPRM_DIR . 'includes/admin/tools/class-wprm-tools-wpurp-nutrition.php' );

			require_once( WPRM_DIR . 'includes/admin/class-wprm-dashboard.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-feedback.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-health-check.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-import-helper.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-import-manager.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-marketing.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-manage-posts.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-manage.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-modal.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-privacy.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-reports-manager.php' );
			require_once( WPRM_DIR . 'includes/admin/class-wprm-tools-manager.php' );
		}
	}

	/**
	 * Admin notice to show when the required version is not met.
	 *
	 * @since    1.9.0
	 */
	public function admin_notices( $notices ) {
		if ( defined( 'WPRMP_VERSION' ) ) {
			if ( version_compare( WPRMP_VERSION, WPRM_PREMIUM_VERSION_REQUIRED ) < 0 ) {
				// Require version.
				$text = '<p>' . __( 'Please update to at least the following plugin versions:', 'wp-recipe-maker' );
				$text .= '<br/>WP Recipe Maker Premium ' . WPRM_PREMIUM_VERSION_REQUIRED . '</p>';
				$text .= '<p>';
				$text .= '<a href="https://help.bootstrapped.ventures/docs/wp-recipe-maker/updating-wp-recipe-maker/" target="_blank">' . __( 'More information on updating add-ons', 'wp-recipe-maker' ) . '</a>';
				$text .= '</p>';

				$notices[] = array(
					'id' => 'update_required_' . WPRM_PREMIUM_VERSION_REQUIRED,
					'title' => 'WP Recipe Maker Premium - ' . __( 'Update Required', 'wp-recipe-maker' ),
					'text' => $text,
					'dismissable' => false,
					'capability' => 'update_plugins',
					'location' => array( 'wprm_manage', 'plugins' ),
				);
			} else if ( version_compare( WPRMP_VERSION, WPRM_PREMIUM_VERSION_RECOMMENDED ) < 0 ) {
				// Recommended version.
				$text = '<p>' . __( 'Please update to at least the following plugin versions:', 'wp-recipe-maker' );
				$text .= '<br/>WP Recipe Maker Premium ' . WPRM_PREMIUM_VERSION_RECOMMENDED . '</p>';
				$text .= '<p>';
				$text .= '<a href="https://help.bootstrapped.ventures/docs/wp-recipe-maker/updating-wp-recipe-maker/" target="_blank">' . __( 'More information on updating add-ons', 'wp-recipe-maker' ) . '</a>';
				$text .= '</p>';

				$notices[] = array(
					'id' => 'update_recommended_' . WPRM_PREMIUM_VERSION_RECOMMENDED,
					'title' => 'WP Recipe Maker Premium - ' . __( 'Update Recommended', 'wp-recipe-maker' ),
					'text' => $text,
					'capability' => 'update_plugins',
					'location' => array( 'wprm_manage', 'plugins' ),
				);
			}
		}

		return $notices;
	}

	/**
	 * Adjust action links on the plugins page.
	 *
	 * @since	2.1.0
	 * @param	array $links Current plugin action links.
	 */
	public function plugin_action_links( $links ) {
		if ( ! WPRM_Addons::is_active( 'premium' ) ) {
			return array_merge( array( '<a href="https://bootstrapped.ventures/wp-recipe-maker/get-the-plugin/" target="_blank">Upgrade to Premium</a>' ), $links );
		} else {
			array_unshift( $links, '<span style="color: #32373c">' . __( 'Required by WP Recipe Maker Premium', 'wp-recipe-maker' ) . '</span>' );
			return $links;
		}
	}
}
