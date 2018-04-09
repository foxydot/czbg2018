<?php
/*
 * some media handling.
 */
//if genesis get image returns nothing, then we should select a random image out of our set of images and use that.

//add_filter('genesis_get_image','msdlab_placeholder_image', 10, 6);

function msdlab_placeholder_image($output, $args, $id, $html, $url, $src){
    return $output;
}