<?php
if (!class_exists('MSDEventSupport')) {
    class MSDEventSupport
    {
        var $cpt = 'page';
        function __construct(){
            global $current_screen;
            //Actions
            add_action( 'init', array(&$this,'register_metaboxes') );
            add_action('admin_print_scripts', array(&$this,'add_admin_scripts') );

            //add cols to manage panel
            add_filter( 'manage_edit-'.$this->cpt.'_columns', array(&$this,'my_edit_columns' ));
            add_action( 'manage_'.$this->cpt.'_posts_custom_column', array(&$this,'my_manage_columns'), 10, 2 );

            add_filter( 'wp_post_revision_meta_keys', array(&$this,'add_meta_keys_to_revision') );

        }

        function add_admin_scripts(){
            global $current_screen;
            if($current_screen->post_type == $this->cpt){
                global $wp_scripts;
                wp_enqueue_script('jquery-ui-datepicker');
                // get registered script object for jquery-ui
                $ui = $wp_scripts->query('jquery-ui-core');

                // tell WordPress to load the Smoothness theme from Google CDN
                $protocol = is_ssl() ? 'https' : 'http';
                $url = "$protocol://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css";
                wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
            }
        }

        function register_metaboxes(){
            global $event_info;
            $event_info = new WPAlchemy_MetaBox(array
            (
                'id' => '_event_information',
                'title' => 'Event Info',
                'types' => array($this->cpt),
                'context' => 'side',
                'priority' => 'low',
                'template' => plugin_dir_path(dirname(__FILE__)).'/template/metabox-event.php',
                'autosave' => TRUE,
                'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
            ));
        }



        function my_edit_columns( $columns ) {

            $columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => __( 'Title' ),
                'event_start_date' => __( 'Start Date' ),
                'event_end_date' => __( 'End Date' ),
                'author' => __( 'Author' ),
                'date' => __( 'Date' )
            );

            return $columns;
        }

        function my_manage_columns( $column, $post_id ) {
            global $post,$event_info;

            switch( $column ) {
                /* If displaying the 'logo' column. */
                case 'event_start_date' :
                case 'event_end_date' :
                    $event_info->the_meta();
                    $meta = $column;
                    print $event_info->get_the_value($meta);
                    break;
                default :
                    break;
            }
        }

        function add_meta_keys_to_revision( $keys ) {
            $keys[] = 'event_start_date';
            $keys[] = 'event_end_date';
            $keys[] = 'event_blurb';
            $keys[] = 'event_recurs_boolean';
            $keys[] = 'event_recurs_frequency';
            $keys[] = 'event_recurs_period';
            $keys[] = 'event_recurs_end';
            $keys[] = '_event_information_fields';
            return $keys;
        }
    }

}