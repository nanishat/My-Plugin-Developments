<?php
namespace MemberDirectory;

class ContactHandler {
    public function __construct() {
        add_action('admin_post_nopriv_member_contact', [$this, 'handle_submission']);
        add_action('admin_post_member_contact', [$this, 'handle_submission']);
    }

    public function handle_submission() {
        $member_email = sanitize_email($_POST['member_email']);
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);

        wp_mail($member_email, 'New Contact Message', $message);

        // Store in options table for simplicity
        $messages = get_option('member_messages', []);
        $messages[] = [
            'to' => $member_email,
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'time' => current_time('mysql')
        ];
        update_option('member_messages', $messages);

        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }
}
