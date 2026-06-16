<?php
session_start();
require_once 'config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    
    // 1. Get customer details from form and sanitize
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // 2. Check if customer already exists by email
    $customer_query = "SELECT id FROM customers WHERE email = '$email'";
    $customer_result = mysqli_query($conn, $customer_query);
    
    if(mysqli_num_rows($customer_result) > 0) {
        $customer_row = mysqli_fetch_assoc($customer_result);
        $customer_id = $customer_row['id'];
        
        // Update their details just in case they changed
        mysqli_query($conn, "UPDATE customers SET full_name='$full_name', phone='$phone', address='$address' WHERE id=$customer_id");
    } else {
        // Create new customer
        $insert_customer = "INSERT INTO customers (full_name, email, phone, address) VALUES ('$full_name', '$email', '$phone', '$address')";
        mysqli_query($conn, $insert_customer);
        $customer_id = mysqli_insert_id($conn);
    }

    // 3. Calculate total order amount
    $cart_total = 0;
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    $prod_query = "SELECT id, price FROM products WHERE id IN ($product_ids)";
    $prod_result = mysqli_query($conn, $prod_query);
    
    // Store product prices for order_items table later
    $product_prices = [];
    while($row = mysqli_fetch_assoc($prod_result)) {
        $product_prices[$row['id']] = $row['price'];
        $cart_total += $row['price'] * $_SESSION['cart'][$row['id']];
    }

    // 4. Create the Order
    $insert_order = "INSERT INTO orders (customer_id, total_amount, status) VALUES ($customer_id, $cart_total, 'Pending')";
    mysqli_query($conn, $insert_order);
    $order_id = mysqli_insert_id($conn);

    // 5. Create Order Items and update stock
    foreach($_SESSION['cart'] as $id => $quantity) {
        $price = $product_prices[$id];
        $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES ($order_id, $id, $quantity, $price)";
        mysqli_query($conn, $insert_item);
        
        // Reduce stock quantity
        mysqli_query($conn, "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE id = $id");
    }

    // 6. Clear the cart
    $_SESSION['cart'] = [];

    // 7. Redirect to success page
    header("Location: order_success.php?order_id=" . $order_id);
    exit;

} else {
    // If accessed directly without post data or empty cart
    header('Location: index.php');
    exit;
}
?>
