<?php
/*
 *  shortcodes
 */
add_shortcode('latest','msdlab_latest_shortcode_handler');

function msdlab_latest_shortcode_handler($atts){
    $args = (shortcode_atts( array(
        'post_type' => 'post',
        'posts_per_page' => '1',
    ), $atts ));
    global $post;
    $my_query = new WP_Query($args);
    ob_start();
    while ( $my_query->have_posts() ) : $my_query->the_post();
        print '<article>';
        printf( '<a href="%s" title="%s" class="latest_image_wrapper alignleft">%s</a>', get_permalink(), the_title_attribute('echo=0'), genesis_get_image(array('size' => 'thumbnail')) );
        print '<div>';
        printf( '<a href="%s" title="%s" class="latest-title"><h3>%s</h3></a>', get_permalink(), the_title_attribute('echo=0'), get_the_title() );
        genesis_post_info();
        print '</div>';
        print '</article>';
    endwhile;
    $ret = ob_get_contents();
    ob_end_clean();
    return $ret;
}

function _msd_get_news_from_blog( $atts ) {
    extract( shortcode_atts( array(
        'numberposts' => 5,
        'blog_id' => FALSE,
        'category' => FALSE,
    ), $atts ) );
    if($blog_id){
        switch_to_blog($blog_id);
    }
    $args['numberposts'] = $numberposts;
    if($category){
        $args['category_name'] = $category;
    }
    $headlines = get_posts($args);
    foreach($headlines AS $hl){
        $list .= '
        <li>
            <a href="'.get_permalink($hl->ID).'" title="'.$hl->post_title.'" >'.get_the_post_thumbnail($hl->ID,array(75,75)).$hl->post_title.'</a>
        </li>
        ';
    }
    if($blog_id){
        restore_current_blog();
    }
    return $list;
}
add_shortcode( 'hotnews', '_msd_get_news_from_blog' );

function _msd_get_rss_from_blog( $atts ) {
    extract( shortcode_atts( array(
        'numberposts' => 5,
        'url' => FALSE,
    ), $atts ) );

    while ( stristr($url, 'http') != $url )
        $url = substr($url, 1);

    if ( empty($url) )
        return;

    // self-url destruction sequence
    if ( $url == site_url() || $url == home_url() )
        return;

    $rss = fetch_feed($url);
    $link = '';

    if ( ! is_wp_error($rss) ) {
        $desc = esc_attr(strip_tags(@html_entity_decode($rss->get_description(), ENT_QUOTES, get_option('blog_charset'))));
        if ( empty($title) )
            $title = esc_html(strip_tags($rss->get_title()));
        $link = esc_url(strip_tags($rss->get_permalink()));
        while ( stristr($link, 'http') != $link )
            $link = substr($link, 1);
    }

    $url = esc_url(strip_tags($url));
    $ret = _msd_shortcode_rss_output( $rss, array('items' => $numberposts) );

    if ( ! is_wp_error($rss) )
        $rss->__destruct();
    unset($rss);
    return $ret;
}

/**
 * Display the RSS entries in a list.
 *
 * @since 2.5.0
 *
 * @param string|array|object $rss RSS url.
 * @param array $args Widget arguments.
 */
