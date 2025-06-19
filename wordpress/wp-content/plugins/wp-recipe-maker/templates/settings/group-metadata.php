<?php
/**
 * Template for the plugin settings structure.
 *
 * @link       http://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/templates/settings
 */

$metadata = array(
	'id' => 'metadata',
	'icon' => 'code',
	'name' => __( 'Recipe Metadata', 'wp-recipe-maker' ),
	'subGroups' => array(
		array(
			'name' => __( 'General', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'yoast_seo_integration',
					'name' => __( 'Integrate with Yoast SEO', 'wp-recipe-maker' ),
					'description' => __( 'Integrate with Yoast SEO Schema (version 11+) when enabled.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => true,
				),
				array(
					'id' => 'rank_math_integration',
					'name' => __( 'Integrate with Rank Math', 'wp-recipe-maker' ),
					'description' => __( 'Integrate with the Rank Math Schema Graph when enabled.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => true,
				),
			),
		),
		array(
			'name' => __( 'Recipe fields', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'metadata_include_ingredient_notes',
					'name' => __( 'Include Ingredient Notes', 'wp-recipe-maker' ),
					'description' => __( 'Include the ingredient notes field in the recipeIngredient metadata.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => true,
				),
				array(
					'id' => 'metadata_suitablefordiet',
					'name' => __( 'Use SuitableForDiet Metadata', 'wp-recipe-maker' ),
					'description' => __( 'Allow setting of Suitable Diets for recipes.', 'wp-recipe-maker' ),
					'documentation' => 'https://schema.org/suitableForDiet',
					'type' => 'toggle',
					'default' => false,
				),
				array(
					'id' => 'metadata_keywords_in_template',
					'name' => __( 'Show keywords in template', 'wp-recipe-maker' ),
					'description' => __( 'Show keywords in the recipe template as well as the metadata.', 'wp-recipe-maker' ),
					'documentation' => 'https://developers.google.com/search/docs/data-types/recipe',
					'type' => 'toggle',
					'default' => true,
				),
			),
		),
		array(
			'name' => __( 'Review Metadata', 'wp-recipe-maker' ),
			'description' => __( 'Reviews are written comments from actual visitors that are specifically reviewing the recipe.', 'wp-recipe-maker' ),
			'documentation' => 'https://developers.google.com/search/docs/appearance/structured-data/review-snippet#guidelines',
			'settings' => array(
				array(
					'id' => 'metadata_review_include',
					'name' => __( 'Include Review Metadata', 'wp-recipe-maker' ),
					'description' => __( 'Include review metadata as part of the recipe metadata.', 'wp-recipe-maker' ),
					'type' => 'dropdown',
					'options' => array(
						'always' => __( 'Always include, for any recipe that has comments with ratings', 'wp-recipe-maker' ),
						'featured_only' => __( 'Only output review metadata with comments that have been specifically set as "Featured Review"', 'wp-recipe-maker' ),
						'never' => __( 'Never include review metadata', 'wp-recipe-maker' ),
					),
					'default' => 'always',
				),
				array(
					'id' => 'metadata_review_append_featured',
					'name' => __( 'Append Featured Reviews', 'wp-recipe-maker' ),
					'description' => __( 'Featured Reviews will always be included, optionally appended by regular comments with a rating', 'wp-recipe-maker' ),
					'type' => 'dropdown',
					'options' => array(
						'no' => __( 'No, if there is at least 1 "Featured Review" for this recipe, do not include other comments', 'wp-recipe-maker' ),
						'yes_5' => __( 'Yes, if there are less than 5 "Featured Reviews", append with other comments', 'wp-recipe-maker' ),
						'yes_10' => __( 'Yes, if there are less than 10 "Featured Reviews", append with other comments', 'wp-recipe-maker' ),
					),
					'default' => 'no',
					'dependency' => array(
						'id' => 'metadata_review_include',
						'value' => 'always',
					),
				),
			),
		),
		array(
			'name' => __( 'Guided Recipes', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'metadata_restrict_ingredient_length',
					'name' => __( 'Restrict Ingredient Length', 'wp-recipe-maker' ),
					'description' => __( 'Try to prevent "Invalid string length" warning for ingredients by not including ingredient notes if they get too long.', 'wp-recipe-maker' ),
					'documentation' => 'https://help.bootstrapped.ventures/article/263-metadata-for-guided-recipes',
					'type' => 'toggle',
					'default' => true,
				),
				array(
					'id' => 'metadata_instruction_name',
					'name' => __( 'Instruction Name Field', 'wp-recipe-maker' ),
					'description' => __( 'How to handle the name field that Google wants for every instruction step.', 'wp-recipe-maker' ),
					'documentation' => 'https://help.bootstrapped.ventures/article/263-metadata-for-guided-recipes',
					'type' => 'dropdown',
					'options' => array(
						'ignore' => __( 'Hide and ignore name field (this will get you warnings in Google Search Console)', 'wp-recipe-maker' ),
						'reuse' => __( 'Use regular instruction text if name is not set', 'wp-recipe-maker' ),
						'strict' => __( 'Only use in metadata when explicitely set in recipe', 'wp-recipe-maker' ),
					),
					'default' => 'reuse',
				),
			),
		),
		array(
			'name' => __( 'Archive Pages', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'itemlist_metadata_archive_pages',
					'name' => __( 'Automatic ItemList Metadata', 'wp-recipe-maker' ),
					'description' => __( 'Automatically output ItemList metadata on archive pages.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => false,
				),
				array(
					'id' => 'itemlist_metadata_archive_pages_post_types',
					'name' => __( 'Post Type Behaviour', 'wp-recipe-maker' ),
					'description' => __( 'Handle archive pages for all post types or just the recipe post type.', 'wp-recipe-maker' ),
					'type' => 'dropdown',
					'options' => array(
						'wprm' => __( 'Only include metadata for the WPRM recipe post type (prevents searching the content)', 'wp-recipe-maker' ),
						'all' => __( 'Search all post types for recipes inside of the post content to include in the metadata', 'wp-recipe-maker' ),
					),
					'default' => 'all',
					'dependency' => array(
						'id' => 'itemlist_metadata_archive_pages',
						'value' => true,
					),
				),
			),
		),
		array(
			'name' => __( 'Video Metadata', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'metadata_youtube_agree_terms',
					'name' => __( 'Agree to YouTube Terms of Service', 'wp-recipe-maker' ),
					'description' => __( 'When enabled, the plugin will automatically retrieve the video details to include in the recipe metadata through the YouTube Data API. By enabling you agree to be bound by the YouTube Terms of Service:', 'wp-recipe-maker' ),
					'documentation' => 'https://www.youtube.com/t/terms',
					'type' => 'toggle',
					'default' => true,
				),
				array(
					'name' => __( 'Google Privacy Policy', 'wp-recipe-maker' ),
					'description' => __( 'Whenever you add a YouTube embed as the recipe video, the plugin will use the YouTube Data API to retrieve the video details. These details will be stored to use in the recipe metadata and refreshed on a weekly basis (or whenever you update the recipe).', 'wp-recipe-maker' ),
					'type' => 'button',
					'button' => __( 'Read the Google Privacy Policy', 'wp-recipe-maker' ),
					'link' => 'http://www.google.com/policies/privacy',
					'dependency' => array(
						'id' => 'metadata_youtube_agree_terms',
						'value' => true,
					),
				),
				array(
					'id' => 'metadata_youtube_api_key',
					'name' => __( 'Personal YouTube Data API key', 'wp-recipe-maker' ),
					'description' => __( 'Optionally set your own API key for retrieving the YouTube video metadata. Leave the setting blank to use the default shared key.', 'wp-recipe-maker' ),
					'documentation' => 'https://help.bootstrapped.ventures/article/260-setting-your-own-youtube-data-api-key',
					'type' => 'text',
					'default' => '',
				),
			),
		),
		array(
			'name' => __( 'Advanced', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'metadata_location',
					'name' => __( 'Output Recipe Metadata', 'wp-recipe-maker' ),
					'description' => __( 'Use "Next to recipe in HTML body element" when your recipe is not part of the post content but placed elsewhere using custom code.', 'wp-recipe-maker' ),
					'type' => 'dropdown',
					'options' => array(
						'head' => __( 'In HTML head element', 'wp-recipe-maker' ),
						'recipe' => __( 'Next to recipe in HTML body element', 'wp-recipe-maker' ),
					),
					'default' => 'head',
				),
				array(
					'id' => 'metadata_only_show_for_first_recipe',
					'name' => __( 'Only show metadata for first recipe', 'wp-recipe-maker' ),
					'description' => __( 'When enabled, only the metadata for the very first food recipe on the page well get added.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => false,
				),
				array(
					'id' => 'metadata_force_mediavine_video_output',
					'name' => __( 'Force Mediavine video metadata', 'wp-recipe-maker' ),
					'description' => __( 'When enabled, the Mediavine video metadata will get output as well as the recipe video metadata. This is their recommendation but results in duplicate video metadata.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => true,
				),
			),
		),
	),
);
