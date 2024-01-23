<?php

require_once 'Database.php';

class Cart {

    private $dbModel;

    public function __construct() {
        $this->dbModel = new Database();
    }

    public function addToCart($userID, $productID, $quantity) {
        $conn = $this->dbModel->connect();

        $stmt = $conn->prepare("SELECT * FROM Cart WHERE UserID = ?");
        $stmt->execute([$userID]);
        $existingCart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingCart) {
            $stmt = $conn->prepare("SELECT * FROM Cart WHERE UserID = ? AND ProductID = ?");
            $stmt->execute([$userID, $productID]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
            if($existingItem){
                $stmt = $conn->prepare("UPDATE Cart SET Quantity = Quantity + ? WHERE UserID = ? AND ProductID = ?");
                $stmt->execute([$quantity, $userID, $productID]);
            }else{
                $stmt = $conn->prepare("INSERT INTO Cart (UserID, ProductID, Quantity) VALUES (?, ?, ?)");
                $stmt->execute([$userID, $productID, $quantity]);

            }

        } else {

            $stmt = $conn->prepare("INSERT INTO Cart (UserID, ProductID, Quantity) VALUES (?, ?, ?)");
            $stmt->execute([$userID, $productID, $quantity]);
        }

        return ["success" => true, "message" => "Produto adicionado ao carrinho com sucesso"];
    }

    public function getCartItems($userID) {
        $conn = $this->dbModel->connect();


        $stmt = $conn->prepare("
            SELECT Cart.ProductID, Products.Name, Products.Price, Cart.Quantity
            FROM Cart
            INNER JOIN Products ON Cart.ProductID = Products.ProductID
            WHERE Cart.UserID = ?
        ");
        $stmt->execute([$userID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearCart($userID) {
        $conn = $this->dbModel->connect();
        $stmt = $conn->prepare("DELETE FROM Cart  WHERE UserID = ?");
        $stmt->execute([$userID]);
    }

    public function checkout($userID) {
        $conn = $this->dbModel->connect();
        try {
            $conn->beginTransaction();
            $cartItems = $this->getCartItems($userID);

            if (!empty($cartItems)) {


                $stmt = $conn->prepare("INSERT INTO Orders (UserID, OrderDate) VALUES (?, NOW())");
                $stmt->execute([$userID]);
                $orderID = $conn->lastInsertId();
                foreach ($cartItems as $cartItem) {
                    $stmt = $conn->prepare("INSERT INTO OrderItems (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$orderID, $cartItem['ProductID'], $cartItem['Quantity'], $cartItem['Price']]);
                }

                $conn->commit();
                $this->clearCart($userID);

                return ["success" => true, "message" => "Pedido realizado com sucesso"];
            } else {
                return ["success" => false, "message" => "Não há itens no carrinho para realizar o checkout"];
            }
        } catch (PDOException $e) {

            $conn->rollBack();

            return ["success" => false, "message" => "Erro ao processar o pedido"];
        }
    }

    public function getOrders($userID) {
        $conn = $this->dbModel->connect();

        $stmt = $conn->prepare("
            SELECT Orders.OrderID, Orders.OrderDate, OrderItems.ProductID, Products.Name, OrderItems.Quantity, OrderItems.Price
            FROM Orders
            INNER JOIN OrderItems ON Orders.OrderID = OrderItems.OrderID
            INNER JOIN Products ON OrderItems.ProductID = Products.ProductID
            WHERE Orders.UserID = ?
        ");
        $stmt->execute([$userID]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $groupedOrders = [];
        foreach ($orders as $order) {
            $orderID = $order['OrderID'];
            if (!isset($groupedOrders[$orderID])) {
                $groupedOrders[$orderID] = [
                    'OrderID' => $orderID,
                    'OrderDate' => $order['OrderDate'],
                    'TotalAmount' => 0,
                    'Products' => [],
                ];
            }

            $totalAmount = $groupedOrders[$orderID]['TotalAmount'];
            $totalAmount += $order['Quantity'] * $order['Price'];
            $groupedOrders[$orderID]['TotalAmount'] = $totalAmount;

            $groupedOrders[$orderID]['Products'][] = [
                'Name' => $order['Name'],
                'Quantity' => $order['Quantity'],
                'Price' => $order['Price'],
            ];
        }

        return json_encode(array_values($groupedOrders));

    }
}
