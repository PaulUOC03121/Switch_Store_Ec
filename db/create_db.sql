CREATE DATABASE switch_store_ec;

USE switch_store_ec;

CREATE TABLE IF NOT EXISTS Category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(100) NOT NULL UNIQUE,
    visit_count INT NOT NULL DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES Category(id)
);

CREATE TABLE IF NOT EXISTS Customer (
    email VARCHAR(100) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS RegisteredCustomer (
    email VARCHAR(100) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    FOREIGN KEY (email) REFERENCES Customer(email)
);

CREATE TABLE IF NOT EXISTS GuestCustomer (
    email VARCHAR(100) PRIMARY KEY,
    FOREIGN KEY (email) REFERENCES Customer(email)
);

CREATE TABLE IF NOT EXISTS Address (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_email VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    street_address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    FOREIGN KEY (customer_email) REFERENCES Customer(email)
);

CREATE TABLE IF NOT EXISTS Purchase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_email VARCHAR(100) NOT NULL,
    order_date DATETIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    shipping_address_id INT NOT NULL, 
    type ENUM('Invitado', 'Registrado') NOT NULL,
    status ENUM('Pendiente', 'Enviado', 'Entregado') NOT NULL DEFAULT 'Pendiente',
    FOREIGN KEY (customer_email) REFERENCES Customer(email),
    FOREIGN KEY (shipping_address_id) REFERENCES Address(id)
);

CREATE TABLE IF NOT EXISTS Purchase_Item (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (purchase_id) REFERENCES Purchase(id),
    FOREIGN KEY (product_id) REFERENCES Product(id)
);