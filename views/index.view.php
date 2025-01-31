<?php
require_once 'config/config.php';
require_once 'models/Database.php';
require_once 'models/Contact.php';
require_once 'models/City.php';
require_once 'models/Group.php';

$db = new Database();
$contact = new Contact($db);
$city = new City($db);
$group = new Group($db);

$contacts = $contact->getAll();
$cities = $city->getAll();
$groups = $group->getAll();
$tags = $contact->getAllTags();
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

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="contacts-tab" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="true">Contacts</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="groups-tab" data-toggle="tab" href="#groups" role="tab" aria-controls="groups" aria-selected="false">Groups</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                <div class="my-3">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addContactModal">Add New Contact</button>
                    <button class="btn btn-secondary ml-2" id="exportXML">Export XML</button>
                    <button class="btn btn-secondary ml-2" id="exportJSON">Export JSON</button>
                    <select id="tagFilter" class="form-control d-inline-block ml-2" style="width: auto;">
                        <option value="">Filter by Tag</option>
                        <?php foreach ($tags as $tag): ?>
                            <option value="<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars($tag); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <table id="contactsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>First Name</th>
                            <th>Email</th>
                            <th>Street</th>
                            <th>Zip Code</th>
                            <th>City</th>
                            <th>Tags</th>
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
                                <td><?php echo htmlspecialchars($contact['tags']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-contact" data-id="<?php echo $contact['id']; ?>">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-contact" data-id="<?php echo $contact['id']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="groups" role="tabpanel" aria-labelledby="groups-tab">
                <div class="my-3">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addGroupModal">Add New Group</button>
                </div>

                <table id="groupsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groups as $group): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($group['name']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-group" data-id="<?php echo $group['id']; ?>">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-group" data-id="<?php echo $group['id']; ?>">Delete</button>
                                    <button class="btn btn-sm btn-secondary manage-group" data-id="<?php echo $group['id']; ?>">Manage</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
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
                        <div class="form-group">
                            <label for="tags">Tags (comma-separated)</label>
                            <input type="text" class="form-control" id="tags" name="tags">
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
                        <div class="form-group">
                            <label for="edit_tags">Tags (comma-separated)</label>
                            <input type="text" class="form-control" id="edit_tags" name="tags">
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

    <!-- Add Group Modal -->
    <div class="modal fade" id="addGroupModal" tabindex="-1" role="dialog" aria-labelledby="addGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGroupModalLabel">Add New Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addGroupForm">
                        <div class="form-group">
                            <label for="group_name">Group Name</label>
                            <input type="text" class="form-control" id="group_name" name="name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveGroup">Save Group</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Group Modal -->
    <div class="modal fade" id="editGroupModal" tabindex="-1" role="dialog" aria-labelledby="editGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGroupModalLabel">Edit Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editGroupForm">
                        <input type="hidden" id="edit_group_id" name="id">
                        <div class="form-group">
                            <label for="edit_group_name">Group Name</label>
                            <input type="text" class="form-control" id="edit_group_name" name="name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updateGroup">Update Group</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Group Modal -->
    <div class="modal fade" id="manageGroupModal" tabindex="-1" role="dialog" aria-labelledby="manageGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageGroupModalLabel">Manage Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Group Contacts</h6>
                    <table id="groupContactsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Inherited</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="groupContactsBody">
                        </tbody>
                    </table>

                    <h6 class="mt-4">Connected Groups</h6>
                    <table id="connectedGroupsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Group Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="connectedGroupsBody">
                        </tbody>
                    </table>

                    <h6 class="mt-4">Add Contact to Group</h6>
                    <form id="addContactToGroupForm">
                        <div class="form-group">
                            <select class="form-control" id="contactToAdd">
                                <?php foreach ($contacts as $contact): ?>
                                    <option value="<?php echo $contact['id']; ?>"><?php echo htmlspecialchars($contact['name'] . ' ' . $contact['first_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" id="addContactToGroup">Add Contact</button>
                    </form>

                    <h6 class="mt-4">Connect Group</h6>
                    <form id="connectGroupForm">
                        <div class="form-group">
                            <select class="form-control" id="groupToConnect">
                                <?php foreach ($groups as $g): ?>
                                    <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" id="connectGroup">Connect Group</button>
                    </form>
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