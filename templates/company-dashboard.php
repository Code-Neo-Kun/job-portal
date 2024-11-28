 <div class="wrap">
            <h1>Registered Companies</h1>
            <?php if (empty($company_users)) : ?>
                <p>No companies registered.</p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($company_users as $company) : ?>
                            <tr>
                                <td><?php echo esc_html($company->display_name); ?></td>
                                <td><?php echo esc_html($company->user_email); ?></td>
                                <td><?php echo esc_html($company->user_registered); ?></td>
                                <td>
                                    <a href="<?php echo get_edit_user_link($company->ID); ?>" class="button">Edit</a>
                                    <a href="#" class="button button-danger" onclick="deleteCompany(<?php echo $company->ID; ?>)">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- JavaScript to confirm deletion -->
        <script>
            function deleteCompany(userId) {
                if (confirm('Are you sure you want to delete this company?')) {
                    window.location.href = '<?php echo admin_url('users.php?action=delete&user='); ?>' + userId;
                }
            }
        </script>