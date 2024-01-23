<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Meus Pedidos</title>
</head>
<body>
<header>
    <h1>Meus Pedidos</h1>
    <a href="../index.php">Voltar para a loja</a>
</header>

<div class="container">
    <section id="order-list"></section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userID = 1;
        fetchOrders(userID);
    });

    function fetchOrders(userID) {
        fetch(`../index.php?action=showOrders&userID=${userID}`)
            .then(response => response.json())
            .then(orders => {
                displayOrders(orders);
            })
            .catch(error => console.error('Erro ao obter a lista de pedidos:', error));
    }

    function displayOrders(orders) {
        const orderList = document.getElementById('order-list');
        orderList.innerHTML = '';
        var ordersParsed = JSON.parse(orders);
        ordersParsed.forEach(order => {
            const orderRow = document.createElement('div');
            orderRow.classList.add('order-row');

            const orderInfo = document.createElement('div');
            orderInfo.classList.add('order-info');
            orderInfo.innerHTML = `
                    <span>Código do Pedido: ${order.OrderID}</span>
                    <span>Data do Pedido: ${order.OrderDate}</span>
                `;
            orderRow.appendChild(orderInfo);

            const orderDetails = document.createElement('div');
            orderDetails.classList.add('order-details');

            order.Products.forEach(product => {
                const productInfo = document.createElement('div');
                productInfo.innerHTML = `
                        <span>Produto: ${product.Name}</span>
                        <span>Quantidade: ${product.Quantity}</span>

                    `;
                orderDetails.appendChild(productInfo);
            });
            const totalAmountColumn = document.createElement('div');
            totalAmountColumn.classList.add('total-amount');
            totalAmountColumn.innerHTML = `<span>Total: $${order.TotalAmount}</span>`;
            orderDetails.appendChild(totalAmountColumn);

            orderRow.appendChild(orderDetails);
            orderList.appendChild(orderRow);
        });
    }
</script>
</body>
</html>
