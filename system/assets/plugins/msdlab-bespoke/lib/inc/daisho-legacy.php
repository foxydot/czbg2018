<?php
//connector for code janked out of Daisho to maintain old styling.
if (!class_exists('Daisho_Legacy_Support')) {
    class Daisho_Legacy_Support
    {
        //Properties
        private $options;

        //Methods
        function __construct()
        {
            global $current_screen;
            //"Constants" setup
            //Actions
            //add_action('msdlab_title_area', array(&$this, 'do_header_buffer'),10);
            add_action( 'wp_enqueue_scripts', array(&$this,'flow_content_slider_scripts') );

            //Filters

            //Shortcodes
            // Add shortcodes for backwards compatibility
            add_shortcode( 'toggle', 'symple_toggle_shortcode' );
            add_shortcode( 'accordion_group', 'symple_accordion_main_shortcode' );
            add_shortcode( 'accordion', 'symple_accordion_section_shortcode' );
            add_shortcode( 'tabs', 'symple_tabgroup_shortcode' );
            add_shortcode( 'tab', 'symple_tab_shortcode' );

            add_shortcode( 'slide', array(&$this,'flow_superslide_slide_shortcode') );
            add_shortcode( 'flow-sidebar', array(&$this,'flow_shortcode_sidebar') );
            add_shortcode('content_block', array(&$this,'flow_content_slider') );

        }

        /**
         * SuperSlide [slide] shortcode.
         *
         * It is deprecated since Daisho 1.9.5, WordPress 3.6 and August 2013.
         * It is left here to maintain backwards compatibility. It should be removed
         * sometime in 2014 or 2015.
         *
         * @param array Shortcode attributes.
         * @param string Inner content of the shortcode.
         * @return string Slide output.
         *
         */
        function flow_superslide_slide_shortcode($atts, $content = null){
            $class = shortcode_atts( array('text_color' => '#ffffff', 'image' => '', 'image_alt' => '', 'video_vimeo' => '', 'video_youtube' => '', 'video_mp4' => '', 'video_ogg' => '', 'video_webm' => '', 'video_poster' => '', 'slide_desc' => '', 'slide_horizontal' => '', 'slide_fitscreen' => '', 'slide_noresize' => '', 'custom' => ''), $atts );

            /* Slide Description */
            if(($class['slide_desc'] != '') && ($class['slide_desc'] != '<h4></h4>')){
                $slide_desc = $class['slide_desc'];
                //$slide_desc = "data-title=\"".$slide_desc."\"";
            }else{
                $slide_desc = false;
            }

            if($content && ($content != '') && ($content != '<h4></h4>')){
                $slide_desc = $content;
                //$slide_desc = "data-title=\"".$slide_desc."\"";
            }else{
                $slide_desc = false;
            }


            if((isset($class['image_alt'])) && ($class['image_alt'] != '')){
                $image_alt = $class['image_alt'];
            }else{
                $image_alt = '';
            }

            /* Slide Text/Cursor Color */
            if($class['text_color'] == '#ffffff'){
                $text_color = 'text_white';
            }else{
                $text_color = 'text_black';
            }

            /* ------------------------------------*/
            /* -------->>> IMAGE SLIDE <<<---------*/
            /* ------------------------------------*/
            if((isset($class['image'])) && ($class['image'] != '')){
                $image = $class['image'];
                if($class['slide_horizontal'] == 'true'){ $horizontal = 'slide_horizontal '; }else{ $horizontal = ''; }
                if($class['slide_horizontal'] == 'true' || $class['slide_fitscreen'] == 'true'){ $slide_fitscreen = 'slide_fitscreen'; }else{ $slide_fitscreen = ''; }

                $the_slide = '<div class="project-slide project-slide-image wp-caption">';
                $the_slide .= '<img class="myimage" src="' . $image . '" alt="' . $image_alt . '" />';
                if($slide_desc){
                    $the_slide .= '<div class="wp-caption-text superslide-caption-text">' . $slide_desc . '</div>';
                }
                $the_slide .= '</div>';

                return $the_slide;

                /* ------------------------------------*/
                /* ----->>> HTML5 VIDEO SLIDE <<<------*/
                /* ------------------------------------*/
            }elseif(($class['video_mp4'] != '' or $class['video_ogg'] != '' or $class['video_webm'] != '')){
                $video_mp4 = $class['video_mp4'];
                $video_ogg = $class['video_ogg'];
                $video_webm = $class['video_webm'];

                if($class['video_poster'] != ''){
                    $video_poster = 'poster="'.$class['video_poster'].'"';
                }else{
                    $video_poster = "";
                }

                $the_slide = '<div class="project-slide project-slide-video">';
                $the_slide .= do_shortcode('[video mp4="'.$video_mp4.'" ogg="'.$video_ogg.'" webm="'.$video_webm.'" '.$video_poster.' preload="true"]');
                if($slide_desc){
                    $the_slide .= '<div class="wp-caption-text superslide-caption-text">' . $slide_desc . '</div>';
                }
                $the_slide .= '</div>';

                return $the_slide;

                /* ------------------------------------*/
                /* ------->>> YOUTUBE SLIDE <<<--------*/
                /* ------------------------------------*/
            }elseif($class['video_youtube'] != ''){
                $video_youtube = $class['video_youtube'];
                if($class['slide_noresize'] = 'true'){ $video_noresize = 'height="360" width="640"'; }else{ $video_noresize = 'height="100%" width="100%"'; }
                $strText = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.)+)(\/)?(\S+)?/i', 'link', $video_youtube );
                if($strText == 'link'){ $array_link_explode = explode('v=', $video_youtube); $array_link_explode = explode('&', $array_link_explode[1]); $video_youtube =$array_link_explode[0]; }
                $video_poster = $class['video_poster'];

                $the_slide = '<div class="project-slide project-slide-youtube">';
                $the_slide .= '<div class="youtube_container">';
                $the_slide .= '<iframe class="youtube-player" type="text/html" width="1120" height="660" src="http://www.youtube.com/embed/'.$video_youtube.'?wmode=opaque&amp;hd=1&amp;rel=0" frameborder="0"></iframe>';
                $the_slide .= '</div>';
                if($slide_desc){
                    $the_slide .= '<div class="wp-caption-text superslide-caption-text">' . $slide_desc . '</div>';
                }
                $the_slide .= '</div>';

                return $the_slide;

                /* ------------------------------------*/
                /* -------->>> VIMEO SLIDE <<<---------*/
                /* ------------------------------------*/
            }elseif($class['video_vimeo'] != ''){
                $video_vimeo = $class['video_vimeo'];
                if($class['slide_noresize'] = 'true'){ $video_noresize = 'height="360" width="640"'; }else{ $video_noresize = 'height="100%" width="100%"'; }
                $strText = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.)+)(\/)?(\S+)?/i', 'link', $video_vimeo );
                if($strText == 'link'){ $array_link_explode = explode('.com/', $video_vimeo); $video_vimeo = $array_link_explode[1]; }
                $video_poster = $class['video_poster'];

                $the_slide = '<div class="project-slide project-slide-vimeo">';
                $the_slide .= '<div class="youtube_container">';
                $the_slide .= '<iframe src="http://player.vimeo.com/video/'.$video_vimeo.'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff&amp;hd=1" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
                $the_slide .= '</div>';
                if($slide_desc){
                    $the_slide .= '<div class="wp-caption-text superslide-caption-text">' . $slide_desc . '</div>';
                }
                $the_slide .= '</div>';

                return $the_slide;

                /* -------------------------------------*/
                /* -------->>> CUSTOM SLIDE <<<---------*/
                /* -------------------------------------*/
            }elseif($class['custom'] != ''){
                return $class['custom'];
            }else{
                return false;
            }
        }

        function flow_content_slider($atts, $content = null) {
            $class = shortcode_atts( array('title' => '', 'icon' => '', 'image' => '', 'description' => '', 'link' => '', 'post_type' => '', 'posts_per_page' => '', 'arrows_top_position' => '120px'), $atts );

            $output = $image = $description = $icon = '';

            if($class['icon'] != ''){
                $icon = 'data-icon="' . $class['icon'] . '"';
            }

            if($class['image'] != ''){
                $image = '<img class="cb-image" src="' . $class['image'] . '" alt="" />';
            }

            if($class['description'] != ''){
                $description = '<span class="cb-description">'.$class['description'].'</span>';
            }

            $title = $class['title'];
            if($class['link'] != ''){
                $title = '<a class="cb-title-link" href="' . $class['link'] . '">' . $class['title'] . '</a>';
                if($image){
                    $image = '<a class="cb-image-link" href="' . $class['link'] . '">' . $image . '</a>';
                }
            }

            // Post Type carousel
            if($class['post_type'] != ''){
                if(empty($class['posts_per_page'])){
                    $post_per_page = -1; // -1 shows all posts
                }else{
                    $post_per_page = $class['posts_per_page'];
                }

                $do_not_show_stickies = 1; // 0 to show stickies
                $args = array(
                    'post_type' => 'news',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'posts_per_page' => $post_per_page,
                    'ignore_sticky_posts' => $do_not_show_stickies
                );
                $block_query = new WP_Query( $args );
                if( $block_query->have_posts() ) {
                    while($block_query->have_posts()){
                        $block_query->the_post();
                        $output .= '<div class="grid_4 content-block" id="post-' . get_the_ID() . '" data-arrows-top-position="' . $class['arrows_top_position'] . '">';
                        $output .= '<div class="cb-date">' . esc_html( get_the_date() ) . '</div>';
                        $output .= '<div class="cb-title cb-news-title">';
                        $output .= '<a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
                        $output .= '</div>';
                        $output .= '<div class="cb-content"><p>' . get_the_excerpt() . '</p></div>';
                        $output .= '<div style="clear: both;"></div>';
                        $output .= '</div>';
                    }
                }
                wp_reset_postdata();
            }else{
                $output = '<div class="grid_4 content-block" data-arrows-top-position="' . $class['arrows_top_position'] . '">';
                $output .= '<div class="cb-date"></div>';
                $output .= $image;
                $output .= '<div class="cb-title" ' . $icon . '>' . $title . $description . '</div>';
                $output .= '<div class="cb-content">';
                $output .= '<p>' . do_shortcode( $content ) . '</p>';
                $output .= '</div>';
                $output .= '</div>';
            }

            return $output;
        }

        function flow_content_slider_scripts() {
            wp_enqueue_script( 'iscroll', plugin_dir_url(__DIR__). '/inc/library-iscroll4/iscroll.js', array( 'jquery' ), '4.1.9', true );
            wp_enqueue_script( 'flow-content-slider-script', plugin_dir_url(__DIR__). '/inc/shortcode-content-slider/content-slider.js', array( 'jquery', 'iscroll' ), false, true );
            wp_enqueue_style( 'daisho-legacy-style', plugin_dir_url(__DIR__). '/css/daisho-legacy.css' );
            wp_enqueue_style( 'flow-content-slider-style', plugin_dir_url(__DIR__). '/inc/shortcode-content-slider/content-slider.css' );
        }

        /**
         * A shortcode that allows inserting widgets in content.
         *
         * @param array Shortcode attributes.
         * @param string Inner content of the shortcode.
         * @return string Iframe with a video.
         */
        function flow_shortcode_sidebar( $atts, $content = null ) {
            $atts = shortcode_atts( array( 'id' => '' ), $atts );
            if ( $atts['id'] != '' ) {
                $this_sidebar = '<div class="post-sidebar">';

                ob_start();
                dynamic_sidebar( apply_filters( 'flow_sidebar', $atts['id'] ) );
                $this_sidebar .= ob_get_contents();
                ob_end_clean();

                $this_sidebar .= '</div>';

                return $this_sidebar;
            }
        }
    }
}