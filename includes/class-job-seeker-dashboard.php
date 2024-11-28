<?php
class Job_Seeker_Dashboard {

    public static function init() {
        // Register the shortcode for displaying the dashboard
        add_shortcode('job_seeker_dashboard', [__CLASS__, 'render_dashboard']);

        // Enqueue custom styles and scripts
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_dashboard_assets']);

        // Handle job application via AJAX
        add_action('wp_ajax_apply_for_job', [__CLASS__, 'apply_for_job']);
    }

    /**
     * Enqueue styles and scripts for the job seeker dashboard.
     */
    public static function enqueue_dashboard_assets() {
        if (is_page() && has_shortcode(get_post()->post_content, 'job_seeker_dashboard')) {
            wp_enqueue_style('job-seeker-dashboard-style', plugin_dir_url(__FILE__) . '../assets/css/job-seeker-dashboard.css');
            wp_enqueue_script('job-seeker-dashboard-script', plugin_dir_url(__FILE__) . '../assets/js/job-seeker-dashboard.js', ['jquery'], null, true);
        }
    }

    /**
     * Render the job seeker dashboard.
     */
    public static function render_dashboard() {
        // Check if the user has the 'job_seeker' role
        if (!is_user_logged_in() || !current_user_can('job_seeker')) {
            return '<p>You must be logged in as a job seeker to view this dashboard.</p>';
        }

        $user = wp_get_current_user();
        $applied_jobs = self::get_applied_jobs($user->ID);  // Get applied jobs
        
        ob_start();
        ?>
        <h2>Job Seeker Dashboard</h2>

        <h3>Available Jobs</h3>
        <?php
        $jobs = new WP_Query(['post_type' => 'job', 'post_status' => 'publish']);
        while ($jobs->have_posts()) {
            $jobs->the_post();
            ?>
            <div>
                <h4><?php the_title(); ?></h4>
                <p><?php the_content(); ?></p>
                <?php if (self::has_applied($user->ID, get_the_ID())) : ?>
                    <p>You have already applied for this job.</p>
                <?php else: ?>
                    <button class="apply-job" data-job-id="<?php echo get_the_ID(); ?>">Apply</button>
                <?php endif; ?>
            </div>
            <?php
        }
        wp_reset_postdata();
        
        // Render applied jobs section
        if (!empty($applied_jobs)) {
            echo '<h3>Your Applied Jobs</h3>';
            foreach ($applied_jobs as $job) {
                echo '<p><a href="' . get_permalink($job->ID) . '">' . esc_html($job->post_title) . '</a></p>';
            }
        } else {
            echo '<p>You haven’t applied to any jobs yet.</p>';
        }

        return ob_get_clean();
    }

    /**
     * Get the list of jobs a job seeker has applied for.
     */
    public static function get_applied_jobs($user_id) {
        global $wpdb;

        $applied_jobs = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT p.ID, p.post_title
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->prefix}job_applications j ON p.ID = j.job_post_id
                WHERE j.user_id = %d",
                $user_id
            )
        );

        return $applied_jobs;
    }

    /**
     * Check if the job seeker has already applied for a specific job.
     */
    public static function has_applied($user_id, $job_id) {
        global $wpdb;

        $result = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) 
                FROM {$wpdb->prefix}job_applications 
                WHERE user_id = %d AND job_post_id = %d",
                $user_id, $job_id
            )
        );

        return $result > 0;
    }

    /**
     * Handle job application via AJAX.
     */
    public static function apply_for_job() {
        if (!is_user_logged_in() || !current_user_can('job_seeker')) {
            wp_send_json_error('Access denied');
        }

        $job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;
        if (!$job_id) {
            wp_send_json_error('Invalid job ID');
        }

        $user_id = get_current_user_id();

        // Check if the user has already applied
        if (self::has_applied($user_id, $job_id)) {
            wp_send_json_error('You have already applied for this job');
        }

        // Insert job application
        global $wpdb;
        $wpdb->insert(
            "{$wpdb->prefix}job_applications",
            [
                'user_id' => $user_id,
                'job_post_id' => $job_id,
                'application_date' => current_time('mysql')
            ]
        );

        wp_send_json_success('Job application submitted');
    }
}

// Initialize the Job_Seeker_Dashboard class
Job_Seeker_Dashboard::init();
