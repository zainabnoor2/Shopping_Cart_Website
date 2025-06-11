<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Sample products - consistent with home.php and cart.php
$products = [
    1 => ['name' => 'Wireless Headphones', 'price' => 99.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/b8c51358-2079-4172-bff2-e4a50165d6dd.png'],
    2 => ['name' => 'Smart Watch', 'price' => 149.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/81e96e74-1798-45da-ba3b-674f856d46b4.png'],
    3 => ['name' => 'Portable Speaker', 'price' => 79.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/c4b9dc95-4569-4d35-abc7-7fbb21dbae66.png'],
    4 => ['name' => 'Bluetooth Keyboard', 'price' => 49.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/4b119575-bf18-49c9-89ed-2ab9e6846121.png'],
    5 => ['name' => 'Gaming Mouse', 'price' => 39.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/90c72abf-3b16-4d57-9b28-8a18d4083050.png'],
    6 => ['name' => 'HD Webcam', 'price' => 59.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/44585251-0455-457a-8e60-0090fa492b0d.png'],
    7 => ['name' => 'Laptop Stand', 'price' => 29.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/8c104a47-5cd9-41e7-abe6-d261f2f56c3e.png'],
    8 => ['name' => 'External Hard Drive', 'price' => 89.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/0b10e916-398e-434a-b5cd-96606e26dab6.png'],
    9 => ['name' => 'Smartphone Tripod', 'price' => 19.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/c777faa9-c148-4628-a893-66e6ea1418be.png'],
    10 => ['name' => 'Noise Cancelling Earbuds', 'price' => 129.99, 'image' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/1ee968e9-1f65-448f-a265-b4aea7b24cba.png'],
];

if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
    // Redirect to home if cart empty
    header('Location: home.php');
    exit;
}

$cartItems = $_SESSION['cart'];
$totalPrice = 0.0;
foreach ($cartItems as $id => $qty) {
    if (isset($products[$id])) {
        $totalPrice += $products[$id]['price'] * $qty;
    }
}

$orderPlaced = false;
// Process order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    // In a real app, here you would save order to DB, handle payment etc.
    // For now simulate order placement
    $_SESSION['cart'] = []; // Clear cart
    $orderPlaced = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ElectroBay - Checkout</title>
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
        padding: 0 24px 32px 24px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    h2 {
        font-weight: 700;
        font-size: 24px;
        padding: 24px 0 16px 0;
        border-bottom: 1px solid #e5e7eb;
        margin: 0;
    }
    ul.order-summary {
        list-style: none;
        padding: 0;
        margin-top: 24px;
    }
    ul.order-summary li {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
        font-size: 16px;
    }
    ul.order-summary li strong {
        font-weight: 700;
    }
    .total {
        font-size: 20px;
        font-weight: 700;
        padding: 20px 0 10px 0;
        text-align: right;
        color: #111827;
    }
    .btn, button {
        background-color: #2563eb;
        color: #fff;
        border: none;
        padding: 14px 20px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: flex;
        justify-content: center;
        gap: 8px;
        align-items: center;
        margin-top: 24px;
    }
    .btn:hover, button:hover {
        background-color: #1e40af;
    }
    .success-message {
        padding: 40px 0;
        font-size: 20px;
        font-weight: 700;
        color: #16a34a;
        text-align: center;
    }
    @media (max-width: 640px) {
        main {
            margin: 32px 16px;
            padding: 16px;
        }
        ul.order-summary li {
            font-size: 14px;
        }
        h2 {
            font-size: 20px;
            padding-bottom: 12px;
        }
    }
</style>
</head>
<body>
<header>
    <h1>ShopEasy</h1>
    <nav>
        <a href="cart.php" aria-label="Back to Cart">
            <span class="material-icons" aria-hidden="true">shopping_cart</span> Cart
        </a>
        <form method="POST" action="logout.php" style="display:inline;">
            <button type="submit" class="btn-logout" aria-label="Logout">Logout</button>
        </form>
    </nav>
</header>
<main>
<?php if ($orderPlaced): ?>
    <div class="success-message" role="alert" aria-live="polite">
        <span class="material-icons" aria-hidden="true" style="font-size:40px; vertical-align: middle;">check_circle</span>  
        Thank you for your order! Your purchase was successful.
    </div>
    <a href="home.php" class="btn" aria-label="Back to shopping">
        <span class="material-icons" aria-hidden="true">home</span> Continue Shopping
    </a>
<?php else: ?>
    <h2>Order Summary</h2>
    <ul class="order-summary" aria-live="polite">
        <?php foreach ($cartItems as $id => $qty):
            if (!isset($products[$id])) continue;
            $product = $products[$id];
            $subtotal = $product['price'] * $qty;
        ?>
        <li>
            <span><?=htmlspecialchars($product['name'])?> x <?= $qty ?></span>
            <span>$<?= number_format($subtotal, 2) ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
    <p class="total" aria-label="Total price">$<?= number_format($totalPrice, 2) ?></p>

    <form method="POST" action="checkout.php" aria-label="Confirm order form">
        <button type="submit" name="confirm_order" class="btn" aria-live="polite">
            <span class="material-icons" aria-hidden="true">payment</span> Confirm Purchase
        </button>
    </form>
<?php endif; ?>
</main>
</body>
</html>

