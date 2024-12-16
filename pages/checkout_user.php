<?php
// Start session to access $_SESSION
session_start();

// Include the database connection file
require_once '../db/db_connection.php';

// Verify if the page was accessed with POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtain products and total from form
    $products = $_POST['products'] ?? [];
    $total = floatval($_POST['total'] ?? 0);

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
    <title>Finalizar Compra - Usuario | Switch Store Ec</title>
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
            <h1 style="display: none;">Switch Store Ec - Finalizar Compra (Usuario)</h1>
            <div class="checkout-data">
                <!-- User Info -->
                <div class="user-info">
                    <!-- Guest user -->
                    <div class="guest-user-info">
                        <h3>Comprar como invitado</h3>
                        <form id="guest-form" action="checkout_address.php" method="POST">
                            <div class="form-item">
                                <label for="guest-email">Correo electrónico:</label>
                                <input type="email" id="guest-email" name="guest_email" required>
                            </div>
                            <?php foreach ($cart as $item): ?>
                                <input type="hidden" name="products[]" value="<?php echo htmlspecialchars(json_encode($item)); ?>">
                            <?php endforeach; ?>
                            <input type="hidden" name="total" value="<?php echo $total; ?>">
                            <input type="hidden" name="purchase_type" value="Invitado">
                            <button type="submit" id="guest-continue-btn" class="continue-btn">Continuar</button>
                        </form>
                    </div>

                    <!-- Registered user -->
                    <div class="registered-user-info">
                        <h3>Comprar como usuario registrado</h3>
                        <form id="registered-form" action="checkout_address.php" method="POST">
                            <div class="form-item">
                                <label for="registered-email">Correo electrónico:</label>
                                <input type="email" id="registered-email" name="registered_email" required>
                            </div>
                            <div class="form-item">
                                <label for="registered-password">Contraseña: </label>
                                <input type="password" id="registered-password" name="registered_password" required>
                                <button type="button" id="generate-password-btn" class="generate-btn">Generar</button>
                            </div>
                            <p id="password-info" style="display: none; color: green;">Contraseña generada automáticamente</p>
                            <?php foreach ($cart as $item): ?>
                                <input type="hidden" name="products[]" value="<?php echo htmlspecialchars(json_encode($item)); ?>">
                            <?php endforeach; ?>
                            <input type="hidden" name="total" value="<?php echo $total; ?>">
                            <input type="hidden" name="purchase_type" value="Registrado">
                            <button type="submit" id="registered-continue-btn" class="continue-btn">Continuar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer section -->
    <footer>
        <p>© 2024 Switch Store Ec</p>
    </footer>

    <!-- Script to generate a password -->
    <script src="../js/generate_password.js"></script>
</body>
</html>

<?php $conn->close(); ?>