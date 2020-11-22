<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'astra-theme-css','astra-menu-animation' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION


// Add Excerpts to Pages
add_post_type_support( 'page', 'excerpt' );

add_filter( 'the_content', 'p2p_filter_the_content', 1 ); 
function p2p_filter_the_content( $content ) {
    if ( is_page('Plan An Event') && in_the_loop() && is_main_query() ) {
        $pagePermalink = get_permalink();
        $childArgs = array(
            'sort_order' => 'ASC',
            'child_of' => get_the_ID()
        );
        $children = get_pages($childArgs);        
        $html = '<ul id="steps-list">';
            foreach($children as $child) {
                $link = $pagePermalink . '/' . $child->post_name;
                $icon = get_field('icon', $child->ID);
                $icon = $icon ? $icon['sizes']['medium'] : false;
                $buttonLabel = get_field('button_label', $child->ID);
                $buttonLabel = $buttonLabel ? $buttonLabel : 'Learn More';
                // var_dump($child);
                $html .= '<div class="step">';
                    $html .= '<a href="'. $link .'" class="step-thumb-container">';
                        $html .= '<img src="' . $icon . '" alt="' . $child->post_title . '">';
                    $html .= '</a>';
                    $html .= '<div class="step-content-container">';
                        $html .= '<h3>' . $child->post_title . '</h3>';
                        $html .= '<p>' . $child->post_excerpt . '</p>';
                        $html .= '<a href="'. $link .'">';
                            $html .= '<button>' . $buttonLabel . '</button>';
                        $html .= '</a>';
                    $html .= '</div>';
                $html .= '</div>';
            }
        $html .= '</ul>';
        return $content . $html;
    } 
    return $content;
}