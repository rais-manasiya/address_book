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
    FOREIGN KEY (city_id) REFERENCES cities(id)
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