<?php
class Company_Registration {
    public static function init() {
        // Register the company registration form shortcode
        add_shortcode('company_registration', [__CLASS__, 'render_registration_form']);
    }

    // Render the company registration form
    public static function render_registration_form() {
        ob_start(); ?>

        <h2>Company Registration</h2>
        <form method="POST">
            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" required /><br>

            <label for="company_email">Email:</label>
            <input type="email" name="company_email" required /><br>

            <label for="company_password">Password:</label>
            <input type="password" name="company_password" required /><br>

            <label for="company_confirm_password">Confirm Password:</label>
            <input type="password" name="company_confirm_password" required /><br>

            <input type="submit" name="submit_company_registration" value="Register">
        </form>

        <?php
        // Handle form submission
        if (isset($_POST['submit_company_registration'])) {
            self::handle_registration();
        }

        return ob_get_clean();
    }

    // Handle the form submission
    public static function handle_registration() {
        if ($_POST['company_password'] !== $_POST['company_confirm_password']) {
            echo '<p style="color:red;">Passwords do not match!</p>';
            return;
        }

        // Prepare user data
        $userdata = [
            'user_login' => sanitize_email($_POST['company_email']),
            'user_email' => sanitize_email($_POST['company_email']),
            'user_pass' => sanitize_text_field($_POST['company_password']),
            'display_name' => sanitize_text_field($_POST['company_name']),
            'role' => 'company', // Assign the 'company' role
        ];

        // Create the user (company)
        $user_id = wp_insert_user($userdata);

        if (!is_wp_error($user_id)) {
            echo '<p style="color:green;">Company registered successfully!</p>';
        } else {
            echo '<p style="color:red;">Registration failed: ' . $user_id->get_error_message() . '</p>';
        }
    }
}

// Ensure the company registration class is initialized
Company_Registration::init();
