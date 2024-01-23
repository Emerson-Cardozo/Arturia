<?php
require_once 'Model/Cart.php';
require_once 'Model/Product.php';

class Controller {

    private $productModel;
    private $cartModel;

    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }

    public function getProducts() {
        $products = $this->productModel->getProducts();
        echo json_encode($products);
    }

    public function addToCart($userID, $productID, $quantity) {
        $result = $this->cartModel->addToCart($userID, $productID, $quantity);
        echo json_encode($result);
    }

    public function getCartItems($userID) {
        $cartItems = $this->cartModel->getCartItems($userID);
        echo json_encode($cartItems);
    }

    public function checkout($userID) {
        $result = $this->cartModel->checkout($userID);
        echo json_encode($result);
    }

    public function showOrders($userID) {
        $orders = $this->cartModel->getOrders($userID);
        echo json_encode($orders);

    }


}
