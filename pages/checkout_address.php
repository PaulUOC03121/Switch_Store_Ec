<?php
// Start session to access $_SESSION
session_start();

// Include the database connection file
require_once '../db/db_connection.php';

// Verify if the page was accessed with POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtain products, total and purchase type from form
    $products = $_POST['products'] ?? [];
    $total = floatval($_POST['total'] ?? 0);
    $purchaseType = $_POST['purchase_type'];

    // Decode the data from the products
    $cart = [];
    foreach ($products as $productJson) {
        $cart[] = json_decode($productJson, true);
    }

    // Obtain the user data
    if ($purchaseType === 'Invitado') {
        $guestEmail = $_POST['guest_email'];
        $registeredEmail = '';
        $registeredPassword = '';
    } else {
        $guestEmail = '';
        $registeredEmail = $_POST['registered_email'];
        $registeredPassword = $_POST['registered_password'];
    }
} else {
    // Redirect to cart if needed
    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Dirección | Switch Store Ec</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>

    <!-- Header section -->
    <header>
        <!-- First row: logo and icons -->
        <div class="header-row top-row">
            <div class="logo">
                <a href="../index.php">
                    <img src="../img/site/logo.webp" alt="Logo" style="width: 150px; height: 150px;">
                </a>
            </div>
            <div class="user-actions">
                <a href="admin_panel.php"><img src="../img/site/admin_panel.webp" alt="Admin Panel" style="width: 35px; height: 35px;"></a>
                <a href="cart.php"><img src="../img/site/cart.webp" alt="Cart" style="width: 35px; height: 35px;"></a>
            </div>
        </div>

        <!-- Second row: menu -->
        <div class="header-row bottom-row">
            <nav>
                <ul class="menu">
                    <li><a href="../index.php">Inicio</a></li>
                    <?php
                    // Get categories from the database
                    $categories = getCategories($conn);

                    // Display categories
                    foreach ($categories as $category) {
                        ?>
                        <li><a href="category.php?id=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main content area -->
    <main>
        <div class="checkout">
            <h1 style="display: none;">Switch Store Ec - Finalizar Compra (Dirección)</h1>
            <div class="checkout-data">
                <!-- Address data -->
                <div class="address-info">
                    <h3>Dirección de Envío</h3>
                    <form id="address-form" action="checkout_summary.php" method="POST">
                        <div class="form-item">
                            <label for="shipping-name">Nombre del destinatario:</label>
                            <input type="text" id="shipping-name" name="shipping_name" required>
                        </div>
                        <div class="form-item">
                            <label for="street-address">Dirección:</label>
                            <input type="text" id="street-address" name="street_address" required>
                        </div>
                        <div class="form-item">
                            <label for="city">Ciudad:</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-item">
                            <label for="state">Estado/Provincia:</label>
                            <input type="text" id="state" name="state" required>
                        </div>
                        <div class="form-item">
                            <label for="zip-code">Código Postal:</label>
                            <input type="text" id="zip-code" name="zip_code" required>
                        </div>
                        <div class="form-item">
                            <label for="country">País:</label>
                            <input type="text" id="country" name="country" required>
                        </div>
                        <div class="form-item">
                            <label for="phone">Teléfono:</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                        <?php foreach ($cart as $item): ?>
                            <input type="hidden" name="products[]" value="<?php echo htmlspecialchars(json_encode($item)); ?>">
                        <?php endforeach; ?>
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        <input type="hidden" name="purchase_type" value="<?php echo $purchaseType; ?>">
                        <input type="hidden" name="guest_email" value="<?php echo $guestEmail; ?>">
                        <input type="hidden" name="registered_email" value="<?php echo $registeredEmail; ?>">
                        <input type="hidden" name="registered_password" value="<?php echo $registeredPassword; ?>">
                        <button type="submit" id="address-continue-btn" class="continue-btn">Continuar</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer section -->
    <footer>
        <p>© 2024 Switch Store Ec</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>