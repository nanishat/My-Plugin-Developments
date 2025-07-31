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

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/TeamCPT.php';

// Use the TeamCPT class from the namespace and initialize it
use MemberDirectory\TeamCPT;
new TeamCPT();

// Register shortcode: [simple_member_directory]
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

// Register the "Member" custom post type
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


// Register meta boxes for Members
function smd_add_member_meta_boxes() {
    add_meta_box('smd_member_info', 'Member Details', 'smd_render_member_meta_box', 'smd_member', 'normal', 'default');
}
add_action('add_meta_boxes', 'smd_add_member_meta_boxes');

// Render the fields inside the meta box
function smd_render_member_meta_box($post) {
    wp_nonce_field('smd_save_member_meta', 'smd_member_meta_nonce');

    $email = get_post_meta($post->ID, '_smd_email', true);
    $phone = get_post_meta($post->ID, '_smd_phone', true);
    $position = get_post_meta($post->ID, '_smd_position', true);
    $favorite_color = get_post_meta($post->ID, '_smd_color', true);

    // Get all teams (team CPT)
    $selected_teams = get_post_meta($post->ID, '_smd_teams', true) ?: [];
    $teams = get_posts([
        'post_type' => 'team',
        'numberposts' => -1
    ]);

    ?>
    <p><label><strong>Position</strong><br>
    <input type="text" name="smd_position" value="<?php echo esc_attr($position); ?>" class="widefat"></label></p>

    <p><label><strong>Email</strong><br>
    <input type="email" name="smd_email" value="<?php echo esc_attr($email); ?>" class="widefat"></label></p>

    <p><label><strong>Phone</strong><br>
    <input type="text" name="smd_phone" value="<?php echo esc_attr($phone); ?>" class="widefat"></label></p>

    <p><label><strong>Favorite Color</strong><br>
    <input type="color" name="smd_color" value="<?php echo esc_attr($favorite_color ?: '#000000'); ?>"></label></p>

    <p><label><strong>Team Association</strong><br>
    <select name="smd_teams[]" multiple class="widefat" style="height: auto;">
        <?php foreach ($teams as $team): ?>
            <option value="<?php echo $team->ID; ?>" <?php selected(in_array($team->ID, $selected_teams)); ?>>
                <?php echo esc_html($team->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select></label></p>
    <?php
}

// Save custom meta data
function smd_save_member_meta($post_id) {
    if (!isset($_POST['smd_member_meta_nonce']) || !wp_verify_nonce($_POST['smd_member_meta_nonce'], 'smd_save_member_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    update_post_meta($post_id, '_smd_position', sanitize_text_field($_POST['smd_position']));
    update_post_meta($post_id, '_smd_email', sanitize_email($_POST['smd_email']));
    update_post_meta($post_id, '_smd_phone', sanitize_text_field($_POST['smd_phone']));
    update_post_meta($post_id, '_smd_color', sanitize_hex_color($_POST['smd_color']));
    update_post_meta($post_id, '_smd_teams', array_map('intval', $_POST['smd_teams'] ?? []));
}
add_action('save_post', 'smd_save_member_meta');
