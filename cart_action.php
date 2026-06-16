<?php
session_start();

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    // Initialize cart if it doesn't exist
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if($action == 'add' && $product_id > 0) {
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        // If product already in cart, add to quantity
        if(isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    } 
    elseif($action == 'remove' && $product_id > 0) {
        if(isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    } 
    elseif($action == 'update' && $product_id > 0) {
        $quantity = (int)$_POST['quantity'];
        if($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    elseif($action == 'clear') {
        $_SESSION['cart'] = [];
    }

    // Redirect back to cart
    header('Location: cart.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>
