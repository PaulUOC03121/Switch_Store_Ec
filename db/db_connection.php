<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// load the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$servername = $_ENV['DB_HOST']; // Server name
$dbname = $_ENV['DB_NAME'];     // Database name
$username = $_ENV['DB_USER'];   // Database username
$password = $_ENV['DB_PASS'];   // Database password

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Set character set to UTF-8
$conn->set_charset("utf8");

// Function to get product information by ID
function getProductById($conn, $productId) {
    $sql = "SELECT * FROM Product WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Function to get purchase information by ID
function getPurchaseById($conn, $purchaseId) {
    $sql = "SELECT * FROM Purchase WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $purchaseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Function to get address information by ID
function getAddressById($conn, $addressId) {
    $sql = "SELECT * FROM Address WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $addressId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Function to get categories
function getCategories($conn) {
    $sql = "SELECT * FROM Category";
    $result = $conn->query($sql);
    $categories = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    return $categories;
}

// Function to get Purchases
function getPurchases($conn) {
    $sql = "SELECT * FROM Purchase";
    $result = $conn->query($sql);
    
    $purchases = [];
    while ($row = $result->fetch_assoc()) {
        $purchases[] = $row;
    }
    
    return $purchases;
}

// Function to get Products
function getProducts($conn) {
    $sql = "SELECT * FROM Product";
    $result = $conn->query($sql);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    
    return $products;
}

// Function to get category name by ID
function getCategoryName($conn, $categoryId) {
    $sql = "SELECT name FROM Category WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $stmt->bind_result($categoryName);

    if ($stmt->fetch()) {
        return $categoryName;
    } else {
        return null;
    }
}

// Function to get products by category ordered by number of visits descending and alphabetically in case of tie
function getProductsByCategory($conn, $categoryId) {
    $sql = "SELECT p.* FROM Product p JOIN Category c ON p.category_id = c.id WHERE c.id = ? ORDER BY p.visit_count DESC, p.name ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
    return $products;
}

// Function to get a purchase's items
function getPurchaseItems($conn, $purchase_id) {
    $sql = "SELECT * FROM Purchase_Item WHERE purchase_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $purchase_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = array();
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    $stmt->close();
    return $items;
}

// Function to create a visit record in the 'Visit' table
function createVisit($conn, $productId) {
    $stmt = $conn->prepare("UPDATE Product SET visit_count = visit_count + 1 WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->close();
}

// Function to insert an entry in Customer
function insertCustomer($conn, $email) {
    $stmt = $conn->prepare("INSERT INTO Customer (email) VALUES (?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();
}

// Function to insert an entry in RegisteredCustomer
function insertRegisteredCustomer($conn, $email, $password) {
    $stmt = $conn->prepare("INSERT INTO RegisteredCustomer (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->close();
}

// Function to insert an entry in GuestCustomer
function insertGuestCustomer($conn, $email) {
    $stmt = $conn->prepare("INSERT INTO GuestCustomer (email) VALUES (?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();
}

// Function to insert an entry in Address
function insertAddress($conn, $email, $shippingName, $streetAddress, $city, $state, $zipCode, $country, $phone) {
    $stmt = $conn->prepare("INSERT INTO Address (customer_email, name, street_address, city, state, zip_code, country, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $email, $shippingName, $streetAddress, $city, $state, $zipCode, $country, $phone);
    $stmt->execute();
    $stmt->close();

    // Get the last inserted ID
    return $conn->insert_id;
}

// Function to insert an entry in Purchase
function insertPurchase($conn, $email, $total, $addressId, $purchaseType) {
    $stmt = $conn->prepare("INSERT INTO Purchase (customer_email, order_date, total_amount, shipping_address_id, type) VALUES (?, NOW(), ?, ?, ?)");
    $stmt->bind_param("sdis", $email, $total, $addressId, $purchaseType);
    $stmt->execute();
    $stmt->close();

    // Get the last inserted ID
    return $conn->insert_id;
}

// Function to insert an entry in Purchase_Item
function insertPurchaseItem($conn, $purchaseId, $productId, $quantity, $price) {
    $stmt = $conn->prepare("INSERT INTO Purchase_Item (purchase_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $purchaseId, $productId, $quantity, $price);
    $stmt->execute();
    $stmt->close();
}

// Function to delete a Product
function deleteProduct($conn, $productId) {
    try {
        $conn->begin_transaction();

        $sql = "DELETE FROM Product WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        return "Producto eliminado correctamente.";
    } catch (Exception $e) {
        $conn->rollback();
        return "No es posible eliminar este producto porque ya ha sido vendido.";
    }
}

// Function to delete a Category
function deleteCategory($conn, $categoryId) {
    try {
        $conn->begin_transaction();

        // Delete products from the category
        $sqlProducts = "DELETE FROM Product WHERE category_id = ?";
        $stmtProducts = $conn->prepare($sqlProducts);
        $stmtProducts->bind_param('i', $categoryId);
        $stmtProducts->execute();
        $stmtProducts->close();

        // Delete the category
        $sql = "DELETE FROM Category WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        return "Categoría eliminada correctamente.";
    } catch (Exception $e) {
        $conn->rollback();
        return "No es posible eliminar esta categoría porque ya se han vendido productos de esta categoría.";
    }
}

?>