<?php
/*
 * Some pre-launch tools to bring the WP up to date with preexisiting data.
 */
if(!class_exists('MSDLab_Conversion_Tools')){
    class MSDLab_Conversion_Tools{
        //properties
        private $queries;
        //constructor
        function __construct(){
            add_action('admin_menu', array(&$this,'settings_page'));
            add_action( 'wp_ajax_move_animal_meta', array(&$this,'move_animal_meta') );
            add_action( 'wp_ajax_move_foursquare_meta', array(&$this,'move_foursquare_meta') );
        }
        //methods
        function move_animal_meta(){
            global $wpdb,$post;
            //get all animal posts
            $args = array(
                'post_type' => 'animals',
                'posts_per_page' => -1,
            );
            $animals = new WP_Query($args);
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
            $meta_map2 = array('Right Sidebar' => 'sidebarintro');
            //iterate through, get meta
            if ( $animals->have_posts() ) {
                while($animals->have_posts()){
                    $animals->the_post();
                    //get meta
                    $meta = get_post_meta($post->ID);
                    $animal_information_fields = unserialize($meta['_animal_information_fields']);
                    $sidebar_content_fields = unserialize($meta['_sidebar_content_fields']);
                    //remap meta
                    if(isset($meta['Logos'])){
                        $animal_information_fields[] = '_animal_logos';
                        $oldlogos = $meta['Logos'];
                        $newlogos = array();
                        if(in_array('Species @ Risk Image',$oldlogos)){
                            $newlogos[] = 'species-at-risk';
                        }
                        if(in_array('Species Survival Plan Image',$oldlogos)){
                            $newlogos[] = 'species-survival-plan';
                        }
                        $olddata = get_post_meta($post->ID,'_animal_logos',true);
                        update_post_meta($post->ID,'_animal_logos',$newlogos,$olddata);
                    }
                    foreach ($meta_map AS $k => $v){
                        if(isset($meta[$k]) && count($meta[$k]) == 1){
                            $animal_information_fields[] = '_animal_'.$v;
                            $olddata = get_post_meta($post->ID,'_animal_'.$v,true);
                            update_post_meta($post->ID,'_animal_'.$v,$meta[$k][0],$olddata);
                        }
                    }
                    update_post_meta($post->ID,'_animal_information_fields',$animal_information_fields,unserialize($meta['_animal_information_fields']));

                    foreach ($meta_map2 AS $k => $v){
                        if(isset($meta[$k]) && count($meta[$k]) == 1){
                            $sidebar_content_fields[] = '_msdlab_sidebarbool';
                            $olddata = get_post_meta($post->ID,'_msdlab_sidebarbool',true);
                            update_post_meta($post->ID,'_msdlab_sidebarbool',true,$olddata);
                            $sidebar_content_fields[] = '_msdlab_'.$v;
                            $olddata = get_post_meta($post->ID,'_msdlab_'.$v,true);
                            update_post_meta($post->ID,'_msdlab_'.$v,$meta[$k][0],$olddata);
                        }
                    }
                    update_post_meta($post->ID,'_sidebar_content_fields',$sidebar_content_fields,$meta['_sidebar_content_fields']);
                    //report
                    print get_the_title() .' updated<br>';
                }
            }
            wp_reset_postdata();
        }

        function move_foursquare_meta(){
            global $wpdb,$post;
            //get all animal posts
            $args = array(
                'post_type' => 'page',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => '_wp_page_template',
                        'value'   => 'template-fourway.php',
                    ),
                ),
            );
            $pages = new WP_Query($args);
            //iterate through, get meta
            if ( $pages->have_posts() ) {
                while($pages->have_posts()){
                    $pages->the_post();
                    //get meta
                    $meta = get_post_meta($post->ID);
                    if(isset($meta['_msdlab_tabs'])){
                        $tabs = unserialize($meta['_msdlab_tabs'][0]);
                    }
                    //get old data
                    if(!isset($meta['_sectioned_page_fields'])) {
                        update_post_meta($post->ID,'_sectioned_page_fields',array('_msdlab_sections'));
                    }
                    //remap meta
                    foreach ($tabs AS $i => $tab){
                        $msdlab_sections[$i] = array(
                            'content-area-title' => $tab['title'],
                            'content-area-content' => $tab['content'],
                        );
                    }
                    update_post_meta($post->ID,'_msdlab_sections',$msdlab_sections,unserialize($meta['_msdlab_sections']));

                    //report
                    print '<a href="'.get_the_permalink().'">'.get_the_title() .'</a> updated<br>';

                }
            }
            wp_reset_postdata();
        }

        //utility
        function settings_page()
        {
            if ( count($_POST) > 0 && isset($_POST['csf_settings']) )
            {
                //do post stuff if needed.

            }
            add_submenu_page('tools.php',__('Convert Old Data'),__('Convert Old Data'), 'administrator', 'convert-options', array(&$this,'settings_page_content'));
        }
        function settings_page_content()
        {

            ?>
            <style>
                span.note{
                    display: block;
                    font-size: 0.9em;
                    font-style: italic;
                    color: #999999;
                }
                body{
                    background-color: transparent;
                }
                .input-table.even{background-color: rgba(0,0,0,0.1);padding: 2rem 0;}
                .input-table .description{display:none}
                .input-table li:after{content:".";display:block;clear:both;visibility:hidden;line-height:0;height:0}
                .input-table label{display:block;font-weight:bold;margin-right:1%;float:left;width:14%;text-align:right}
                .input-table label span{display:inline;font-weight:normal}
                .input-table span{color:#999;display:block}
                .input-table .input{width:85%;float:left}
                .input-table .input .half{width:48%;float:left}
                .input-table textarea,.input-table input[type='text'],.input-table select{display:inline;margin-bottom:3px;width:90%}
                .input-table .mceIframeContainer{background:#fff}
                .input-table h4{color:#999;font-size:1em;margin:15px 6px;text-transform:uppercase}
            </style>
            <script>
                jQuery(document).ready(function($) {
                    $('.move_animal_meta').click(function(){
                        var data = {
                            action: 'move_animal_meta',
                        }
                        jQuery.post(ajaxurl, data, function(response) {
                            $('.response1').html(response);
                            console.log(response);
                        });
                    });
                    $('.move_foursquare_meta').click(function(){
                        var data = {
                            action: 'move_foursquare_meta',
                        }
                        jQuery.post(ajaxurl, data, function(response) {
                            $('.response1').html(response);
                            console.log(response);
                        });
                    });
                });
            </script>
            <div class="wrap">
                <h2>Data Conversion Tools</h2>
                <dl>
                    <dt>Move animal meta:</dt>
                    <dd><button class="move_animal_meta">Go</button></dd>
                </dl>
                <dl>
                    <dt>Move foursquare meta:</dt>
                    <dd><button class="move_foursquare_meta">Go</button></dd>
                </dl>
                <div class="response1"></div>
            </div>
            <?php
        }

    }
}