function _msd_shortcode_rss_output( $rss, $args = array() ) {
    if ( is_string( $rss ) ) {
        $rss = fetch_feed($rss);
    } elseif ( is_array($rss) && isset($rss['url']) ) {
        $args = $rss;
        $rss = fetch_feed($rss['url']);
    } elseif ( !is_object($rss) ) {
        return;
    }

    if ( is_wp_error($rss) ) {
        if ( is_admin() || current_user_can('manage_options') )
            $ret .=  '<p>' . sprintf( __('<strong>RSS Error</strong>: %s'), $rss->get_error_message() ) . '</p>';
        return $ret;
    }

    $default_args = array( 'show_author' => 0, 'show_date' => 0, 'show_summary' => 0 );
    $args = wp_parse_args( $args, $default_args );
    extract( $args, EXTR_SKIP );

    $items = (int) $items;
    if ( $items < 1 || 20 < $items )
        $items = 10;
    $show_summary  = (int) $show_summary;
    $show_author   = (int) $show_author;
    $show_date     = (int) $show_date;

    if ( !$rss->get_item_quantity() ) {
        $ret .=  '<ul><li>' . __( 'An error has occurred; the feed is probably down. Try again later.' ) . '</li></ul>';
        $rss->__destruct();
        unset($rss);
        return $ret;
    }

    $ret .=  '<ul>';
    foreach ( $rss->get_items(0, $items) as $item ) {
        $link = $item->get_link();
        while ( stristr($link, 'http') != $link )
            $link = substr($link, 1);
        $link = esc_url(strip_tags($link));
        $title = esc_attr(strip_tags($item->get_title()));
        if ( empty($title) )
            $title = __('Untitled');

        $desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );
        $desc = wp_html_excerpt( $desc, 360 );

        // Append ellipsis. Change existing [...] to [&hellip;].
        if ( '[...]' == substr( $desc, -5 ) )
            $desc = substr( $desc, 0, -5 ) . '[&hellip;]';
        elseif ( '[&hellip;]' != substr( $desc, -10 ) )
            $desc .= ' [&hellip;]';

        $desc = esc_html( $desc );

        if ( $show_summary ) {
            $summary = "<div class='rssSummary'>$desc</div>";
        } else {
            $summary = '';
        }

        $date = '';
        if ( $show_date ) {
            $date = $item->get_date( 'U' );

            if ( $date ) {
                $date = ' <span class="rss-date">' . date_i18n( get_option( 'date_format' ), $date ) . '</span>';
            }
        }

        $author = '';
        if ( $show_author ) {
            $author = $item->get_author();
            if ( is_object($author) ) {
                $author = $author->get_name();
                $author = ' <cite>' . esc_html( strip_tags( $author ) ) . '</cite>';
            }
        }

        if ( $link == '' ) {
            $ret .=  "<li>$title{$date}{$summary}{$author}</li>";
        } else {
            $ret .=  "<li><a class='rsswidget' href='$link' title='$desc'>$title</a>{$date}{$summary}{$author}</li>";
        }
    }
    $ret .=  '</ul>';
    $rss->__destruct();
    unset($rss);
    return $ret;
}
add_shortcode( 'rsshotnews', '_msd_get_rss_from_blog' );

function _msdlab_menu_shortcode_handler($atts){
    global $post;
    $isevent = false;
    extract( shortcode_atts( array(
        'orderby' => 'title',
        'type' => 'child-pages',
        'post_id' => $post->ID,
    ), $atts ) );
    switch($type){
        case 'child-pages':
        default:
        $args = array(
            'post_type' => 'page',
            'post_parent' => $post_id,
            'posts_per_page' => -1,
        );
            break;
    }
    switch($orderby){
        case 'date':
        case 'event-date':
            $isevent = true;
            $args['meta_key'] = 'event_start_date';
            $args['orderby'] = 'meta_value';
            $args['order'] = 'ASC';
            $args['meta_query'] = array(
        array(
            'key'     => 'event_start_date',
            'value'   => date('m/01/Y'),
            'compare' => '>=',
            'meta_type'    => 'DATE'
        ),
    );
            break;
        default:
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        break;
    }
    $cpquery = new WP_Query($args);
    if($cpquery->have_posts()){
        if($isevent){$old_month = date("F Y");}
        $ret = array();
        $ret[] = '<div class="menu-gallery">';
        $ret[] = '<div class="month-header">'.$old_month.'</div>';
        while($cpquery->have_posts()){
        $cpquery->the_post();
        if($isevent){
            $month = date("F Y",strtotime(get_post_meta($post->ID,'event_start_date',true)));
            //error_log('old month: '.$old_month.' | month: '.$month);
            if($old_month != $month){
                $ret[] = '<div class="month-header">'.$month.'</div>';
                $old_month = $month;
            }
        }
            $ret[] = '<article class="entry col-xs-12 col-sm-6 col-md-4" itemscope="" itemtype="https://schema.org/CreativeWork">
<header class="entry-header">
<h2 class="entry-title" style="background-image:url(' . get_the_post_thumbnail_url() . ')" itemprop="headline">
<a class="entry-title-link" rel="bookmark" href="' . get_the_permalink() . '"></a>
</h2>
</header><div class="entry-content" itemprop="text"></div>
<footer class="entry-footer"><a class="entry-title-link" rel="bookmark" href="' . get_the_permalink() . '"><span>' . get_the_title() . '</span></a>';
        if($isevent){
            $start = get_post_meta($post->ID,'event_start_date',true);
            $end = get_post_meta($post->ID,'event_end_date',true);
            $ret[] = '<div class="event-date">';
            $ret[] = date("F j",strtotime($start));
            if(strtotime($end) > strtotime($start)){
                $ret[] = ' – '. date("F j",strtotime($end));
            }
            $ret[] = '</div>';
        }
$ret[] = '</footer></article>';
        }
        $ret[] = '</div>';
    }
    wp_reset_postdata();
    return implode("\n",$ret);
}
add_shortcode( 'menu', '_msdlab_menu_shortcode_handler' );