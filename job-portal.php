<?php
/*
Plugin Name: Job Portal
Description: A job portal plugin with role-based functionality.
Version: 1.0
Author: Neo Kun
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    die('fuck off');
}

// Include core files
require_once plugin_dir_path(__FILE__) . 'includes/class-user-roles.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-company-dashboard.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-job-seeker-dashboard.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-admin-panel.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-job-posts.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-job-applications.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-notifications.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-analytics-dashboard.php';

// Initialize components
add_action('plugins_loaded', function () {
    User_Roles::init();
    Company_Dashboard::init();
    Job_Seeker_Dashboard::init();
    Admin_Panel::init();
    Job_Posts::init();
    Job_Applications::init();
    Notifications::init();
    Analytics_Dashboard::init();
});
function job_portal_admin_assets() {
    wp_enqueue_style('job-portal-admin-css', plugins_url('assets/css/style.css', __FILE__));
    wp_enqueue_script('job-portal-admin-js', plugins_url('assets/js/main.js', __FILE__), ['jquery'], null, true);
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js');  // For graphs
}
add_action('admin_enqueue_scripts', 'job_portal_admin_assets');
