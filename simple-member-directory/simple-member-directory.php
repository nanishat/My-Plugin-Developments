<?php
/**
 * Plugin Name:         Simple Member Directory
 * Plugin URI:          https://wordpress.com/plugins/simple-member-directory/
 * Description:         A simple member directory for your website where each member can be associated with multiple teams.
 * Version:             1.0.0
 * Requires at least:   5.2
 * Requires PHP:        7.4
 * Author:              Safiul Mujnebin
 * License:             GPLv2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:         smd
 */

// Register a shortcode: [simple_member_directory]
add_shortcode('simple_member_directory', 'smd_display_members');

function smd_display_members() {
    $args = array(
        'post_type' => 'smd_member',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $output = '<div class="smd-member-directory">';

        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="smd-member">';
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<p>' . get_the_excerpt() . '</p>';
            if (has_post_thumbnail()) {
                $output .= get_the_post_thumbnail(get_the_ID(), 'thumbnail');
            }
            $output .= '</div>';
        }

        $output .= '</div>';
        wp_reset_postdata();
    } else {
        $output = '<p>No members found.</p>';
    }

    return $output;
}


function smd_register_member_post_type() {
    $labels = array(
        'name' => 'Members',
        'singular_name' => 'Member',
        'menu_name' => 'Members',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Member',
        'new_item' => 'New Member',
        'edit_item' => 'Edit Member',
        'view_item' => 'View Member',
        'all_items' => 'All Members',
        'search_items' => 'Search Members',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
    );

    register_post_type('smd_member', $args);
}
add_action('init', 'smd_register_member_post_type');
?>
