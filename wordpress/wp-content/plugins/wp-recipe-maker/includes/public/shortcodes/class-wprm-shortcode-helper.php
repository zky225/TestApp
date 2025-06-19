<?php
/**
 * Providing helper functions to use in the recipe shortcodes.
 *
 * @link       http://bootstrapped.ventures
 * @since      6.0.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes
 */

/**
 * Providing helper functions to use in the recipe shortcodes.
 *
 * @since      6.0.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public/shortcodes
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Shortcode_Helper {
    /**
	 * Get attributes for the label container.
	 *
	 * @since	6.0.0
	 */
	public static function get_label_container_atts() {
		return array(
			'text_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => 'text_styles',
			),
            'label_container' => array(
				'default' => '0',
				'type' => 'toggle',
			),
            'style' => array(
				'default' => 'separate',
				'type' => 'dropdown',
				'options' => array(
					'inline' => 'Inline',
					'separate' => 'On its own line',
					'separated' => 'On separate lines',
					'columns' => 'Columns',
                ),
                'dependency' => array(
					'id' => 'label_container',
					'value' => '1',
				),
			),
			'icon' => array(
				'default' => '',
                'type' => 'icon',
                'dependency' => array(
					'id' => 'label_container',
					'value' => '1',
				),
			),
			'icon_color' => array(
				'default' => '#333333',
				'type' => 'color',
				'dependency' => array(
                    array(
                        'id' => 'label_container',
                        'value' => '1',
                    ),
                    array(
                        'id' => 'icon',
                        'value' => '',
                        'type' => 'inverse',
                    ),
				),
			),
			'label' => array(
				'default' => '',
                'type' => 'text',
                'dependency' => array(
					'id' => 'label_container',
					'value' => '1',
				),
			),
			'label_separator' => array(
				'default' => ' ',
                'type' => 'text',
                'dependency' => array(
                    array(
                        'id' => 'label_container',
                        'value' => '1',
                    ),
                    array(
                        'id' => 'label',
                        'value' => '',
                        'type' => 'inverse',
                    ),
				),
			),
			'label_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => 'text_styles',
				'dependency' => array(
                    array(
                        'id' => 'label_container',
                        'value' => '1',
                    ),
                    array(
                        'id' => 'label',
                        'value' => '',
                        'type' => 'inverse',
                    ),
				),
			),
			'accessibility_label' => array(
				'default' => '',
                'type' => 'text',
                'dependency' => array(
					'id' => 'label_container',
					'value' => '1',
				),
			),
			// Needs to pass trough but not actually shown.
			'table_borders' => array(
				'default' => 'top-bottom',
			),
			'table_borders_inside' => array(
				'default' => '1',
			),
			'table_border_width' => array(
				'default' => '1px',
			),
			'table_border_style' => array(
				'default' => 'dotted',
			),
			'table_border_color' => array(
				'default' => '#666666',
			),
		);
    }

    /**
	 * Get label container.
	 *
	 * @since	6.0.0
	 * @param	mixed $atts Attributes for the shortcode.
	 * @param	string $fields Field to get the container for.
     * @param	string $content Content to put inside the container.
	 */
	public static function get_label_container( $atts, $fields, $content ) {
		if ( ! (bool) $atts['label_container'] ) {
			return $content;
		}

		// Clean up $fields.
		if ( ! is_array( $fields ) ) {
			$fields = array( $fields );
		}

		foreach ( $fields as $index => $field ) {
			$fields[ $index ] = str_replace( ' ', '', $field );
		}

		// Get optional icon.
		$icon = '';
		if ( $atts['icon'] ) {
			$icon = WPRM_Icon::get( $atts['icon'], $atts['icon_color'] );

			if ( $icon ) {
				$icon_classes = array(
					'wprm-recipe-icon',
				);
				foreach ( $fields as $field ) {
					$icon_classes[] = 'wprm-recipe-' . $field . '-icon';
				}

				$icon = '<span class="' . esc_attr( implode( ' ', $icon_classes ) ) . '">' . $icon . '</span> ';
			}
		}

		// Check for accessibility label.
		$accessibility_label = '';
		if ( $atts['accessibility_label'] ) {
			$accessibility_label = '<span class="sr-only screen-reader-text wprm-screen-reader-text">' . self::sanitize_html( $atts['accessibility_label'] ) . '</span>';
		}

		// Get optional label.
		$label = '';
		if ( $atts['label'] ) {
			$label_classes = array(
				'wprm-recipe-details-label',
				'wprm-block-text-' . $atts['label_style'],
			);
			foreach ( $fields as $field ) {
				$label_classes[] = 'wprm-recipe-' . $field . '-label';
			}

			$aria_hidden = '';
			if ( $accessibility_label ) {
				$aria_hidden = ' aria-hidden="true"';
			}

			$translated_label = WPRM_i18n::maybe_translate( $atts['label'] );
			$label = '<span class="' . esc_attr( implode( ' ', $label_classes ) ) . '"' . $aria_hidden . '>' . self::sanitize_html( $translated_label ) . self::sanitize_html( $atts['label_separator'] ) . '</span>';
		}

		// Inline style.
		$style = '';
		if ( 'table' === $atts['style'] ) {
			if ( 'none' === $atts['table_borders'] ) {
				$atts['table_border_width'] = 0;
			}

			$style .= 'border-width: ' . $atts['table_border_width'] . ';';
			$style .= 'border-style: ' . $atts['table_border_style'] . ';';
			$style .= 'border-color: ' . $atts['table_border_color'] . ';';
		}

        // Get container code.
        $classes = array(
			'wprm-recipe-block-container',
			'wprm-recipe-block-container-' . $atts['style'],
			'wprm-block-text-' . $atts['text_style'],
		);
		foreach ( $fields as $field ) {
			$classes[] = 'wprm-recipe-' . $field . '-container';
		}

		$container = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" style="' . esc_attr( $style ) . '">';
		$container .= $icon;
		$container .= $accessibility_label;
		$container .= $label;
		$container .= $content;
		$container .= '</div>';
        

		return $container;
	}

	/**
	 * Get attributes for a section.
	 *
	 * @since	6.0.0
	 */
	public static function get_section_atts() {
		return array(
			'text_style' => array(
				'default' => 'normal',
				'type' => 'dropdown',
				'options' => 'text_styles',
			),
			'header' => array(
				'default' => '',
				'type' => 'text',
			),
			'header_tag' => array(
				'default' => 'h3',
				'type' => 'dropdown',
				'options' => 'header_tags',
				'dependency' => array(
					'id' => 'header',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'header_style' => array(
				'default' => 'bold',
				'type' => 'dropdown',
				'options' => 'text_styles',
				'dependency' => array(
					'id' => 'header',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'header_align' => array(
				'default' => 'left',
				'type' => 'dropdown',
				'options' => array(
					'left' => 'Left',
					'center' => 'Center',
					'right' => 'Right',
				),
				'dependency' => array(
                    array(
                        'id' => 'header',
                        'value' => '',
                        'type' => 'inverse',
					),
				),
			),
			'header_decoration' => array(
				'default' => 'none',
				'type' => 'dropdown',
				'options' => array(
					'none' => 'None',
					'line' => 'Line',
					'icon' => 'Icon',
				),
				'dependency' => array(
					'id' => 'header',
					'value' => '',
					'type' => 'inverse',
				),
			),
			'header_line_color' => array(
				'default' => '#9B9B9B',
				'type' => 'color',
				'dependency' => array(
                    array(
                        'id' => 'header',
                        'value' => '',
                        'type' => 'inverse',
					),
					array(
                        'id' => 'header_decoration',
                        'value' => 'line',
                    ),
				),
			),
			'header_icon' => array(
				'default' => '',
				'type' => 'icon',
				'dependency' => array(
					array(
						'id' => 'header',
						'value' => '',
						'type' => 'inverse',
					),
					array(
						'id' => 'header_decoration',
						'value' => 'icon',
					),
				),
			),
			'header_icon_color' => array(
				'default' => '#333333',
				'type' => 'color',
				'dependency' => array(
					array(
						'id' => 'header',
						'value' => '',
						'type' => 'inverse',
					),
					array(
						'id' => 'header_decoration',
						'value' => 'icon',
					),
					array(
						'id' => 'header_icon',
						'value' => '',
						'type' => 'inverse',
					),
				),
			),
		);
	}
	
	/**
	 * Get section header to output.
	 *
	 * @since	6.0.0
	 * @param	mixed $atts Attributes for the shortcode.
	 * @param	string $field Field to get the header for.
	 * @param	string $args Optional arguments.
	 */
	public static function get_section_header( $atts, $field, $args = array() ) {
		$header = '';

		if ( $atts['header'] ) {
			$classes = array(
				'wprm-recipe-header',
				'wprm-recipe-' . $field . '-header',
				'wprm-block-text-' . $atts['header_style'],
				'wprm-align-' . $atts['header_align'],
				'wprm-header-decoration-' . $atts['header_decoration'],
			);

			// Custom inline styling.
			$style = '';

			// Add decoration before/after header.
			$before_header = '';
			$after_header = '';
			if ( 'line' === $atts['header_decoration'] ) {
				if ( 'left' === $atts['header_align'] || 'center' === $atts['header_align'] ) {
					$after_header = '<div class="wprm-decoration-line" style="border-color: ' . esc_attr( $atts['header_line_color'] ) . '"></div>';
				}
				if ( 'right' === $atts['header_align'] || 'center' === $atts['header_align'] ) {
					$before_header = '<div class="wprm-decoration-line" style="border-color: ' . esc_attr( $atts['header_line_color'] ) . '"></div>';
				}
			} elseif ( 'icon' === $atts['header_decoration'] ) {
				$icon = '';
				if ( $atts['header_icon'] ) {
					$icon = WPRM_Icon::get( $atts['header_icon'], $atts['header_icon_color'] );

					if ( $icon ) {
						$icon = '<span class="wprm-recipe-icon" aria-hidden="true">' . $icon . '</span> ';
					}
				}
				$before_header = $icon;
			}

			// Special for ingredients.
			if ( 'ingredients' === $field ) {
				if ( 'header' === $atts['unit_conversion'] && isset( $args['unit_conversion_atts'] ) ) {
					$after_header .= '&nbsp;' . WPRM_SC_Unit_Conversion::shortcode( $args['unit_conversion_atts'] );
					$classes[] = 'wprm-header-has-actions';
				}
				if ( 'header' === $atts['adjustable_servings'] && isset( $args['adjustable_servings_atts'] ) ) {
					$after_header .= '&nbsp;' . WPRM_SC_Adjustable_Servings::shortcode( $args['adjustable_servings_atts'] );
					$classes[] = 'wprm-header-has-actions';
				}
			}

			// Special for instructions.
			if ( 'instructions' === $field ) {
				if ( 'header' === $atts['media_toggle'] && isset( $args['media_toggle_atts'] ) ) {
					$after_header .= '&nbsp;' . WPRM_SC_Media_Toggle::shortcode( $args['media_toggle_atts'] );
					$classes[] = 'wprm-header-has-actions';
				}
			}

			// Header text with optional placeholders.
			$header_text = WPRM_i18n::maybe_translate( $atts['header'] );
			if ( isset( $atts['id'] ) ) {
				$recipe = WPRM_Template_Shortcodes::get_recipe( $atts['id'] );
				if ( $recipe ) {
					$header_text = $recipe->replace_placeholders( $header_text );
				}
			}

			$tag = self::sanitize_html_element( $atts['header_tag'] );
			$header .= '<' . $tag . ' class="' . esc_attr( implode( ' ', $classes ) ) . '" style="' . esc_attr( $style ) . '">' . $before_header . self::sanitize_html( $header_text ) . $after_header . '</' . $tag . '>';
		}

		return $header;
	}

	/**
	 * Get toggle switch to output.
	 *
	 * @since	7.0.0
	 * @param	mixed $atts 	Attributes for the shortcode.
	 * @param	string $args	Arguments for the output.
	 */
	public static function get_toggle_switch( $atts, $args ) {
		$output = '';

		if ( isset( $args['class'] ) ) {
			// UID for this toggle.
			$uid = isset( $args['uid'] ) ? $args['uid'] : wp_rand();

			$width = intval( $atts['switch_width'] );
			$width = $width < 20 ? 40 : $width;

			// Calculate other sizes.
			$height = ceil( $width / 2 );

			// Custom style for the slider.
			$slider_style = '';
			$slider_style .= 'width: ' . $width . 'px;';
			$slider_style .= 'height: ' . $height . 'px;';
			$slider_style .= 'margin-top: -' . ( $height / 2 ) . 'px;';
			$slider_style .= 'background-color: ' . $atts['switch_inactive'] . ';';

			if ( 'rounded' === $atts['switch_style'] ) {
				$slider_style .= 'border-radius: ' . ( $height / 2 ) . 'px;';
			}

			// Custom style for the label.
			$label_style = '';
			$label_style .= 'margin-left: ' . ( $width + 10 ) . 'px;';

			// Classes.
			$classes = array(
				'wprm-toggle-switch',
				'wprm-toggle-switch-' . $atts['switch_style'],
			);

			if ( '#333333' !== $atts['switch_active'] ) {
				$output .= '<style type="text/css">#wprm-toggle-switch-' . $uid . ' input:checked + .wprm-toggle-switch-slider { background-color: ' . esc_html( $atts['switch_active'] ) . ' !important; }</style>';
			}

			$output .= '<label id="wprm-toggle-switch-' . esc_attr( $uid ) . '" class="' . esc_attr( implode( ' ', $classes ) ) . '">';
			$output .= '<input type="checkbox" id="' . esc_attr( $args['class'] . '-' . $uid ) . '" class="' . esc_attr( $args['class'] ) . '" />';
			$output .= '<span class="wprm-toggle-switch-slider" style="' . esc_attr( $slider_style ) . '"></span>';
			
			if ( isset( $args['label'] ) ) {
				$label_classes = array(
					'wprm-toggle-switch-label',
				);

				if ( isset( $args['label_classes'] ) ) {
					$label_classes = array_merge( $label_classes, $args['label_classes'] );
				}

				$output .= '<span class="' . esc_attr( implode( ' ', $label_classes ) ) . '" style="' . esc_attr( $label_style ) . '">';
				$output .= $args['label'];
				$output .= '</span>';
			}

			$output .= '</label>';
		}

		return $output;
	}

	/**
	 * Get attributes for a toggle switch.
	 *
	 * @since	7.0.0
	 */
	public static function get_toggle_switch_atts() {
		return array(
			'switch_style' => array(
				'default' => 'rounded',
				'type' => 'dropdown',
				'options' => array(
					'square' => 'Square Toggle',
					'rounded' => 'Rounded Toggle',
				),
			),
			'switch_width' => array(
				'default' => '40',
				'type' => 'number',
			),
			'switch_inactive' => array(
				'default' => '#cccccc',
				'type' => 'color',
			),
			'switch_active' => array(
				'default' => '#333333', // Update if statement above when changing.
				'type' => 'color',
			),
		);
	}

	/**
	 * Sanitize HTML in shortcode for output.
	 *
	 * @since	8.6.0
	 */
	public static function sanitize_html( $text ) {
		if ( $text ) {
			$text = str_replace( '&quot;', '"', $text );
			$text = wp_kses_post( $text );
		}

		return $text;
	}

	/**
	 * Sanitize HTML element in shortcode for output.
	 *
	 * @since	9.2.0
	 */
	public static function sanitize_html_element( $tag ) {
		$allowed = array(
			'p' => 'p',
			'span' => 'span',
			'div' => 'div',
			'h1' => 'h1',
			'h2' => 'h2',
			'h3' => 'h3',
			'h4' => 'h4',
			'h5' => 'h5',
			'h6' => 'h6',
		);

		if ( ! isset( $allowed[ $tag ] ) ) {
			$tag = 'span';
		}

		return $tag;
	}

	/**
	 * Add inline style to image.
	 *
	 * @since	9.1.0
	 */
	public static function add_inline_style( $html, $style, $tag = 'img' ) {
		if ( $html && $style ) {
			$start_pos = stripos( $html, '<' . $tag );

			if ( false !== $start_pos ) {
				$end_pos = stripos( $html, '>', $start_pos );

				if ( false !== $end_pos ) {
					$element = substr( $html, $start_pos, $end_pos - $start_pos + 1 );

					if ( false !== stripos( $element, ' style="' ) ) {
						$element = preg_replace( '/ style="(.*?);?"/i', ' style="$1;wprm_new_style_placeholder"', $element );
						$element = str_replace( 'wprm_new_style_placeholder', esc_attr( $style ), $element );
					} else {
						$element = str_ireplace( '<' . $tag, '<' . $tag . ' style="' . esc_attr( $style ) . '"', $element );
					}

					$html = substr_replace( $html, $element, $start_pos, $end_pos - $start_pos + 1 );
				}
			}
		}

		return $html;
	}

	/**
	 * Get style to force image size.
	 *
	 * @since	9.1.0
	 */
	public static function get_force_image_size_style( $size ) {
		$style = '';

		$style .= 'width: ' . $size[0] . 'px;';
		$style .= 'max-width: 100%;';
		$style .= 'height: ' . $size[1] . 'px;';
		$style .= 'object-fit: cover;';

		return $style;
	}

	/**
	 * Get the thumbnail size they need to get.
	 *
	 * @since	9.2.0
	 */
	public static function get_thumbnail_image_size( $image_id, $size, $force_size ) {
		$thumbnail_size = $size;

		if ( is_array( $size ) && $size[0] && $size[1] && $force_size ) {
			$ratio = $size[0] / $size[1];

			$original_image = wp_get_attachment_image_src( $image_id, 'full' );

			if ( $original_image && $original_image[1] && $original_image[2] ) {
				$original_image_ratio = $original_image[1] / $original_image[2];

				if ( $ratio > $original_image_ratio ) {
					// Need at least the full width.
					$thumbnail_size = array( $size[0], 999999 );
				} else {
					// Need at least the full height.
					$thumbnail_size = array( 999999, $size[1] );
				}
			}
		}

		return $thumbnail_size;
	}
}
