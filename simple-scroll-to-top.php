<?php
/*
Plugin Name: Simple Scroll To Top
Plugin URI: https://github.com/bugsplat404/wp-plugin-sstt
Description: A simple, configurable scroll-to-top button plugin for WordPress. Requires the CMB2 plugin for configuration options.
Version: 1.1
Author: Heiko
Tested up to: 6.6.2
Tested on PHP: 8.2
*/

// Checks if CMB2 Plugin is installed
if ( file_exists( WP_PLUGIN_DIR . '/cmb2/init.php' ) ) {
    require_once WP_PLUGIN_DIR . '/cmb2/init.php';
} else {
    // Error, if CMB2 is not installed
    add_action( 'admin_notices', 'sstt_cmb2_notice' );
    function sstt_cmb2_notice() {
        echo '<div class="error"><p>SSTT requires the CMB2 Plugin to work. Please install it.</p></div>';
    }
    return;
}

// Include scripts and styles
function sstt_enqueue_scripts() {
    // Register CSS and JS files
    wp_enqueue_style( 'sstt-style', plugin_dir_url( __FILE__ ) . 'css/sstt-style.css' );
    wp_enqueue_script( 'sstt-script', plugin_dir_url( __FILE__ ) . 'js/sstt-script.js', array('jquery'), null, true );

    $sstt_options = get_option( 'sstt_options' );

    /* 
	 * CSS-Settings with default values
	 * Default-values: Blue, Round, Slide-In, Bottom-Rigth
	 */
    $button_color = isset( $sstt_options['color'] ) ? $sstt_options['color'] : '#3498db';
    $hover_color = isset( $sstt_options['hover_color'] ) ? $sstt_options['hover_color'] : '#2980b9';
    $button_size = isset( $sstt_options['size'] ) ? $sstt_options['size'] : '50';
    $button_shape = isset( $sstt_options['shape'] ) ? $sstt_options['shape'] : 'circle';

    // Border Radius based on button form
    $border_radius = '50%';
    if ( $button_shape == 'square' ) {
        $border_radius = '0';
    } elseif ( $button_shape == 'rounded' ) {
        $border_radius = '10px';
    }

	// Setting up position of the button
    $position = isset( $sstt_options['position'] ) ? $sstt_options['position'] : 'right';
    $bottom_distance = isset( $sstt_options['bottom_distance'] ) ? $sstt_options['bottom_distance'] : '30';
    $side_distance = isset( $sstt_options['side_distance'] ) ? $sstt_options['side_distance'] : '30';
    $z_index = isset( $sstt_options['z_index'] ) ? $sstt_options['z_index'] : '9999';
    $hide_on_mobile = isset( $sstt_options['hide_on_mobile'] ) && $sstt_options['hide_on_mobile'] ? true : false;

    // Define custom, variable CSS-Styles
    $custom_css = "
    #scrollToTop {
        background-color: {$button_color};
        width: {$button_size}px;
        height: {$button_size}px;
        line-height: {$button_size}px;
        border-radius: {$border_radius};
        bottom: {$bottom_distance}px;
        right: {$side_distance}px;
        z-index: {$z_index};
    }
    #scrollToTop:hover {
        background-color: {$hover_color};
    }
    ";

    // Setting postion left | rigth
    if ( isset( $position ) && $position == 'left' ) {
        $custom_css .= "
        #scrollToTop {
            right: auto;
            left: {$side_distance}px;
        }
        ";
    }

    // Should the button be visible on mobile
    if ( $hide_on_mobile ) {
        $custom_css .= "
        @media only screen and (max-width: 767px) {
            #scrollToTop {
                display: none !important;
            }
        }
        ";
    }

    wp_add_inline_style( 'sstt-style', $custom_css );


    $scroll_speed = isset( $sstt_options['scroll_speed'] ) ? $sstt_options['scroll_speed'] : '800';
    $display_threshold = isset( $sstt_options['display_threshold'] ) ? $sstt_options['display_threshold'] : '100';
    $animation_effect = isset( $sstt_options['animation_effect'] ) ? $sstt_options['animation_effect'] : 'slide';

    // Pass scroll button options to JavaScript
    wp_localize_script( 'sstt-script', 'sstt_vars', array(
        'position'          => $position,
        'scroll_speed'      => $scroll_speed,
        'display_threshold' => $display_threshold,
        'animation_effect'  => $animation_effect,
        'side_distance'     => $side_distance, 
    ) );
}
add_action( 'wp_enqueue_scripts', 'sstt_enqueue_scripts' );


