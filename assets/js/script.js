document.addEventListener('DOMContentLoaded', function () {
    fetchProducts();
    fetchCartItems();
});

function fetchProducts() {

    fetch('index.php?action=getProducts')
        .then(response => response.json())
        .then(products => {
            displayProducts(products);
        })
        .catch(error => console.error('Erro ao obter a lista de produtos:', error));
}

function displayProducts(products) {
    const productList = document.getElementById('product-list');

    productList.innerHTML = '';

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('product-card');
        productCard.innerHTML = `
            <img src="${product.ImageURL}" alt="${product.Name}">
            <h3>${product.Name}</h3>
            <p>${product.Description}</p>
            <p>Preço: $${product.Price}</p>
            <button onclick="addToCart(${product.ProductID})">Adicionar ao Carrinho</button>
        `;
        productList.appendChild(productCard);
    });
}

function addToCart(productID) {
    const userID = 1;
    const quantity = 1;

    fetch('index.php?action=addToCart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `userID=${userID}&productID=${productID}&quantity=${quantity}`,
    })
        .then(response => response.json())
        .then(result => {
            showMessage(result.message);
            fetchCartItems();
        })
        .catch(error => {
            showMessage('Erro ao adicionar produto ao carrinho.');
            console.error('Erro ao adicionar produto ao carrinho:', error);
        });
}

function fetchCartItems() {
    const userID = 1;

    fetch(`index.php?action=getCartItems&userID=${userID}`)
        .then(response => response.json())
        .then(cartItems => {
            displayCartItems(cartItems);
        })
        .catch(error => console.error('Erro ao obter itens do carrinho:', error));
}

function displayCartItems(cartItems) {
    const cartList = document.getElementById('cart-list');
    cartList.innerHTML = '';

    cartItems.forEach(cartItem => {
        const cartItemRow = document.createElement('div');
        cartItemRow.classList.add('cart-item');
        cartItemRow.innerHTML = `
            <span>${cartItem.Name}</span>
            <span>${cartItem.Quantity}x</span>
        `;
        cartList.appendChild(cartItemRow);
    });
}

function checkout() {
    const userID = 1;

    fetch(`index.php?action=checkout&userID=${userID}`)
        .then(response => response.json())
        .then(result => {
            showMessage(result.message);
            fetchCartItems();
        })
        .catch(error => {
            showMessage('Erro ao processar o pedido.');
            console.error('Erro ao processar o pedido:', error);
        });
}

document.addEventListener('DOMContentLoaded', function () {
    if (window.location.pathname.includes('orders.php')) {
        const userID = 1;
        fetchOrders(userID);
    } else {
        fetchProducts();
        fetchCartItems();
    }
});
function goToOrders() {
    const userID = 1;
    window.location.href = `View/orders.php?userID=${userID}`;
}

function showMessage(message) {
    const messageContainer = document.getElementById('message-container');
    messageContainer.innerText = message;
    messageContainer.classList.add('show');
    setTimeout(() => {
        messageContainer.classList.remove('show');
    }, 3000);
}

