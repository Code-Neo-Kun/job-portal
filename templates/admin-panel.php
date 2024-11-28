<div class="wrap">
    <h1>Job Portal Admin Dashboard</h1>

    <!-- Companies Section -->
    <h2>Companies</h2>
    <?php
    $companies = get_users(['role' => 'company']);
    if ($companies):
    ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td><?php echo esc_html($company->ID); ?></td>
                        <td><?php echo esc_html($company->display_name); ?></td>
                        <td><?php echo esc_html($company->user_email); ?></td>
                        <td>
                            <a href="<?php echo esc_url(get_edit_user_link($company->ID)); ?>">View</a> |
                            <a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $company->ID)); ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No companies registered.</p>
    <?php endif; ?>

    <!-- Job Posts Section -->
    <h2>Job Posts</h2>
    <?php
    $args = [
        'post_type' => 'job_post',
        'posts_per_page' => -1,
    ];
    $job_posts = new WP_Query($args);
    if ($job_posts->have_posts()):
    ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($job_posts->have_posts()): $job_posts->the_post(); ?>
                    <tr>
                        <td><?php echo esc_html(get_the_title()); ?></td>
                        <td><?php echo esc_html(get_post_status()); ?></td>
                        <td>
                            <a href="<?php echo get_edit_post_link(); ?>">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No job posts available.</p>
    <?php endif; ?>

    <!-- Job Seekers Section -->
    <h2>Job Seekers</h2>
    <?php
    $job_seekers = get_users(['role' => 'job_seeker']);
    if ($job_seekers):
    ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($job_seekers as $seeker): ?>
                    <tr>
                        <td><?php echo esc_html($seeker->ID); ?></td>
                        <td><?php echo esc_html($seeker->display_name); ?></td>
                        <td><?php echo esc_html($seeker->user_email); ?></td>
                        <td>
                            <a href="<?php echo esc_url(get_edit_user_link($seeker->ID)); ?>">View</a> |
                            <a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $seeker->ID)); ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No job seekers registered.</p>
    <?php endif; ?>
</div>

<!-- new code -->

<div class="wrap">
<?php
// Calculate date range
$current_date = date('Y-m-d'); // Today's date
$one_month_ago = date('Y-m-d', strtotime('-1 month')); // Date one month ago
?>
    <h1>Statistics (<?php echo esc_html($one_month_ago); ?> to <?php echo esc_html($current_date); ?>)</h1>
    <div class="dashboard-metrics">
        <div class="metric-box">Companies: 9</div>
        <div class="metric-box">Newest Jobs: 9</div>
        <div class="metric-box">Resumes: 0</div>
        <div class="metric-box">Active Jobs: 9</div>
    </div>

    <div class="graph-section">
        <!-- Placeholder for Graph -->
        <h2>Statistics (2024-10-27 to 2024-11-27)</h2>
        <canvas id="jobStatsGraph"></canvas>
    </div>

    <div class="section-links">
        <a href="#">How to Set Up</a>
        <a href="#">Shortcodes Documentation</a>
    </div>
</div>