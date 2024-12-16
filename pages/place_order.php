<?php
// Start session to access $_SESSION
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Include the database connection file
        require_once '../db/db_connection.php';

        // Get purchase type from form
        $purchaseType = $_POST['purchase_type'];

        // Insert Customer (Registered or Guest)
        if ($purchaseType === 'Invitado') {
            $email = $_POST['guest_email'];

            insertCustomer($conn, $email);
            insertGuestCustomer($conn, $email);
        } else {
            $email = $_POST['registered_email'];
            $password = password_hash($_POST['registered_password'], PASSWORD_BCRYPT);

            insertCustomer($conn, $email);
            insertRegisteredCustomer($conn, $email, $password);
        }

        // Insert Address
        $shippingName = $_POST['shipping_name'];
        $streetAddress = $_POST['street_address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zipCode = $_POST['zip_code'];
        $country = $_POST['country'];
        $phone = $_POST['phone'];

        $addressId = insertAddress($conn, $email, $shippingName, $streetAddress, $city, $state, $zipCode, $country, $phone);

        // Insert Purchase
        $total = $_POST['total'];

        $purchaseId = insertPurchase($conn, $email, $total, $addressId, $purchaseType);

        // Insert Purchase Items
        $products = $_POST['products'] ?? [];
        // Decode the data from the products
        $cart = [];
        foreach ($products as $productJson) {
            $cart[] = json_decode($productJson, true);
        }
        foreach ($cart as $item) {
            insertPurchaseItem($conn, $purchaseId, $item['id'], $item['quantity'], $item['price']);
        }

        // Confirm transaction
        $conn->commit();

        // End session
        unset($_SESSION['cart']);
        header("Location: success.php");
        exit;
    } catch (Exception $e) {
        $conn->rollBack();
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: cart.php");
    exit;
}