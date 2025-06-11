<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Sample products - should be in sync with home.php
$products = [
    1 => ['name' => 'Wireless Headphones', 'price' => 99.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/44f87ca1-90df-4f3b-9baa-2e5a9c95c132.png'],
    2 => ['name' => 'Smart Watch', 'price' => 149.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/c5631cf9-222b-4451-8e6e-a52a11524c03.png'],
    3 => ['name' => 'Portable Speaker', 'price' => 79.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/c0ef2099-a893-4f8f-9620-0b3968b840e6.png'],
    4 => ['name' => 'Bluetooth Keyboard', 'price' => 49.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/78f9f78f-59a9-4c0f-a60e-0888a09d2e94.png'],
    5 => ['name' => 'Gaming Mouse', 'price' => 39.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/1984f752-ce20-472d-b67f-ed8fcb0406ec.png'],
    6 => ['name' => 'HD Webcam', 'price' => 59.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/91e463c0-a641-4d89-9cc8-86d53a873f05.png'],
    7 => ['name' => 'Laptop Stand', 'price' => 29.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/9f5a494d-b2cc-4864-a357-3413df49da1f.png'],
    8 => ['name' => 'External Hard Drive', 'price' => 89.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/a6693e44-61c4-4ff8-af8f-a10702fd2e8e.png'],
    9 => ['name' => 'Smartphone Tripod', 'price' => 19.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/393050d3-1b96-4e76-aa23-238e5c92986a.png'],
    10 => ['name' => 'Noise Cancelling Earbuds', 'price' => 129.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/aa205de5-318f-47bd-8244-e2b5c9b0646d.png'],
];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart updates (changing quantity/removing items)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        foreach ($_POST['quantities'] as $productId => $qty) {
            $productId = (int)$productId;
            $qty = (int)$qty;
            if ($qty <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId] = $qty;
            }
        }
    } elseif (isset($_POST['remove'])) {
        $productId = (int)$_POST['remove'];
        unset($_SESSION['cart'][$productId]);
    }
    header('Location: cart.php');
    exit;
}

$cartItems = $_SESSION['cart'];

// Calculate total items count and total price
$totalPrice = 0.0;
$totalCount = 0;
foreach ($cartItems as $id => $qty) {
    if (isset($products[$id])) {
        $totalPrice += $products[$id]['price'] * $qty;
        $totalCount += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ElectroBay - Cart</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f9fafb;
        margin: 0;
        color: #374151;
    }
    header {
        background: #fff;
        padding: 16px 32px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 100;
    }
    header h1 {
        margin: 0;
        font-weight: 700;
        font-size: 28px;
        color: #111827;
    }
    nav a {
        margin-left: 24px;
        font-weight: 600;
        font-size: 16px;
        color: #2563eb;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    nav a:hover {
        text-decoration: underline;
    }
    nav a .material-icons {
        font-size: 20px;
    }
    .btn-logout {
        background: none;
        border: none;
        color: #dc2626;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    .btn-logout:hover {
        color: #b91c1c;
    }
    main {
        max-width: 900px;
        margin: 48px auto;
        padding: 0 24px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        padding-bottom: 32px;
    }
    h2 {
        font-weight: 700;
        font-size: 24px;
        padding: 24px 0 16px 0;
        border-bottom: 1px solid #e5e7eb;
        margin: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 24px;
    }
    th, td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    th {
        font-weight: 700;
        color: #374151;
    }
    .product-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
    input[type="number"] {
        width: 60px;
        padding: 6px;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 16px;
        text-align: center;
    }
    .btn {
        background-color: #2563eb;
        color: #fff;
        border: none;
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: inline-flex;
        justify-content: center;
        gap: 8px;
        align-items: center;
    }
    .btn:hover {
        background-color: #1e40af;
    }
    .btn-remove {
        background-color: transparent;
        color: #dc2626;
        font-size: 24px;
        border: none;
        cursor: pointer;
        padding: 0;
    }
    .btn-remove:hover {
        color: #b91c1c;
    }
    .actions {
        display: flex;
        justify-content: space-between;
        padding: 24px 0;
        border-top: 1px solid #e5e7eb;
    }
    .total {
        font-weight: 700;
        font-size: 20px;
        color: #111827;
    }
    .empty-msg {
        padding: 40px 0;
        font-size: 18px;
        color: #6b7280;
        text-align: center;
    }
    @media (max-width: 640px) {
        main {
            margin: 32px 16px;
            padding: 16px;
        }
        th, td {
            padding: 8px 12px;
            font-size: 14px;
        }
        .product-image {
            width: 60px;
            height: 45px;
        }
        input[type="number"] {
            width: 50px;
            font-size: 14px;
        }
    }
</style>
</head>
<body>
<header>
    <h1>ElectroBay</h1>
    <nav>
        <a href="home.php" aria-label="Back to Home">
            <span class="material-icons" aria-hidden="true">home</span> Home
        </a>
        <form method="POST" action="logout.php" style="display:inline;">
            <button type="submit" class="btn-logout" aria-label="Logout">Logout</button>
        </form>
    </nav>
</header>
<main>
    <h2>Your Shopping Cart</h2>
    <?php if (empty($cartItems)): ?>
        <p class="empty-msg">Your cart is empty. <a href="home.php">Start shopping now</a>.</p>
    <?php else: ?>
        <form method="POST" action="cart.php" aria-label="Update cart items">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cartItems as $id => $qty):
                    if (!isset($products[$id])) continue;
                    $product = $products[$id];
                    $subtotal = $product['price'] * $qty;
                ?>
                    <tr>
                        <td>
                            <img src="<?=htmlspecialchars($product['image'])?>" alt="<?=htmlspecialchars($product['name'])?>" class="product-image" onerror="this.onerror=null;this.src='https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/5f4a4257-3a7e-47a2-98b7-f6e19cca1a3e.png';" />
                            <div><?=htmlspecialchars($product['name'])?></div>
                        </td>
                        <td>$<?=number_format($product['price'], 2)?></td>
                        <td>
                            <input type="number" name="quantities[<?=$id?>]" value="<?=$qty?>" min="0" aria-label="Quantity for <?=htmlspecialchars($product['name'])?>" />
                        </td>
                        <td>$<?=number_format($subtotal, 2)?></td>
                        <td>
                            <button type="submit" name="remove" value="<?=$id?>" class="btn-remove" aria-label="Remove <?=htmlspecialchars($product['name'])?>">
                                <span class="material-icons" aria-hidden="true">delete</span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="actions">
                <button type="submit" name="update" value="1" class="btn" aria-label="Update cart quantities">
                    <span class="material-icons" aria-hidden="true">update</span> Update Cart
                </button>
                <div class="total" aria-live="polite">Total: $<?=number_format($totalPrice, 2)?></div>
                <a href="checkout.php" class="btn" aria-label="Proceed to checkout">
                    <span class="material-icons" aria-hidden="true">payment</span> Checkout
                </a>
            </div>
        </form>
    <?php endif; ?>
</main>
</body>
</html>

