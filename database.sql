CREATE DATABASE address_book;

USE address_book;

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