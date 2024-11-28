document.addEventListener('DOMContentLoaded', function () {
    // Handle Job Application Form Submission via AJAX
    const jobApplicationForm = document.querySelector('form#job-application-form');
    if (jobApplicationForm) {
        jobApplicationForm.addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            
            fetch('/wp-admin/admin-ajax.php?action=apply_for_job', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);  // Success message
                } else {
                    alert('There was an error: ' + data.message);  // Error message
                }
            })
            .catch(error => {
                alert('An error occurred: ' + error.message); // Handle fetch error
            });
        });
    }

    // Handle the Chart rendering for Job Statistics
    const ctx = document.getElementById('jobStatsGraph').getContext('2d');
    if (ctx) {
        fetch('/wp-admin/admin-ajax.php?action=get_job_stats')  // Example endpoint for dynamic data
            .then(response => response.json())
            .then(data => {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,  // Dynamic labels from backend
                        datasets: [{
                            label: 'Jobs',
                            data: data.jobs,  // Dynamic job data from backend
                            borderColor: 'green',
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);  // Handle fetch error for chart data
            });
    }
});
