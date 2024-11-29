

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