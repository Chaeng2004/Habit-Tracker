document.addEventListener('DOMContentLoaded', function () {
    // Attach confirmation dialog to all delete forms
    const deleteForms = document.querySelectorAll('form.inline-form.delete-form');
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!confirm('Are you sure you want to delete this habit?')) {
                event.preventDefault();
            }
        });
    });
});