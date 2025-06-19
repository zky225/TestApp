<?php
/**
 * Responsible for handling the find ratings tool.
 *
 * @link       http://bootstrapped.ventures
 * @since      5.6.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/admin
 */

/**
 * Responsible for handling the find ratings tool.
 *
 * @since      5.6.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/admin
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Tools_Find_Ratings {

	/**
	 * Register actions and filters.
	 *
	 * @since	5.6.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu_page' ), 20 );
		add_action( 'wp_ajax_wprm_finding_ratings', array( __CLASS__, 'ajax_finding_ratings' ) );
	}

	/**
	 * Add the tools submenu to the WPRM menu.
	 *
	 * @since	5.6.0
	 */
	public static function add_submenu_page() {
		add_submenu_page( '', __( 'Finding Ratings', 'wp-recipe-maker' ), __( 'Finding Ratings', 'wp-recipe-maker' ), WPRM_Settings::get( 'features_tools_access' ), 'wprm_finding_ratings', array( __CLASS__, 'finding_ratings' ) );
	}

	/**
	 * Get the template for the finding ratings page.
	 *
	 * @since    2.2.0
	 */
	public static function finding_ratings() {
		$args = array(
			'post_type' => WPRM_POST_TYPE,
			'post_status' => 'all',
			'posts_per_page' => -1,
			'fields' => 'ids',
		);

		$posts = get_posts( $args );

		// Make sure rating DB is on latest version.
		WPRM_Rating_Database::update_database( '0.0' );

		// Only when debugging.
		if ( WPRM_Tools_Manager::$debugging ) {
			$result = self::find_ratings( $posts ); // Input var okay.
			WPRM_Debug::log( $result );
			die();
		}

		// Handle via AJAX.
		wp_localize_script( 'wprm-admin', 'wprm_tools', array(
			'action' => 'finding_ratings',
			'posts' => $posts,
			'args' => array(),
		));

		require_once( WPRM_DIR . 'templates/admin/menu/tools/finding-ratings.php' );
	}

	/**
	 * Find ratings through AJAX.
	 *
	 * @since    2.1.0
	 */
	public static function ajax_finding_ratings() {
		if ( check_ajax_referer( 'wprm', 'security', false ) ) {
			if ( current_user_can( WPRM_Settings::get( 'features_tools_access' ) ) ) {
				$posts = isset( $_POST['posts'] ) ? json_decode( wp_unslash( $_POST['posts'] ) ) : array(); // Input var okay.

				$posts_left = array();
				$posts_processed = array();

				if ( count( $posts ) > 0 ) {
					$posts_left = $posts;
					$posts_processed = array_map( 'intval', array_splice( $posts_left, 0, 10 ) );

					$result = self::find_ratings( $posts_processed );

					if ( is_wp_error( $result ) ) {
						wp_send_json_error( array(
							'redirect' => add_query_arg( array( 'sub' => 'advanced' ), admin_url( 'admin.php?page=wprm_tools' ) ),
						) );
					}
				}

				wp_send_json_success( array(
					'posts_processed' => $posts_processed,
					'posts_left' => $posts_left,
				) );
			}
		}

		wp_die();
	}

	/**
	 * Find recipes in posts to link parents.
	 *
	 * @since	2.1.0
	 * @param	array $posts IDs of posts to search.
	 */
	public static function find_ratings( $posts ) {
		foreach ( $posts as $post_id ) {
			$recipe = WPRM_Recipe_Manager::get_recipe( $post_id );

			if ( $recipe ) {
				// Get comment ratings.
				$comments = array();

				$comment_post_ids = array();

				if ( 'public' === WPRM_Settings::get( 'post_type_structure' ) && WPRM_Settings::get( 'post_type_comments' ) ) {
					$comment_post_ids[] = $post_id;
				}

				if ( $recipe->parent_post_id() ) {
					$comment_post_ids[] = $recipe->parent_post_id();
				}

				// Check for comment ratings in both recipe itself and parent post.
				if ( $comment_post_ids ) {
					$args = array(
						'post__in' => $comment_post_ids,
						'status' => array( 'all', 'trash' ),
					);
					$comments = get_comments( $args );
	
					// Check for Multi Rating ratings associated with parent post.
					if ( class_exists( 'MRP_Multi_Rating_API' ) ) {
						$mrp_ratings = MRP_Multi_Rating_API::get_rating_entries( array( 'post_id' => $recipe->parent_post_id() ) );
	
						foreach ( $mrp_ratings as $mrp_rating ) {
							if ( 'approved' === $mrp_rating['entry_status'] ) {
								$rating_value = array_sum( $mrp_rating['rating_item_values'] );
	
								if ( 0 < $rating_value && $rating_value <= 5 ) {
									$rating = array(
										'date' => $mrp_rating['entry_date'],
										'user_id' => $mrp_rating['user_id'],
										'ip' => 'mrp_rating_' . $mrp_rating['rating_entry_id'],
										'rating' => $rating_value,
									);
	
									// Check if comment rating or user rating.
									$comment_id = isset( $mrp_rating['comment_id'] ) ? intval( $mrp_rating['comment_id'] ) : false;
									if ( $comment_id ) {
										$rating['comment_id'] = $comment_id;
									} else {
										$rating['recipe_id'] = $recipe->id();
									}
	
									WPRM_Rating_Database::add_or_update_rating( $rating );
								}
							}
						}
					}
				}

				foreach ( $comments as $comment ) {
					$comment_rating = intval( get_comment_meta( $comment->comment_ID, 'wprm-comment-rating', true ) );

					if ( ! $comment_rating ) {
						// Check for EasyRecipe or WP Tasty rating.
						$comment_rating = intval( get_comment_meta( $comment->comment_ID, 'ERRating', true ) );
					}

					if ( ! $comment_rating ) {
						// Check for Cookbook rating.
						$comment_rating = intval( get_comment_meta( $comment->comment_ID, 'cookbook_comment_rating', true ) );
					}

					if ( ! $comment_rating ) {
						// Check for SRP rating.
						$comment_rating = intval( get_comment_meta( $comment->comment_ID, 'recipe_rating', true ) );
					}

					if ( ! $comment_rating ) {
						// Check for Zip Recipes rating.
						$comment_rating = intval( get_comment_meta( $comment->comment_ID, 'zrdn_post_recipe_rating', true ) );
					}

					if ( ! $comment_rating ) {
						// Check for Comment Rating Field rating.
						$crfp_ratings = get_comment_meta( $comment->comment_ID, 'crfp', true );

						if ( is_array( $crfp_ratings ) ) {
							$comment_rating = intval( reset( $crfp_ratings ) );
						}
					}
					
					if ( ! $comment_rating ) {
						// Check for WPSSO and WP Delicious rating.
						$comment_rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
					}

					if ( ! $comment_rating ) {
						// Check for WP Zoom rating.
						$comment_rating = intval( get_comment_meta( $comment->comment_ID, 'wpzoom-rcb-comment-rating', true ) );
					}

					// RecipePress Reloaded uses comment_karma field, but only use this if we're sure that plugin was used. Comment Karma could be anything.
					// if ( ! $comment_rating ) {
					// 	$comment_karma = intval( $comment->comment_karma );

					// 	if ( 0 < $comment_karma && $comment_karma <= 5 ) {
					// 		$comment_rating = $comment_karma;
					// 	}
					// }

					if ( $comment_rating && 0 < $comment_rating && $comment_rating <= 5 ) {
						$rating = array(
							'date' => $comment->comment_date,
							'comment_id' => $comment->comment_ID,
							'user_id' => $comment->user_id,
							'ip' => $comment->comment_author_IP,
							'rating' => $comment_rating,
						);

						WPRM_Rating_Database::add_or_update_rating( $rating );
					}
				}

				// Get user ratings.
				// WP-PostRatings.
				global $wpdb;
				$wp_postratings_table = $wpdb->prefix . 'ratings';
				if ( $wp_postratings_table === $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $wp_postratings_table ) ) ) {
					if ( $comment_post_ids ) {
						$postratings = $wpdb->get_results( $wpdb->prepare(
							"SELECT * FROM `%1s`
							WHERE rating_postid IN (" . implode( ', ', array_fill( 0, count( $comment_post_ids ), '%d' ) ) . ")",
							array_merge( array( $wp_postratings_table ), $comment_post_ids )
						) );

						foreach ( $postratings as $postrating ) {
							$postrating = (array) $postrating;
							$rating_value = isset( $postrating['rating_rating'] ) ? intval( $postrating['rating_rating'] ) : 0;

							if ( 0 < $rating_value && $rating_value <= 5 ) {
								$rating = array(
									'recipe_id' => $recipe->id(),
									'date' => date( 'Y-m-d H:i:s', $postrating['rating_timestamp'] ),
									'user_id' => $postrating['rating_userid'],
									'ip' => $postrating['rating_ip'],
									'rating' => $rating_value,
								);

								WPRM_Rating_Database::add_or_update_rating( $rating );
							}
						}
					}
				}

				// SRP User Ratings.
				$srp_user_ratings = $recipe->parent_post_id() ? get_post_meta( $recipe->parent_post_id(), '_ratings', true ) : false;

				if ( $srp_user_ratings ) {
					$srp_user_ratings = json_decode( $srp_user_ratings, true );

					foreach ( $srp_user_ratings as $user_or_ip => $rating_value ) {
						if ( '' . intval( $user_or_ip ) === '' . $user_or_ip ) {
							$rating = array(
								'recipe_id' => $recipe->id(),
								'user_id' => $user_or_ip,
								'ip' => '',
								'rating' => $rating_value,
							);
						} else {
							$rating = array(
								'recipe_id' => $recipe->id(),
								'user_id' => 0,
								'ip' => $user_or_ip,
								'rating' => $rating_value,
							);
						}

						WPRM_Rating_Database::add_or_update_rating( $rating );
					}
				}

				// Cooked Pro ratings.
				$cooked_ratings = get_post_meta( $post_id, '_recipe_votes', true );

				if ( ! $cooked_ratings && $recipe->parent_post_id() ) {
					$cooked_ratings = get_post_meta( $recipe->parent_post_id(), '_recipe_votes', true );
				}

				if ( $cooked_ratings ) {
					foreach ( $cooked_ratings as $user_id => $rating_value ) {
						$rating = array(
							'recipe_id' => $recipe->id(),
							'user_id' => $user_id,
							'ip' => '',
							'rating' => $rating_value,
						);
	
						WPRM_Rating_Database::add_or_update_rating( $rating );
					}
				}

				// All In One Schema Rich Snippets plugin.
				$schema_ratings = $recipe->parent_post_id() ? get_post_meta( $recipe->parent_post_id(), 'post-rating', false ) : false;

				if ( $schema_ratings ) {
					foreach ( $schema_ratings as $schema_rating ) {
						$rating = array(
							'recipe_id' => $recipe->id(),
							'user_id' => 0,
							'ip' => $schema_rating['user_ip'],
							'rating' => intval( $schema_rating['user_rating'] ),
						);
	
						WPRM_Rating_Database::add_or_update_rating( $rating );
					}
				}

				// WPRM User Ratings.
				$user_ratings = get_post_meta( $post_id, 'wprm_user_ratings' );

				foreach ( $user_ratings as $user_rating ) {
					if ( isset( $user_rating['rating'] ) ) {
						$rating = array(
							'date' => '2000-01-01 00:00:00',
							'recipe_id' => $post_id,
							'user_id' => $user_rating['user'],
							'ip' => $user_rating['ip'],
							'rating' => $user_rating['rating'],
						);

						WPRM_Rating_Database::add_or_update_rating( $rating );
					}
				}

				// Always update recipe rating cache.
				WPRM_Rating::update_recipe_rating( $recipe->ID() );
			}
		}
	}
}

WPRM_Tools_Find_Ratings::init();
