<?php
require_once 'config/config.php';
require_once 'models/Database.php';
require_once 'models/Contact.php';
require_once 'models/City.php';

$db = new Database();
$contact = new Contact($db);
$city = new City($db);

$contacts = $contact->getAll();
$cities = $city->getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Book</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="container-fluid mt-5">
        <h1 class="mb-4">Address Book</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addContactModal">Add New Contact</button>
        <button class="btn btn-secondary mb-3 ml-2" id="exportXML">Export XML</button>
        <button class="btn btn-secondary mb-3 ml-2" id="exportJSON">Export JSON</button>

        <table id="contactsTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>First Name</th>
                    <th>Email</th>
                    <th>Street</th>
                    <th>Zip Code</th>
                    <th>City</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contact['name']); ?></td>
                        <td><?php echo htmlspecialchars($contact['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($contact['email']); ?></td>
                        <td><?php echo htmlspecialchars($contact['street']); ?></td>
                        <td><?php echo htmlspecialchars($contact['zip_code']); ?></td>
                        <td><?php echo htmlspecialchars($contact['city_name']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-info edit-contact" data-id="<?php echo $contact['id']; ?>">Edit</button>
                            <button class="btn btn-sm btn-danger delete-contact" data-id="<?php echo $contact['id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Contact Modal -->
    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addContactModalLabel">Add New Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addContactForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" class="form-control" id="street" name="street" required>
                        </div>
                        <div class="form-group">
                            <label for="zip_code">Zip Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                        </div>
                        <div class="form-group">
                            <label for="city_id">City</label>
                            <select class="form-control" id="city_id" name="city_id" required>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo $city['id']; ?>"><?php echo htmlspecialchars($city['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveContact">Save Contact</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Contact Modal -->
    <div class="modal fade" id="editContactModal" tabindex="-1" role="dialog" aria-labelledby="editContactModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editContactModalLabel">Edit Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editContactForm">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="form-group">
                            <label for="edit_name">Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_first_name">First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_street">Street</label>
                            <input type="text" class="form-control" id="edit_street" name="street" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_zip_code">Zip Code</label>
                            <input type="text" class="form-control" id="edit_zip_code" name="zip_code" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_city_id">City</label>
                            <select class="form-control" id="edit_city_id" name="city_id" required>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo $city['id']; ?>"><?php echo htmlspecialchars($city['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateContact">Update Contact</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>