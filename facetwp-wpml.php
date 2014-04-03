<?php
/*
Plugin Name: FacetWP - WPML
Plugin URI: https://facetwp.com/
Description: WPML support for FacetWP
Version: 1.0.1
Author: Matt Gibbs
Author URI: https://facetwp.com/
GitHub Plugin URI: https://github.com/mgibbs189/facetwp-wpml
GitHub Branch: 1.0.1
License: GPLv2
*/

// exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


class FWP_WPML
{
    public $lang;


    function __construct() {
        add_action( 'init' , array( $this, 'init' ) );
    }


    // Intialize
    function init() {
        add_filter( 'facetwp_indexer_query_args', array( $this, 'facetwp_indexer_query_args' ) );
    }


    // Index all languages
    function facetwp_indexer_query_args( $args ) {
        $args['suppress_filters'] = true; // query posts in all languages
        return $args;
    }
}


$fwp_wpml = new FWP_WPML();
