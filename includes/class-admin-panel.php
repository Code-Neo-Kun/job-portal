<?php
class Admin_Panel
{
    public static function init()
    {
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

        // Submenu: Manage Companies
        add_submenu_page(
            'job-portal-admin',
            'Manage Companies',
            'Companies',
            'manage_options',
            'job-portal-companies',
            [__CLASS__, 'render_manage_companies']
        );

        // Submenu: Manage Jobseekers
        add_submenu_page(
            'job-portal-admin',
            'Manage Jobseekers',
            'Jobseekers',
            'manage_options',
            'job-portal-jobseekers',
            [__CLASS__, 'render_manage_jobseekers']
        );
    }

    // Dashboard Overview
    public static function render_admin_dashboard()
    {
        global $wpdb;

        $job_stats = self::get_job_stats($wpdb);

        // Pass data to JavaScript for charts
        $data = [
            'Active Jobs' => $job_stats['active_jobs'],
            'Inactive Jobs' => $job_stats['inactive_jobs'],
            'Total Jobs' => $job_stats['total_jobs'],
            'Companies' => $job_stats['companies_count'],
            'Job Seekers' => $job_stats['job_seekers_count'],
        ];
        ?>
        <div class="wrap">
            <h1>Job Portal Admin Dashboard</h1>
            <p>Overview of the portal activity.</p>

            <!-- Dashboard Metrics -->
            <div class="dashboard-metrics">
                <div class="metric-box">Companies: <?php echo esc_html($job_stats['companies_count']); ?></div>
                <div class="metric-box">Total Jobs: <?php echo esc_html($job_stats['total_jobs']); ?></div>
                <div class="metric-box">Job Seekers: <?php echo esc_html($job_stats['job_seekers_count']); ?></div>
                <div class="metric-box">Active Jobs: <?php echo esc_html($job_stats['active_jobs']); ?></div>
            </div>

            <!-- Chart Section -->
            <div id="piechart_3d" style="width: 700px; height: 500px;"></div>

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                google.charts.load("current", { packages: ["corechart"] });
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Metric', 'Count'],
                        ['Active Jobs', <?php echo $data['Active Jobs']; ?>],
                        ['Inactive Jobs', <?php echo $data['Inactive Jobs']; ?>],
                        ['Total Jobs', <?php echo $data['Total Jobs']; ?>],
                        ['Companies', <?php echo $data['Companies']; ?>],
                        ['Job Seekers', <?php echo $data['Job Seekers']; ?>]
                    ]);

                    var options = {
                        title: 'Job Portal Statistics',
                        is3D: true,
                        pieSliceText: 'value',
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                    chart.draw(data, options);
                }
            </script>
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
    public static function get_job_stats($wpdb)
    {
        $total_jobs = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type = 'job_post'");
        $active_jobs = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type = 'job_post' AND post_status = 'publish'");
        $inactive_jobs = $total_jobs - $active_jobs;

        $companies_count = count(get_users(['role' => 'company']));
        $job_seekers_count = count(get_users(['role' => 'job_seeker']));

        return [
            'total_jobs' => $total_jobs,
            'active_jobs' => $active_jobs,
            'inactive_jobs' => $inactive_jobs,
            'companies_count' => $companies_count,
            'job_seekers_count' => $job_seekers_count,
        ];
    }
}

// Initialize the Admin Panel class
Admin_Panel::init();
