<?php
class Admin_Panel
{
    public static function init()
    {
        // Hook into WordPress admin menu
        add_action('admin_menu', [__CLASS__, 'add_admin_pages']);
    }

    public static function add_admin_pages()
    {
        // Main admin menu
        add_menu_page(
            'Job Portal Admin',
            'Job Portal',
            'manage_options',
            'job-portal-admin',
            [__CLASS__, 'render_admin_dashboard'],
            'dashicons-portfolio',
            25
        );

        // Submenu Manage
        $submenus = [
            ['Manage Companies', 'Companies', 'job-portal-companies', [__CLASS__, 'render_manage_companies']],
            ['Manage Jobseekers', 'Jobseekers', 'job-portal-jobseekers', [__CLASS__, 'render_manage_jobseekers']],
            ['How to Set Up', 'How to Set Up', 'plugin-how-to-setup', [__CLASS__, 'render_setup_page']],
            ['Shortcodes Documentation', 'Shortcodes Documentation', 'plugin-shortcodes-doc', [__CLASS__, 'render_shortcodes_page']],
        ];

        foreach ($submenus as $submenu) {
            add_submenu_page(
                'job-portal-admin',
                $submenu[0],
                $submenu[1],
                'manage_options',
                $submenu[2],
                $submenu[3]
            );
        }
    }

    // Add a custom menu for the dashboard
    // Function to retrieve dynamic data
    function job_portal_get_data()
    {
        // Example: Retrieve dynamic data from the database
        $data = [
            'companies' => wp_count_posts('company')->publish,  // Custom post type: company
            'newest_jobs' => wp_count_posts('job')->publish,    // Custom post type: job
            'resumes' => wp_count_posts('resume')->publish,     // Custom post type: resume
            'active_jobs' => wp_count_posts('job')->publish,    // Example: Same as 'newest_jobs'
        ];

        return $data;
    }

    /**
     * Render the parent documentation page (will display subpages)
     */
    public static function render_parent_page()
    {
        echo '<div class="wrap">';
        echo '<h1>Plugin Documentation</h1>';
        echo '<p>Welcome to the plugin documentation section. Choose a topic from the left menu.</p>';
        echo '</div>';
    }

    /**
     * Render the "How to Set Up" sub-page
     */
    public static function render_setup_page()
    {
        echo '<div class="wrap">';
        echo '<h1>How to Set Up</h1>';
        echo '<p>Follow the steps below to set up the plugin:</p>';
        echo '<ul>
                <li>Step 1: Download the plugin.</li>
                <li>Step 2: Install and activate the plugin in your WordPress dashboard.</li>
                <li>Step 3: Configure the plugin settings from the settings page.</li>
                <li>Step 4: Start using the features.</li>
              </ul>';
        echo '</div>';
    }

    /**
     * Render the "Shortcodes Documentation" sub-page
     */
    public static function render_shortcodes_page()
    {
        echo '<div class="wrap">';
        echo '<h1>Shortcodes Documentation</h1>';
        echo '<p>Here is a list of available shortcodes you can use:</p>';
        echo '<ul>
                <li><strong>[job_list]</strong>: Display a list of jobs on a page.</li>
                <li><strong>[apply_form]</strong>: Display the job application form on a page.</li>
                <li><strong>[job_details]</strong>: Display the details of a specific job.</li>
              </ul>';
        echo '</div>';
    }

