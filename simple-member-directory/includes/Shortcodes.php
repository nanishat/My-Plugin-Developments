<?php
namespace MemberDirectory;

class Shortcodes {
    public static function init() {
        add_shortcode('all_members', [self::class, 'all_members']);
        add_shortcode('all_teams', [self::class, 'all_teams']);
    }

    public static function all_members() {
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/all-members.php';
        return ob_get_clean();
    }

    public static function all_teams() {
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/all-teams.php';
        return ob_get_clean();
    }
}
