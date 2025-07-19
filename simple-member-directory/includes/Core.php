<?php
namespace MemberDirectory;

class Core {
    public static function init() {
        add_action('init', [self::class, 'register_things']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
    }

    public static function register_things() {
        new MemberCPT();
        new TeamCPT();
        new Rewrite();
        new ContactHandler();
        Shortcodes::init();
    }

    public static function enqueue_assets() {
        wp_enqueue_style('member-directory-style', plugin_dir_url(__FILE__) . '../assets/css/style.css');
    }
}
