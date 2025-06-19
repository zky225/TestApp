<?php
/**
 * Calculate and store the recipe rating.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.22.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 */

/**
 * Calculate and store the recipe rating.
 *
 * @since      1.22.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Rating {

	/**
	 * Update the rating for the recipes affected by a specific comment.
	 *
	 * @since    1.22.0
	 * @param	 int $comment_id Comment ID to update the rating for.
	 */
	public static function update_recipe_rating_for_comment( $comment_id ) {
		$comment = get_comment( $comment_id );
		$post_id = $comment->comment_post_ID;

		$recipe_ids = WPRM_Recipe_Manager::get_recipe_ids_from_post( $post_id );

		if ( $recipe_ids ) {
			foreach ( $recipe_ids as $recipe_id ) {
				self::update_recipe_rating( $recipe_id );
			}
		}
	}

	/**
	 * Update the rating for a specific recipe.
	 *
	 * @since    1.22.0
	 * @param	 int $recipe_id Recipe ID to to update the rating for.
	 */
	public static function update_recipe_rating( $recipe_id ) {
		$recipe = WPRM_Recipe_Manager::get_recipe( $recipe_id );

		$recipe_rating = array(
			'count' => 0,
			'total' => 0,
			'average' => 0,
			'type' => array(
				'comment' => 0,
				'no_comment' => 0,
				'user' => 0,
			),
		);

		if ( ! $recipe ) {
			return $recipe_rating;
		}

		// Update comment ratings without text.
		$parent_post_id = $recipe->parent_post_id();
		if ( $parent_post_id ) {
			self::update_comment_ratings_no_text( $parent_post_id );
		}

		// Get ratings.
		$ratings = self::get_ratings_for( $recipe_id );

		foreach ( $ratings['ratings'] as $rating ) {
			$recipe_rating['count']++;
			$recipe_rating['total'] += intval( $rating->rating );

			if ( 0 < intval( $rating->recipe_id ) ) {
				$recipe_rating['type']['user']++;
			} else {
				if ( '1' === $rating->has_comment ) {
					$recipe_rating['type']['comment']++;
				} else {
					$recipe_rating['type']['no_comment']++;
				}
			}
		}

		// Calculate average.
		if ( $recipe_rating['count'] > 0 ) {
			$recipe_rating['average'] = ceil( $recipe_rating['total'] / $recipe_rating['count'] * 100 ) / 100;
		}

		// Update recipe rating and average (to sort by).
		update_post_meta( $recipe_id, 'wprm_rating', $recipe_rating );
		update_post_meta( $recipe_id, 'wprm_rating_average', $recipe_rating['average'] );
		update_post_meta( $recipe_id, 'wprm_rating_count', $recipe_rating['count'] );

		// Update parent post with rating data (TODO account for multiple recipes in a post).
		if ( $parent_post_id ) {
			update_post_meta( $parent_post_id, 'wprm_rating', $recipe_rating );
			update_post_meta( $parent_post_id, 'wprm_rating_average', $recipe_rating['average'] );
			update_post_meta( $parent_post_id, 'wprm_rating_count', $recipe_rating['count'] );
		}

		// Update SEO checker if this is the first rating.
		if ( 1 === $recipe_rating['count'] ) {
			WPRM_Seo_Checker::update_seo_for( $recipe_id );
		}		

		return $recipe_rating;
	}

	/**
	 * Update the comment ratings without text.
	 *
	 * @since    9.5.0
	 * @param	 int $post_id Post ID to check the comment ratings for.
	 */
	public static function update_comment_ratings_no_text( $post_id ) {
		$post_id = intval( $post_id );

		$ratings = WPRM_Rating_Database::get_ratings( array(
			'where' => 'post_id = ' . $post_id . ' AND approved = 1',
		) );

		$comment_ratings_without_text = array();
		$ratings_need_migration = array();

		foreach ( $ratings['ratings'] as $rating ) {
			// Keep track of comments without text.
			if ( is_null( $rating->has_comment ) && '0' !== $rating->comment_id ) {
				$ratings_need_migration[] = $rating;
			} else {
				if ( '0' === $rating->has_comment ) {
					$comment_ratings_without_text[] = $rating->comment_id;
				}
			}
		}

		// Check if there are comment ratings that haven't been checked for text yet.
		if ( $ratings_need_migration ) {
			$comment_ids = wp_list_pluck( $ratings_need_migration, 'comment_id' );

			$comments = get_comments( array(
				'comment__in' => $comment_ids,
			) );

			// Check which comments have text.
			$comments_status = array();

			foreach ( $comments as $comment ) {
				$comment_text = trim( $comment->comment_content );

				if ( $comment_text ) {
					$comments_status[ $comment->comment_ID ] = '1';
				} else {
					$comments_status[ $comment->comment_ID ] = '0';
				}
			}

			$update_rating_database_has_comment = array();
			$update_rating_database_no_comment = array();
			foreach ( $ratings_need_migration as $rating ) {
				if ( isset( $comments_status[ $rating->comment_id ] ) ) {
					if ( '1' === $comments_status[ $rating->comment_id ] ) {
						$update_rating_database_has_comment[] = $rating->id;
					} else {
						$update_rating_database_no_comment[] = $rating->id;
						$comment_ratings_without_text[] = $rating->comment_id;
					}
				}
			}

			global $wpdb;
			$table_name = WPRM_Rating_Database::get_table_name();

			// Update ratings in database.
			if ( $update_rating_database_has_comment ) {
				$wpdb->query( $wpdb->prepare(
					"UPDATE `%1s`
					SET has_comment = '1'
					WHERE id IN (" . implode( ', ', array_fill( 0, count( $update_rating_database_has_comment ), '%d' ) ) . ")",
					array_merge( array( $table_name ), $update_rating_database_has_comment )
				) );
			}
			if ( $update_rating_database_no_comment ) {
				$wpdb->query( $wpdb->prepare(
					"UPDATE `%1s`
					SET has_comment = '0'
					WHERE id IN (" . implode( ', ', array_fill( 0, count( $update_rating_database_no_comment ), '%d' ) ) . ")",
					array_merge( array( $table_name ), $update_rating_database_no_comment )
				) );
			}
		}

		update_post_meta( $post_id, 'wprm_rating_no_text_comments', $comment_ratings_without_text );

		return $comment_ratings_without_text;
	}

	/**
	 * Get the ratings for a specific recipe.
	 *
	 * @since    2.2.0
	 * @param	 int $recipe_id Recipe ID to to get the ratings for.
	 */
	public static function get_ratings_for( $recipe_id ) {
		$recipe = WPRM_Recipe_Manager::get_recipe( $recipe_id );

		$ratings = array(
			'total' => 0,
			'ratings' => array(),
		);
		$query_where = '';

		// Prevent error when recipe doesn't exist.
		if ( ! $recipe ) {
			return $ratings;
		}

		// Get comment ratings.
		if ( WPRM_Settings::get( 'features_comment_ratings' ) || ( WPRM_Addons::is_active( 'premium' ) && WPRM_Settings::get( 'features_user_ratings' ) ) ) {
			if ( WPRM_Migrations::is_migrated_to( 'ratings_db_post_id' ) ) {
				// Can be comment ratings both to recipe itself and its parent post.
				$post_ids = array();

				if ( 'public' === WPRM_Settings::get( 'post_type_structure' ) && WPRM_Settings::get( 'post_type_comments' ) ) {
					$post_ids[] = $recipe_id;
				}

				$parent_post_id = $recipe->parent_post_id();
				if ( $parent_post_id ) {
					$post_ids[] = $parent_post_id;
				}

				if ( $post_ids ) {
					$where_comments = 'approved = 1 AND post_id IN (' . implode( ', ', array_map( 'intval', $post_ids ) ) . ')';
					$query_where .= $query_where ? ' OR ' . $where_comments : $where_comments;
				}
			} else {
				$comments = get_approved_comments( $recipe->parent_post_id() );
				$comment_ids = array_map( 'intval', wp_list_pluck( $comments, 'comment_ID' ) );

				if ( count( $comment_ids ) ) {
					$where_comments = 'comment_id IN (' . implode( ',', $comment_ids ) . ')';
					$query_where .= $query_where ? ' OR ' . $where_comments : $where_comments;
				}
			}
		}

		// Get user ratings.
		if ( WPRM_Addons::is_active( 'premium' ) && WPRM_Settings::get( 'features_user_ratings' ) ) {
			$where_recipe = 'recipe_id = ' . intval( $recipe_id );
			$query_where .= $query_where ? ' OR ' . $where_recipe : $where_recipe;
		}

		if ( $query_where ) {
			$rating_args = array(
				'where' => $query_where,
			);
			$ratings = WPRM_Rating_Database::get_ratings( $rating_args );
		}

		return $ratings;
	}

	/**
	 * Get the ratings summary for a specific recipe.
	 *
	 * @since    5.0.0
	 * @param	 int $recipe_id Recipe ID to to get the ratings summary for.
	 */
	public static function get_ratings_summary_for( $recipe_id ) {
		$ratings = array(
			'count' => get_post_meta( $recipe_id, 'wprm_rating_count', true ),
			'average' => get_post_meta( $recipe_id, 'wprm_rating_average', true ),
			'comment_ratings' => false,
			'user_ratings' => false,
		);

		if ( WPRM_Settings::get( 'features_comment_ratings' ) || ( WPRM_Addons::is_active( 'premium' ) && WPRM_Settings::get( 'features_user_ratings' ) ) ) {
			$count = 0;
			$total = 0;

			$comment_ratings = WPRM_Comment_Rating::get_ratings_for( $recipe_id );
			foreach ( $comment_ratings as $comment_rating ) {
				$count++;
				$total += intval( $comment_rating->rating );
			}

			if ( $count ) {
				$ratings['comment_ratings'] = array(
					'count' => $count,
					'average' => ceil( $total / $count * 100 ) / 100,
				);
			}
		}

		if ( WPRM_Addons::is_active( 'premium' ) && WPRM_Settings::get( 'features_user_ratings' ) ) {
			$count = 0;
			$total = 0;

			$user_ratings = WPRMP_User_Rating::get_ratings_for( $recipe_id );
			foreach ( $user_ratings as $user_rating ) {
				$count++;
				$total += intval( $user_rating->rating );
			}

			if ( $count ) {
				$ratings['user_ratings'] = array(
					'count' => $count,
					'average' => ceil( $total / $count * 100 ) / 100,
				);
			}
		}

		return $ratings;
	}

	/**
	 * Get the formatted rating.
	 *
	 * @since    7.2.0
	 * @param	 mixed 	$rating Rating to display.
	 * @param	 int 	$decimals Decimals to use for the average.
	 */
	public static function get_formatted_rating( $rating, $decimals = 2 ) {
		$formatted = '';

		if ( $rating ) {
			$nbr_ratings = isset( $rating['count'] ) ? intval( $rating['count'] ) : 0;

			if ( 0 === $nbr_ratings ) {
				$text = WPRM_Settings::get( 'rating_details_zero' );
			} elseif ( 1 === $nbr_ratings ) {
				$text = WPRM_Settings::get( 'rating_details_one' );
			} else {
				$text = WPRM_Settings::get( 'rating_details_multiple' );				
			}

			// Show user voted text, if a user has voted.
			$user_vote = isset( $rating['user'] ) ? intval( $rating['user'] ) : 0;
			if ( 0 < $user_vote ) {
				$user_voted_text = WPRM_Settings::get( 'rating_details_user_voted' );
				$text = str_ireplace( '%not_voted%', '', $text );
				$text = str_ireplace( '%voted%', $user_voted_text, $text );
			} else {
				$user_not_voted_text = WPRM_Settings::get( 'rating_details_user_not_voted' );
				$text = str_ireplace( '%voted%', '', $text );
				$text = str_ireplace( '%not_voted%', $user_not_voted_text, $text );
			}

			// Average decimals.
			$formatted_average = WPRM_Recipe_Parser::format_quantity( $rating['average'], $decimals );
			
			// Replace placeholders.
			$text = str_ireplace( '%average%', '<span class="wprm-recipe-rating-average">' . $formatted_average . '</span>', $text );
			$text = str_ireplace( '%votes%', '<span class="wprm-recipe-rating-count">' . $nbr_ratings . '</span>', $text );
			$text = str_ireplace( '%user%', '<span class="wprm-recipe-rating-user">' . $user_vote . '</span>', $text );

			$formatted = trim( $text );
		}

		return $formatted;
	}
}
