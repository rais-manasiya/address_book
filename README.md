# Address Book Project

## Table of Contents
1. [Project Overview](#project-overview)
2. [Technologies Used](#technologies-used)
3. [Project Structure](#project-structure)
4. [Setup Instructions](#setup-instructions)
5. [Usage Guide](#usage-guide)
6. [Features](#features)
7. [API Endpoints](#api-endpoints)
8. [Testing](#testing)
9. [Export Functionality](#export-functionality)
10. [Group Management](#group-management)

## Project Overview

This Address Book project is a small-scale web application that allows users to manage contact information. It provides functionality to create, read, update, and delete contact, as well as export the contact list in XML and JSON formats.

## Technologies Used

- PHP 7.4+ (Core PHP with OOP principles)
- MySQL 5.7+
- HTML5
- CSS3
- Bootstrap
- jQuery
- AJAX
- DataTables
- PDO for database interactions

## Project Structure

The project consists of the following main files:

- `index.php`: Main entry point and user interface
- `config.php`: Database configuration
- `Database.php`: Database connection and query execution class
- `Contact.php`: Contact model class for CRUD operations
- `Group.php`: Group model class for CRUD operations
- `City.php`: City model class for retrieving city data
- `ajax_handler.php`: Handles AJAX requests for contact and group operations
- `export.php`: Handles exporting contacts and group to XML and JSON formats
- `script.js`: Client-side JavaScript for AJAX calls and UI interactions
- `ContactTest.php`: Basic test cases for the Contact class

The project follows a modular structure:

```markdown
address-book/
├── config/
│   └── config.php
├── models/
│   ├── Database.php
│   ├── Contact.php
│   ├── City.php
│   └── Group.php
├── controllers/
│   ├── ajax_handler.php
│   └── export.php
├── views/
│   ├── index.php
│   └── 404.php
├── tests/
│       └── ContactTest.php
├── assets/
│   ├── css/
│   │   └── styles.css
│   ├── js/
│       └── script.js
├── index.php
└── README.md
```

## Setup Instructions

1. Ensure you have a web server (e.g., Apache) with PHP 7.4+ and MySQL 5.7+ installed.

2. Clone the project repository or copy all files to your web server's document root.

3. Create a new MySQL database named `address_book`.

4. Import the database structure by running the following SQL commands:

   ```sql
   CREATE TABLE cities (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL
   );

   CREATE TABLE contacts (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL,
      first_name VARCHAR(100) NOT NULL,
      email VARCHAR(100) NOT NULL,
      street VARCHAR(255) NOT NULL,
      zip_code VARCHAR(20) NOT NULL,
      city_id INT NOT NULL,
      tags VARCHAR(255),
      FOREIGN KEY (city_id) REFERENCES cities(id)
   );

   CREATE TABLE `groups` (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL
   );

   CREATE TABLE group_contacts (
      group_id INT,
      contact_id INT,
      is_inherited BOOLEAN DEFAULT false,
      FOREIGN KEY (group_id) REFERENCES `groups`(id),
      FOREIGN KEY (contact_id) REFERENCES contacts(id),
      PRIMARY KEY (group_id, contact_id)
   );

   CREATE TABLE group_connections (
      parent_group_id INT,
      child_group_id INT,
      FOREIGN KEY (parent_group_id) REFERENCES `groups`(id),
      FOREIGN KEY (child_group_id) REFERENCES `groups`(id),
      PRIMARY KEY (parent_group_id, child_group_id)
   );

   -- Insert some sample cities
   INSERT INTO cities (name) VALUES 
   ('Mumbai'),
   ('Ahmedabad'),
   ('Banglore'),
   ('Hydrabad'),
   ('Pune'),
   ('New York'),
   ('Los Angeles'),
   ('Chicago'),
   ('Houston'),
   ('Phoenix');
   ```

5. Update the database connection details in the `config.php` file:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'address_book');
   ```

## Usage Guide

1. Open a web browser and navigate to the location where you installed the project (e.g., `http://localhost/address-book`).

2. The main page displays a list of all contacts in a table format.

3. To add a new contact:
   - Click the "Add New Contact" button.
   - Fill in the contact details in the modal form.
   - Click "Save Contact" to add the new entry.

4. To edit an existing contact:
   - Click the "Edit" button next to the contact you want to modify.
   - Update the contact details in the modal form.
   - Click "Update Contact" to save the changes.

5. To delete a contact:
   - Click the "Delete" button next to the contact you want to remove.
   - Confirm the deletion when prompted.

6. To manage groups:
   - Use the "Add Group" button to create new groups.
   - Click "Manage" next to a group to add or remove contacts.
   - Use the group hierarchy features to create parent-child relationships between groups.

7. To export the contact list:
   - Click the "Export XML" button to download the list in XML format.
   - Click the "Export JSON" button to download the list in JSON format.

8. Use the search box in the top right corner of the table to filter contacts based on any field.

## Features

- Create, read, update, and delete contact entries
- Create and manage groups of contacts
- Implement group hierarchy with inheritance of contacts
- List all contacts and groups in paginated, searchable tables
- Export contact list to XML and JSON formats
- City selection from a predefined list
- Form validation for contact information
- Responsive design for mobile and desktop use

## API Endpoints

The project uses AJAX calls to communicate with the server. The main endpoints are:

- POST `controllers/ajax_handler.php`
  - Actions: add, update, delete (for contacts and groups)
  - add_contact_to_group, remove_contact_from_group
  - connect_groups, disconnect_groups

- GET `controllers/ajax_handler.php`
   - Actions: get_contact, get_all_contacts, get_group_contacts, get_connected_groups

- GET `controllers/export.php`
  - Format: xml, json
  - Exports the contact list in the specified format

## Testing

Basic test cases are provided in the `tests/ContactTest.php` file. To run the tests:

1. From the command line, navigate to the project directory.
2. Run the following command:
   ```
   php tests/ContactTest.php
   ```
3. If all tests pass, you should see the message "All tests passed successfully!".

## Export Functionality

The export feature allows users to download the entire contact list in either XML or JSON format.

- XML Export: Provides a structured XML file with contact information.
- JSON Export: Offers a JSON array of contact objects, useful for data interchange.

## Group Management

The application now supports organizing contacts into groups:

- Create and manage groups
- Add contacts to groups
- Remove contacts from groups
- Implement group hierarchy (parent-child relationships)
- Inherit contacts from parent groups to child groups
- Manage group connections and disconnections
