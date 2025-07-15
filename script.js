$(document).ready(function() {
    // Search functionality
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        searchContacts();
    });

    // Add contact form submission
    $('#addContactForm').on('submit', function(e) {
        e.preventDefault();
        addContact();
    });

    // Edit contact form submission
    $('#editContactForm').on('submit', function(e) {
        e.preventDefault();
        editContact();
    });

    // Delete contact
    $(document).on('click', '.delete-contact', function() {
        if (confirm('Are you sure you want to delete this contact?')) {
            deleteContact($(this).data('id'));
        }
    });

    // View contact
    $(document).on('click', '.view-contact', function() {
        viewContact($(this).data('id'));
    });

    // Edit contact button click
    $(document).on('click', '.edit-contact', function() {
        getContactForEdit($(this).data('id'));
    });

    // Add contact submit button
    $('#submitContact').on('click', function() {
        $('#addContactForm').submit();
    });

    // Edit contact submit button
    $('#submitEdit').on('click', function() {
        $('#editContactForm').submit();
    });

    // Show all contacts button
    $('#showAllContacts').on('click', function() {
        // Clear search inputs
        $('#searchForm input').val('');
        // Get all contacts
        $.get('api/get_contacts.php', function(data) {
            updateContactsTable(data);
        });
    });
});

// Function definitions
function searchContacts() {
    $.get('api/search_contacts.php', $('#searchForm').serialize(), function(data) {
        updateContactsTable(data);
    });
}

function addContact() {
    var formData = new FormData($('#addContactForm')[0]);
    $.ajax({
        url: 'api/add_contact.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                // Hide modal and remove backdrop
                $('#addContactModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('#addContactForm')[0].reset();
                searchContacts();
            } else {
                alert('Error adding contact');
            }
        }
    });
}

function editContact() {
    var formData = new FormData($('#editContactForm')[0]);
    $.ajax({
        url: 'api/edit_contact.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                // Hide modal and remove backdrop
                $('#editContactModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                searchContacts();
            } else {
                alert('Error updating contact');
            }
        }
    });
}

function deleteContact(id) {
    $.post('api/delete_contact.php', { id: id }, function(response) {
        if (response.success) {
            searchContacts();
        } else {
            alert('Error deleting contact');
        }
    });
}

function viewContact(id) {
    $.get('api/get_contact.php', { id: id }, function(contact) {
        $('#view_photo').attr('src', contact.photo);
        $('#view_name').text(contact.first_name + ' ' + contact.last_name);
        $('#view_email').text(contact.email);
        $('#view_phone').text(contact.phone);
        $('#viewContactModal').modal('show');
    });
}

function getContactForEdit(id) {
    $.get('api/get_contact.php', { id: id }, function(contact) {
        $('#edit_id').val(contact.id);
        $('#edit_first_name').val(contact.first_name);
        $('#edit_last_name').val(contact.last_name);
        $('#edit_email').val(contact.email);
        $('#edit_phone').val(contact.phone);
        $('#current_photo').attr('src', contact.photo);
        $('#editContactModal').modal('show');
    });
}

function updateContactsTable(contacts) {
    var table = $('#contactsTable');
    table.empty(); // Clear the entire table
    
    // Create table with header
    var tableHTML = `
        <table class="table table-striped table-hover mt-3">
            <tr class="table-dark">
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>`;
    
    // Add contact rows
    contacts.forEach(function(contact) {
        tableHTML += `
            <tr>
                <td><img src="${contact.photo}" class="contact-img" alt="Contact photo"></td>
                <td>${contact.first_name} ${contact.last_name}</td>
                <td>${contact.email}</td>
                <td>${contact.phone}</td>
                <td>
                    <button class="btn btn-info btn-sm view-contact" data-id="${contact.id}">👁 View</button>
                    <button class="btn btn-warning btn-sm edit-contact" data-id="${contact.id}">✏ Edit</button>
                    <button class="btn btn-danger btn-sm delete-contact" data-id="${contact.id}">🗑 Delete</button>
                </td>
            </tr>`;
    });
    
    tableHTML += '</table>';
    table.html(tableHTML);
} 