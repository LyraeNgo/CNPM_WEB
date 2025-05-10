<?php
require_once("./BE/db.php");
require_once("./BE/product.php");

$conn = create_connection();
// Check if productID is set in the URL
if (isset($_GET['productId'])) {
    $productID = $_GET['productId'];
    
} else {
    echo "Product ID not provided!";
}



$productId = $_GET['productId'];


$stmt = $conn->prepare("SELECT * FROM product WHERE productId = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMPUTER SHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="asset/css/style.css">
    <script src="main.js"></script>
    <style>
        .page-top, header {
            color: whitesmoke;
        }
        body {
            background-color: rgb(243, 243, 243);
        }
    </style>
</head>
<body style="padding-top: 100px;">
<div class="fixed-top">
<!-- Top Contact -->
<section class="page-top py-2 bg-dark text-white ">
    <div class="container">
        <div class="row">
            <div class="col text-center text-md-left">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item mr-3"><i class="fa-solid fa-phone-volume"></i> <a href="tel:0905379388" class="text-white">0905379388</a></li>
                    <li class="list-inline-item"><i class="fa-solid fa-envelope"></i> <a href="mailto:nguyencaoky1603@gmail.com" class="text-white">nguyencaoky1603@gmail.com</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Header -->
<header class="bg-white shadow-sm py-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-3 text-center text-md-left mb-2 mb-md-0">
                <a href="index.php"> <img width="150px" src="asset/images/Screenshot 2025-04-23 000327.png" alt="" class="img-fluid" ></a>
            </div>
            <!-- search bar -->
            <div class="col-12 col-md-6 mb-2 mb-md-0">
                <form action="productShow.php" method="get">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Nhập sản phẩm cần tìm...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Tìm kiếm <i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- account and cart -->
            <div class="col-12 col-md-3 text-center text-md-right text-dark">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="account.php"><i class="fa-solid fa-user"></i> Tài khoản</a></li>
                    <li class="list-inline-item "><i class="fa-solid fa-cart-shopping"></i> <span class="dot-cart">0</span></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- Navigation Menu -->
<!-- NAVBAR -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="container">
    <!-- Mobile toggle -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Category toggle button -->
    <button class="btn  ml-auto text-white" type="button" data-toggle="collapse" data-target="#cate">
      DANH MỤC SẢN PHẨM
    </button>

    <!-- Main menu -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto header-top-user_cart">
        <li class="nav-item"><a class="nav-link" href="#">Xây dựng cấu hình pc</a></li>
        <li class="nav-item"><a class="nav-link" href="#">PC gaming</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Màn hình</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Laptop</a></li>
        <li class="nav-item"><a class="nav-link" href="#">PC văn phòng</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Khuyến mãi</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Giới thiệu</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- CATEGORY GRID -->
<div class="container collapse " id="cate" >
  <div class="row">
    <?php 
      require_once("./BE/db.php");
      $conn = create_connection();
      $sql = "SELECT * FROM category LIMIT 25";
      $caterogy = $conn->query($sql);

      if ($caterogy && $caterogy->num_rows > 0) {
          while ($row = $caterogy->fetch_assoc()) {
              echo '<div class="col-6 col-md-2 mb-3">';
              echo '<div class="border p-1 text-center text-white">' . htmlspecialchars($row['name']) . '</div>';
              echo '</div>';
          }
      } else {
          echo '<div class="col-12 text-danger">Không có danh mục!</div>';
      }
    ?>
  </div>
</div>


</div>


<!-- Product Details -->
<section class="py-5 mt-5 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="asset/productImg/<?=$product['image']?>" class="img-fluid" alt="<?=$product['name']?>">
            </div>
            <div class="col-md-6">
                <h2 style="font-weight:bolder"><?=$product['name']?></h2>
                <p><strong>Description:</strong> <?=$product['description']?></p>
                <p><strong>Price:</strong> $<?=$product['price']?></p>
                <p><strong>Stock:</strong> <?=$product['stockQuantity']?></p>
                <button class="btn btn-primary">Add to Cart</button>
            </div>
        </div>
    </div>
</section>
<section class="py-5 bg-light">
  <div class="container">
    <div class="row text-center">
      <!-- Feature 1 -->
      <div class="col-12 col-md-3 mb-4">
        <i class="fa-solid fa-truck-fast fa-2x text-primary mb-2"></i>
        <h5>Giao hàng nhanh</h5>
        <p class="text-muted">Miễn phí giao hàng toàn quốc trong 24h.</p>
      </div>
      <!-- Feature 2 -->
      <div class="col-12 col-md-3 mb-4">
        <i class="fa-solid fa-shield-halved fa-2x text-primary mb-2"></i>
        <h5>Bảo hành uy tín</h5>
        <p class="text-muted">Bảo hành 1 đổi 1 trong 12 tháng đầu.</p>
      </div>
      <!-- Feature 3 -->
      <div class="col-12 col-md-3 mb-4">
        <i class="fa-solid fa-headset fa-2x text-primary mb-2"></i>
        <h5>Hỗ trợ 24/7</h5>
        <p class="text-muted">Tư vấn kỹ thuật và hỗ trợ khách hàng mọi lúc.</p>
      </div>
      <!-- Feature 4 -->
      <div class="col-12 col-md-3 mb-4">
        <i class="fa-solid fa-tags fa-2x text-primary mb-2"></i>
        <h5>Giá tốt mỗi ngày</h5>
        <p class="text-muted">Luôn có chương trình khuyến mãi hấp dẫn.</p>
      </div>
    </div>
  </div>
</section>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</body>
<!-- Footer -->
<footer class="bg-dark text-white pt-5 pb-3 mt-5">
  <div class="container">
    <div class="row">
      <!-- About Us -->
      <div class="col-md-4">
        <h5>About Us</h5>
        <p>We offer top-quality computers, laptops, and accessories. Trusted by thousands of tech enthusiasts.</p>
      </div>

      <!-- Quick Links -->
      <div class="col-md-4">
        <h5>Quick Links</h5>
        <ul class="list-unstyled">
          <li><a href="index.php" class="text-white">Home</a></li>
          <li><a href="products.php" class="text-white">Products</a></li>
          <li><a href="about.php" class="text-white">About</a></li>
          <li><a href="contact.php" class="text-white">Contact</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="col-md-4">
        <h5>Contact Us</h5>
        <p><i class="fa-solid fa-phone-volume"></i> +123 456 7890</p>
        <p><i class="fa-solid fa-envelope"></i> support@computershop.com</p>
        <p><i class="fa-solid fa-map-location-dot"></i> 131, Dao Cam Moc Street, Dis 8, HCM city</p>
      </div>
    </div>
    



    <hr class="bg-light">

    <div class="text-center">
      &copy; <?php echo date("Y"); ?> Computer Shop. All rights reserved.
    </div>
  </div>
</footer>
<!-- Scroll to Top Button -->
<button onclick="topFunction()" id="scrollTopBtn" class="btn btn-primary" style="font-weight: bolder; ">
  ↑
</button>

</html>
























