<?php
// Include the database connection file
require_once '../db/db_connection.php';

// Initialize variables
$product = null;
$title = "Producto no encontrado";

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $productId = intval($_GET['id']);
    
    if ($productId > 0) {
        // Get product information using getProductById function
        $product = getProductById($conn, $productId);
        
        if ($product !== null) {
            // Update title
            $title = htmlspecialchars($product['name']);
            
            // Create a new visit record
            createVisit($conn, $productId);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> | Switch Store Ec</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/product.css">
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
        <?php if ($product): ?>
            <div class="product-info">
                <div class="product-image">
                    <img src="../img/products/<?php echo $product['image']; ?>" alt="Imagen del producto: <?php echo $product['name']; ?>">
                </div>
                <div class="product-details">
                    <h1>Switch Store Ec - <?php echo $product['name']; ?></h1>
                    <h2>Precio:</h2>
                    <div class="product-price">$ <?php echo number_format($product['price'], 2, ',', '.'); ?></div>
                    <h2>Descripción:</h2>
                    <p><?php echo $product['description']; ?></p>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image']); ?>">
                        <button type="submit" class="add-to-cart-btn">Añadir al carrito</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>Producto no encontrado</p>
        <?php endif; ?>
    </main>

    <!-- Footer section -->
    <footer>
        <p>© 2024 Switch Store Ec</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>