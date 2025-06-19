<?php
/**
 * Layout Settings
 *
 * @package Vilva
 */

function vilva_customize_register_layout( $wp_customize ) {
    
    $wp_customize->add_panel( 
        'layout_settings',
         array(
            'priority'    => 30,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Layout Settings', 'vilva' ),
            'description' => __( 'Change different page layout from here.', 'vilva' ),
        ) 
    );

    /** Header Layout Settings */
    $wp_customize->add_section(
        'header_layout',
        array(
            'title'    => __( 'Header Layout', 'vilva' ),
            'panel'    => 'layout_settings',
        )
    );
    /** Note */
    $wp_customize->add_setting(
        'header_layout_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Note_Control( 
            $wp_customize,
            'header_layout_text',
            array(
                'section'     => 'header_layout',
                'priority'   => 50,
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'vilva' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/vilva-pro/?utm_source=vilva&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );

   
    $wp_customize->add_setting( 
        'header_layout_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'header_layout_settings',
            array(
                'section'    => 'header_layout',
                'feat_class' => 'upg-to-pro',
                'priority'   => 50,
                'choices'    => array(
                    'one' => get_template_directory_uri() . '/images/pro/header-layout.png',
                ),
            )
        )
    );


    /** Header Layout Section Ends */

     /** Slider Layout Section */

     $wp_customize->add_section(
        'slider_layout_settings',
        array(
            'title'    => __( 'Slider Layout', 'vilva' ),
            'panel'    => 'layout_settings',
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'slider_layout_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Note_Control( 
            $wp_customize,
            'slider_layout_text',
            array(
                'section'     => 'slider_layout_settings',
                'priority'   => 30,
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'vilva' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/vilva-pro/?utm_source=vilva&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );

   
    $wp_customize->add_setting( 
        'slider_layout_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'slider_layout_settings',
            array(
                'section'    => 'slider_layout_settings',
                'feat_class' => 'upg-to-pro',
                'priority'   => 50,
                'choices'    => array(
                    'one' => get_template_directory_uri() . '/images/pro/slider-layout.png',
                ),
            )
        )
    );

    /** Slider Layout Section Ends */

    /** Home Page Layout */

    $wp_customize->add_section(
        'home_layout_settings',
        array(
            'title'    => __( 'Home Page Layout', 'vilva' ),
            'panel'    => 'layout_settings',
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'blog_page_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Note_Control( 
            $wp_customize,
            'blog_page_text',
            array(
                'section'     => 'home_layout_settings',
                'priority'   => 30,
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'vilva' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/vilva-pro/?utm_source=vilva&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );

   
    $wp_customize->add_setting( 
        'blog_page_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'blog_page_settings',
            array(
                'section'    => 'home_layout_settings',
                'feat_class' => 'upg-to-pro',
                'priority'   => 50,
                'choices'    => array(
                    'one' => get_template_directory_uri() . '/images/pro/homepage-layout.png',
                ),
            )
        )
    );

    /** Home Page Layout Ends */

    /** Archive Page Layout */

    $wp_customize->add_section(
        'archive_image_section',
        array(
            'title'    => __( 'Archive Page Layout', 'vilva' ),
            'panel'    => 'layout_settings',
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'archive_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Note_Control( 
            $wp_customize,
            'archive_text',
            array(
                'section'     => 'archive_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'vilva' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/vilva-pro/?utm_source=vilva&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );

   
    $wp_customize->add_setting( 
        'archive_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'archive_settings',
            array(
                'section'    => 'archive_image_section',
                'feat_class' => 'upg-to-pro',
                'choices'    => array(
                    'one' => get_template_directory_uri() . '/images/pro/archive-layout.png',
                ),
            )
        )
    );

    /** Archive Page Layout Ends */

    /** Featured Area Layout */

    $wp_customize->add_section(
        'feat_area_image_section',
        array(
            'title'    => __( 'Featured Area Layout', 'vilva' ),
            'panel'    => 'layout_settings',
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'feat_area_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Note_Control( 
            $wp_customize,
            'feat_area_text',
            array(
                'section'     => 'feat_area_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'vilva' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/vilva-pro/?utm_source=vilva&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );

   
    $wp_customize->add_setting( 
        'feat_area_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'feat_area_settings',
            array(
                'section'    => 'feat_area_image_section',
                'feat_class' => 'upg-to-pro',
                'choices'    => array(
                    'one' => get_template_directory_uri() . '/images/pro/featured-area.png',
                ),
            )
        )
    );

    /** Featured Area Layout Ends */

    /** Single Post Layout */

    $wp_customize->add_section(
        'single_layout_image_section',
        array(
            'title'    => __( 'Single Post Layout', 'vilva' ),
            'panel'    => 'layout_settings',
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'single_layout_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Note_Control( 
            $wp_customize,
            'single_layout_text',
            array(
                'section'     => 'single_layout_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'vilva' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/vilva-pro/?utm_source=vilva&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );

   
    $wp_customize->add_setting( 
        'single_layout_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'single_layout_settings',
            array(
                'section'    => 'single_layout_image_section',
                'feat_class' => 'upg-to-pro',
                'choices'    => array(
                    'one' => get_template_directory_uri() . '/images/pro/single-layout.png',
                ),
            )
        )
    );

    /** Single Post Layout Ends */

    /** Pagination Settings */

    $wp_customize->add_section(
        'pagination_image_section',
        array(
            'title'    => __( 'Pagination Settings', 'vilva' ),
            'panel'    => 'layout_settings',
        )
    );

    /** Note */
    $wp_customize->add_setting(
        'pagination_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Note_Control( 
            $wp_customize,
            'pagination_text',
            array(
                'section'     => 'pagination_image_section',
                'description' => sprintf( __( '%1$sThis feature is available in Pro version.%2$s %3$sUpgrade to Pro%4$s ', 'vilva' ),'<div class="featured-pro"><span>', '</span>', '<a href="https://blossomthemes.com/wordpress-themes/vilva-pro/?utm_source=vilva&utm_medium=customizer&utm_campaign=upgrade_to_pro" target="_blank">', '</a></div>' ),
            )
        )
    );

   
    $wp_customize->add_setting( 
        'pagination_settings', 
        array(
            'default'           => 'one',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'pagination_settings',
            array(
                'section'    => 'pagination_image_section',
                'feat_class' => 'upg-to-pro',
                'choices'    => array(
                    'one' => get_template_directory_uri() . '/images/pro/pagination.png',
                ),
            )
        )
    );

    /** Pagination Settings Ends */

    /** Home Page Layout Settings */
    $wp_customize->add_section(
        'general_layout_settings',
        array(
            'title'    => __( 'General Sidebar Layout', 'vilva' ),
            'panel'    => 'layout_settings',
        )
    );
    
    /** Page Sidebar layout */
    $wp_customize->add_setting( 
        'page_sidebar_layout', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'page_sidebar_layout',
            array(
                'section'     => 'general_layout_settings',
                'label'       => __( 'Page Sidebar Layout', 'vilva' ),
                'description' => __( 'This is the general sidebar layout for pages. You can override the sidebar layout for individual page in respective page.', 'vilva' ),
                'choices'     => array(
                    'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.jpg' ),
                    'centered'      => esc_url( get_template_directory_uri() . '/images/1cc.jpg' ),
                    'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.jpg' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.jpg' ),
                )
            )
        )
    );
    
    /** Post Sidebar layout */
    $wp_customize->add_setting( 
        'post_sidebar_layout', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'post_sidebar_layout',
            array(
                'section'     => 'general_layout_settings',
                'label'       => __( 'Post Sidebar Layout', 'vilva' ),
                'description' => __( 'This is the general sidebar layout for posts & custom post. You can override the sidebar layout for individual post in respective post.', 'vilva' ),
                'choices'     => array(
                    'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.jpg' ),
                    'centered'      => esc_url( get_template_directory_uri() . '/images/1cc.jpg' ),
                    'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.jpg' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.jpg' ),
                )
            )
        )
    );
    
    /** Post Sidebar layout */
    $wp_customize->add_setting( 
        'layout_style', 
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'vilva_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Radio_Image_Control(
            $wp_customize,
            'layout_style',
            array(
                'section'     => 'general_layout_settings',
                'label'       => __( 'Default Sidebar Layout', 'vilva' ),
                'description' => __( 'This is the general sidebar layout for whole site.', 'vilva' ),
                'choices'     => array(
                    'no-sidebar'    => esc_url( get_template_directory_uri() . '/images/1c.jpg' ),
                    'left-sidebar'  => esc_url( get_template_directory_uri() . '/images/2cl.jpg' ),
                    'right-sidebar' => esc_url( get_template_directory_uri() . '/images/2cr.jpg' ),
                )
            )
        )
    );
}
add_action( 'customize_register', 'vilva_customize_register_layout' );