    // Dashboard Overview
    public static function render_admin_dashboard()
    {
        global $wpdb;

        // Fetch job statistics
        $job_stats = self::get_job_stats($wpdb);

        // Provide fallback values to prevent undefined index errors
        $data = [
            'companies' => $job_stats['companies_count'] ?? 0,
            'newest_jobs' => $job_stats['total_jobs'] ?? 0,
            'resumes' => 0, // If you need to fetch resume data, replace this with an actual query
            'active_jobs' => $job_stats['active_jobs'] ?? 0,
        ];
?>
        <div class="wrap">
            <div class="sidebar">
                <ul>
                    <li><a href="#" data-page="dashboard">Dashboard</a>
                    </li>
                    <li><a href="#" data-page="companies">Companies</a>
                    </li>
                    <li><a href="#" data-page="jobseekers">Jobseekers</a></li>
                    <li><a href="#" data-page="setup">Configuration</a>
                    </li>
                    <li><a href="#" data-page="shortcodes">Shortcodes</a></li>
                    <li><a href="#" data-page="Jobs">Jobs</a>
                    </li>
                    <li><a href="#" data-page="Resume">Resume</a>
                    </li>
                    <li><a href="#" data-page="Reports">Reports</a>
                    </li>
                </ul>
            </div>

            <div class="main">
                <div class="header">
                    <h1>Dashboard</h1>
                    <div class="buttons">
                        <button>Add Company</button>
                        <button>All Companies</button>
                        <button>All Jobs</button>
                        <button>Add Job</button>
                    </div>
                </div>

                <div class="dashboard-cards">
                    <div class="card">
                        <h3>Companies</h3>
                        <span>(<?php echo esc_html($data['companies']); ?>)</span>
                    </div>
                    <div class="card">
                        <h3>Newest Jobs</h3>
                        <span>(<?php echo esc_html($data['newest_jobs']); ?>)</span>
                    </div>
                    <div class="card">
                        <h3>Resume</h3>
                        <span>(<?php echo esc_html($data['resumes']); ?>)</span>
                    </div>
                    <div class="card">
                        <h3>Active Jobs</h3>
                        <span>(<?php echo esc_html($data['active_jobs']); ?>)</span>
                    </div>
                </div>

                <div class="additional-content">
                    <div class="box">
                        <h4>How to Set Up WP Job Portal</h4>
                        <p>Find step-by-step setup instructions and access tutorial videos.</p>
                        <button>How to Set Up</button>
                        <button>Visit Help Page</button>
                    </div>
                    <div class="box">
                        <h4>Shortcodes</h4>
                        <p>Explore all shortcodes and watch tutorials on creating pages.</p>
                        <button>Shortcodes</button>
                    </div>
                    <div class="box">
                        <h4>Manage Addons</h4>
                        <p>Check the status of your addons and install new ones.</p>
                        <button>Check Status</button>
                        <button>Install Guide</button>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts">
                    <h3>Job Portal Statistics</h3>
                    <div id="piechart_3d" style="width: 400px; height: 300px;"></div>

                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                        google.charts.load("current", {
                            packages: ["corechart"]
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['Metric', 'Count'],
                                ['Active Jobs', <?php echo esc_js($data['active_jobs']); ?>],
                                ['Companies', <?php echo esc_js($data['companies']); ?>],
                                ['Newest Jobs', <?php echo esc_js($data['newest_jobs']); ?>],
                                ['Resumes', <?php echo esc_js($data['resumes']); ?>]
                            ]);

                            var options = {
                                title: 'Job Portal Statistics',
                                is3D: true,
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                            chart.draw(data, options);
                        }
                    </script>
                </div>
            </div>
        </div>

    <?php
    }


    // Manage Companies
    public static function render_manage_companies()
    {
    ?>
        <div class="wrap">
            <h1>Registered Companies</h1>
            <?php
            $args = [
                'role'    => 'company',
                'orderby' => 'user_login',
                'order'   => 'ASC',
            ];
            $companies = get_users($args);

            if ($companies) {
                echo '<table class="widefat"><thead><tr><th>Name</th><th>Email</th><th>Actions</th></tr></thead><tbody>';
                foreach ($companies as $company) {
                    echo '<tr>';
                    echo '<td>' . esc_html($company->display_name) . '</td>';
                    echo '<td>' . esc_html($company->user_email) . '</td>';
                    echo '<td><a href="#">Approve</a> | <a href="#">Reject</a></td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p>No companies registered yet.</p>';
            }
            ?>
        </div>
    <?php
    }

    // Manage Jobseekers
    public static function render_manage_jobseekers()
    {
    ?>
        <div class="wrap">
            <h1>Registered Jobseekers</h1>
            <?php
            $args = [
                'role'    => 'job_seeker',
                'orderby' => 'user_login',
                'order'   => 'ASC',
            ];
            $jobseekers = get_users($args);

            if ($jobseekers) {
                echo '<table class="widefat"><thead><tr><th>Name</th><th>Email</th><th>Actions</th></tr></thead><tbody>';
                foreach ($jobseekers as $jobseeker) {
                    echo '<tr>';
                    echo '<td>' . esc_html($jobseeker->display_name) . '</td>';
                    echo '<td>' . esc_html($jobseeker->user_email) . '</td>';
                    echo '<td><a href="#">Approve</a> | <a href="#">Reject</a></td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p>No jobseekers registered yet.</p>';
            }
            ?>
        </div>
<?php
    }

    // Fetch job statistics
    // Fetch job statistics
    public static function get_job_stats($wpdb)
    {
        $total_jobs = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type = 'job_post'");
        $active_jobs = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type = 'job_post' AND post_status = 'publish'");
        $inactive_jobs = $total_jobs - $active_jobs;

        $companies_count = count(get_users(['role' => 'company']));
        $job_seekers_count = count(get_users(['role' => 'job_seeker']));

        // Ensure all keys are included in the array
        return [
            'total_jobs' => $total_jobs ?: 0,
            'active_jobs' => $active_jobs ?: 0,
            'inactive_jobs' => $inactive_jobs ?: 0,
            'companies_count' => $companies_count ?: 0,
            'job_seekers_count' => $job_seekers_count ?: 0,
        ];
    }
}

// Initialize the Admin Panel class
Admin_Panel::init();
?>