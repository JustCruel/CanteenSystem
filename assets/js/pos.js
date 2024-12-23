let products = [];
        let cart = []; // Initialize cart as an empty array

        window.onload = function() {
            fetchCategories();
            fetchAllProducts();
            updateCart();
        };

    // Fetch products from the server
    function fetchAllProducts() {
            console.log("Fetching all products...");
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_products.php?category=all', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.error) {
                        console.error(response.error);
                    } else {
                        products = response;
                        displayProducts(products);
                    }
                } else {
                    console.error("Error fetching products:", this.status);
                }
            };
            xhr.send();
        }

    // Display products (initially and after filtering)
    function displayProducts(productsToShow) {
        const productContainer = document.querySelector('.product-grid');
        productContainer.innerHTML = '';
    
        productsToShow.forEach(product => {
            productContainer.innerHTML += `
                <div class="product-card" onclick="addToCart(${product.id}, '${product.name}', ${product.selling_price}, ${parseInt(product.quantity)})">
                    <img src="assets/images/${product.image}" alt="${product.name}">
                    <div class="product-info"> <!-- New container for product info -->
                        <h4>${product.name}</h4>
                        <p>₱${product.selling_price}</p>
                        <p>Stock: ${product.quantity}</p>
                    </div>
                </div>
            `;
        });
    }
    
    
    // Filter products based on search input
    function filterProducts() {
    const searchValue = document.getElementById('search-box').value.toLowerCase();
    const filteredProducts = products.filter(product => 
        product.name.toLowerCase().includes(searchValue)
    );
    displayProducts(filteredProducts); // Display filtered products
}


        function fetchCategories() {
            console.log("Fetching categories..."); // Debugging
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_categories.php', true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const categories = JSON.parse(this.responseText);
                    console.log("Categories fetched:", categories); // Debugging
                    if (categories.length === 0) {
                        console.error("No categories found."); // Log if no categories
                    } else {
                        displayCategories(categories);
                    }
                } else {
                    console.error("Error fetching categories:", this.status); // Log HTTP errors
                }
            };
            xhr.send();
        }

        function displayCategories(categories) {
            const categoryCardContainer = document.querySelector('.category-card-container');
            
            // Add a min-height style to ensure uniform card sizes
            const categoryCard = document.querySelector('.category-card');
            categoryCard.style.minHeight = '120px'; // Adjust height to make cards a bit bigger
        
            // Clear existing content
            categoryCard.innerHTML = '';
        
            // Display "All Products" button first as a card
            categoryCard.innerHTML += `
                <div class="category-item card p-3 text-center mb-2" style="max-width: 120px; margin: auto;" onclick="fetchAllProducts()">
                    <h4 class="category-title" style="font-size: 16px;">All</h4>
                </div>
            `;
            
            // Loop through all categories from the database and create cards
            categories.forEach(category => {
                // Category title only
                categoryCard.innerHTML += `
                    <div class="category-item card p-3 text-center mb-2" style="max-width: 120px; margin: auto;" onclick="fetchProducts('${category.name}')">
                        <h4 class="category-title" style="font-size: 16px;">${category.name}</h4>
                    </div>
                `;
            });
        
            // Wrap category cards in a Bootstrap grid for responsiveness
            categoryCardContainer.innerHTML = `
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    ${categoryCard.innerHTML}
                </div>
            `;
        }
        
        // Function to map categories to icons (no longer needed but kept for future reference)
        function getCategoryIcon(categoryName) {
            switch (categoryName.toLowerCase()) {
                case 'drinks':
                    return 'fas fa-coffee'; // Coffee icon
                case 'snacks':
                    return 'fas fa-cookie'; // Cookie icon
                case 'meals':
                    return 'fas fa-utensils'; // Utensils icon
                case 'desserts':
                    return 'fas fa-ice-cream'; // Ice cream icon
                default:
                    return 'fas fa-box'; // Default box icon
            }
        }
        
        
        

        function fetchProducts(category) {
    console.log("Fetching products for category:", category);
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_products.php?category=' + encodeURIComponent(category), true);
    xhr.onload = function() {
        if (this.status === 200) {
            console.log("Response from server:", this.responseText); // Debugging
            try {
                const response = JSON.parse(this.responseText);
                if (response.error) {
                    console.error(response.error);
                } else {
                    products = response;
                    displayProducts(products);
                }
            } catch (e) {
                console.error("Error parsing JSON:", e);
                console.error("Received response:", this.responseText);
            }
        } else {
            console.error("Error fetching products:", this.status);
        }
    };
    xhr.send();
}


