<?php
class Analytics_Dashboard {
    public static function init() {
        add_submenu_page(
            'job-portal-admin',
            'Analytics',
            'Analytics',
            'manage_options',
            'job-portal-analytics',
            [__CLASS__, 'render_analytics']
        );
    }

    public static function render_analytics() {
        echo '<h1>Analytics</h1>';
        echo '<p>Display graphs and metrics here (e.g., total jobs, applications, etc.).</p>';
    }
}

Analytics_Dashboard::init();
