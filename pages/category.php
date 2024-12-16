<?php
// Include the database connection file
require_once '../db/db_connection.php';

// Initialize variables
$categoryId = null;
$title = "Categoría no encontrada";
$products = [];
$categoryName = null;

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $categoryId = intval($_GET['id']);
    $categoryName = getCategoryName($conn, $categoryId);
    
    if ($categoryName) {
        // Update the title
        $title = htmlspecialchars($categoryName);
        
        // Get products for the category
        $products = getProductsByCategory($conn, $categoryId);
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
</head>
<body>

    <!-- Header section containing the website title and navigation menu -->
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
        <?php if ($categoryName): ?>
            <h1 style="display: none;">Switch Store Ec - <?php echo $categoryName; ?></h1>
            <?php if (count($products) > 0): ?>
                <div class="products-row">
                    <?php foreach ($products as $product): ?>
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="product-card">
                            <img src="../img/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="width: 180px; height: 180px;">
                            <h3><?php echo $product['name']; ?></h3>
                            <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No hay productos disponibles en esta categoría.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Categoría no encontrada.</p>
        <?php endif; ?>
    </main>

    <!-- Footer section -->
    <footer>
        <p>© 2024 Switch Store Ec</p>
    </footer>

</body>
</html>

<?php $conn->close(); ?>