// Add scroll button to Footer
function sstt_add_button() {
	
    $sstt_options = get_option( 'sstt_options' );

    // Tooltip-Text, Icon-Selection default values
    $tooltip_text = isset( $sstt_options['tooltip_text'] ) ? $sstt_options['tooltip_text'] : __( 'Nach oben scrollen', 'sstt' );
    $icon = isset( $sstt_options['icon'] ) ? $sstt_options['icon'] : 'arrow_up';

    // Create HTML custom icon or use default
    $icon_html = '';
    if ( $icon == 'custom' && !empty( $sstt_options['custom_icon_id'] ) ) {
        $image = wp_get_attachment_image_src( $sstt_options['custom_icon_id'], 'full' );
        if ( $image ) {
            $icon_html = '<img src="' . esc_url( $image[0] ) . '" alt="' . esc_attr( $tooltip_text ) . '" style="width:100%; height:100%;" />';
        } else {
            $icon = 'arrow_up';
        }
    }

    // Use default icons if empty
    if ( empty( $icon_html ) ) {
        if ( $icon == 'chevron_up' ) {
            $icon_html = '<span>&#x25B2;</span>';
        } else {
            $icon_html = '<span>&uarr;</span>';
        }
    }

    echo '<div id="scrollToTop" title="' . esc_attr( $tooltip_text ) . '">' . $icon_html . '</div>';
}
add_action( 'wp_footer', 'sstt_add_button' );


/*
 * Register Admin-Menu using CMB2 plugin
 * Adding all customizable Settings for the scroll button
 */
