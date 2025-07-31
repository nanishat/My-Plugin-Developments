<?php
namespace MemberDirectory;

class TeamCPT {
    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
    }

    public function register_cpt() {
        /*register_post_type('team', [
            'label' => 'Teams',
            'public' => true,
            'supports' => ['title', 'editor'],
            'show_in_rest' => true
        ]);*/

        register_post_type('team', [
            'labels' => [
                'name' => 'Teams',
                'singular_name' => 'Team',
                'add_new_item' => 'Add New Team',
                'edit_item' => 'Edit Team',
                'new_item' => 'New Team',
                'view_item' => 'View Team',
                'all_items' => 'All Teams',
                'search_items' => 'Search Teams',
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'team'],
            'supports' => ['title', 'editor', 'thumbnail'],
            'show_in_rest' => true,
            'menu_position' => 6,
            'menu_icon' => 'dashicons-groups',
        ]);


        register_taxonomy('smd_team', 'member', [
            'label' => 'Member Teams',
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'rewrite' => false,
        ]);

    }

}
