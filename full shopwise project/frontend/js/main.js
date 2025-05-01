// When document ready
$(document).ready(function () {
  loadProducts();
  loadCategories();
});

// Load products
function loadProducts() {
  fetch("http://localhost:8000/api/products")
    .then((response) => response.json())
    .then((data) => {
      let html = "";
      data.forEach((product) => {
        html += `
            <div class="col-md-3 mb-4">
              <div class="card">
                <img src="${product.image_url}" class="card-img-top" alt="${product.name}">
                <div class="card-body">
                  <h5 class="card-title">${product.name}</h5>
                  <p class="card-text">$${product.price}</p>
                  <button class="btn btn-primary" onclick="addToCart(${product.id})">Add to Cart</button>
                </div>
              </div>
            </div>
          `;
      });
      $("#productList").html(html);
    });
}

// Load categories
function loadCategories() {
  fetch("http://localhost:8000/api/categories")
    .then((response) => response.json())
    .then((data) => {
      let html = "";
      data.forEach((category) => {
        html += `<button class="btn btn-outline-secondary">${category.name}</button>`;
      });
      $("#categoryList").html(html);
    });
}

function addToCart(productId) {
  const token = localStorage.getItem('token');
  // const token = '2|jgq82KAj6cgBdSduJtusLOY0fY1K4eQW74LmOQh1b775ef2a';
  fetch("http://localhost:8000/api/cart", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Authorization": `Bearer ${token}`,
    },
    body: JSON.stringify({
      product_id: productId,
      quantity: 1,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      alert("Product added to cart!");
    })
    .catch((error) => console.error("Error:", error));
}

$(document).ready(function () {
  if ($("#cartItems").length) {
    loadCart();
  }
});

function loadCart() {
  fetch("http://localhost:8000/api/cart")
    .then((response) => response.json())
    .then((data) => {
      let html = "";
      let subtotal = 0;
      data.forEach((item) => {
        console.log("Full cart item object:", item); // Log the entire item object
        console.log("Cart Item ID:", item.id); // Keep this for comparison

        // Make sure you are using the correct property for the cart item's unique ID
        const cartItemId = item.id; // Assuming 'id' is the correct property

        let total = item.product.price * item.quantity;
        subtotal += total;
        html += `
            <tr>
              <td>${item.product.name}</td>
              <td>$${item.product.price}</td>
              <td>${item.quantity}</td>
              <td>$${total}</td>
              <td><button class="btn btn-danger btn-sm" onclick="removeFromCart(${cartItemId})">Remove</button></td>
            </tr>
          `;
      });
      $("#cartItems").html(html);
      $("#subtotal").text(subtotal);
    });
}

// Remove item from cart
// function removeFromCart(itemId) {
//   fetch(`http://localhost:8000/api/cart/${itemId}`, {
//     method: "DELETE",
//   })
//     .then((response) => response.json())
//     .then((data) => {
//       loadCart(); // Reload cart
//     });
// }

// Remove item from cart
function removeFromCart(itemId) {
  fetch(`http://localhost:8000/api/cart/${itemId}`, {
    method: "DELETE",
  })
    .then(response => {
      if (!response.ok) {
        // Handle HTTP errors (e.g., 404, 500)
        console.error(`HTTP error! status: ${response.status}`);
        return Promise.reject(`HTTP error! status: ${response.status}`);
      }
      // Only try to parse as JSON if the response is successful
      return response.json();
    })
    .then(data => {
      console.log('Item removed successfully:', data); // Optional: Log the success message
      loadCart(); // Reload cart
    })
    .catch(error => {
      console.error('Error removing item from cart:', error);
      // Optionally display an error message to the user
    });
}

$('#loginForm').submit(function(e) {
    e.preventDefault();
    
    const email = $('#email').val();
    const password = $('#password').val();
    
    fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ email, password })
    })
    .then(response => response.json())
    .then(data => {
      alert('Login successful!');
      // Save token if needed
      localStorage.setItem('token', data.token);
      window.location.href = 'index.html'; // Redirect to home
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Login failed.');
    });
  });

  $('#checkoutForm').submit(function(e) {
    e.preventDefault();

    const name = $('#name').val();
    const address = $('#address').val();
    const phone = $('#phone').val();
    const token = localStorage.getItem('token'); // Get the token

    fetch('http://localhost:8000/api/checkout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}` // Include the Authorization header
      },
      body: JSON.stringify({ name, address, phone })
    })
    .then(response => response.json())
    .then(data => {
      alert('Order placed successfully!');
      window.location.href = 'index.html'; // Redirect after order
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Checkout failed.');
    });
  });
  
