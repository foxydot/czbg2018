<?php

//force full-width
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//add taxonomy image to header
add_action('msdlab_title_area','msdlab_do_class_archive_banner');
function msdlab_do_class_archive_banner(){
    global $msd_custom;
    $queried_object = get_queried_object();
    $bannerimage = $msd_custom->animal_class->get_term_image($queried_object->term_id,$queried_object->taxonomy);
    if (strlen($bannerimage) > 0) {
        $background = ' style="background-image:url(' . $bannerimage . ')"';
        $bannerclass .= ' has-background';
    }
    print '<div class="banner clearfix ' . $banneralign . ' ' . $bannerclass . '">';
    print '<div class="wrap"' . $background . '>';
    print '<div class="gradient">';
    print '<div class="bannertext">';
    print '<h1 class="archive-title">'.$queried_object->name.'</h1>';
    print '</div>';
    print '</div>';
    print '</div>';
    print '</div><hr class="clear padded">';
}
remove_all_actions('genesis_before_loop');
//mess with the loop
remove_all_actions('genesis_entry_content');
add_filter('genesis_post_title_output','msdlab_do_class_archive_block',10,3);
function msdlab_do_class_archive_block($output, $wrap, $title){
    global $post;
    $output = preg_replace('/class="entry-title-link"/','class="entry-title-link" style="background-image:url('.get_the_post_thumbnail_url().')"',$output);
    return $output;
}
add_filter('genesis_attr_entry','msdlab_block_entry_attr');

function msdlab_block_entry_attr($attr){
    global $post;
        $attr['class'] .= ' col-xs-12 col-sm-6 col-md-4';
    return $attr;
}

// Initialize Genesis.
genesis();