function sstt_register_settings() {
	
	// Header Settings
    $cmb = new_cmb2_box( array(
        'id'           => 'sstt_option_metabox',
        'title'        => __( 'Scroll To Top Button Einstellungen', 'sstt' ),
        'object_types' => array( 'options-page' ),
        'option_key'   => 'sstt_options',
        'icon_url'     => 'dashicons-arrow-up',
        'menu_title'   => __( 'Simple Scroll To Top', 'sstt' ),
        'parent_slug'  => 'options-general.php',
    ) );

    // Button Position
    $cmb->add_field( array(
        'name'    => __( '📍 Position', 'sstt' ),
        'id'      => 'position',
        'type'    => 'select',
        'options' => array(
            'right' => __( 'Unten Rechts', 'sstt' ),
            'left'  => __( 'Unten Links', 'sstt' ),
        ),
        'default' => 'right',
    ) );

    // Button Color
    $cmb->add_field( array(
        'name'    => __( '🎨 Button Farbe', 'sstt' ),
        'id'      => 'color',
        'type'    => 'colorpicker',
        'default' => '#3498db',
    ) );

    // Button Hover-Color
    $cmb->add_field( array(
        'name'    => __( '✍️ Hover-Farbe', 'sstt' ),
        'id'      => 'hover_color',
        'type'    => 'colorpicker',
        'default' => '#2980b9',
    ) );

    // Button Size
    $cmb->add_field( array(
        'name'    => __( '📏 Größe (px)', 'sstt' ),
        'id'      => 'size',
        'type'    => 'text_small',
        'default' => '50',
    ) );

    // Button Form
    $cmb->add_field( array(
        'name'    => __( '⭕ Form', 'sstt' ),
        'id'      => 'shape',
        'type'    => 'select',
        'options' => array(
            'circle'  => __( '⚪Kreis', 'sstt' ),
            'square'  => __( '⬜ Quadrat', 'sstt' ),
            'rounded' => __( '🔲 Abgerundetes Rechteck', 'sstt' ),
        ),
        'default' => 'circle',
    ) );

    // Button Icon
    $cmb->add_field( array(
        'name'    => __( '🖼️ Icon', 'sstt' ),
        'id'      => 'icon',
        'type'    => 'select',
        'options' => array(
            'arrow_up'   => __( 'Pfeil nach oben', 'sstt' ),
            'chevron_up' => __( 'Chevron nach oben', 'sstt' ),
            'custom'     => __( 'Eigenes Icon 👇', 'sstt' ),
        ),
        'default' => 'arrow_up',
    ) );

    // Custom Icon, visible only if Custom Icon is selected
    $cmb->add_field( array(
        'name'         => __( 'Eigenes Icon', 'sstt' ),
        'id'           => 'custom_icon',
        'type'         => 'file',
        'options'      => array(
            'url' => false,
        ),
        'text'         => array(
            'add_upload_file_text' => __( 'Eigenes Icon hochladen', 'sstt' ),
        ),
        'preview_size' => array( 50, 50 ),
        'query_args'   => array( 'type' => 'image' ),
        'attributes'   => array(
            'data-conditional-id'     => 'icon',
            'data-conditional-value'  => 'custom',
        ),
    ) );

    // Scroll-Speed
    $cmb->add_field( array(
        'name'    => __( '🛫 Scroll-Geschwindigkeit (ms)', 'sstt' ),
        'id'      => 'scroll_speed',
        'type'    => 'text_small',
        'default' => '800',
    ) );

    // Display threshold 
    $cmb->add_field( array(
        'name'    => __( '👁️ Anzeigeschwelle (px)', 'sstt' ),
        'id'      => 'display_threshold',
        'type'    => 'text_small',
        'default' => '100',
    ) );

    // Space to Bottom
    $cmb->add_field( array(
        'name'    => __( '↕️ Abstand vom unteren Rand (px)', 'sstt' ),
        'id'      => 'bottom_distance',
        'type'    => 'text_small',
        'default' => '30',
    ) );

    // Space to Side
    $cmb->add_field( array(
        'name'    => __( '↔️ Abstand vom seitlichen Rand (px)', 'sstt' ),
        'id'      => 'side_distance',
        'type'    => 'text_small',
        'default' => '30',
    ) );

    // Tooltip-Text
    $cmb->add_field( array(
        'name'    => __( '📝 Tooltip-Text', 'sstt' ),
        'id'      => 'tooltip_text',
        'type'    => 'text',
        'default' => __( 'Nach oben scrollen', 'sstt' ),
    ) );

    // Animationeffect
    $cmb->add_field( array(
        'name'    => __( '💥 Animationseffekt', 'sstt' ),
        'id'      => 'animation_effect',
        'type'    => 'select',
        'options' => array(
            'slide'   => __( 'Hinein-/Herausschieben', 'sstt' ),
            'fade'    => __( 'Ein-/Ausblenden', 'sstt' ),
            'none'    => __( 'Keine Animation', 'sstt' ),
        ),
        'default' => 'slide',
    ) );

    // Display on Mobile Devices
    $cmb->add_field( array(
        'name' => __( '📱 Auf mobilen Geräten ausblenden', 'sstt' ),
        'id'   => 'hide_on_mobile',
        'type' => 'checkbox',
    ) );

    // Z-Index
    $cmb->add_field( array(
        'name'    => __( '🗂️ Z-Index', 'sstt' ),
        'id'      => 'z_index',
        'type'    => 'text_small',
        'default' => '9999',
    ) );
}

add_action( 'cmb2_admin_init', 'sstt_register_settings' );
?>
