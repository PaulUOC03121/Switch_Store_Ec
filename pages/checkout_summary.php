<?php
// Start session to access $_SESSION
session_start();

// Include the database connection file
require_once '../db/db_connection.php';

// Verify if the page was accessed with POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtain all data from form
    $products = $_POST['products'] ?? [];
    $total = floatval($_POST['total'] ?? 0);
    $purchaseType = $_POST['purchase_type'];
    $guestEmail = $_POST['guest_email'];
    $registeredEmail = $_POST['registered_email'];
    $registeredPassword = $_POST['registered_password'];
    $shippingName = $_POST['shipping_name'];
    $streetAddress = $_POST['street_address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipCode = $_POST['zip_code'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];

    // Decode the data from the products
    $cart = [];
    foreach ($products as $productJson) {
        $cart[] = json_decode($productJson, true);
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
    <title>Finalizar Compra - Resumen | Switch Store Ec</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cart.css">
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
            <h1 style="display: none;">Switch Store Ec - Finalizar Compra (Resumen)</h1>
            <!-- Purchase data -->
            <div class="checkout-data">
                <h2>Resumen del pedido</h2>
                <ul class="checkout-summary">
                    <li><strong>Email:</strong> 
                        <?php 
                        if ($purchaseType === "Invitado") {
                            echo htmlspecialchars($guestEmail);
                        } else {
                            echo htmlspecialchars($registeredEmail);
                        }
                        ?>
                    </li>
                    <li><strong>Nombre del destinatario:</strong> <?php echo htmlspecialchars($shippingName); ?></li>
                    <li><strong>Dirección:</strong> <?php echo htmlspecialchars($streetAddress); ?></li>
                    <li><strong>Ciudad:</strong> <?php echo htmlspecialchars($city); ?></li>
                    <li><strong>Estado/Provincia:</strong> <?php echo htmlspecialchars($state); ?></li>
                    <li><strong>Código Postal:</strong> <?php echo htmlspecialchars($zipCode); ?></li>
                    <li><strong>País:</strong> <?php echo htmlspecialchars($country); ?></li>
                    <li><strong>Teléfono:</strong> <?php echo htmlspecialchars($phone); ?></li>
                </ul>
            </div>

            <!-- Purchase products -->
            <ul class="cart-list">
                <?php foreach ($cart as $item): ?>
                    <li class="cart-item">
                        <div class="product-info">
                            <img src="../img/products/<?php echo $item['image']; ?>" alt="Imagen del producto: <?php echo $item['name']; ?>" class="product-image">
                            <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                        </div>
                        <div class="product-details">
                            <p>Cantidad: <?php echo $item['quantity']; ?></p>
                            <p>Precio Unitario: $<?php echo number_format($item['price'], 2); ?></p>
                            <p>Subtotal: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Purchase confirmation -->
            <div class="checkout-confirmation">
                <h3>Total: $<?php echo $total; ?></h3>
                <form id="place-order-form" action="place_order.php" method="POST">
                    <?php foreach ($cart as $item): ?>
                        <input type="hidden" name="products[]" value="<?php echo htmlspecialchars(json_encode($item)); ?>">
                    <?php endforeach; ?>
                    <input type="hidden" name="purchase_type" value="<?php echo $purchaseType; ?>">
                    <input type="hidden" name="guest_email" value="<?php echo htmlspecialchars($guestEmail); ?>">
                    <input type="hidden" name="registered_email" value="<?php echo htmlspecialchars($registeredEmail); ?>">
                    <input type="hidden" name="registered_password" value="<?php echo htmlspecialchars($registeredPassword); ?>">
                    <input type="hidden" name="shipping_name" value="<?php echo htmlspecialchars($shippingName); ?>">
                    <input type="hidden" name="street_address" value="<?php echo htmlspecialchars($streetAddress); ?>">
                    <input type="hidden" name="city" value="<?php echo htmlspecialchars($city); ?>">
                    <input type="hidden" name="state" value="<?php echo htmlspecialchars($state); ?>">
                    <input type="hidden" name="zip_code" value="<?php echo htmlspecialchars($zipCode); ?>">
                    <input type="hidden" name="country" value="<?php echo htmlspecialchars($country); ?>">
                    <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">

                    <button type="submit" id="place-order-btn" class="place-order-btn">Confirmar pedido</button>
                </form>
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