<?php
// Start session to access $_SESSION
session_start();

// Include the database connection file
require_once '../db/db_connection.php';

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $purchaseId = intval($_GET['id']);
    
    if ($purchaseId > 0) {
        // Get purchase information using getPurchaseById function
        $purchase = getPurchaseById($conn, $purchaseId);

        // Get purchase items using getPurchaseItems function
        $purchaseItems = getPurchaseItems($conn, $purchaseId);

        // Get address information usin getAddressById function
        $address = getAddressById($conn, $purchase['shipping_address_id']);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Pedido | Switch Store Ec</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/checkout.css">
    <link rel="stylesheet" href="../css/admin_panel.css">
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
            <h1 style="display: none;">Switch Store Ec - Resumen Pedido</h1>
            <!-- Purchase data -->
            <div class="checkout-data">
                <h2>Resumen del pedido</h2>
                <ul class="checkout-summary">
                    <li><strong>Email:</strong> <?php echo htmlspecialchars($purchase['customer_email']); ?></li>
                    <li><strong>Fecha del Pedido:</strong> <?php echo htmlspecialchars($purchase['order_date']); ?></li>
                    <li><strong>Tipo:</strong> <?php echo htmlspecialchars($purchase['type']); ?></li>
                    <li><strong>Estado:</strong> <?php echo htmlspecialchars($purchase['status']); ?></li>
                    <br>
                    <li><strong>Nombre del destinatario:</strong> <?php echo htmlspecialchars($address['name']); ?></li>
                    <li><strong>Dirección:</strong> <?php echo htmlspecialchars($address['street_address']); ?></li>
                    <li><strong>Ciudad:</strong> <?php echo htmlspecialchars($address['city']); ?></li>
                    <li><strong>Estado/Provincia:</strong> <?php echo htmlspecialchars($address['state']); ?></li>
                    <li><strong>Código Postal:</strong> <?php echo htmlspecialchars($address['zip_code']); ?></li>
                    <li><strong>País:</strong> <?php echo htmlspecialchars($address['country']); ?></li>
                    <li><strong>Teléfono:</strong> <?php echo htmlspecialchars($address['phone']); ?></li>
                </ul>
                <h3>Total: $<?php echo htmlspecialchars($purchase['total_amount']); ?></h3>
            </div>

            <!-- Purchase products -->
            <ul class="cart-list">
                <?php foreach ($purchaseItems as $purchaseItem): ?>
                    <?php $product = getProductById($conn, $purchaseItem['product_id']); ?>
                    <li class="cart-item">
                        <div class="product-info">
                            <img src="../img/products/<?php echo $product['image']; ?>" alt="Imagen del producto: <?php echo $product['name']; ?>" class="product-image">
                            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                        </div>
                        <div class="product-details">
                            <p>Cantidad: <?php echo $purchaseItem['quantity']; ?></p>
                            <p>Precio Unitario: $<?php echo number_format($purchaseItem['price'], 2); ?></p>
                            <p>Subtotal: $<?php echo number_format($purchaseItem['price'] * $purchaseItem['quantity'], 2); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <a href="admin_panel.php" class="back-btn">Volver al Panel de Administración</a>
        </div>
    </main>

    <!-- Footer section -->
    <footer>
        <p>© 2024 Switch Store Ec</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>