// Display products (initially and after filtering)
function displayProducts(productsToShow) {
    const productContainer = document.querySelector('.product-grid');
    productContainer.innerHTML = '';

    // Sort products alphabetically by name before displaying
    productsToShow.sort((a, b) => a.name.localeCompare(b.name));

    productsToShow.forEach(product => {
        productContainer.innerHTML += `
            <div class="product-card" onclick="addToCart(${product.id}, '${product.name}', ${product.selling_price}, ${parseInt(product.quantity)})">
                <img src="assets/images/${product.image}" alt="${product.name}">
                <h4>${product.name}</h4>
                <p>₱${product.selling_price}</p>
                <p>Stock: ${product.quantity}</p>
            </div>
        `;
    });
}

function updateCart() {
    const cartItems = document.getElementById('cart-items');
    const cancelOrderBtn = document.getElementById('cancel-order-btn');

    // Check if there are any items in the cart
    if (cartItems.children.length === 0) {
        cancelOrderBtn.disabled = true; // Disable the button
    } else {
        cancelOrderBtn.disabled = false; // Enable the button
    }
}

         // Add to cart function
        function addToCart(id, name, price, stock) {
            console.log(`Adding to cart: ${name}, stock: ${stock}`);
            const existingProductIndex = cart.findIndex(item => item.id === id);
            if (existingProductIndex > -1) {
                if (cart[existingProductIndex].quantity < stock) {
                    cart[existingProductIndex].quantity++;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Out of Stock',
                        text: 'You cannot add more of this item to the cart because it exceeds available stock.',
                    });
                }
            } else {
                if (stock > 0) {
                    cart.push({ id, name, price, quantity: 1, stock: stock });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Out of Stock',
                        text: 'This product is out of stock.',
                    });
                }
            }
            renderCart();
            updateCart();
        }

