<?php
/**
 * Template for the plugin settings structure.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.3.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/templates/settings
 */

$recipe_roundup = array(
	'id' => 'recipeRoundup',
	'icon' => 'list',
	'name' => __( 'Recipe Roundup', 'wp-recipe-maker' ),
	'description' => __( "Use this feature for your recipe roundup posts and we'll automatically output ItemList metadata allowing you to show up as a carousel in Google.", 'wp-recipe-maker' ),
	'documentation' => 'https://help.bootstrapped.ventures/article/182-itemlist-metadata-for-recipe-roundup-posts',
	'subGroups' => array(
		array(
			'name' => __( 'Template', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'recipe_roundup_template',
					'name' => __( 'Food Recipe Roundup Template', 'wp-recipe-maker' ),
					'description' => __( 'Default roundup template to use for the food recipes on your website.', 'wp-recipe-maker' ),
					'type' => 'dropdownTemplateModern',
					'priority' => 'roundup',
					'default' => 'roundup-clean',
				),
				array(
					'id' => 'howto_recipe_roundup_template',
					'name' => __( 'How-to Instructions Roundup Template', 'wp-recipe-maker' ),
					'description' => __( 'Default roundup template to use for the how-to instructions on your website.', 'wp-recipe-maker' ),
					'type' => 'dropdownTemplateModern',
					'priority' => 'roundup',
					'default' => 'roundup-clean',
					'dependency' => array(
						'id' => 'recipe_template_show_types',
						'value' => true,
					),
				),
				array(
					'id' => 'other_recipe_roundup_template',
					'name' => __( 'Other Recipe Roundup Template', 'wp-recipe-maker' ),
					'description' => __( 'Default roundup template to use for the "other (no metadata)" recipes on your website.', 'wp-recipe-maker' ),
					'type' => 'dropdownTemplateModern',
					'priority' => 'roundup',
					'default' => 'roundup-clean',
					'dependency' => array(
						'id' => 'recipe_template_show_types',
						'value' => true,
					),
				),
				array(
					'name' => __( 'Template Editor', 'wp-recipe-maker' ),
					'documentation' => 'https://help.bootstrapped.ventures/article/53-template-editor',
					'type' => 'button',
					'button' => __( 'Open the Template Editor', 'wp-recipe-maker' ),
					'link' => admin_url( 'admin.php?page=wprm_template_editor' ),
				),
			),
		),
		array(
			'name' => __( 'External Links', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'recipe_roundup_default_nofollow',
					'name' => __( 'Nofollow Default', 'wp-recipe-maker' ),
					'description' => __( 'Default value to use for the nofollow attribute when creating a new roundup item.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => false,
				),
				array(
					'id' => 'recipe_roundup_default_newtab',
					'name' => __( 'Open in New Tab Default', 'wp-recipe-maker' ),
					'description' => __( 'Default value to use for the open in new tab attribute when creating a new roundup item.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => true,
				),
			),
		),
		array(
			'name' => __( 'Advanced', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'recipe_roundup_published_only',
					'name' => __( 'Only show published posts', 'wp-recipe-maker' ),
					'description' => __( 'The roundup item will only show up if it has the "Published" status.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => false,
				),
				array(
					'id' => 'recipe_roundup_no_metadata_when_recipe',
					'name' => __( 'No metadata when there is recipe metadata', 'wp-recipe-maker' ),
					'description' => __( 'Do not output the ItemList metadata when there is already recipe metadata on the same page.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => false,
				),
				array(
					'id' => 'recipe_roundup_internal_new_tab',
					'name' => __( 'Open internal links in a new tab', 'wp-recipe-maker' ),
					'description' => __( 'Force recipe links to your own site to open in a new tab as well.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => false,
				),
			),
		),
		array(
			'name' => __( 'Microlink API', 'wp-recipe-maker' ),
			'description' => __( 'A third party API is used to automatically retrieve the title, summary and image of an external site. Their free plan includes a limited number of requests per day. When adding lots of roundup items within 24 hours you might hit this limit and temporarily not see the fields filled in automatically anymore.', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'microlink_api_key',
					'name' => __( 'Microlink API Key', 'wp-recipe-maker' ),
					'description' => __( 'Optionally add your own microlink.io API key. Leave blank to use the free plan (limited number of requests per day).', 'wp-recipe-maker' ),
					'documentation' => 'https://microlink.io#pricing',
					'type' => 'text',
					'default' => '',
				),
			),
		),
	),
);
