<?php
/**
 * Creating a function to create our CPT
 * Custom post type / example gallery, plz change for your projects
 * If you not use CPT, commented this function
 */
function custom_post_type()
{
    // Projects (Gallery)
    // Set UI labels for Custom Post Type
    $labels = array(
        'name' => _x('Projects', 'Post Type General Name', 'my-theme'),
        'singular_name' => _x('Project', 'Post Type Singular Name', 'my-theme'),
        'menu_name' => __('Projects', 'my-theme'),
        'parent_item_colon' => __('Project', 'my-theme'),
        'all_items' => __('All Projects', 'my-theme'),
        'view_item' => __('View Project', 'my-theme'),
        'add_new_item' => __('Add New Project', 'my-theme'),
        'add_new' => __('Add New', 'my-theme'),
        'edit_item' => __('Edit Project', 'my-theme'),
        'update_item' => __('Update Project', 'my-theme'),
        'search_items' => __('Search Project', 'my-theme'),
        'not_found' => __('Not Found', 'my-theme'),
        'not_found_in_trash' => __('Not found in Trash', 'my-theme'),
    );

    // Set other options for Custom Post Type
    $args = array(
        'label' => __('Project', 'my-theme'),
        'description' => __('Project', 'my-theme'),
        'labels' => $labels,
        'menu_icon' => 'dashicons-images-alt2',
        // Features this CPT supports in Post Editor
        'show_in_rest' => true,
        'supports' => array('title', 'thumbnail', 'editor'),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    );

    // Registering your Custom Post Type
    register_post_type('projects', $args);

    register_taxonomy('projects_category', array('projects'), array(
            'hierarchical' => true,
            'label' => 'Categories',
            'singular_label' => 'Category',
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'project', 'with_front' => false)
        )
    );
    register_taxonomy_for_object_type('projects_category', 'projects');
};
add_action('init', 'custom_post_type', 0);