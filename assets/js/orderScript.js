document.addEventListener('DOMContentLoaded', function () {
fetchOrders(1);
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
    console.log(orders);
    const orderList = document.getElementById('order-list');
    orderList.innerHTML = '';

    orders.forEach(order => {
        const orderRow = document.createElement('div');
        orderRow.classList.add('order-row');
        orderRow.innerHTML = `
            <span>Código do Pedido: ${order.OrderID}</span>
            <span>Produto: ${order.Name}</span>
            <span>Quantidade: ${order.Quantity}</span>
            <span>Preço: $${order.Price.toFixed(2)}</span>
        `;
        orderList.appendChild(orderRow);
    });
}
