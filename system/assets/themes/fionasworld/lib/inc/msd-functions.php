<?php
function msdlab_excerpt($content){
    global $post;
    return msdlab_get_excerpt($post->ID);
}

function msdlab_get_excerpt( $post_id, $excerpt_length = 50, $trailing_character = '&nbsp;<i class="fa fa-arrow-circle-right"></i>' ) {
    $the_post = get_post( $post_id );
    $the_excerpt = strip_tags( strip_shortcodes( $the_post->post_excerpt ) );
     
    if ( empty( $the_excerpt ) )
        $the_excerpt = strip_tags( strip_shortcodes( $the_post->post_content ) );
     
    $words = explode( ' ', $the_excerpt, $excerpt_length + 1 );
     
    if( count( $words ) > $excerpt_length )
        $words = array_slice( $words, 0, $excerpt_length );
     
    $the_excerpt = implode( ' ', $words ) . '<a href="'.get_permalink($post_id).'">'.$trailing_character.'</a>';
    return $the_excerpt;
}

/* Change Excerpt length */
function msdlab_excerpt_length( $length ) {
    return 20;
}

// add classes for various browsers
add_filter('body_class','browser_body_class');
function browser_body_class($classes) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
 
    if($is_lynx) $classes[] = 'lynx';
    elseif($is_gecko) $classes[] = 'gecko';
    elseif($is_opera) $classes[] = 'opera';
    elseif($is_NS4) $classes[] = 'ns4';
    elseif($is_safari) $classes[] = 'safari';
    elseif($is_chrome) $classes[] = 'chrome';
    elseif($is_IE) $classes[] = 'ie';
    else $classes[] = 'unknown';
 
    if($is_iphone) $classes[] = 'iphone';
    return $classes;
}

add_filter('body_class','pagename_body_class');
function pagename_body_class($classes) {
	global $post;
	if(is_page()){
		$classes[] = $post->post_name;
	}
	return $classes;
}

add_filter('body_class','section_body_class');
function section_body_class($classes) {
	global $post;
	$post_data = get_post(get_topmost_parent($post->ID));
	$classes[] = 'partition-'.$post_data->post_name;
	return $classes;
}
add_filter('body_class','category_body_class');
function category_body_class($classes) {
    global $post;
	$post_categories = wp_get_post_categories( $post->ID );
	foreach($post_categories as $c){
		$cat = get_category( $c );
		$classes[] = 'category-'.$cat->slug;
	}
    return $classes;
}

// add classes for subdomain
if(is_multisite()){
	add_filter('body_class','subdomain_body_class');
	function subdomain_body_class($classes) {
		global $subdomain;
		$site = get_current_site()->domain;
		$url = get_bloginfo('url');
		$sub = preg_replace('@http://@i','',$url);
		$sub = preg_replace('@'.$site.'@i','',$sub);
		$sub = preg_replace('@\.@i','',$sub);
		$classes[] = 'site-'.$sub;
		$subdomain = $sub;
		return $classes;
	}
}

add_action('template_redirect','set_section');
function set_section(){
	global $post, $section;
	$section = get_section();
}

function get_section(){
    global $post;
    $post_data = get_post(get_topmost_parent($post->ID));
    $section = $post_data->post_name;
    return $section;
}

function get_section_title(){
    global $post;
    $post_data = get_post(get_topmost_parent($post->ID));
    $section = $post_data->post_title;
    return $section;
}

function get_topmost_parent($post_id){
	$parent_id = get_post($post_id)->post_parent;
	if($parent_id == 0){
		$parent_id = $post_id;
	}else{
		$parent_id = get_topmost_parent($parent_id);
	}
	return $parent_id;
}

