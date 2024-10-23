document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('product-modal');

    // Function to show the modal
    window.showProductModal = function (productId) {
        // Add logic here to fetch and display product details based on productId
        // For this example, we are using static content
        document.getElementById('modal-product-title').innerText = `Product ${productId}`;
        modal.style.display = 'flex';
    };

    // Function to close the modal
    window.closeModal = function () {
        modal.style.display = 'none';
    };

    // Close the modal if the user clicks outside of it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});
//------------------------------------------seller adding.css------------------------------------//
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('product-form');
    const productList = document.getElementById('product-list');
    let products = [];

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        
        // Get form data
        const title = document.getElementById('product-title').value;
        const images = document.getElementById('product-images').files;
        const price = document.getElementById('product-price').value;
        const description = document.getElementById('product-description').value;

        // Basic validation
        if (title && images.length > 0 && price && description) {
            // Add product to the list
            const product = {
                title,
                images,
                price,
                description
            };
            products.push(product);
            displayProducts();

            // Reset form
            form.reset();
        } else {
            alert('Please fill in all fields.');
        }
    });

    function displayProducts() {
        productList.innerHTML = '';
        products.forEach((product, index) => {
            const productCard = document.createElement('div');
            productCard.classList.add('product-card');
            productCard.innerHTML = `
                <img src="${URL.createObjectURL(product.images[0])}" alt="${product.title}" class="product-image">
                <h2 class="product-title">${product.title}</h2>
                <p class="product-cost">$${product.price}</p>
                <button class="buy-button">Buy Product</button>
                <button class="delete-button" onclick="deleteProduct(${index})">Delete</button>
            `;
            productList.appendChild(productCard);
        });
    }

    window.deleteProduct = function (index) {
        products.splice(index, 1);
        displayProducts();
    };
});
