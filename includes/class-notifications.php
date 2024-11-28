<?php
class Notifications {

    public static function init() {
        // Hook into post status changes
        add_action('transition_post_status', [__CLASS__, 'send_job_post_notification'], 10, 3);
    }

    /**
     * Send notification when a job post's status changes.
     *
     * @param string $new_status The new status of the post.
     * @param string $old_status The old status of the post.
     * @param WP_Post $post The post object.
     */
    public static function send_job_post_notification($new_status, $old_status, $post) {
        // Only send notification if post type is 'job_post' and the status has changed to 'publish' or 'draft'
        if ($post->post_type !== 'job_post') {
            return;
        }

        // Only send email when the post is published or unpublished (customize as needed)
        if ($old_status === $new_status) {
            return; // No status change
        }

        // Construct the email subject and message based on the new post status
        if ($new_status === 'publish') {
            // Job post has been published
            $subject = 'Your Job Post has been Published';
            $message = 'Congratulations! Your job post titled "' . $post->post_title . '" has been published and is now live.';
        } elseif ($new_status === 'draft') {
            // Job post has been unpublished or moved to draft
            $subject = 'Your Job Post has been Unpublished';
            $message = 'Your job post titled "' . $post->post_title . '" has been moved to draft status and is no longer visible on the site.';
        } else {
            // For other status changes, you can customize the message
            $subject = 'Your Job Post Status Changed';
            $message = 'Your job post titled "' . $post->post_title . '" has changed status to ' . $new_status . '.';
        }

        // Send the notification to the admin and the post author
        $admin_email = get_option('admin_email');
        $author_email = get_the_author_meta('user_email', $post->post_author);

        // Use the send_notification method to send the emails
        self::send_notification($admin_email, $subject, $message);
        self::send_notification($author_email, $subject, $message);
    }

    /**
     * Send a generic notification email.
     *
     * @param string $email The recipient's email address.
     * @param string $subject The subject of the email.
     * @param string $message The content of the email.
     */
    public static function send_notification($email, $subject, $message) {
        wp_mail($email, $subject, $message);
    }
}

// Initialize the Notifications class
Notifications::init();
