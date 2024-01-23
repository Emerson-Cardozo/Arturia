<?php

require_once ('Controller/Controller.php');
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller = new Controller();

    switch ($action) {
        case 'getProducts':
            $controller->getProducts();
            break;
        case 'addToCart':
            $userID = $_POST['userID'];
            $productID = $_POST['productID'];
            $quantity = $_POST['quantity'];
            $controller->addToCart($userID, $productID, $quantity);
            break;
        case 'getCartItems':
            $userID = $_GET['userID'];
            $controller->getCartItems($userID);
            break;
        case 'checkout':
            $userID = $_GET['userID'];
            $controller->checkout($userID);
            break;
        case 'showOrders':
            $userID = $_GET['userID'];
            $controller->showOrders($userID);
            exit();
            break;
        default:

            break;
    }
} else {
    include './View/index.html';
}
