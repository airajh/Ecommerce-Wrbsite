<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
?>
<?php include_once('layouts/header.php'); ?>



<style>
         .picture-container {
      display: flex;
      flex-direction: row; /* Set the direction to row */
      justify-content: center; /* Center the images horizontally */
      align-items: center; /* Center the images vertically */
    }

    .inline-block {
      margin: 10px;
      padding: 20px;
    }

    .picture {
      width: 100px; /* Adjust the width as needed */
      height: 100px; /* Adjust the height as needed */
      object-fit: cover;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    /* Apply font-family to the div element */
    .picture-container, .inline-block {
      font-family: Arial, sans-serif;
    }

    .description {
      margin-top: 5px; /* Adjust the margin as needed */
      text-align: center; /* Center the description text */
    }

    #order-summary {
      margin-top: 20px;
    }

    #purchase-table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }

    #purchase-table th, #purchase-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }#order-summary {
      margin-top: 20px;
    }

    #purchase-table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
    }

    #purchase-table th, #purchase-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    </style>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
 <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center">
         <h1>Welcome To <hr> Glam Trinket Arts & Craft</h1>
         <p>Find out the product you want!</p>
      </div>
    </div>
 </div>
</div>
<div>
<br><br><br><br>
<form action="/action_page.php">
<html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Form</title>

<h2>Choose Product</h2>
<div class="picture-container">
    <div class="inline-block">
      <img src="uploads/products/P1.png" alt="Picture 1" class="picture">
      <p class="description">Necklace</p>
    </div>
    <div class="inline-block">
      <img src="uploads/products/P2.png" alt="Picture 2" class="picture">
      <p class="description">Gold Keychain</p>
    </div>
    <div class="inline-block">
      <img src="uploads/products/P4.png" alt="Picture 3" class="picture">
      <p class="description">Blue Ring</p>
    </div>
    <div class="inline-block">
      <img src="uploads/products/P7.jpg" alt="Picture 4" class="picture">
      <p class="description">Blue Earings & <br> Necklace</p>
    </div>
    <div class="inline-block">
      <img src="uploads/products/P9.jpg" alt="Picture 5" class="picture">
      <p class="description">Butterfly Necklace</p>
    </div>
    <div class="inline-block">
      <img src="uploads/products/P6.png" alt="Picture 5" class="picture">
      <p class="description">Photo Preservation</p>
    </div>
    <div class="inline-block">
      <img src="uploads/products/P10.jpg" alt="Picture 5" class="picture">
      <p class="description">Keychain Preservation</p>
    </div>
  </div>


<h2>Pick Here to Order Items</h2>


<form id="orderForm">
  <label for="name">Name:</label>
  <input type="text" id="name" placeholder="Enter your name">

  <label for="email">Email:</label>
  <input type="email" id="email" placeholder="Enter your email">

  <label for="address">Address:</label>
  <input type="text" id="address" placeholder="Enter your address">

  <br><br><label for="product">Select a Product:</label>
  <select id="product" onchange="showImageInput()">
    <option value="necklace">Necklace - ₱199</option>
    <option value="goldKeychain">Gold Keychain - ₱99</option>
    <option value="butterflyNecklace">Butterfly Necklace - ₱199</option>
    <option value="blueRing">Blue Ring - ₱150</option>
    <option value="keychainPreservation">Keychain Preservation - ₱150</option>
    <option value="photoPreservation">Photo Preservation - ₱500</option>
    <option value="blueEarringNecklace">Blue Earring and Necklace - ₱299</option>
  </select>

  <div id="imgInputContainer" style="display: none;">
    <label for="imgInput">Insert Img:</label>
    <input type="file" id="imgInput" style="border: 2px solid black;">
  </div>

  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" min="1" value="1">

  <br><br><button type="button" onclick="addToOrder()">Add to Order</button>
</form>

<div id="order-summary">
  <h3>Order Summary</h3>
  <ul id="summary-list"></ul>
  <p id="total-amount">Total Amount: ₱0</p>
  <button type="button" onclick="clearOrder()">Clear Order</button>
  <button type="button" onclick="purchaseOrder()">Purchase Order</button>
</div>

<table id="purchase-table">
  <thead>
    <tr>
      <th>Email</th>
      <th>Name</th>
      <th>Address</th>
      <th>Ordered Item</th>
      <th>Price</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody id="purchase-table-body"></tbody>
