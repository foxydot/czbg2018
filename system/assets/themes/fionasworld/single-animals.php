<?php
/**
 * Genesis Framework.
 * Fiona's World Animal page
 */

function animal_header(){
    global $post,$animal_info;
    $animal_info->the_meta();
    if($animal_info->get_the_value('latin_name') != ''){
        print '<h2><i class="latin_name">'.$animal_info->get_the_value('latin_name').'</i></h2>';
    }
    print '<p class="classes">'.get_the_term_list( $post->ID, 'class', '', ', ', '' ).'</p>';
}
add_action('genesis_entry_header','animal_header');
function animal_quick_facts(){
    global $post,$animal_info,$sidebar_content;
    $animal_info->the_meta();
    $meta_map = array(
        'Latin name' => 'latin_name',
        'Common name' => 'common_name',
        'Where' => 'where',
        'Prounciation' => 'pronunciation',
        'Length' => 'length',
        'Height' => 'height',
        'Weight' => 'weight',
        'Wingspan' => 'wingspan',
        'Ecological' => 'ecological',
        'Venomous' => 'venomous',
        'Lifespan' => 'lifespan',
        'Habitat' => 'habitat',
        'Diet' => 'diet',
        'Risk Status' => 'risk_status',
        'Action Images' => 'action_images',
        'Video Embed Code' => 'video_embed_code',
        'Caption' => 'caption',
        'Range' => 'range',
        'Range Map' => 'range_map',
    );
    foreach($meta_map AS $k => $v){
        if($animal_info->get_the_value($v) != ''){
            $sidebar_content .= '<li class="animal-meta '.$v.'"><strong>'.$k.':</strong> '.$animal_info->get_the_value($v).'</li>';
        }
    }
}
add_action('genesis_entry_header','animal_quick_facts', 4);

// Initialize Genesis.
genesis();