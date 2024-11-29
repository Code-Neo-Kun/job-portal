jQuery(document).ready(function($) {
    $('.sidebar a').on('click', function(e) {
        e.preventDefault(); // Prevent default anchor behavior

        const page = $(this).attr('data-page'); // Get the page identifier
        $('.main').html('<p>Loading...</p>'); // Show a loading message

        // Make an AJAX request to load the content
        $.ajax({
            url: ajaxurl, // WordPress provides ajaxurl globally in the admin
            type: 'POST',
            data: {
                action: 'load_admin_content',
                page: page
            },
            success: function(response) {
                $('.main').html(response); // Update the main content area
            },
            error: function() {
                $('.main').html('<p>Error loading content.</p>');
            }
        });
    });
});
