<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// load the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$admin_username = $_ENV['ADMIN_USER'];   // Admin user
$admin_password = $_ENV['ADMIN_PASS'];   // Admin password

// Start session to access $_SESSION
session_start();

// Include the database connection file
require_once '../db/db_connection.php';

// Manejar logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header('Location: admin_panel.php'); // Redirect to login
    exit;
}

// Verify if the login form was sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Verify the credentials
    if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_panel.php'); // Redirect to administration panel
        exit;
    } else {
        $error_message = 'Usuario o contraseña incorrectos.';
    }
}

// Manage the delete of products and categories
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['delete_product_id'])) {
        $productId = intval($_GET['delete_product_id']);
        $message = deleteProduct($conn, $productId);
        echo "<script>
            alert('" . addslashes($message) . "');
            window.location.href = 'admin_panel.php';
        </script>";
        exit;
    }
    if (isset($_GET['delete_category_id'])) {
        $categoryId = intval($_GET['delete_category_id']);
        $message = deleteCategory($conn, $categoryId);
        echo "<script>
            alert('" . addslashes($message) . "');
            window.location.href = 'admin_panel.php';
        </script>";
        exit;
    }
}

// Verify if the administrator is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    //Show the login form of the administrator is not authenticated
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width: 100%, initial-scale=1.0">
        <title>Inicio de Sesión | Switch Store Ec</title>
        <link rel="stylesheet" href="../css/style.css">
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
            <h2>Inicio de sesión de administrador</h2>
            <form method="POST" action="admin_panel.php">
                <div class="form-item">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-item">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="login-btn">Iniciar sesión</button>
            </form>
            <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>
        </main>

        <!-- Footer section -->
        <footer>
            <p>© 2024 Switch Store Ec</p>
        </footer>
    </body>
    </html>
    <?php
    exit;
}

// Get all purchases
$purchases = getPurchases($conn);

// Get all products
$products = getProducts($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width: 100%, initial-scale=1.0">
    <title>Panel de Administración | Switch Store Ec</title>
    <link rel="stylesheet" href="../css/style.css">
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
        <h1>Panel de administración</h1>
        
        <!-- Manage Purchases -->
        <h2>Pedidos Realizados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchases as $purchase) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($purchase['id']); ?></td>
                        <td><?php echo htmlspecialchars($purchase['customer_email']); ?></td>
                        <td>$ <?php echo htmlspecialchars($purchase['total_amount']); ?></td>
                        <td><?php echo htmlspecialchars($purchase['status']); ?></td>
                        <td>
                            <a class="details" href="purchase.php?id=<?php echo $purchase['id']; ?>">Ver detalles</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Manage Categories -->
        <h2>Gestionar Categorías</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Categoría</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['id']); ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td>
                            <a class="delete" href="admin_panel.php?delete_category_id=<?php echo $category['id']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Manage Products -->
        <h2>Gestionar Productos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Num Visitas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars(getCategoryName($conn, $product['category_id'])); ?></td>
                        <td>$ <?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['visit_count']); ?></td>
                        <td>
                            <a class="delete" href="admin_panel.php?delete_product_id=<?php echo $product['id']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Form to logout -->
        <form method="POST" action="admin_panel.php">
            <button type="submit" name="logout" class="logout-btn">Cerrar sesión</button>
        </form>
    </main>

    <!-- Footer section -->
    <footer>
        <p>© 2024 Switch Store Ec</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>