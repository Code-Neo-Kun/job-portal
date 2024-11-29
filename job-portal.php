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
require_once plugin_dir_path(__FILE__) . 'includes/class-company-dashboard.php';
require_once plugin_dir_path(__FILE__) . '/includes/pages/register-login.php';



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
function job_portal_admin_assets()
{
    wp_enqueue_style('job-portal-admin-css', plugins_url('assets/css/style.css', __FILE__));
    wp_enqueue_script('job-portal-admin-js', plugins_url('assets/js/main.js', __FILE__), ['jquery'], null, true);
    wp_enqueue_script('job-portal-admin-script', plugin_dir_url(__FILE__) . '../assets/js/admin-scripts.js', array('jquery'), '1.0', true);
    wp_localize_script('job-portal-admin-script', 'ajaxurl', admin_url('admin-ajax.php')); // Pass AJAX URL to script
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js');  // For graphs
}
add_action('admin_enqueue_scripts', 'job_portal_admin_assets');


class Job_Portal_Init
{
    public function __construct()
    {
        // Register activation hook to create tables on activation
        register_activation_hook(__FILE__, array($this, 'create_job_portal_tables'));
    }

    public function create_job_portal_tables()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Table for Companies
        $table_companies = $wpdb->prefix . 'job_portal_companies';
        $sql_companies = "CREATE TABLE $table_companies (
            company_id mediumint(9) NOT NULL AUTO_INCREMENT,
            company_name varchar(255) NOT NULL,
            company_email varchar(100) NOT NULL,
            company_address text,
            company_description text,
            company_logo varchar(255),
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (company_id)
        ) $charset_collate;";

        // Table for Jobseekers
        $table_jobseekers = $wpdb->prefix . 'job_portal_jobseekers';
        $sql_jobseekers = "CREATE TABLE $table_jobseekers (
            jobseeker_id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(15),
            resume_url varchar(255),
            profile_picture varchar(255),
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (jobseeker_id)
        ) $charset_collate;";

        // Include the necessary file for dbDelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Execute the SQL statements
        dbDelta($sql_companies);
        dbDelta($sql_jobseekers);
    }
}
// AJAX callback for dynamic content loading
function load_admin_section()
{
    // Check nonce for security
    check_ajax_referer('job_portal_nonce', 'nonce');

    $section = sanitize_text_field($_POST['section']);

    switch ($section) {
        case 'dashboard':
            Admin_Panel::render_admin_dashboard();
            break;
        case 'companies':
            Admin_Panel::render_manage_companies();
            break;
        case 'jobseekers':
            Admin_Panel::render_manage_jobseekers();
            break;
        case 'setup':
            Admin_Panel::render_setup_page();
            break;
        case 'shortcodes':
            Admin_Panel::render_shortcodes_page();
            break;
        default:
            echo '<p>Section not found.</p>';
            break;
    }

    wp_die(); // Important: Terminate AJAX call
}
add_action('wp_ajax_load_admin_section', 'load_admin_section');
