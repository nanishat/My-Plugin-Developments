<?php
namespace MemberDirectory;

class MemberCPT {
    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post_member', [$this, 'save_meta']);
    }

    public function register_cpt() {
        register_post_type('member', [
            'label' => 'Members',
            'public' => true,
            'supports' => ['title', 'thumbnail'],
            'show_in_rest' => true,
            'rewrite' => false // Disable default rewrite, we'll handle it manually
        ]);
    }

    public function add_meta_boxes() {
        add_meta_box('member_details', 'Member Details', [$this, 'meta_box_html'], 'member');
    }

    public function meta_box_html($post) {
        $email = get_post_meta($post->ID, 'email', true);
        $color = get_post_meta($post->ID, 'favorite_color', true);
        ?>
        <p>Email: <input type="email" name="email" value="<?php echo esc_attr($email); ?>" required></p>
        <p>Favorite Color: <input type="color" name="favorite_color" value="<?php echo esc_attr($color); ?>"></p>
        <?php
    }

    public function save_meta($post_id) {
        if (isset($_POST['email'])) {
            $email = sanitize_email($_POST['email']);
            // Check for duplicate email
            $query = new \WP_Query([
                'post_type' => 'member',
                'meta_query' => [
                    [
                        'key' => 'email',
                        'value' => $email,
                        'compare' => '=',
                    ]
                ],
                'post__not_in' => [$post_id]
            ]);
            if ($query->have_posts()) {
                wp_die('Duplicate email not allowed.');
            }

            update_post_meta($post_id, 'email', $email);
        }

        if (isset($_POST['favorite_color'])) {
            update_post_meta($post_id, 'favorite_color', sanitize_hex_color($_POST['favorite_color']));
        }
    }
}
