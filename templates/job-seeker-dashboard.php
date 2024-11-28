<div class="job-seeker-dashboard">
    <h2>Welcome, <?php echo esc_html($user->display_name); ?>!</h2>

    <h3>Your Applied Jobs</h3>
    <?php if (!empty($applied_jobs)) : ?>
        <ul>
            <?php foreach ($applied_jobs as $job) : ?>
                <li>
                    <a href="<?php echo get_permalink($job->ID); ?>"><?php echo esc_html($job->post_title); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>You haven't applied to any jobs yet.</p>
    <?php endif; ?>
</div>
