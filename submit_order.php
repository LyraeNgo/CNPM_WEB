<?php 
    session_start();
    require_once("./BE/db.php");
    require_once("./BE/product.php");
    $conn = create_connection();
    if($conn->connect_error) {
        die("fail to connect" . $conn->connect_error);
    }

    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT name FROM customer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $stmt->close();
      $conn->close();
    
    if(isset($_SESSION['username'])){
      $username = $row['name'];
    }else{
      $username='Tài Khoản';
    }}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Giỏ hàng | GIANG-HIEUKY</title>
  <link rel="stylesheet" href="cart.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="asset/css/submit_order.css">
</head>
<body>
  <header class="navbar">
    <div class="logo"><a href="index.php">🖥️ GIANG-HIEUKY</a></div>
    <div class="nav-right">
      <a href="index.php">Trang chủ</a>
      <?php if (isset($_SESSION['username'])): ?>
        <span class="text-muted"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($username) ?></span>
      <?php else: ?>
        <a href="account.php"><i class="fa-solid fa-user"></i> Tài Khoản</a>
      <?php endif; ?>
    </div>
  </header>
  <section class="cart-container">
    <h1>🛒 Giỏ hàng của bạn</h1>
    <div class="cart-header">
      <span>Sản phẩm</span>
      <span>Đơn giá</span>
      <span>Số lượng</span>
      <span>Thành tiền</span>
      <span>Thao tác</span>
    </div>

    <form action="submit_order.php" method="POST">
      <div class="cart-item">
        <div class="product-info">
          <img src="${product.image}" alt="">
          <div>
            <p>Laptop Asus VivoBook S14 S433EA-EK216T</p>
            <small>Phân loại: <input type="text" name="category" value="Laptop" readonly></small>
            <input type="hidden" name="productName" value="Laptop Asus VivoBook S14 S433EA-EK216T">
            <input type="hidden" name="price" value="100000000">
          </div>
        </div> 
        <div>₫100.000.000</div>
        <div class="quantity">
          <button type="button" onclick="changeQty(1, -1)">-</button>
          <button type="button" onclick="changeQty(1, 1)">+</button>
        </div>
        <div class="subtotal">₫100.000.000</div>
        <div><button type="button" class="remove-btn" onclick="removeProduct(1)">Xóa</button></div>
      </div>

      <div class="cart-item">
        <div class="product-info">
          <img src="${product.image}" alt="">
          <div>
            <p>Laptop Dell Inspiron 15 3000</p>
            <small>Phân loại: <input type="text" name="category" value="Laptop" readonly></small>
            <input type="hidden" name="productName" value="Laptop Dell Inspiron 15 3000">
            <input type="hidden" name="price" value="150000000">
          </div>
        </div>
        <div>₫150.000.000</div>
        <div class="quantity">
          <button type="button" onclick="changeQty(2, -1)">-</button>
          <input type="text" name="quantity" value="1" id="qty-2">
          <button type="button" onclick="changeQty(2, 1)">+</button>
        </div>
        <div class="subtotal">₫150.000.000</div>
        <div><button type="button" class="remove-btn" onclick="removeProduct(2)">Xóa</button></div>
      </div>

      <hr>
      <div style="margin-top: 20px;">
        <h4>Phương thức thanh toán</h4>
        <label><input type="radio" name="paymentMethod" value="COD" checked> Thanh toán khi nhận hàng</label><br>
        <label><input type="radio" name="paymentMethod" value="e-wallet">Thẻ Ngân Hàng</label><br>
        <div id="e-wallet-container" style="margin-top: 10px;"></div>
        <label><input type="radio" name="paymentMethod" value="QR"> Trả trước (QR)</label>
        <div id="qr-container" style="margin-top: 10px;"></div>

      </div>

      <div class="cart-footer">
        <input type="hidden" name="customerId" value="1"> 
        <div class="right">
          <span>Tổng cộng: <strong class="total">₫0</strong></span>
          <button type="submit" class="checkout">Đặt Hàng</button>
        </div>
      </div>
    </form>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>

  <script>
    $(document).ready(function() {
      // Get selected products from localStorage
      const selectedProducts = JSON.parse(localStorage.getItem('selectedProducts') || '[]');
      
      // Clear existing cart items
      $('.cart-item').remove();
      
      // Add selected products to the cart
      selectedProducts.forEach(product => {
        const cartItem = `
          <div class="cart-item">
            <div class="product-info">
              <img src="asset/productImg/${product.image}" alt="">
              <div>
                <p>${product.name}</p>
                <small>Phân loại: <input type="text" name="category[]" value="${product.category}" readonly></small>
                <input type="hidden" name="productName[]" value="${product.name}">
                <input type="hidden" name="price[]" value="${product.price}">
                <input type="hidden" name="quantity[]" value="${product.quantity}">
              </div>
            </div>
            <div>₫${parseInt(product.price).toLocaleString()}</div>
            <div class="quantity">
              <input type="text" name="quantity" value="${product.quantity}" readonly>
            </div>
            <div class="subtotal">₫${(parseInt(product.price) * parseInt(product.quantity)).toLocaleString()}</div>
            <div><button type="button" class="remove-btn" disabled>Xóa</button></div>
          </div>
        `;
        $('form').prepend(cartItem);
      });

      // Calculate and display total
      let total = 0;
      selectedProducts.forEach(product => {
        total += parseInt(product.price) * parseInt(product.quantity);
      });
      $('.total').text('₫' + total.toLocaleString());
    });

    function generateQRCode() {
      const qrContainer = $('#qr-container');
      qrContainer.empty(); 
  
      const randomCode = "PAY_" + Math.random().toString(36).substring(2, 10).toUpperCase();
      const qrDiv = $('<div id="qrcode"></div>');
      qrContainer.append(qrDiv);
  
      new QRCode(document.getElementById("qrcode"), {
        text: randomCode,
        width: 128,
        height: 128
      });
    }
  
    function generateBankCards() {
      const eWalletContainer = $('#e-wallet-container');
      eWalletContainer.empty();

      const cards = [
        { amount: '₫50000', bank: 'Nam A Bank', description: 'Đơn từ 299.000₫ với thẻ tín dụng Nam A Bank JCB', color: '#f39c12' },
        { amount: '₫50000', bank: 'Shinhan Finance', description: 'Đơn từ 4400.000₫ - Mỗi ngày với thẻ Mastercard', color: '#3498db' },
        { amount: '₫100000', bank: 'LPBank', description: 'Đơn từ 500.000₫ - Mỗi ngày', color: '#d35400' },
        { amount: '₫50000', bank: 'ACB', description: 'Đơn từ 500.000₫ - Mỗi ngày với thẻ tín dụng ACB', color: '#2980b9' },
        { amount: '₫30000', bank: 'SHB', description: 'Đơn từ 200.000₫ với thẻ SHB Visa debit', color: '#e67e22' },
        { amount: '₫30000', bank: 'SHB', description: 'Đơn từ 200.000₫ với thẻ SHB Mastercard debit', color: '#e67e22' },
      ];
      cards.forEach(card => {
        const cardDiv = $('<div></div>').css({
          background: card.color,
          padding: '10px',
          margin: '5px',
          borderRadius: '5px',
          color: 'white',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          cursor: 'pointer'
        }).click(function() {
          eWalletContainer.empty();
          eWalletContainer.append(cardDiv.clone());
        });

        const contentDiv = $('<div></div>').css({
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'center'
        });

        const amountSpan = $('<span></span>').text(card.amount + ' Giảm').css({
          fontWeight: 'bold',
          fontSize: '16px'
        });
        const descSpan = $('<span></span>').text(card.description).css({
          fontSize: '12px'
        });

        const bankLogo = $('<div></div>').text(card.bank).css({
          fontSize: '12px',
          fontWeight: 'bold',
          background: 'white',
          color: card.color,
          padding: '5px',
          borderRadius: '3px'
        });

        contentDiv.append(amountSpan, descSpan);
        cardDiv.append(contentDiv, bankLogo);
        eWalletContainer.append(cardDiv);
      });
    }
  
    $(document).ready(function () {
      $('input[name="paymentMethod"]').on('change', function () {
        $('#qr-container').empty();
        $('#e-wallet-container').empty();
        if (this.value === 'QR') {
          generateQRCode();
        } else if (this.value === 'e-wallet') {
          generateBankCards();
        }
      });
  
      $('form').on('submit', function (e) {
        e.preventDefault();
        // Get form data
        const formData = new FormData(this);
        formData.append('paymentMethod', $('input[name="paymentMethod"]:checked').val());
        
        // Send data to server
        $.ajax({
          url: 'submit_order.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            alert('Đặt hàng thành công!');
            window.location.href = 'index.php';
          },
          error: function() {
            alert('Có lỗi xảy ra khi đặt hàng!');
          }
        });
      });
    });
  </script>
  
</body>
</html>
