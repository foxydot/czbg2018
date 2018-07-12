<?php
//force full-width
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//add taxonomy image to header
add_action('msdlab_title_area',array('MSDNewsCPT','msdlab_do_archive_banner'));

remove_all_actions('genesis_before_loop');
//mess with the loop
remove_all_actions('genesis_entry_content');
add_filter('genesis_post_title_output',array('MSDNewsCPT','msdlab_do_taxonomy_archive_block'),10,3);

add_filter('genesis_attr_entry',array('MSDNewsCPT','msdlab_block_entry_attr'));



// Initialize Genesis.
genesis();