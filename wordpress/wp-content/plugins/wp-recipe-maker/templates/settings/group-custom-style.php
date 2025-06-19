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

$custom_style = array(
	'id' => 'customStyle',
	'icon' => 'brush',
	'name' => __( 'Custom Style', 'wp-recipe-maker' ),
	'settings' => array(
		array(
			'id' => 'features_custom_style',
			'name' => __( 'Use Custom Styling', 'wp-recipe-maker' ),
			'description' => __( "Disable if you don't want to output inline CSS.", 'wp-recipe-maker' ) . ' ' . __( 'If you do so, styling changes will have to be made elsewhere and not from this settings page.', 'wp-recipe-maker' ),
			'type' => 'toggle',
			'default' => true,
		),
	),
	'subGroups' => array(
		array(
			'name' => __( 'CSS Code', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'recipe_css',
					'name' => __( 'Recipe CSS', 'wp-recipe-maker' ),
					'description' => __( 'This custom styling will be output on your website.', 'wp-recipe-maker' ),
					'type' => 'code',
					'code' => 'css',
					'default' => '',
					'dependency' => array(
						'id' => 'features_custom_style',
						'value' => true,
					),
				),
				array(
					'id' => 'print_css',
					'name' => __( 'Recipe Print CSS', 'wp-recipe-maker' ),
					'description' => __( 'This custom styling will be output on the recipe print page.', 'wp-recipe-maker' ),
					'type' => 'code',
					'code' => 'css',
					'default' => '',
				),
			),
		),
		array(
			'name' => __( 'Tooltips', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'tooltip_background_color',
					'name' => __( 'Background Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#333333',
				),
				array(
					'id' => 'tooltip_text_color',
					'name' => __( 'Text Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#FFFFFF',
				),
				array(
					'id' => 'tooltip_link_color',
					'name' => __( 'Link Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#FFFFFF',
				),
				array(
					'id' => 'tooltip_dropdown_styling',
					'name' => __( 'Style dropdown in tooltip', 'wp-recipe-maker' ),
					'description' => __( 'Enable to apply custom styling on any dropdowns that appear in our tooltips.', 'wp-recipe-maker' ),
					'type' => 'toggle',
					'default' => false,
				),
				array(
					'id' => 'tooltip_dropdown_background_color',
					'name' => __( 'Dropdown Background Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#333333',
					'dependency' => array(
						'id' => 'tooltip_dropdown_styling',
						'value' => true,
					),
				),
				array(
					'id' => 'tooltip_dropdown_border_color',
					'name' => __( 'Dropdown Border Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#333333',
					'dependency' => array(
						'id' => 'tooltip_dropdown_styling',
						'value' => true,
					),
				),
				array(
					'id' => 'tooltip_dropdown_text_color',
					'name' => __( 'Dropdown Text Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#FFFFFF',
					'dependency' => array(
						'id' => 'tooltip_dropdown_styling',
						'value' => true,
					),
				),
				array(
					'id' => 'tooltip_dropdown_font_size',
					'name' => __( 'Dropdown Font Size', 'wp-recipe-maker' ),
					'type' => 'number',
					'suffix' => 'px',
					'default' => '16',
					'dependency' => array(
						'id' => 'tooltip_dropdown_styling',
						'value' => true,
					),
				),
			),
			'dependency' => array(
				'id' => 'features_custom_style',
				'value' => true,
			),
		),
		array(
			'name' => __( 'Popup Modal', 'wp-recipe-maker' ),
			'description' => __( 'Some plugin features will make a modal pop up for users to interact with. With these settings you can change how that modal will appear to match the styling of your site.', 'wp-recipe-maker' ),
			'settings' => array(
				array(
					'id' => 'modal_font_size',
					'name' => __( 'Font Size', 'wp-recipe-maker' ),
					'type' => 'number',
					'suffix' => 'px',
					'default' => '16',
				),
				array(
					'id' => 'modal_background_color',
					'name' => __( 'Background Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#ffffff',
				),
				array(
					'id' => 'modal_title_color',
					'name' => __( 'Title Text Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#000000',
				),
				array(
					'id' => 'modal_content_color',
					'name' => __( 'Content Text Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#444444',
				),
				array(
					'id' => 'modal_button_background_color',
					'name' => __( 'Button Background Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#444444',
				),
				array(
					'id' => 'modal_button_text_color',
					'name' => __( 'Button Text Color', 'wp-recipe-maker' ),
					'type' => 'color',
					'default' => '#ffffff',
				),
			),
			'dependency' => array(
				'id' => 'features_custom_style',
				'value' => true,
			),
		),
	),
);
