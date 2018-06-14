<?php
//force full-width
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//add taxonomy image to header
add_action('msdlab_title_area',array('MSDLab_Animal_Support','msdlab_do_animal_archive_banner'));

remove_all_actions('genesis_before_loop');
add_action('genesis_before_loop',array('MSDLab_Animal_Support','switch_taxonomies'));
//mess with the loop
remove_all_actions('genesis_entry_content');
add_filter('genesis_post_title_output',array('MSDLab_Animal_Support','msdlab_do_taxonomy_archive_block'),10,3);

add_filter('genesis_attr_entry',array('MSDLab_Animal_Support','msdlab_block_entry_attr'));



// Initialize Genesis.
genesis();