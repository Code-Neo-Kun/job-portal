<?php
class Job_Applications {

    public static function init() {
        add_action('wp_ajax_apply_for_job', [__CLASS__, 'apply_for_job']);
        add_action('wp_ajax_nopriv_apply_for_job', [__CLASS__, 'apply_for_job']);  // Handle non-logged-in users
    }

    /**
     * Handles job application via AJAX.
     */
    public static function apply_for_job() {
        // Verify nonce for security
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'job_application_nonce')) {
            wp_send_json_error(['message' => 'Invalid nonce']);
        }

        // Ensure required fields are provided
        if (empty($_POST['applicant_name']) || empty($_POST['applicant_email']) || empty($_POST['job_id'])) {
            wp_send_json_error(['message' => 'Please fill in all the required fields.']);
        }

        $applicant_name = sanitize_text_field($_POST['applicant_name']);
        $applicant_email = sanitize_email($_POST['applicant_email']);
        $job_id = intval($_POST['job_id']);
        
        // Handle resume upload if provided
        $resume = isset($_FILES['resume']) ? $_FILES['resume'] : null;
        if ($resume && $resume['error'] === UPLOAD_ERR_OK) {
            // Process resume upload (store in the uploads directory)
            $upload_dir = wp_upload_dir();
            $uploaded_file = wp_handle_upload($resume, ['test_form' => false]);

            if (isset($uploaded_file['url'])) {
                $resume_url = $uploaded_file['url'];
            } else {
                wp_send_json_error(['message' => 'Failed to upload resume.']);
            }
        } else {
            $resume_url = '';  // No resume uploaded
        }

        // Store the job application as a custom post or in a custom database table
        // For simplicity, we will store it as a custom post type (job_application)
        $application_data = [
            'post_title'   => $applicant_name . ' - ' . get_the_title($job_id),
            'post_content' => 'Applicant email: ' . $applicant_email . '<br>Resume: ' . $resume_url,
            'post_status'  => 'pending',  // Pending until reviewed by admin
            'post_type'    => 'job_application',
            'meta_input'   => [
                '_job_id' => $job_id,
                '_applicant_email' => $applicant_email,
                '_resume_url' => $resume_url
            ]
        ];
        
        $application_id = wp_insert_post($application_data);

        if (is_wp_error($application_id)) {
            wp_send_json_error(['message' => 'Error storing the application.']);
        }

        // Send confirmation email to applicant
        $subject = 'Job Application Submitted Successfully';
        $message = 'Dear ' . $applicant_name . ",\n\n" . 
                   'Thank you for applying for the job: ' . get_the_title($job_id) . ".\n\n" . 
                   'We will review your application and get back to you soon.';

        wp_mail($applicant_email, $subject, $message);

        // Respond with success message
        wp_send_json_success(['message' => 'Application submitted successfully!']);
    }
}

// Initialize the Job_Applications class
Job_Applications::init();
?>