add_action('init','msd_allow_all_embeds');
function msd_allow_all_embeds(){
    global $allowedposttags;
    $allowedposttags["div"] = array(
        "class" => true,
        "name" => true,
        "id" => true,
    );
    $allowedposttags["input"] = array(
        "type" => true,
        "value" => true,
        "placeholder" => true,
        "name" => true,
        "id" => true,
    );
    $allowedposttags["iframe"] = array(
        'align'       => true,
        'frameborder' => true,
        'height'      => true,
        'width'       => true,
        'sandbox'     => true,
        'seamless'    => true,
        'scrolling'   => true,
        'srcdoc'      => true,
        'src'         => true,
        'class'       => true,
        'id'          => true,
        'style'       => true,
        'border'      => true,
    );
    $allowedposttags["object"] = array(
        'height'      => true,
        'width'       => true,
    );

    $allowedposttags["param"] = array(
        "name" => true,
        "value" => true,
    );

    $allowedposttags["embed"] = array(
        'align'       => true,
        'frameborder' => true,
        'height'      => true,
        'width'       => true,
        'sandbox'     => true,
        'seamless'    => true,
        'scrolling'   => true,
        'srcdoc'      => true,
        'src'         => true,
        'class'       => true,
        'id'          => true,
        'style'       => true,
        'border'      => true,
    );
}

/* ---------------------------------------------------------------------- */
/* Check the current post for the existence of a short code
/* ---------------------------------------------------------------------- */

if ( !function_exists('msdlab_has_shortcode') ) {

    function msdlab_has_shortcode($shortcode = '') {

        global $post;
        $post_obj = get_post( $post->ID );
        $found = false;

        if ( !$shortcode )
            return $found;
        if ( stripos( $post_obj->post_content, '[' . $shortcode ) !== false )
            $found = true;

        // return our results
        return $found;

    }
}

/**
 * Check if a post is a particular post type.
 */
if(!function_exists('is_cpt')){
	function is_cpt($cpt){
		global $post;
		$ret = get_post_type( $post ) == $cpt?TRUE:FALSE;
		return $ret;
	}
}

function remove_wpautop( $content ) { 
    $content = do_shortcode( shortcode_unautop( $content ) ); 
    $content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );
    return $content;
}

if(!function_exists('get_attachment_id_from_src')){
function get_attachment_id_from_src ($image_src) {

        global $wpdb;
        $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
        $id = $wpdb->get_var($query);
        return $id;

    }
}

add_filter( 'the_content', 'remove_empty_p', 20, 1 );
function remove_empty_p( $content ){
    // clean up p tags around block elements
    $content = preg_replace( array(
        '#<p>\s*<(div|aside|section|article|header|footer)#',
        '#</(div|aside|section|article|header|footer)>\s*</p>#',
        '#</(div|aside|section|article|header|footer)>\s*<br ?/?>#',
        '#<(div|aside|section|article|header|footer)(.*?)>\s*</p>#',
        '#<p>\s*</(div|aside|section|article|header|footer)#',
    ), array(
        '<$1',
        '</$1>',
        '</$1>',
        '<$1$2>',
        '</$1',
    ), $content );

    return preg_replace('#<p>(\s|&nbsp;)*+(<br\s*/*>)*(\s|&nbsp;)*</p>#i', '', $content);
}

add_filter( 'gform_pre_render', 'msdlab_filter_description' );
function msdlab_filter_description($form){
    $form['description'] = do_shortcode($form['description']);
        return $form;
}


add_shortcode('svg-art','msdlab_svg_art_shortcode_handler');
function msdlab_svg_art_shortcode_handler($atts){
    extract(shortcode_atts( array(
        'art' => 'bracket', //default to primary application
    ), $atts ));
    switch($art){
        case 'bracket':
            return '<?xml version="1.0" encoding="utf-8"?>
<svg version="1.1" id="bracket_art" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 47.4 214.2" style="enable-background:new 0 0 47.4 214.2;" xml:space="preserve">
<g>
	
	<path class="st1" d="M0,214.2v-4c13.9,0,19.3-4.8,19.3-17.1v-61.2c0-15,4.7-21.1,13.6-24.7c-8.7-3.6-13.6-9.8-13.6-24.7V21.1
		C19.3,8.8,13.9,4,0,4V0c16.1,0,23.3,6.5,23.3,21.1v61.5c0,15.7,5.4,19.8,16.6,22.7l7.4,1.9l-7.4,1.9c-11.5,3-16.6,7-16.6,22.7v61.2
		C23.3,207.7,16.1,214.2,0,214.2z"/>
</g>
</svg>
';
            break;
    }
}