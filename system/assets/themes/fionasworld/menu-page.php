<?php
/**
 *
Template Name: Mneu/Primary Page
 * Genesis Framework.
 * Fiona's World Landing page (Primary)
 */

//force full-width
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

// Initialize Genesis.

genesis();