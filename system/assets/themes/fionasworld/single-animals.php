<?php
/**
 * Genesis Framework.
 * Fiona's World Animal page
 */

function animal_header(){
    global $post;
    $latin_name = get_post_meta($post->ID,'Latin name',true);

}

// Initialize Genesis.
genesis();