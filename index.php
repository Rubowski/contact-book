<?php
    include 'dbconnect.php';
    $search_query = "SELECT * FROM contacts ORDER BY created_at DESC";
    $result = $conn->query($search_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Contact Book</h1>

        <!-- Search Form -->
        <form id="searchForm" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="last_name" class="form-control" placeholder="Search by Last Name">
            </div>
            <div class="col-md-4">
                <input type="text" name="phone" class="form-control" placeholder="Search by Phone">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">SEARCH</button>
            </div>
            <div class="col-md-2">
                <button type="button" id="showAllContacts" class="btn btn-secondary w-100">SHOW ALL</button>
            </div>
        </form>

        <!-- Add Contact Button -->
        <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#addContactModal">
            ➕ Add New Contact
        </button>

        <!-- Contacts Table -->
        <div id="contactsTable">
        <table class="table table-striped table-hover mt-3">
            <tr class="table-dark">
                <th>Photo</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="<?= $row['photo'] ?: 'uploads/default.png' ?>" class="contact-img"></td>
                <td><?= $row['first_name'] . " " . $row['last_name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td>
                        <button class="btn btn-info btn-sm view-contact" data-id="<?= $row['id'] ?>">👁 View</button>
                        <button class="btn btn-warning btn-sm edit-contact" data-id="<?= $row['id'] ?>">✏ Edit</button>
                        <button class="btn btn-danger btn-sm delete-contact" data-id="<?= $row['id'] ?>">🗑 Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    </div>

    <!-- Add Contact Modal -->
    <div class="modal fade" id="addContactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">➕ Add New Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addContactForm" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label class="form-label">First Name:</label>
                            <input type="text" name="first_name" class="form-control custom-input" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Last Name:</label>
                            <input type="text" name="last_name" class="form-control custom-input" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control custom-input" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Phone:</label>
                            <input type="text" name="phone" class="form-control custom-input" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Photo:</label>
                            <input type="file" name="photo" class="form-control custom-input">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                    <button type="button" class="btn btn-primary" id="submitContact">SAVE CONTACT</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Contact Modal -->
    <div class="modal fade" id="editContactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">✏️ Edit Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editContactForm" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group mb-3">
                            <label class="form-label">First Name:</label>
                            <input type="text" name="first_name" id="edit_first_name" class="form-control custom-input" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Last Name:</label>
                            <input type="text" name="last_name" id="edit_last_name" class="form-control custom-input" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" id="edit_email" class="form-control custom-input" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Phone:</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control custom-input" required>
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label">Current Photo:</label>
                            <div class="text-center mb-3">
                                <img id="current_photo" src="" class="profile-img" style="width: 100px; height: 100px;">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">New Photo (optional):</label>
                            <input type="file" name="photo" class="form-control custom-input">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                    <button type="button" class="btn btn-warning" id="submitEdit">UPDATE CONTACT</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Contact Modal -->
<div class="modal fade" id="viewContactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">👁 Contact Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img id="view_photo" src="" class="profile-img">
                </div>
                <div class="contact-details">
                    <h2 id="view_name" class="text-center mb-4"></h2>
                    <div class="info-group">
                        <p><strong>Email:</strong> <span id="view_email"></span></p>
                        <p><strong>Phone:</strong> <span id="view_phone"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Then Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Finally our custom script -->
    <script src="script.js"></script>
</body>
</html>