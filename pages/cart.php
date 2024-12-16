<?php
// Start session to access $_SESSION
session_start();

// Include the database connection file
require_once '../db/db_connection.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle product addition to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $productId = intval($_POST['product_id']);
        $productName = $_POST['product_name'] ?? '';
        $productPrice = floatval($_POST['product_price']);
        $productImage = $_POST['product_image'] ?? '';
        
        if ($productId > 0 && $productPrice > 0) {
            // Check if the product is already in the cart
            $productExists = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] === $productId) {
                    $item['quantity']++; // Increment the quantity
                    $productExists = true;
                    break;
                }
            }
            
            // If the product doesn't exist, add it
            if (!$productExists) {
                $_SESSION['cart'][] = [
                    'id' => $productId,
                    'name' => $productName,
                    'price' => $productPrice,
                    'image' => $productImage,
                    'quantity' => 1
                ];
            }
        }
    }

    // Handle quantity changes
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['increase_quantity']) && isset($_POST['product_id'])) {
            $productId = intval($_POST['product_id']);
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] === $productId) {
                    $item['quantity']++; // Increment the quantity
                    break;
                }
            }
        }

        if (isset($_POST['decrease_quantity']) && isset($_POST['product_id'])) {
            $productId = intval($_POST['product_id']);
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] === $productId && $item['quantity'] > 1) {
                    $item['quantity']--; // Decrease quantity
                    break;
                }
            }
        }
    }
    
    // Handle product removal
    if (isset($_POST['remove_product_id'])) {
        $productId = intval($_POST['remove_product_id']);
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id'] === $productId) {
                unset($_SESSION['cart'][$index]);
                break;
            }
        }
        // Reorganize cart indices
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    // Handle cart emptying
    if (isset($_POST['empty_cart'])) {
        $_SESSION['cart'] = [];
    }

    // Redirect to avoid form resubmission
    header('Location: cart.php');
    exit;
}

// Display the cart (only if not a POST request)
$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras | Switch Store Ec</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cart.css">
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
        <div class="cart">
            <h1 style="display: none;">Switch Store Ec - Carrito de Compras</h1>
            <?php if (!empty($cart)): ?>
                <ul class="cart-list">
                    <?php
                    $total = 0; // Variable to calculate the total of the cart

                    foreach ($cart as $index => $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <li class="cart-item">
                            <div class="product-info">
                                <img src="../img/products/<?php echo $item['image']; ?>" alt="Imagen del producto: <?php echo $item['name']; ?>" class="product-image">
                                <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                            </div>
                            <div class="product-details">
                                <form action="cart.php" method="POST" class="quantity-form">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    
                                    <!-- Button to decrease quantity -->
                                    <button type="submit" name="decrease_quantity" 
                                            class="quantity-btn decrease-btn"
                                            <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>
                                        -
                                    </button>
                                    
                                    <!-- Current quantity -->
                                    <input type="number" value="<?php echo $item['quantity']; ?>" readonly>
                                    
                                    <!-- Button to increase quantity -->
                                    <button type="submit" name="increase_quantity" class="quantity-btn increase-btn">+</button>
                                </form>
                                <p>Precio Unitario: $<?php echo number_format($item['price'], 2); ?></p>
                                <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                                <form action="cart.php" method="POST">
                                    <input type="hidden" name="remove_product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="remove-btn">Eliminar</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="cart-summary">
                    <h2>Total: $<?php echo number_format($total, 2); ?></h2>
                    <form action="checkout_user.php" method="POST">
                        <?php foreach ($cart as $item): ?>
                            <input type="hidden" name="products[]" value="<?php echo htmlspecialchars(json_encode($item)); ?>">
                        <?php endforeach; ?>
                        <input type="hidden" name="total" value="<?php echo $total; ?>">
                        <button type="submit" name="place_order" class="place-order-btn">Finalizar compra</button>
                    </form>
                    <form action="cart.php" method="POST">
                        <button type="submit" name="empty_cart" class="empty-cart-btn">Vaciar carrito</button>
                    </form>
                </div>
            <?php else: ?>
                <p>Tu carrito está vacío.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer section -->
    <footer>
        <p>© 2024 Switch Store Ec</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>