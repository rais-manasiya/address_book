$(document).ready(function() {
    // Initialize DataTable
    var table = $('#contactsTable').DataTable();

    // Add Contact
    $('#saveContact').click(function() {
        $.ajax({
            url: 'controllers/ajax_handler.php',
            method: 'POST',
            data: $('#addContactForm').serialize() + '&action=add',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    $('#addContactModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            }
        });
    });

    // Edit Contact
    $('.edit-contact').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'controllers/ajax_handler.php',
            method: 'GET',
            data: { action: 'get', id: id },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    $('#edit_id').val(data.contact.id);
                    $('#edit_name').val(data.contact.name);
                    $('#edit_first_name').val(data.contact.first_name);
                    $('#edit_email').val(data.contact.email);
                    $('#edit_street').val(data.contact.street);
                    $('#edit_zip_code').val(data.contact.zip_code);
                    $('#edit_city_id').val(data.contact.city_id);
                    $('#editContactModal').modal('show');
                } else {
                    alert('Error: ' + data.message);
                }
            }
        });
    });

    // Update Contact
    $('#updateContact').click(function() {
        $.ajax({
            url: 'controllers/ajax_handler.php',
            method: 'POST',
            data: $('#editContactForm').serialize() + '&action=update',
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    $('#editContactModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            }
        });
    });

    // Delete Contact
    $('.delete-contact').click(function() {
        if (confirm('Are you sure you want to delete this contact?')) {
            var id = $(this).data('id');
            $.ajax({
                url: 'controllers/ajax_handler.php',
                method: 'POST',
                data: { action: 'delete', id: id },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                }
            });
        }
    });

    // Export XML
    $('#exportXML').click(function() {
        window.location.href = 'controllers/export.php?format=xml';
    });

    // Export JSON
    $('#exportJSON').click(function() {
        window.location.href = 'controllers/export.php?format=json';
    });
});