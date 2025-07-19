<?php
namespace MemberDirectory;

class Rewrite {
    public function __construct() {
        add_action('init', [$this, 'add_rewrite_rules']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_action('template_redirect', [$this, 'handle_template']);
    }

    public function add_rewrite_rules() {
        add_rewrite_rule('^([a-zA-Z0-9]+)_([a-zA-Z0-9]+)/?$', 'index.php?member_slug=$matches[1]_$matches[2]', 'top');
    }

    public function add_query_vars($vars) {
        $vars[] = 'member_slug';
        return $vars;
    }

    public function handle_template() {
        $slug = get_query_var('member_slug');
        if ($slug) {
            include plugin_dir_path(__FILE__) . '../templates/single-member.php';
            exit;
        }
    }
}