function increaseQuantity(id) {
    const item = cart.find(item => item.id === id);
    if (item) {
        // Check if the quantity exceeds available stock
        if (item.quantity < item.stock) {
            item.quantity++;
            renderCart();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Out of Stock',
                text: 'You cannot add more of this item to the cart because it exceeds available stock.',
            });
        }
    }
}
function renderCart() {
            const cartTable = document.querySelector('.checkout-table tbody');
            cartTable.innerHTML = '';

            cart.forEach(item => {
                cartTable.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td>
                            <button class="quantity-button minus" onclick="decreaseQuantity(${item.id})">-</button>
                            <input type="number" value="${item.quantity}" min="1" max="${item.stock}" onchange="updateQuantity(${item.id}, this.value)" style="width: 50px; text-align: center;">
                            <button class="quantity-button plus" onclick="increaseQuantity(${item.id})">+</button>
                        </td>
                        <td>₱${(item.price * item.quantity).toFixed(2)}</td>
                    </tr>
                `;
            });

            calculateTotal();
        }

        function fetchProductByBarcode(barcode) {
    console.log("Fetching product by barcode:", barcode);

    fetch('fetch_product_by_barcode.php?barcode=' + encodeURIComponent(barcode))
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log("Response from fetch_product_by_barcode:", data); // Added for debugging
            
            if (data.success) {
                // Product exists, access it via data.product
                const product = data.product;
                addToCart(product.id, product.name, product.selling_price, product.quantity);
            } else {
                // If product does not exist
                Swal.fire({
                    icon: 'error',
                    title: 'Product Not Found',
                    text: data.error || 'No product found for this barcode.',
                });
            }
        })
        .catch(error => {
            console.error("Error fetching product by barcode:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch product: ' + error.message,
            });
        });
}


function updateQuantity(id, newQuantity) {
    const item = cart.find(item => item.id === id);
    
    if (item) {
        const quantity = parseInt(newQuantity);
        
        if (isNaN(quantity) || quantity < 1) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Quantity',
                text: 'Please enter a valid quantity.',
            });
            renderCart(); // Re-render cart to reset invalid input
        } else if (quantity > item.stock) {
            Swal.fire({
                icon: 'error',
                title: 'Out of Stock',
                text: `Only ${item.stock} items available in stock.`,
            });
            renderCart(); // Re-render cart to reset invalid input
        } else {
            item.quantity = quantity;
            renderCart(); // Update cart
        }
    }
}
function increaseQuantity(id) {
    const item = cart.find(item => item.id === id);
    if (item) {
        if (item.quantity < item.stock) {
            item.quantity++;
            renderCart();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Out of Stock',
                text: 'You cannot add more of this item to the cart because it exceeds available stock.',
            });
        }
    }
}

function decreaseQuantity(id) {
    const item = cart.find(item => item.id === id);
    if (item && item.quantity > 1) {
        item.quantity--;
        renderCart();
    } else if (item.quantity === 1) {
        Swal.fire({
            title: 'Remove item?',
            text: "Are you sure you want to remove this item from the cart?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = cart.filter(cartItem => cartItem.id !== id);
                renderCart();
                updateCart();
            }
        });
    }
}

        function calculateTotal() {
            const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            document.getElementById('total-amount').innerText = `Total: ₱${total.toFixed(2)}`;
        }

      // Confirm Sale
// Confirm Salefunction 
function confirmSale() {
   
    if (cart.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No items in the cart',
            text: 'Please add items to the cart before confirming.',
        });
        return;
    }

    Swal.fire({
        title: 'Select Payment Method',
        text: 'Please select a payment method:',
        showCancelButton: true,
        confirmButtonText: 'Cash',
        cancelButtonText: 'RFID',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn-cash',
            cancelButton: 'btn-rfid'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            paymentMethod = 'cash'; // Set payment method to cash
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            paymentMethod = 'rfid'; // Set payment method to RFID
        } else {
            return; // No selection made, exit function
        }

        // Add confirmation for the selected payment method
        Swal.fire({
            title: 'Confirm Payment Method',
            text: `You have selected ${paymentMethod}. Do you want to proceed?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'No, go back'
        }).then((confirmationResult) => {
            if (confirmationResult.isConfirmed) {
                // Proceed with payment process based on the selected method
                if (paymentMethod === 'rfid') {
                    initiateRFIDPayment(); // Call the RFID payment process
                } else {
                    completeSaleWithCash(); // Complete sale with cash
                }
            }
        });
    });
}



// Initiate RFID Payment
function initiateRFIDPayment() {
    Swal.fire({
        title: 'Waiting for RFID scan...',
        text: "Please scan the RFID tag.",
        icon: 'info',
        showCloseButton: false,
        showCancelButton: true,
        showConfirmButton: false,
        didOpen: () => {
            // Focus on the RFID input field
            document.getElementById('rfid-input').focus();
        }
    });

    document.getElementById('rfid-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const rfidValue = this.value.trim();
            if (rfidValue) {
                Swal.close();
                completeSale(rfidValue); // Call the complete sale function with RFID
            } else {
                // Clear the RFID input box
                this.value = ''; // Clear the input field
                
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid RFID',
                    text: 'Please scan a valid RFID tag.',
                });
            }
        }
    });
}

