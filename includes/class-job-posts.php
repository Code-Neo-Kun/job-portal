<?php
class Job_Posts {

    public static function init() {
        add_action('init', [__CLASS__, 'register_job_post_type']);
        add_action('add_meta_boxes', [__CLASS__, 'add_job_meta_boxes']);
        add_action('save_post', [__CLASS__, 'save_job_meta']);
    }

    /**
     * Register the Job Post custom post type
     */
    public static function register_job_post_type() {
        register_post_type('job_post', [
            'labels' => [
                'name' => 'Job Posts',
                'singular_name' => 'Job Post',
                'add_new' => 'Add New Job',
                'add_new_item' => 'Add New Job Post',
                'edit_item' => 'Edit Job Post',
                'new_item' => 'New Job Post',
                'view_item' => 'View Job Post',
                'all_items' => 'All Job Posts',
                'search_items' => 'Search Job Posts',
                'not_found' => 'No job posts found',
                'not_found_in_trash' => 'No job posts found in Trash',
                'parent_item_colon' => '',
                'menu_name' => 'Job Posts',
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'custom-fields', 'thumbnail'],
            'taxonomies' => ['category', 'post_tag'], // If you want to add categories and tags
            'show_in_rest' => true, // Enables Gutenberg editor
            'rewrite' => ['slug' => 'jobs'],
        ]);

        // Optionally, register custom taxonomies for job categories, locations, etc.
        self::register_job_taxonomies();
    }

    /**
     * Register custom taxonomies for Job Posts (e.g., Job Categories, Locations)
     */
    public static function register_job_taxonomies() {
        // Register Job Categories taxonomy
        register_taxonomy('job_category', 'job_post', [
            'label' => 'Job Categories',
            'rewrite' => ['slug' => 'job-category'],
            'hierarchical' => true,
            'show_in_rest' => true, // Enable for Gutenberg
        ]);

        // Register Job Locations taxonomy
        register_taxonomy('job_location', 'job_post', [
            'label' => 'Job Locations',
            'rewrite' => ['slug' => 'job-location'],
            'hierarchical' => true,
            'show_in_rest' => true, // Enable for Gutenberg
        ]);
    }

    /**
     * Add custom meta boxes for Job Posts
     */
    public static function add_job_meta_boxes() {
        add_meta_box('job_details', 'Job Details', [__CLASS__, 'render_job_meta_box'], 'job_post', 'normal', 'high');
    }

    /**
     * Render the content of the Job Details meta box
     */
    public static function render_job_meta_box($post) {
        // Nonce for security
        wp_nonce_field('save_job_meta', 'job_meta_nonce');

        // Retrieve saved values
        $salary = get_post_meta($post->ID, '_job_salary', true);
        $company_name = get_post_meta($post->ID, '_company_name', true);
        $job_type = get_post_meta($post->ID, '_job_type', true);

        ?>
        <p>
            <label for="job_salary">Salary</label>
            <input type="text" name="job_salary" id="job_salary" value="<?php echo esc_attr($salary); ?>" class="widefat" />
        </p>
        <p>
            <label for="company_name">Company Name</label>
            <input type="text" name="company_name" id="company_name" value="<?php echo esc_attr($company_name); ?>" class="widefat" />
        </p>
        <p>
            <label for="job_type">Job Type</label>
            <select name="job_type" id="job_type" class="widefat">
                <option value="full_time" <?php selected($job_type, 'full_time'); ?>>Full Time</option>
                <option value="part_time" <?php selected($job_type, 'part_time'); ?>>Part Time</option>
                <option value="contract" <?php selected($job_type, 'contract'); ?>>Contract</option>
            </select>
        </p>
        <?php
    }

    /**
     * Save the custom meta data for Job Posts
     */
    public static function save_job_meta($post_id) {
        // Check if nonce is set
        if (!isset($_POST['job_meta_nonce']) || !wp_verify_nonce($_POST['job_meta_nonce'], 'save_job_meta')) {
            return;
        }

        // Check if the user has permission to save the data
        if ('job_post' !== get_post_type($post_id) || !current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save salary, company name, and job type
        if (isset($_POST['job_salary'])) {
            update_post_meta($post_id, '_job_salary', sanitize_text_field($_POST['job_salary']));
        }

        if (isset($_POST['company_name'])) {
            update_post_meta($post_id, '_company_name', sanitize_text_field($_POST['company_name']));
        }

        if (isset($_POST['job_type'])) {
            update_post_meta($post_id, '_job_type', sanitize_text_field($_POST['job_type']));
        }
    }
}

// Initialize the Job_Posts class
Job_Posts::init();
?>
