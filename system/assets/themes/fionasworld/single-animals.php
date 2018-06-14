<?php
/**
 * Genesis Framework.
 * Fiona's World Animal page
 */


add_action('genesis_entry_header', 'animal_header');
add_action('genesis_entry_header', 'reregister_quickfacts', 50);
add_action('genesis_entry_content','animal_media');
remove_all_actions('genesis_after_endwhile');


function animal_header(){
    global $post,$animal_info;
    $animal_info->the_meta();
    if($animal_info->get_the_value('latin_name') != ''){
        print '<h2><i class="latin_name">'.$animal_info->get_the_value('latin_name').'</i></h2>';
    }
    print '<p class="classes">'.get_the_term_list( $post->ID, 'class', '', ', ', '' ).'</p>';
}

function reregister_quickfacts(){
    add_action('genesis_sidebar','animal_adopt_and_featured_image', 6);
    add_action('genesis_sidebar','animal_quick_facts', 6);
}

function animal_quick_facts(){
    global $post,$animal_info;
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
        '1' => 'range_map',
        'Range' => 'range',
    );
    foreach($meta_map AS $k => $v){
        if($animal_info->get_the_value($v) != ''){
            $label = is_numeric($k)?'':'<strong>'.$k.':</strong> ';
            $quickfacts[] = '<li class="animal-meta '.$v.'">'.$label.$animal_info->get_the_value($v).'</li>';
        }
    }
    if(count($quickfacts)>0){
        $qf_sb = '<h4>Quick Facts</h4>
<ul>'.implode("\n",$quickfacts).'</ul>';
        print '<div class="quick-facts">'.$qf_sb."</div>";
    }
}

function animal_media(){
    global $post,$animal_info;
    $animal_info->the_meta();
    $meta_map = array(
        'Video' => array('Embed Code' => 'video_embed'),
        'Risk Status' => array('Risk Status' => 'risk_status','Logos' => 'logos'),
    );
    if ( class_exists( 'SympleShortcodes' ) ) {
        foreach ($meta_map AS $k => $v) {
            $tab = false;
            foreach ($v AS $w => $x) {
                if ($animal_info->get_the_value($x) != '') {
                    if($w == 'Logos'){
                        $tab[$w] = get_animal_logos($x);
                    } else {
                        $tab[$w] = $animal_info->get_the_value($x);
                    }
                }
            }
            if($tab){
                $media[] = '[symple_tab title="'.$k.'"] '.implode("\n",$tab).' [/symple_tab]';
            }
        }
        if (count($media) > 0) {
            $qf_sb = apply_filters('the_content','<br />[symple_tabgroup]'.implode("\n", $media).'[/symple_tabgroup]');
            print $qf_sb;
        }
    }
}

function get_animal_logos($logos){
    if(is_array($logos)){
        foreach($logos AS $logo){
            switch ($logo){
                case 'species-at-risk':
                    $ret[] = '<img src="http://cincinnatizoo.org/wp-content/uploads/2011/01/speciesatrisk.gif" alt="Species @ Risk Image"><br />';
                    break;
                case 'species-survival-plan':
                    $ret[] = '<img src="http://cincinnatizoo.org/wp-content/uploads/2011/01/ssp.gif" alt="Species Survival Plan Image"><br />';
                    break;
            }
        }
        return implode("\n",$ret);
    }
}

function animal_adopt_and_featured_image(){
    $adopt_page = get_page_by_path('/support/adopt/');
    $link = get_post_permalink($adopt_page->ID,true);
    $img = get_stylesheet_directory_uri().'/lib/images/adopt_logo.png';
    printf('<a href="%s" class="adopt"><img src="%s"></a>',$link,$img);
    if(has_post_thumbnail()){
        global $animal_info;
        $animal_info->the_meta();
        print '<div class="featured-image">';
        print get_the_post_thumbnail();
        print '<div class="caption">'.$animal_info->get_the_value('caption').'</div>';
        print '</div>';
    }
}
// Initialize Genesis.
genesis();