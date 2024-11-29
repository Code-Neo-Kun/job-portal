<?php
class Analytics_Dashboard
{
    public static function init()
    {
        // Hook into the 'admin_menu' action to add the analytics submenu
        add_action('admin_menu', [__CLASS__, 'add_analytics_submenu']);
    }

    public static function add_analytics_submenu()
    {
        add_submenu_page(
            'job-portal-admin',         // Parent menu slug
            'Analytics',                // Page title
            'Analytics',                // Menu title
            'manage_options',           // Capability
            'job-portal-analytics',     // Menu slug
            [__CLASS__, 'render_analytics'] // Callback function
        );
    }

    /**
     * Render the Analytics page content.
     */
    public static function render_analytics()
    {
        echo '<div class="wrap"><h1>Analytics</h1><p>Display analytics and graphs here.</p></div>';
    }
}

// Initialize the Analytics_Dashboard class
Analytics_Dashboard::init();
?>
