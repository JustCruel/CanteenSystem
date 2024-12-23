<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/pos.css"/>
    <style>
        /* Layout adjustments */
        .top-bar {
            z-index: 100;
        }

        .category-container, .product-container {
            padding: 10px;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* Category Styling */
        .category-card-container {
            display: flex;
            justify-content: space-between;
            overflow-x: auto;
            height: auto;
        }

        .category-item {
            background-color: #007bff;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            padding: 10px;
            margin: 0 10px;
            text-align: center;
            min-width: 100px;
        }

        .category-item h4 {
            font-size: 14px;
        }

        .category-item:hover {
            background-color: #0056b3;
        }

        /* Product Styling */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 15px;
        }

        .product-card {
    position: relative;
    width: 200px;
    height: 200px;
    border-radius: 8px;
    background-color: #ffffff;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-card img {
    width: 100%;
    height: auto;
    max-height: 100px; /* Limits the image height */
    object-fit: contain;
    border-radius: 8px;
    margin: auto; /* Centers the image vertically and horizontally */
}
.checkout-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 15px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

.product-card h4 {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.product-card p.price {
    position: absolute;
    bottom: 10px;
    left: 10px;
    font-size: 1em;
    color: #1a73e8;
    font-weight: bold;
    margin: 0;
}

.product-card p.stock {
    position: absolute;
    bottom: 10px;
    right: 10px;
    font-size: 0.9em;
    color: #666;
    margin: 0;
}

.product-card hr {
    display: none; /* Remove the line if not needed */
}

#barcode-input {
            position: absolute;
            top: 1px;
        }

    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand logo" href="#">
                <h1>Canteen Point of Sale</h1>
            </a>
            <div class="top-bar-right">
                <a href="logout.php" class="btn btn-outline-light logout-btn">Logout</a>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5 pt-5">
        <div class="row">
            <!-- Left Side - Categories and Products -->
            <div class="col-lg-8 d-flex flex-column">
                <!-- Category Section -->
                <div class="category-container">
                    <h3>Categories</h3>
                    <div class="category-card-container">
                        <div class="category-item" onclick="fetchAllProducts()">All Products</div>
                        <!-- Dynamic category items will be added here via JavaScript -->
                    </div>
                </div>

                <!-- Product Section -->
                <div class="product-container">
                    <input type="text" id="search-box" placeholder="Search for products..." class="form-control mb-3" onkeyup="filterProducts()">
                    <h3>Products</h3>
                    <div class="product-grid">
                        <!-- Product cards will be added here dynamically -->
                    </div>
                </div>
            </div>

            <!-- Right Side - Checkout -->
            <div class="col-lg-4 checkout-container">
                <h2>Checkout</h2>
                <div class="checkout-content">
                    <table class="checkout-table table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                    <!-- Cart items will be dynamically populated here -->
                </tbody>
            </table>
            <div class="horizontal-line"></div>
       
        </div>
        <div class="checkout-actions mt-3">
        <div id="total-amount" class="fw-bold">Total: ₱0</div>
            <button class="btn btn-success btn-block" onclick="confirmSale()">Place Order</button>
            <button id="cancel-order-btn" class="btn btn-danger btn-block" onclick="confirmCancelOrder()">Cancel Order</button>

            <input type="text" id="barcode-input" placeholder="Scan barcode here..." autofocus class="form-control mb-3">
                
            <input type="text" id="rfid-input" class="form-control mt-3" placeholder="Scan RFID" />
        </div>
        <!-- <button class="btn btn-warning mt-3" onclick="openReturnSaleModal()">Return Sale</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Return Sale Modal -->
<div id="returnSaleModal" class="modal" tabindex="-1" aria-labelledby="returnSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnSaleModalLabel">Select Transaction for Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <select id="transaction-select" class="form-select mb-3" onchange="loadTransactionDetails()">
                    <option value="">Select Transaction</option>
                    <!-- Populate with transaction options via JavaScript -->
                </select>
                <div id="transaction-details">
                    <!-- This section will be dynamically populated with transaction products -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="processReturn()">Return Selected Quantities</button>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/pos.js"></script>
    <script>