// Complete sale with cash payment
function completeSaleWithCash() {
    // Show the selected products and prompt for cash amount
    const cartItems = cart.map(item => `<li>${item.name} - Price:₱${item.price}, Quantity: ${item.quantity}</li>`).join('');
    const totalAmountText = document.getElementById('total-amount').innerText.split(' ')[1];
    const totalAmount = parseFloat(totalAmountText.replace(/[^0-9.-]+/g, '')); // Remove currency symbols and parse as float
    
    Swal.fire({
        title: 'Confirm Cash Payment',
        html: `
            <div>
                <strong>Selected Products:</strong>
                <ul>${cartItems}</ul>
                <strong>Total Amount:₱${totalAmount}</strong> 
                <div style="margin-top: 10px;">
                    <label for="cash-input">Enter Cash Amount:</label>
                    <input type="number" id="cash-input" class="swal2-input" placeholder="Cash Amount" />
                </div>
                <div id="change-message" style="margin-top: 10px;"></div>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Confirm Payment',
        preConfirm: () => {
            const cashInput = document.getElementById('cash-input').value;
            if (!cashInput) {
                Swal.showValidationMessage('Please enter a cash amount!');
                return false;
            }

            const cashAmount = parseFloat(cashInput);
            const change = cashAmount - totalAmount;
            if (change < 0) {
                Swal.showValidationMessage('Cash amount is not enough!');
                return false;
            }

            // Set the change in the change message
            document.getElementById('change-message').innerText = `Change: ₱${change.toFixed(2)}`;
            return { cashAmount, change };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { cashAmount, change } = result.value;

            // Prepare sale data including cart items and payment method
            const saleData = JSON.stringify({
                items: cart, // Include items in the cart
                paymentMethod: 'cash', // Specify payment method
                totalAmount: totalAmount,
                cashAmount: cashAmount, // Include cash amount
                change: change.toFixed(2) // Include change
            });
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'confirm_sale.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onload = function() {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (response.success) {
                        Swal.fire({
                            title: 'Transaction Details',
                            html: `
                                <div>
                                    <strong>Selected Products:</strong>
                                    <ul>${cartItems}</ul>
                                    <br>Cash: ₱${cashAmount}
                                    <br>Total Amount: ₱${totalAmount}
                                    <br>Change: ₱${change.toFixed(2)}
                                    <br>Transaction Number: ${response.transaction_number} <!-- Display transaction number -->
                                </div>
                            `,
                            icon: 'info',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Call printReceipt function after sale confirmation
                            const xhrPrint = new XMLHttpRequest();
                            xhrPrint.open('POST', 'print_receipt.php', true); // Send request to print
                            xhrPrint.setRequestHeader('Content-Type', 'application/json');
                            xhrPrint.onload = function() {
                                if (this.status === 200) {
                                    console.log("Receipt printed successfully!");

                                    // Now that the receipt has been printed, clear the cart
                                    cart = []; // Clear the cart
                                    renderCart(); // Update the cart UI
                                    calculateTotal(); // Reset the total amount
                                    location.reload();

                                } else {
                                    console.error("Failed to print receipt.");
                                }
                            };
                            // Send the sale data for receipt printing
                            xhrPrint.send(JSON.stringify({
                                items: cart,
                                totalAmount: totalAmount,
                                cashAmount: cashAmount,
                                change: change,
                                transaction_number: response.transaction_number // Include transaction number
                            }));
                            
                        });
                       
                        
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error confirming sale',
                            text: response.message,
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error processing sale',
                        text: 'Please try again.',
                    });
                }
            };
            xhr.send(saleData); // Send the sale data to confirm sale
        }
    });
}

// Update `completeSale` Function
function completeSale(rfid) {
    const cartItems = cart.map(item => `<li>${item.name} - Price:₱${item.price}, Quantity: ${item.quantity}</li>`).join('');
    const saleData = JSON.stringify({ items: cart, rfid: rfid }); // Changed from cart to items

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'confirm_sale.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (this.status === 200) {
            const response = JSON.parse(this.responseText);
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sale Confirmed!',
                    html: `
                        <div>
                            <strong>Selected Products:</strong>
                            <ul>${cartItems}</ul>
                            <br>Total amount: ${document.getElementById('total-amount').innerText.split(' ')[1]}
                            <br>Transaction Number: ${response.transaction_number} <!-- Display transaction number -->
                        </div>
                    `,
                }).then(() => {
                    // Clear the RFID input field after successful sale
                    document.getElementById('rfid-input').value = ''; 
                    
                    // Send request to print receipt after sale confirmation
                    const xhrPrint = new XMLHttpRequest();
                    xhrPrint.open('POST', 'print_receipt_rfid.php', true); // Send request to print
                    xhrPrint.setRequestHeader('Content-Type', 'application/json');
                    xhrPrint.onload = function() {
                        if (this.status === 200) {
                            console.log("Receipt printed successfully!");
                        } else {
                            console.error("Failed to print receipt.");
                        }
                    };
                    // Pass necessary data for printing the receipt
                    const receiptData = {
                        transaction_number: response.transaction_number,
                        total_amount: response.total_amount,
                        sale_date: response.sale_date,
                        items: response.items
                    };
                    xhrPrint.send(JSON.stringify(receiptData)); // Send the receipt data to print

                   location.reload(); // Refresh the page after confirmation
                });

                cart = []; // Clear the cart after confirming
                renderCart();
                calculateTotal(); // Reset total
            } else {
                cart = [];
                Swal.fire({
                    icon: 'error',
                    title: 'Error confirming sale',
                    text: response.message,
                }).then(() => {
                    location.reload();
                });
                
            }
        } else {
            cart = [];
            Swal.fire({
                icon: 'error',
                title: 'Error confirming sale',
                text: response.message,
            }).then(() => {
                location.reload();
            });
            
        }
    };
    xhr.send(saleData); // Send the sale data to confirm sale
}

console.log("Response from confirm_sale.php:", this.responseText);document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('barcode-input').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            const barcodeValue = this.value.trim();
            if (barcodeValue) {
                fetchProductByBarcode(barcodeValue);
                this.value = ''; // Clear the input field
            }
                }
            });
        });


         function confirmCancelOrder() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to cancel the current order?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelOrder(); // If confirmed, call the cancelOrder function
                    location.reload();
                }
            });
        }

        // Function to cancel the current order
        function cancelOrder() {
            // Clear the cart items
            const cartItems = document.getElementById('cart-items');
            cartItems.innerHTML = ''; // Remove all cart items
            
            // Reset the total amount
            document.getElementById('total-amount').textContent = 'Total: ₱0';
            
            // Clear the cart array or object holding the cart data
            cart = []; // Clear the cart array
            
            // Show a message that the order has been canceled
            Swal.fire({
                icon: 'info',
                title: 'Order Canceled',
                text: 'The current order has been successfully canceled.',
                confirmButtonText: 'OK'
            });
        }

        function getDeviceDateTime() {
            let now = new Date();
            return now.toISOString().slice(0, 19).replace('T', ' '); // Format as 'YYYY-MM-DD HH:MM:SS'
        }
    
        // Sample function to send sale data
        function processSale(cartItems, selectedPaymentMethod, rfidCode) {
            // Prepare the sale data with device date and time
            let saleData = {
                items: cartItems, // Example cart items
                paymentMethod: selectedPaymentMethod,
                rfid: rfidCode, // if applicable
                saleDate: getDeviceDateTime() // Include device's date and time
            };
    
            // Send the sale data to the server via AJAX or Fetch
            fetch('../../confirm_sale.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(saleData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Sale confirmed:', data.message);
                } else {
                    console.log('Error:', data.message);
                }
            });
        }