<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AJDM_Custom_Post_Type{
    function __construct()
    {
        add_action( 'init', [$this, 'cptui_register_my_cpts'] );
        add_action( 'init', [$this, 'cptui_register_my_cpts_contact_submission'] );
    }

    function cptui_register_my_cpts() {

	/**
	 * Post Type: Books.
	 */

	$labels = [
		"name" => esc_html__( "Books", "twentytwentyfive" ),
		"singular_name" => esc_html__( "Book", "twentytwentyfive" ),
		"add_new_item" => esc_html__( "Add New Books", "twentytwentyfive" ),
		"edit_item" => esc_html__( "Edit Book", "twentytwentyfive" ),
		"view_item" => esc_html__( "View Book", "twentytwentyfive" ),
		"featured_image" => esc_html__( "Cover Photo", "twentytwentyfive" ),
		"set_featured_image" => esc_html__( "Set Cover Photo", "twentytwentyfive" ),
		"remove_featured_image" => esc_html__( "Remove Cover Photo", "twentytwentyfive" ),
	];

	$args = [
		"label" => esc_html__( "Books", "twentytwentyfive" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "book", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "thumbnail", "excerpt", "custom-fields" ],
		"show_in_graphql" => false,
	];

	register_post_type( "book", $args );
}

    function cptui_register_my_cpts_contact_submission() {

        /**
         * Post Type: Submissions.
         */

        $labels = [
            "name" => esc_html__( "Submissions", "twentytwentyfive" ),
            "singular_name" => esc_html__( "Submission", "twentytwentyfive" ),
        ];

        $args = [
            "label" => esc_html__( "Submissions", "twentytwentyfive" ),
            "labels" => $labels,
            "description" => "",
            "public" => false,
            "publicly_queryable" => false,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "rest_namespace" => "wp/v2",
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "can_export" => false,
            "rewrite" => [ "slug" => "contact_submission", "with_front" => true ],
            "query_var" => true,
            "supports" => [ "title", "editor" ],
            "show_in_graphql" => false,
        ];

        register_post_type( "contact_submission", $args );
    }




}