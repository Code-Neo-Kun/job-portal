<?php

class User_Roles {

    public static function init() {
        // Register roles during plugin activation
        register_activation_hook(__FILE__, [__CLASS__, 'add_roles']);
        
        // Add a cleanup hook for deactivation (optional)
        register_deactivation_hook(__FILE__, [__CLASS__, 'remove_roles']);
    }

    /**
     * Add custom roles for the plugin.
     */
    public static function add_roles() {
        // Add 'company' role if it doesn't exist
        if (!get_role('company')) {
            add_role('company', 'Company', [
                'read' => true, // Allow reading the dashboard and content
                'edit_posts' => true, // Edit posts (job listings)
                'publish_posts' => true, // Publish job posts
                'edit_others_posts' => false, // Do not allow editing others' posts
                'delete_posts' => true, // Allow deleting their own posts (job posts)
                'delete_others_posts' => false, // Prevent deleting others' posts
                'manage_options' => false, // Do not allow access to general settings
                'create_jobs' => true, // Custom capability for creating job posts
            ]);
        }

        // Optionally, define a 'job_seeker' role if it doesn't exist
        if (!get_role('job_seeker')) {
            add_role('job_seeker', 'Job Seeker', [
                'read' => true,
                'edit_posts' => false,
                'publish_posts' => false,
                'manage_options' => false,
            ]);
        }
    }

    /**
     * Remove custom roles during plugin deactivation (optional).
     */
    public static function remove_roles() {
        remove_role('company');
        remove_role('job_seeker');
    }
}

// Initialize the User_Roles class
User_Roles::init();
