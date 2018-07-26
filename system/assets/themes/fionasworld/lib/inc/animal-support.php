<?php
if(!class_exists('MSDLab_Animal_Support')){
    class MSDLab_Animal_Support{
        function __construct()
        {
            add_action('pre_get_posts', array($this,'change_count'));
            add_action('pre_get_posts', array($this,'alphabetize'));
            add_action('wp_enqueue_scripts',array($this,'msdlab_add_scripts'),12);
            add_action('genesis_before_while',array($this,'maybe_remove_pages'));

            add_action( 'wp_ajax_be_ajax_load_more', array($this,'be_ajax_load_more') );
            add_action( 'wp_ajax_nopriv_be_ajax_load_more', array($this,'be_ajax_load_more') );

        }
        function msdlab_do_animal_archive_banner(){
            global $msd_custom,$page_banner_metabox;
            $animal_page = get_page_by_path('animals-exhibits');
            $animal_id = $animal_page->ID;
            $page_banner_metabox->the_meta($animal_id);
            $queried_object = get_queried_object();
            $bannerimage = $page_banner_metabox->get_the_value('bannerimage');
            if (strlen($bannerimage) > 0) {
                $background = ' style="background-image:url(' . $bannerimage . ')"';
                $bannerclass .= ' has-background';
            }
            print '<div class="banner clearfix ' . $banneralign . ' ' . $bannerclass . '">';
            print '<div class="wrap"' . $background . '>';
            print '<div class="gradient">';
            print '<div class="bannertext">';
            print '<h1 class="archive-title">'.ucwords($queried_object->name).'</h1>';
            print '</div>';
            print '</div>';
            print '</div>';
            print '</div><hr class="clear padded">';
        }
        function msdlab_do_taxonomy_archive_banner(){
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
        function msdlab_do_taxonomy_archive_block($output, $wrap, $title){
            global $post;
            $output = preg_replace('/class="entry-title"/','class="entry-title" style="background-image:url('.get_the_post_thumbnail_url().')"',$output);
            $output = preg_replace('/<a(.*?)>/','<a $1><span>',$output);
            $output = preg_replace('/<\/a>/','</span></a>',$output);
            return $output;
        }
        function msdlab_block_entry_attr($attr){
            global $post;
            $attr['class'] .= ' col-xs-12 col-sm-6 col-md-4';
            return $attr;
        }
        function change_count($query)
        {
            if (is_admin()) return $query;
            if ($query->is_main_query() && $query->is_archive()) {
                $query->set('posts_per_page',15);
            }
        }
        function alphabetize($query)
        {
            if (is_admin()) return $query;
            if(!$query->is_main_query() && !$query->is_archive()) return $query;
            if($query->query['post_type'] != 'animals' && !isset($query->query['class']) && !isset($query->query['exhibit'])) return $query;

            $query->set('orderby','post_title');
            $query->set('order','ASC');

        }
        function switch_taxonomies()
        {
            if (is_admin()) return;
            $term = get_queried_object();
            $kiddos = get_term_children( $term->term_id, 'class' );
            if(count($kiddos) > 999){ //set high to no longer use.
                //remove loop items
                remove_action('genesis_loop','genesis_do_loop');
                //loop the children
                add_action('genesis_loop',array('MSDLab_Animal_Support','add_child_classes'));
            }
        }
        function add_child_classes(){
            $term = get_queried_object();
            $args = array(
                'parent' => $term->term_id,
                'taxonomy' => 'class',
            );
            $kiddos = get_terms($args);
            foreach ($kiddos AS $t){
                $out[] = array(
                    'title' => $t->name,
                    'link' => get_term_link( $t ),
                    'image' => AnimalCPT::get_term_image($t,'class'),
                );
            }
            foreach($out AS $o){
                $ret[] = '<div class="col-md-4 col-sm-6 col-xs-12 animal-link">
<a href="'.$o['link'].'" style="background-image:url('.$o['image'].');" class="link-block">
<h4>'.$o['title'].'</h4>
</a>
</div>';
            }
            print implode("\n",$ret);
        }
        function msdlab_add_scripts()
        {
            if($this->msdlab_is_animal_page()) {
                global $wp_query;
                $args = array(
                    'url' => admin_url('admin-ajax.php'),
                    'query' => $wp_query->query,
                );

                wp_enqueue_script('be-load-more', get_stylesheet_directory_uri() . '/lib/js/animals-jquery-min.js', array('jquery'), '1.0', true);
                wp_localize_script('be-load-more', 'beloadmore', $args);
            }
            if(is_cpt('animals') && is_single()){
                wp_enqueue_script('animal', get_stylesheet_directory_uri() . '/lib/js/animal-jquery-min.js', array('jquery'), '1.0', true);
            }
        }

        function msdlab_is_animal_page(){
            $qo = get_queried_object();
            $animal_keys = array('animals', 'class', 'exhibit', 'conservation');
            if(in_array($qo->name,$animal_keys) || in_array($qo->taxonomy,$animal_keys)){return true;}
            return false;
        }

        function maybe_remove_pages(){
            if(!$this->msdlab_is_animal_page())
                return;
            remove_all_actions('genesis_after_endwhile');
        }

        function be_ajax_load_more() {
            global $wp_query;
            $args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();
            $args['paged'] = esc_attr( $_POST['page'] );
            $args['orderby'] = 'post_title';
            $args['order'] = 'ASC';
            $args['posts_per_page'] = 15;
            $args['ajax'] = true;
            ob_start();
            $loop = new WP_Query( $args );
            if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post();
            print '<div class="col-md-4 col-sm-6 col-xs-12 animal-link">
<a href="'.get_the_permalink().'" style="background-image:url('.get_the_post_thumbnail_url().');" class="link-block">
<h4>'.get_the_title().'</h4>
</a>
</div>';
            endwhile; endif; wp_reset_postdata();
            $data = ob_get_clean();
            wp_send_json_success( $data );
            wp_die();
        }
    }
}