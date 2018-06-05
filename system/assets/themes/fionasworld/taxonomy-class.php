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
    print '</div>';
}
//mess with the loop

// Initialize Genesis.
genesis();