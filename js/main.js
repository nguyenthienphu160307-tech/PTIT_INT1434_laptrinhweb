// đổi số thành tiền
function formatPrice(n) {
    return n.toLocaleString("vi-VN") + " đ";
}

var CART_KEY = "minishop_cart";

// lấy giỏ hàng từ localStorage
function getCart() {
    var data = localStorage.getItem(CART_KEY);
    if (data) {
        return JSON.parse(data);
    }
    return {};
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

// cập nhật số lượng trên menu
function updateCartCount() {
    var cart = getCart();
    var count = 0;
    for (var id in cart) {
        count += cart[id];
    }
    var badge = document.getElementById("cart-count");
    if (badge) {
        badge.textContent = count;
    }
}

function addToCart(id) {
    var cart = getCart();
    if (cart[id]) {
        cart[id] = cart[id] + 1;
    } else {
        cart[id] = 1;
    }
    saveCart(cart);
    updateCartCount();
}

// nút thêm vào giỏ
var buttons = document.querySelectorAll(".add-to-cart");
for (var i = 0; i < buttons.length; i++) {
    buttons[i].addEventListener("click", function () {
        addToCart(this.dataset.id);
    });
}

updateCartCount();

// tính tiền trong trang giỏ hàng
function updateCartTotals() {
    var rows = document.querySelectorAll(".cart-table tbody tr");
    var total = 0;
    for (var i = 0; i < rows.length; i++) {
        var price = Number(rows[i].dataset.price);
        var qty = Number(rows[i].querySelector(".qty").value);
        var lineTotal = price * qty;
        rows[i].querySelector(".line-total").textContent = formatPrice(lineTotal);
        total += lineTotal;
    }
    document.getElementById("cart-total").textContent = formatPrice(total);
}

var cartTable = document.querySelector(".cart-table");
if (cartTable) {
    cartTable.addEventListener("input", updateCartTotals);
    updateCartTotals();
}

// xóa giỏ hàng
var clearBtn = document.getElementById("clear-cart");
if (clearBtn) {
    clearBtn.addEventListener("click", function () {
        if (confirm("Xóa toàn bộ giỏ hàng?")) {
            localStorage.removeItem(CART_KEY);
            updateCartCount();
            location.reload();
        }
    });
}

// kiểm tra form đăng ký
var registerForm = document.getElementById("register-form");
if (registerForm) {
    registerForm.addEventListener("submit", function (event) {
        var pass1 = document.getElementById("password").value;
        var pass2 = document.getElementById("password2").value;
        var error = document.getElementById("password-error");

        if (pass1 != pass2) {
            event.preventDefault();
            error.textContent = "Hai mật khẩu không khớp nhau.";
        } else {
            error.textContent = "";
        }
    });
}

// kiểm tra form liên hệ
var contactForm = document.getElementById("contact-form");
if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
        var message = document.getElementById("message").value;
        var error = document.getElementById("message-error");

        if (message.length < 10) {
            event.preventDefault();
            error.textContent = "Nội dung tin nhắn phải có ít nhất 10 ký tự.";
        } else {
            error.textContent = "";
        }
    });
}
