<?php
// Include the database connection file
require_once 'db/db_connection.php';

// Get categories from the database
$categories = getCategories($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Switch Store Ec</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Header section containing the website title and navigation menu -->
    <header>
        <!-- First row: logo and icons -->
        <div class="header-row top-row">
            <div class="logo">
                <a href="index.php">
                    <img src="img/site/logo.webp" alt="Logo" style="width: 150px; height: 150px;">
                </a>
            </div>
            <div class="user-actions">
                <a href="pages/admin_panel.php"><img src="img/site/admin_panel.webp" alt="Admin Panel" style="width: 35px; height: 35px;"></a>
                <a href="pages/cart.php"><img src="img/site/cart.webp" alt="Cart" style="width: 35px; height: 35px;"></a>
            </div>
        </div>

        <!-- Second row: menu -->
        <div class="header-row bottom-row">
            <nav>
                <ul class="menu">
                    <li><a href="index.php">Inicio</a></li>
                    <?php
                    // Display categories
                    foreach ($categories as $category) {
                        ?>
                        <li><a href="pages/category.php?id=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main content area -->
    <main>
        <h1 style="display: none;">Switch Store Ec - Home</h1>
        
        <!-- For each category, create a row -->
        <?php foreach ($categories as $category): ?>
            <div class="category-row">
                <!-- Category card -->
                <a href="pages/category.php?id=<?php echo $category['id']; ?>" class="category-card">
                    <h2><?php echo $category['name']; ?></h2>
                </a>
                
                <!-- Top 4 products for this category -->
                <?php
                $topProducts = getProductsByCategory($conn, $category['id']);
                foreach (array_slice($topProducts, 0, 4) as $product): ?>
                    <a href="pages/product.php?id=<?php echo $product['id']; ?>" class="product-card">
                        <img src="img/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="width: 180px; height: 180px;">
                        <h3><?php echo $product['name']; ?></h3>
                        <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </main>

    <!-- Footer section -->
    <footer>
        <p>© 2024 Switch Store Ec</p>
    </footer>

</body>
</html>

<?php $conn->close(); ?>