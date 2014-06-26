<?php
/*
Plugin Name: FacetWP - WPML
Plugin URI: https://facetwp.com/
Description: WPML support for FacetWP
Version: 1.0.3
Author: Matt Gibbs
Author URI: https://facetwp.com/
GitHub Plugin URI: https://github.com/mgibbs189/facetwp-wpml
GitHub Branch: 1.0.3
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
        add_action( 'wp_footer', array( $this, 'wp_footer' ), 30 );
        add_filter( 'facetwp_query_args', array( $this, 'facetwp_query_args' ), 10, 2 );
        add_filter( 'facetwp_indexer_query_args', array( $this, 'indexer_query_args' ) );
        add_action( 'facetwp_indexer_post', array( $this, 'set_post_langcode' ) );
    }


    /**
     * Put the language into FWP_HTTP
     */
    function wp_footer() {
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            $lang = ICL_LANGUAGE_CODE;
            echo "<script>FWP_HTTP.lang = '$lang';</script>";
        }
    }


    /**
     * Query posts for the current language
     */
    function facetwp_query_args( $args, $class ) {
        if ( isset( $class->http_params['lang'] ) ) {
            global $sitepress;
            $sitepress->switch_lang( $class->http_params['lang'] );
        }
        return $args;
    }


    // Index all languages
    function indexer_query_args( $args ) {
        $args['suppress_filters'] = true; // query posts in all languages
        return $args;
    }


    // Set the indexer language code
    function set_post_langcode( $params ) {
        global $sitepress;

        $post_id = $params['post_id'];
        $language_code = $this->get_post_langcode( $post_id );
        $sitepress->switch_lang( $language_code );
    }


    // Find a post's language code
    function get_post_langcode( $post_id ) {
        global $wpdb;
     
        $query = $wpdb->prepare( "SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = %d", $post_id );
        return $wpdb->get_var( $query );
    }
}


$fwp_wpml = new FWP_WPML();