</table>

<script>
  let orderData = [];

  function addToOrder() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const address = document.getElementById('address').value;
    const product = document.getElementById('product').value;
    const quantity = parseInt(document.getElementById('quantity').value, 10);

    let imgSrc = '';

    if (product === 'photoPreservation') {
      const imgInput = document.getElementById('imgInput');
      if (imgInput.files.length > 0) {
        const imgFile = imgInput.files[0];
        imgSrc = URL.createObjectURL(imgFile);
      }
    }

    const existingEntry = orderData.find(entry => entry.name === name && entry.email === email && entry.address === address && entry.product === product);
    if (existingEntry) {
      existingEntry.quantity += quantity;
      existingEntry.itemTotal = existingEntry.quantity * getItemPrice(product);
    } else {
      const itemTotal = quantity * getItemPrice(product);
      orderData.push({ email, name, address, product, quantity, itemTotal, imgSrc });
    }

    updateOrderSummary();
  }

  function clearOrder() {
    orderData = [];
    updateOrderSummary();
  }

  function purchaseOrder() {
    const purchaseTableBody = document.getElementById('purchase-table-body');
    purchaseTableBody.innerHTML = '';

    let grandTotal = 0;

    orderData.forEach(item => {
      const row = document.createElement('tr');

      const emailCell = document.createElement('td');
      emailCell.textContent = item.email;
      row.appendChild(emailCell);

      const nameCell = document.createElement('td');
      nameCell.textContent = item.name;
      row.appendChild(nameCell);

      const addressCell = document.createElement('td');
      addressCell.textContent = item.address;
      row.appendChild(addressCell);

      const itemNameCell = document.createElement('td');

      if (item.imgSrc) {
        const imgLink = document.createElement('a');
        imgLink.href = item.imgSrc;
        imgLink.target = '_blank';
        imgLink.textContent = `${item.quantity} x ${item.product} (View Image)`;
        itemNameCell.appendChild(imgLink);
      } else {
        itemNameCell.textContent = `${item.quantity} x ${item.product}`;
      }

      row.appendChild(itemNameCell);

      const itemPriceCell = document.createElement('td');
      const price = getItemPrice(item.product);
      itemPriceCell.textContent = `₱${price}`;
      row.appendChild(itemPriceCell);

      const itemTotalCell = document.createElement('td');
      itemTotalCell.textContent = `₱${item.itemTotal}`;
      row.appendChild(itemTotalCell);

      grandTotal += item.itemTotal;

      purchaseTableBody.appendChild(row);
    });

    // Add Grand Total row
    const grandTotalRow = document.createElement('tr');
    const grandTotalCell = document.createElement('td');
    grandTotalCell.colSpan = 5;
    grandTotalCell.textContent = 'Grand Total';
    grandTotalRow.appendChild(grandTotalCell);

    const grandTotalAmountCell = document.createElement('td');
    grandTotalAmountCell.textContent = `₱${grandTotal}`;
    grandTotalRow.appendChild(grandTotalAmountCell);

    purchaseTableBody.appendChild(grandTotalRow);
  }

  function updateOrderSummary() {
    const summaryList = document.getElementById('summary-list');
    const totalAmountElement = document.getElementById('total-amount');

    summaryList.innerHTML = '';
    let totalAmount = 0;

    orderData.forEach(item => {
      const listItem = document.createElement('li');
      listItem.textContent = `${item.quantity} x ${item.product} - ₱${item.itemTotal}`;
      summaryList.appendChild(listItem);

      totalAmount += item.itemTotal;
    });

    totalAmountElement.textContent = `Total Amount: ₱${totalAmount}`;
  }

  function getItemPrice(product) {
    switch (product) {
      case 'necklace':
        return 199;
      case 'goldKeychain':
        return 99;
      case 'butterflyNecklace':
        return 199;
      case 'blueRing':
        return 150;
      case 'keychainPreservation':
        return 150;
      case 'photoPreservation':
        return 500;
      case 'blueEarringNecklace':
        return 299;
      default:
        return 0;
    }
  }

  function showImageInput() {
    const imgInputContainer = document.getElementById('imgInputContainer');
    const productSelect = document.getElementById('product');
    imgInputContainer.style.display = productSelect.value === 'photoPreservation' ? 'block' : 'none';
  }
</script>

</body>
</html>

</div>
</div>
<?php include_once('layouts/footer.php'); ?>
