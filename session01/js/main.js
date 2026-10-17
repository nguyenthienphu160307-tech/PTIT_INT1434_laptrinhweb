let price = 250000;
console.log(typeof price);

price = "hai trăm";
const SHOP = "MiniShop";
console.log(typeof price);
console.log(Math.floor(7 / 2));
console.log("5" == 5);
console.log("5" === 5);
console.log("5" + 5);
console.log(Number("5") + 5);

let tenSanPham = "Chuột M1";
console.log(`Sản phẩm: ${tenSanPham}`);

function formatPrice(amount) {
    return amount.toLocaleString("vi-VN") + " đ";
}

function parsePrice(text) {
    return Number(text.replace(/\D/g, ""));
}

const products = [
    { id: 1, name: "Chuột không dây M1", price: 250000 },
    { id: 2, name: "Bàn phím cơ K2", price: 790000 },
    { id: 3, name: "Tai nghe Bluetooth T3", price: 450000 },
];
console.log(products.length);
console.log(products[0].name);

let total = 0;
for (let i = 0; i < products.length; i++) {
    total += products[i].price;
}
console.log("Tổng:", formatPrice(total));

for (const product of products) {
    console.log(`${product.name}: ${formatPrice(product.price)}`);
}

products.forEach(function (product) {
    console.log(product.id, product.name);
});

products.push({ id: 4, name: "USB 64GB U4", price: 150000 });
console.log(products.length);

const cheap = products.filter(function (product) {
    return product.price < 300000;
});
console.log(cheap);

const CART_KEY = "minishop_cart";
function getCart() {
    try {
        const data = localStorage.getItem(CART_KEY);
        return data ? JSON.parse(data) : {};
    } catch (error) {
        return {};
    }
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function getCartBadge() {
    let badge = document.querySelector("#cart-count");
    if (!badge) {
        const cartLink = document.querySelector('.main-nav a[href="cart.html"]');
        if (!cartLink) {
            return null;
        }
        badge = document.createElement("span");
        badge.id = "cart-count";
        badge.className = "badge";
        cartLink.appendChild(badge);
    }

    return badge;
}

function updateCartCount() {
    const cart = getCart();
    let count = 0;
    for (const id in cart) {
        count += cart[id];
    }
    const badge = getCartBadge();
    if (badge) {
        badge.textContent = count;
        badge.hidden = count === 0;
    }
}

function addToCart(id) {
    const cart = getCart();
    cart[id] = (cart[id] || 0) + 1;
    saveCart(cart);
    updateCartCount();
}

function createAddButton(id) {
    const button = document.createElement("button");
    button.type = "button";
    button.className = "btn add-to-cart";
    button.dataset.id = id;
    button.textContent = "Thêm vào giỏ";
    button.addEventListener("click", function () {
    addToCart(button.dataset.id);
    button.textContent = "Đã thêm ✓";
    setTimeout(function () {
    button.textContent = "Thêm vào giỏ";
}, 1000);
});
return button;
}

document.querySelectorAll(".product-card").forEach(function (card, index) {
    card.appendChild(createAddButton(index + 1));
});

const productDetail = document.querySelector(".product-detail");
if (productDetail) {
    const backLink = productDetail.querySelector("p:last-child");
    productDetail.insertBefore(createAddButton(1), backLink);
}
updateCartCount();
function updateCartTotals(table) {
    let sum = 0;
    let totalCell = null;
    table.querySelectorAll("tbody tr").forEach(function (row) {
    const qtyInput = row.querySelector('input[type="number"]');
        if (!qtyInput) {
            totalCell = row.querySelector("td:last-child");
            return;
        }
        const cells = row.querySelectorAll("td");
        const unitPrice = parsePrice(cells[1].textContent);
        let qty = Number(qtyInput.value);
        if (!qty || qty < 0) {
            qty = 0;
        }

        const lineTotal = unitPrice * qty;
        cells[3].textContent = formatPrice(lineTotal);
        sum += lineTotal;
    });

    if (totalCell) {
        totalCell.innerHTML = "<strong>" + formatPrice(sum) + "</strong>";
    }
}

const cartTable = document.querySelector(".cart-table");

if (cartTable) {
    cartTable.addEventListener("input", function () {
        updateCartTotals(cartTable);
    });

    updateCartTotals(cartTable);
}

const password2Input = document.querySelector("#password2");

if (password2Input) {
    const registerForm = password2Input.closest("form");
    const passwordInput = document.querySelector("#password");

    const error = document.createElement("span");
    error.id = "password-error";
    error.className = "error";
    password2Input.insertAdjacentElement("afterend", error);

    registerForm.addEventListener("submit", function (event) {
        if (passwordInput.value !== password2Input.value) {
            event.preventDefault();
            error.textContent = "Hai mật khẩu không khớp nhau.";
        } else {
            error.textContent = "";
        }
    });

    password2Input.addEventListener("input", function () {
        error.textContent = "";
    });
}