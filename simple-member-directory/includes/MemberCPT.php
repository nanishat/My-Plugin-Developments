<?php
namespace MemberDirectory;

class MemberCPT {
    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('add_meta_boxes', [$this, 'add_messages_meta_box']);
        add_action('save_post_member', [$this, 'save_meta']);
        add_filter('manage_member_posts_columns', [$this, 'add_member_columns']);
        add_action('manage_member_posts_custom_column', [$this, 'render_member_columns'], 10, 2);

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
        $first = get_post_meta($post->ID, 'first_name', true);
        $last = get_post_meta($post->ID, 'last_name', true);
        $cover = get_post_meta($post->ID, 'cover_image', true);
        $address = get_post_meta($post->ID, 'address', true);
        $status = get_post_meta($post->ID, 'status', true);
        ?>
        <p>First Name: <input type="text" name="first_name" value="<?php echo esc_attr($first); ?>" required></p>
        <p>Last Name: <input type="text" name="last_name" value="<?php echo esc_attr($last); ?>" required></p>
        <p>Email: <input type="email" name="email" value="<?php echo esc_attr($email); ?>" required></p>
        <p>Cover Image URL: <input type="url" name="cover_image" value="<?php echo esc_url($cover); ?>"></p>
        <p>Address: <input type="text" name="address" value="<?php echo esc_attr($address); ?>"></p>
        <p>Favorite Color: <input type="color" name="favorite_color" value="<?php echo esc_attr($color); ?>"></p>
        <p>Status:
            <select name="status">
                <option value="Active" <?php selected($status, 'Active'); ?>>Active</option>
                <option value="Draft" <?php selected($status, 'Draft'); ?>>Draft</option>
            </select>
        </p>
        <?php
    }

    public function add_messages_meta_box() {
        add_meta_box('member_messages', 'Contact Messages', [$this, 'render_messages_meta_box'], 'member');
    }

    public function render_messages_meta_box($post) {
        $email = get_post_meta($post->ID, 'email', true);
        $messages = get_option('member_messages', []);
        $member_messages = array_filter($messages, function ($msg) use ($email) {
            return $msg['to'] === $email;
        });

        if (empty($member_messages)) {
            echo '<p>No messages found.</p>';
            return;
        }

        echo '<ul>';
        foreach (array_reverse($member_messages) as $msg) {
            echo '<li style="margin-bottom:10px;">';
            echo '<strong>From:</strong> ' . esc_html($msg['name']) . ' (' . esc_html($msg['email']) . ')<br>';
            echo '<strong>Message:</strong> ' . esc_html($msg['message']) . '<br>';
            echo '<strong>Time:</strong> ' . esc_html($msg['time']);
            echo '</li>';
        }
        echo '</ul>';
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

        if (isset($_POST['first_name'])) {
            update_post_meta($post_id, 'first_name', sanitize_text_field($_POST['first_name']));
        }
        if (isset($_POST['last_name'])) {
            update_post_meta($post_id, 'last_name', sanitize_text_field($_POST['last_name']));
        }
        if (isset($_POST['cover_image'])) {
            update_post_meta($post_id, 'cover_image', esc_url_raw($_POST['cover_image']));
        }
        if (isset($_POST['address'])) {
            update_post_meta($post_id, 'address', sanitize_text_field($_POST['address']));
        }
        if (isset($_POST['status'])) {
            update_post_meta($post_id, 'status', $_POST['status'] === 'Draft' ? 'Draft' : 'Active');
        }

    }

    public function add_member_columns($columns) {
        $columns['messages'] = 'Messages';
        return $columns;
    }

public function render_member_columns($column, $post_id) {
    if ($column === 'messages') {
            $email = get_post_meta($post_id, 'email', true);
            $messages = get_option('member_messages', []);
            $count = 0;

            foreach ($messages as $msg) {
                if ($msg['to'] === $email) {
                    $count++;
                }
            }

            echo esc_html($count);
        }
    }

}
