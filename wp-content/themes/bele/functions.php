<?php

/****** Activate Menus ******/
function register_my_menus() {
  register_nav_menus(
    array(
      'header-menu' => __( 'Header Menu' ),
    )
  );
}
add_action( 'init', 'register_my_menus' );

/****** include custom jQuery ******/
function enqueue_scripts() {

	wp_deregister_script('jquery');
  wp_deregister_script('bootstrap');
	wp_enqueue_script('jquery', get_template_directory_uri() .'/assets/js/jquery.min.js', array(), null, true);

}
add_action('wp_enqueue_scripts', 'enqueue_scripts');
	
/****** ADD OPTIONS TO ACF ******/
if( function_exists('acf_add_options_page') ) {	
	acf_add_options_page();	
}

/****** CHANGE Excerpt [...] ******/
function bele_excerpt_more( $more ) {
	return ' ...';
}
add_filter( 'excerpt_more', 'bele_excerpt_more' );


function add_excerpts() {
    add_post_type_support( 'page', 'excerpt' );
    add_post_type_support( 'post', 'excerpt' );
}
add_action( 'init', 'add_excerpts' );


?>