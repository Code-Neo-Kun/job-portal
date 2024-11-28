<?php
class Company_Dashboard
{
    public static function init()
    {
        // Initialize admin and front-end functionalities
        add_action('admin_menu', [__CLASS__, 'add_company_menu']);
        add_action('wp_ajax_delete_company', [__CLASS__, 'delete_company']);
        add_shortcode('company_dashboard', [__CLASS__, 'render_dashboard']);
    }

    // Add menu item in admin dashboard
    public static function add_company_menu()
    {
        add_submenu_page(
            'job_portal_dashboard',      // Parent slug
            'Companies',                 // Page title
            'Companies',                 // Menu title
            'manage_options',            // Capability
            'job_portal_companies',      // Menu slug
            [__CLASS__, 'render_companies_page'] // Callback function
        );
    }

    // Render the Companies page in admin dashboard
    public static function render_companies_page()
    {
        // Fetch company users
        $company_users = get_users(['role' => 'company']);
        ?>
        <div class="wrap">
            <h1>Registered Companies</h1>
            <?php if (empty($company_users)) : ?>
                <p>No companies registered.</p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($company_users as $company) : ?>
                            <tr>
                                <td><?php echo esc_html($company->display_name); ?></td>
                                <td><?php echo esc_html($company->user_email); ?></td>
                                <td><?php echo esc_html(date('F j, Y', strtotime($company->user_registered))); ?></td>
                                <td>
                                    <a href="<?php echo get_edit_user_link($company->ID); ?>" class="button">Edit</a>
                                    <a href="#" class="button button-danger" onclick="deleteCompany(<?php echo $company->ID; ?>)">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- JavaScript for deletion using AJAX -->
        <script>
            function deleteCompany(userId) {
                if (confirm('Are you sure you want to delete this company?')) {
                    var data = {
                        action: 'delete_company',
                        user_id: userId,
                        nonce: '<?php echo wp_create_nonce('delete_company_nonce'); ?>'
                    };
                    
                    jQuery.post(ajaxurl, data, function(response) {
                        if(response.success) {
                            alert('Company deleted successfully!');
                            location.reload(); // Reload the page to see the changes
                        } else {
                            alert('Failed to delete company: ' + response.data);
                        }
                    });
                }
            }
        </script>
        <?php
    }

    // AJAX function to handle company deletion
    public static function delete_company()
    {
        // Check nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'delete_company_nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        // Check if user has permission
        if (!current_user_can('delete_users')) {
            wp_send_json_error('Permission denied');
        }

        // Get the user ID from the POST data
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        if (!$user_id) {
            wp_send_json_error('Invalid user ID');
        }

        // Delete the company (user)
        $user = get_user_by('id', $user_id);
        if ($user) {
            require_once ABSPATH . 'wp-admin/includes/user.php'; // Make sure the user functions are loaded
            wp_delete_user($user_id);
            wp_send_json_success();
        } else {
            wp_send_json_error('User not found');
        }
    }

    // Render the front-end company dashboard
    public static function render_dashboard()
    {
        if (!current_user_can('company')) {
            return '<p>Access Denied</p>';
        }

        ob_start();
        ?>
        <h2>Company Dashboard</h2>
        <form method="POST" action="">
            <h3>Create Job Post</h3>
            <input type="text" name="job_title" placeholder="Job Title" required />
            <textarea name="job_description" placeholder="Job Description" required></textarea>
            <button type="submit" name="create_job">Create Job</button>
        </form>
        <?php
        if (isset($_POST['create_job'])) {
            self::create_job_post();
        }
        return ob_get_clean();
    }

    // Create a job post from the front-end dashboard
    public static function create_job_post()
    {
        $post_data = [
            'post_title' => sanitize_text_field($_POST['job_title']),
            'post_content' => sanitize_textarea_field($_POST['job_description']),
            'post_status' => 'publish',
            'post_type' => 'job'
        ];
        $post_id = wp_insert_post($post_data);
        if ($post_id) {
            add_post_meta($post_id, 'job_status', 'active');
            echo '<p>Job created successfully!</p>';
        } else {
            echo '<p>Failed to create job.</p>';
        }
    }
}

// Initialize the Company Dashboard class
Company_Dashboard::init();
