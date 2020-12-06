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

// https://css-tricks.com/snippets/wordpress/if-page-is-parent-or-child/
function is_tree($pid) { // $pid = The ID of the page we're looking for pages underneath
	global $post; // load details about this page
	if (is_page()&&($post->post_parent==$pid||is_page($pid))) 
        return true; // we're at the page or at a sub page
	else 
        return false; // we're elsewhere
};


function astra_child_enqueue_styles_scripts() {    
    wp_enqueue_style( 'font-awesome-4', 
    '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    
    wp_register_script( 'theme-tabs', 
    get_stylesheet_directory_uri() . '/js/theme-tabs.js', '', false, true );
    
    if (is_tree(196006)) {
        wp_enqueue_script( 'theme-tabs');
    }
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles_scripts', 10 );



// Add Excerpts to Pages
add_post_type_support( 'page', 'excerpt' );

add_filter( 'the_content', 'p2p_filter_the_content', 1 ); 
function p2p_filter_the_content( $content ) {
    // steps list
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

    // step page tabs
    else if (is_tree(196006)) {
        $html .= '';
        $idx = 0;
        if( have_rows('tabs') ):
            $html .= '<div class="tabs">';
            $tabsHTML = '<nav>';
            $contentHTML = '';
            while( have_rows('tabs') ) : the_row();
                $activeClass = $idx === 0 ? 'active' : '';
                $title = get_sub_field('title');
                $desc = get_sub_field('content');
                $tabsHTML .= '<a href="#tab-'.  $title .'" class="tab-link '. $activeClass .'">'.  $title .'</a>';
                $contentHTML .= '<div id="tab-'. $title .'" class="tab-content '. $activeClass .'">';
                $contentHTML .= $desc;
                if( have_rows('rows') ):
                    while( have_rows('rows') ) : the_row();
                        $contentHTML .= '<div class="wp-block-columns">';
                        if( have_rows('columns') ):
                            while( have_rows('columns') ) : the_row();
                                $colContent = get_sub_field('column_content');
                                $contentHTML .= '<div class="wp-block-column">';
                                $contentHTML .= $colContent;
                                $contentHTML .= '</div>';
                            endwhile;
                        endif;
                        $contentHTML .= '</div>'; // /row
                    endwhile;
                endif;
                $contentHTML .= '</div>'; // .tab-content
                $idx++;
            endwhile;
            $tabsHTML .= '</nav>';
            $html .= $tabsHTML . $contentHTML;
            $html .= '</div>'; // .tabs
        endif;
        return $content . $html;
    }
    return $content;
}