<?php
namespace MemberDirectory;

class TeamCPT {
    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
    }

    public function register_cpt() {
        register_post_type('team', [
            'label' => 'Teams',
            'public' => true,
            'supports' => ['title', 'editor'],
            'show_in_rest' => true
        ]);
    }
}