function openReturnSaleModal() {
            // Fetch transactions for dropdown
            fetch('get_transactions.php')
                .then(response => response.json())
                .then(data => {
                    let transactionSelect = document.getElementById('transaction-select');
                    transactionSelect.innerHTML = '<option value="">Select Transaction</option>';
                    data.forEach(transaction => {
                        transactionSelect.innerHTML += `<option value="${transaction.transaction_number}">Transaction #${transaction.transaction_number}</option>`;
                    });
                    document.getElementById('returnSaleModal').style.display = 'block';
                });
        }

        // Handle modal close event and reload the page
        document.querySelectorAll('#returnSaleModal .btn-close, #returnSaleModal .btn-secondary').forEach(function(button) {
            button.addEventListener('click', function() {
                document.getElementById('returnSaleModal').style.display = 'none';
                location.reload();
            });
        });

        function loadTransactionDetails() {
            const transactionNumber = document.getElementById('transaction-select').value;
            if (!transactionNumber) return;

            // Fetch the transaction details using transaction_number
            fetch(`get_transaction_details.php?transaction_number=${transactionNumber}`)
                .then(response => response.json())
                .then(data => {
                    let transactionDetails = document.getElementById('transaction-details');
                    transactionDetails.innerHTML = '';

                    if (data.products && data.products.length > 0) {
                        data.products.forEach(product => {
                            transactionDetails.innerHTML += `
                                <div class="form-group mb-3">
                                    <label>${product.product_name} - Sold Quantity: ${product.quantity}</label>
                                    <input type="number" class="form-control" data-product-id="${product.product_id}" max="${product.quantity}" min="0" placeholder="Return Quantity">
                                </div>
                            `;
                        });
                    } else {
                        transactionDetails.innerHTML = '<p>No products found for this transaction.</p>';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function processReturn() {
            const quantityInputs = document.querySelectorAll('#transaction-details input[type="number"]');
            const selectedProducts = Array.from(quantityInputs)
                .map(input => {
                    const quantity = parseInt(input.value);
                    const maxQuantity = parseInt(input.max);

                    if (quantity > 0 && quantity <= maxQuantity) {
                        return { product_id: input.getAttribute('data-product-id'), quantity: quantity };
                    } else {
                        return null;
                    }
                })
                .filter(item => item !== null);

            if (selectedProducts.length === 0) {
                alert("Please enter a valid quantity for at least one product.");
                return;
            }

            // SweetAlert confirmation before proceeding
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to process this return?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, return it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send the selected products and quantities to PHP for processing the return
                    const transactionNumber = document.getElementById('transaction-select').value;
                    if (!transactionNumber) {
                        alert("Transaction number is required.");
                        return;
                    }

                    fetch('return_sale.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ products: selectedProducts, transaction_number: transactionNumber })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Return processed successfully.',
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to process return: ' + data.error,
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        }

        function updateCheckoutSection() {
            fetch('get_items_cart.php')
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let cartItems = document.getElementById('cart-items');
                        let totalAmount = 0;
                        cartItems.innerHTML = '';
                        data.forEach(item => {
                            const subtotal = item.price * item.quantity;
                            totalAmount += subtotal;
                            cartItems.innerHTML += `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.quantity}</td>
                                    <td>₱${subtotal.toFixed(2)}</td>
                                </tr>
                            `;
                        });
                        document.getElementById('total-amount').innerText = `Total: ₱${totalAmount.toFixed(2)}`;
                    }
                });
        }

        function displayCategories(categories) {
            const categoryCardContainer = document.querySelector('.category-card-container');
            categoryCardContainer.innerHTML = '<div class="category-item" onclick="fetchAllProducts()">All Products</div>';
            categories.forEach(category => {
                categoryCardContainer.innerHTML += `
                    <div class="category-item" onclick="fetchProducts('${category.name}')">${category.name}</div>
                `;
            });
        }

        function displayProducts(productsToShow) {
            const productContainer = document.querySelector('.product-grid');
            productsToShow.sort((a, b) => a.name.localeCompare(b.name));
            productContainer.innerHTML = '';
            productsToShow.forEach(product => {
                if (parseInt(product.quantity) > 0) {
                productContainer.innerHTML += `
                    <div class="product-card" onclick="addToCart(${product.id}, '${product.name}', ${product.selling_price}, ${product.quantity})">
                        <img src="assets/images/${product.image}" alt="${product.name}">
                        <hr>
                        <h4>${product.name}</h4>
                        <p class="price">₱${product.selling_price}</p>
                        <p class="stock">Stock: ${product.quantity}</p>
                    </div>
                `;
         }
         });
        }

      



    </script>
</body>
</html>
