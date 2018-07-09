<?php
if (!class_exists('AnimalCPT')) {
    class AnimalCPT {
        //Properties
        var $cpt = 'animals';
        //Methods
        /**
         * PHP 4 Compatible Constructor
         */
        public function MSDNewsCPT(){$this->__construct();}

        /**
         * PHP 5 Constructor
         */
        function __construct(){
            global $current_screen;
            //Actions
            add_action( 'init', array(&$this,'register_taxonomies') );
            add_action( 'init', array(&$this,'register_cpt') );
            add_action( 'init', array(&$this,'register_metaboxes') );
            //add_action('admin_head', array(&$this,'plugin_header'));
            add_action('admin_print_scripts', array(&$this,'add_admin_scripts') );
            add_action('admin_print_styles', array(&$this,'add_admin_styles') );
            add_action('admin_footer',array(&$this,'info_footer_hook') );
            // important: note the priority of 99, the js needs to be placed after tinymce loads
            add_action('admin_print_footer_scripts',array(&$this,'print_footer_scripts'),99);

            //Filters

            //Shortcodes
            add_shortcode('animals',array(&$this,'animals_shortcode_handler'));

            //add cols to manage panel
            add_filter( 'manage_edit-'.$this->cpt.'_columns', array(&$this,'my_edit_columns' ));
            add_action( 'manage_'.$this->cpt.'_posts_custom_column', array(&$this,'my_manage_columns'), 10, 2 );
        }


        function register_taxonomies(){

            /**
             * Taxonomy: Class.
             */

            $labels = array(
                "name" => __( 'Class', '' ),
                "singular_name" => __( 'Class', '' ),
            );

            $args = array(
                "label" => __( 'Class', '' ),
                "labels" => $labels,
                "public" => true,
                "hierarchical" => 1,
                "label" => "Class",
                "show_ui" => true,
                "show_in_menu" => true,
                "show_in_nav_menus" => true,
                "query_var" => true,
                "rewrite" => array( 'slug' => 'animals/class', 'with_front' => false, ),
                "show_admin_column" => true,
                "show_in_rest" => false,
                "rest_base" => "",
                "show_in_quick_edit" => true,
            );
            register_taxonomy( "class", array($this->cpt), $args );

            /**
             * Taxonomy: Exhibit.
             */

            $labels = array(
                "name" => __( 'Habitat', '' ),
                "singular_name" => __( 'Habitat', '' ),
            );

            $args = array(
                "label" => __( 'Habitat', '' ),
                "labels" => $labels,
                "public" => true,
                "hierarchical" => 0,
                "label" => "Habitat",
                "show_ui" => true,
                "show_in_menu" => true,
                "show_in_nav_menus" => true,
                "query_var" => true,
                "rewrite" => array( 'slug' => 'animals/habitat', 'with_front' => false, ),
                "show_admin_column" => true,
                "show_in_rest" => false,
                "rest_base" => "",
                "show_in_quick_edit" => true,
            );
            register_taxonomy( "exhibit", array($this->cpt), $args );

            /**
             * Taxonomy: Conservation.  needs pulldown.
             */

            $labels = array(
                "name" => __( 'Conservation', '' ),
                "singular_name" => __( 'Conservation', '' ),
            );

            $args = array(
                "label" => __( 'Conservation', '' ),
                "labels" => $labels,
                "public" => true,
                "hierarchical" => false,
                "label" => "Conservation",
                "show_ui" => true,
                "show_in_menu" => true,
                "show_in_nav_menus" => true,
                "query_var" => true,
                "rewrite" => array( 'slug' => 'animals/conservation', 'with_front' => false, ),
                "show_admin_column" => true,
                "show_in_rest" => false,
                "rest_base" => "",
                "show_in_quick_edit" => true,
            );
            register_taxonomy( "conservation", array($this->cpt), $args );


        }

        function register_cpt() {

            $labels = array(
                'name'                  => _x( 'Animals', 'Post Type General Name', 'fionas-world' ),
                'singular_name'         => _x( 'Animal', 'Post Type Singular Name', 'fionas-world' ),
                'menu_name'             => __( 'Animals', 'fionas-world' ),
                'name_admin_bar'        => __( 'Animal', 'fionas-world' ),
                'archives'              => __( 'Animal Archives', 'fionas-world' ),
                'attributes'            => __( 'Animal Attributes', 'fionas-world' ),
                'parent_item_colon'     => __( 'Parent Animal:', 'fionas-world' ),
                'all_items'             => __( 'All Animals', 'fionas-world' ),
                'add_new_item'          => __( 'Add New Animal', 'fionas-world' ),
                'add_new'               => __( 'Add New', 'fionas-world' ),
                'new_item'              => __( 'New Animal', 'fionas-world' ),
                'edit_item'             => __( 'Edit Animal', 'fionas-world' ),
                'update_item'           => __( 'Update Animal', 'fionas-world' ),
                'view_item'             => __( 'View Animal', 'fionas-world' ),
                'view_items'            => __( 'View Animals', 'fionas-world' ),
                'search_items'          => __( 'Search Animal', 'fionas-world' ),
                'not_found'             => __( 'Not found', 'fionas-world' ),
                'not_found_in_trash'    => __( 'Not found in Trash', 'fionas-world' ),
                'featured_image'        => __( 'Featured Image', 'fionas-world' ),
                'set_featured_image'    => __( 'Set featured image', 'fionas-world' ),
                'remove_featured_image' => __( 'Remove featured image', 'fionas-world' ),
                'use_featured_image'    => __( 'Use as featured image', 'fionas-world' ),
                'insert_into_item'      => __( 'Insert into animal', 'fionas-world' ),
                'uploaded_to_this_item' => __( 'Uploaded to this animal', 'fionas-world' ),
                'items_list'            => __( 'Animals list', 'fionas-world' ),
                'items_list_navigation' => __( 'Animals list navigation', 'fionas-world' ),
                'filter_items_list'     => __( 'Filter animals list', 'fionas-world' ),
            );

            $args = array(
                'labels' => $labels,
                'hierarchical' => false,
                'description' => 'News',
                'supports' => array( 'title', 'editor', 'custom-fields', 'revisions', 'thumbnail','page-attributes', 'genesis-cpt-archives-settings' ),
                'taxonomies' => array( 'class', 'exhibit','conservation' ),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_position' => 20,

                'show_in_nav_menus' => true,
                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'has_archive' => true,
                'query_var' => true,
                'can_export' => true,
                'rewrite' => array('slug'=>'animals','with_front'=>false),
                'capability_type' => 'post',
                //'menu_icon' => plugin_dir_url(dirname(__FILE__)).'/image/paw.svg',
            );

            register_post_type( $this->cpt, $args );
        }


        function register_metaboxes(){
            global $animal_info;
            $animal_info = new WPAlchemy_MetaBox(array
            (
                'id' => '_animal_information',
                'title' => 'Animal Info',
                'types' => array($this->cpt),
                'context' => 'normal',
                'priority' => 'high',
                'template' => plugin_dir_path(dirname(__FILE__)).'/template/metabox-animals.php',
                'autosave' => TRUE,
                'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
                'prefix' => '_animal_' // defaults to NULL
            ));
        }


        function add_admin_scripts() {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
            }
        }

        function add_admin_styles() {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                wp_enqueue_style('custom_meta_css',plugin_dir_url(dirname(__FILE__)).'/css/meta.css');
            }
        }

        function print_footer_scripts()
        {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                print '<script type="text/javascript">/* <![CDATA[ */
					jQuery(function($)
					{
						var i=1;
						$(\'.customEditor textarea\').each(function(e)
						{
							var id = $(this).attr(\'id\');
			 
							if (!id)
							{
								id = \'customEditor-\' + i++;
								$(this).attr(\'id\',id);
							}
			 
							tinyMCE.execCommand(\'mceAddControl\', false, id);
			 
						});
					});
				/* ]]> */</script>';
            }
        }

        function info_footer_hook()
        {
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                ?><script type="text/javascript">
                    jQuery('#postdivrich').before(jQuery('#_contact_info_metabox'));
                </script><?php
            }
        }

        function my_edit_columns( $columns ) {

            $columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => __( 'Title' ),
                'class' => __( 'Class' ),
                'exhibit' => __( 'Exhibit' ),
                'conservation' => __( 'Conservation' ),
                'author' => __( 'Author' ),
                'date' => __( 'Date' )
            );

            return $columns;
        }

        function my_manage_columns( $column, $post_id ) {
            global $post;

            switch( $column ) {
                /* If displaying the 'logo' column. */
                case 'class' :
                case 'exhibit':
                case 'conservation':
                    $taxonomy = $column;
                    if ( $taxonomy ) {
                        $taxonomy_object = get_taxonomy( $taxonomy );
                        $terms = get_the_terms( $post->ID, $taxonomy );
                        if ( is_array( $terms ) ) {
                            $out = array();
                            foreach ( $terms as $t ) {
                                $posts_in_term_qv = array();
                                if ( 'post' != $post->post_type ) {
                                    $posts_in_term_qv['post_type'] = $post->post_type;
                                }
                                if ( $taxonomy_object->query_var ) {
                                    $posts_in_term_qv[ $taxonomy_object->query_var ] = $t->slug;
                                } else {
                                    $posts_in_term_qv['taxonomy'] = $taxonomy;
                                    $posts_in_term_qv['term'] = $t->slug;
                                }

                                $label = esc_html( sanitize_term_field( 'name', $t->name, $t->term_id, $taxonomy, 'display' ) );
                                $out[] = $this->get_edit_link( $posts_in_term_qv, $label );
                            }
                            /* translators: used between list items, there is a space after the comma */
                            echo join( __( ', ' ), $out );
                        } else {
                            echo '<span aria-hidden="true">&#8212;</span><span class="screen-reader-text">' . $taxonomy_object->labels->no_terms . '</span>';
                        }
                    }
                    break;
                default :
                    break;
            }
        }

        function get_edit_link( $args, $label, $class = '' ) {
            $url = add_query_arg( $args, 'edit.php' );

            $class_html = '';
            if ( ! empty( $class ) ) {
                $class_html = sprintf(
                    ' class="%s"',
                    esc_attr( $class )
                );
            }

            return sprintf(
                '<a href="%s"%s>%s</a>',
                esc_url( $url ),
                $class_html,
                $label
            );
        }

        function animals_shortcode_handler($atts){
            extract( shortcode_atts( array(
                'class' => 'all',
                'habitat' => 'all',
                'display' => 'class',
                'columns' => 3,
            ), $atts ) );
            switch($columns){
                case 1:
                    $css = 'col-xs-12';
                    break;
                case 2:
                    $css = 'col-sm-6 col-xs-12';
                    break;
                case 4:
                    $css = 'col-md-3 col-sm-6 col-xs-12';
                    break;
                case 3:
                default:
                    $css = 'col-md-4 col-sm-6 col-xs-12';
                    break;
            }
            switch($display){
                case 'all':
                    global $page_banner_metabox;
                    $args = array(
                        'post_type' => 'animals',
                        'orderby' => 'title',
                        'order' => 'ASC',
                    );
                    if($class != 'all'){
                        //tax query
                        $args['tax_query'][] = array(
                                'taxonomy' => 'class',
                                'field'    => 'slug',
                                'terms'    => $class,
                        );
                    }
                    if($habitat != 'all'){
                        //tax query
                        $args['tax_query'][] = array(
                            'taxonomy' => 'habitat',
                            'field'    => 'slug',
                            'terms'    => $habitat,
                        );
                    }
                    ts_data($args);
                    $posts = get_posts($args);
                    foreach ($posts AS $p){
                        $out[] = array(
                            'title' => $p->post_title,
                            'link' => get_the_permalink( $p->ID ),
                            'image' => get_the_post_thumbnail_url( $p->ID),
                        );
                    }
                    break;
                case 'exhibit':
                case 'habitat':
                $terms = get_terms( array(
                    'taxonomy' => 'exhibit',
                    'hide_empty' => false,
                    'parent'   => 0,
                ) );
                foreach ($terms AS $t){
                    $out[] = array(
                        'title' => $t->name,
                        'link' => get_term_link( $t ),
                        'image' => $this->get_term_image($t,'exhibit'),
                    );
                }
                    break;
                case 'class':
                default:
                    $terms = get_terms( array(
                        'taxonomy' => 'class',
                        'hide_empty' => false,
                        'parent'   => 0,
                    ) );
                    foreach ($terms AS $t){
                        $out[] = array(
                            'title' => $t->name,
                            'link' => get_term_link( $t ),
                            'image' => $this->get_term_image($t,'class'),
                        );
                    }
                    break;
            }
            foreach($out AS $o){
                $ret[] = '<div class="'.$css.' animal-link">
<a href="'.$o['link'].'" style="background-image:url('.$o['image'].');" class="link-block">
<h4>'.$o['title'].'</h4>
</a>
</div>';
            }
            return implode("\n",$ret);
        }

        function get_term_image($term,$taxonomy){
            $source = term_description($term->term_id, $taxonomy);
            preg_match('/<img.*? src="(.*?)".*?\/>/',$source,$matches);
            return $matches[1];
        }

        function cpt_display(){
            global $post;
            if(is_cpt($this->cpt)) {
                if (is_single()){
                    //display content here
                } else {
                    //display for aggregate here
                }
            }
        }
    } //End Class
} //End if class exists statement