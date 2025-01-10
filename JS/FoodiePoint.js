// JavaScript untuk Smooth Scroll Footer Navigation
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll("footer .link a");

    // Tambahkan event listener pada semua link di footer
    links.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("href").substring(1); // Ambil id dari href
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                // Smooth scroll ke bagian target
                window.scrollTo({
                    top: targetSection.offsetTop - 50, // Tambahkan offset jika diperlukan
                    behavior: "smooth"
                });
            }
        });
    });
});

const cart = [];

function addToCart(itemName, itemPrice) {
    cart.push({ name: itemName, price: itemPrice });
    updateCart();
}

function updateCart() {
    const cartItems = document.getElementById('cart-items');
    const totalPrice = document.getElementById('total-price');
    cartItems.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        total += item.price;
        const listItem = document.createElement('li');
        listItem.textContent = `${item.name} - Rp. ${item.price}`;
        cartItems.appendChild(listItem);
    });

    totalPrice.textContent = `Total: Rp. ${total}`;
}

function checkout() {
    if (cart.length === 0) {
        alert('Keranjang kosong! Silakan tambahkan item ke keranjang.');
        return;
    }

    let orderDetails = 'Anda memesan:\n';
    cart.forEach(item => {
        orderDetails += `- ${item.name} (Rp. ${item.price})\n`;
    });

    const total = cart.reduce((sum, item) => sum + item.price, 0);
    orderDetails += `\nTotal: Rp. ${total}`;

    // Tampilkan pesan konfirmasi
    alert(orderDetails);

    // Kirim data ke server menggunakan AJAX
    cart.forEach(item => {
        fetch('checkout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_name=${encodeURIComponent(item.name)}&quantity=1&total_price=${item.price}`,
        })
        .then(response => response.text())
        .then(data => {
            console.log('Checkout response:', data); // Untuk debug
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Kosongkan keranjang setelah checkout
    cart.length = 0;
    updateCart();
}
