<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
};

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'")
        or die('query failed');

    echo mysqli_num_rows($select_cart);

    $fetch_cart = mysqli_fetch_assoc($select_cart);

    if (mysqli_num_rows($select_cart) > 0) 
    {
        $update_cart = mysqli_query($conn, "UPDATE `cart` SET quantity = quantity + '$product_quantity' WHERE name = '$product_name'") or die('query failed');
    } 
    else {
        $insert_cart = mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, image, quantity) 
        VALUES ('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
    }
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bữa sáng</title>
    <!-- for icons  -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- bootstrap  -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- for swiper slider  -->
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">

    <!-- fancy box  -->
    <link rel="stylesheet" href="assets/css/jquery.fancybox.min.css">
    <!-- custom css  -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="body-fixed">

    <!--- #PRELOADER-->

    <div class="preload" data-preaload>
        <div class="circle"></div>
        <p class="text">ND - Restaurant</p>
    </div>

    <!-- start of header  -->
    <header class="site-header" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header-logo">
                        <a href="Trangchu.php">
                            <img src="logo.png" width="180" height="70" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="main-navigation">
                        <button class="menu-toggle"><span></span><span></span></button>
                        <nav class="header-menu">
                            <ul class="menu food-nav-menu">
                                <li><a href="Trangchu.php">Trang chủ</a></li>
                                <li><a href="#about">Thông tin</a></li>
                                <li><a href="#menu">Thực đơn</a></li>
                                <li><a href="#gallery">Trưng bày</a></li>
                                <li><a href="#blog">BLOG</a></li>
                                <li><a href="#contact">Liên hệ</a></li>
                            </ul>
                        </nav>
                        <div class="header-right">
                            <form action="#" class="header-search-form for-des">
                                <input type="search" class="form-input" placeholder="Search Here...">
                                <button type="submit">
                                    <i class="uil uil-search"></i>
                                </button>
                            </form>

                            <a href="cart.php" class="header-btn header-cart">
                                <i class="uil uil-shopping-bag"></i>
                                <span class="cart-number">
                                    <?php
                                    $select = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'")
                                        or die('query failed');

                                    echo mysqli_num_rows($select);
                                    ?>
                                </span>
                            </a>

                            <a href="javascript:void(0)" class="header-btn user">
                                <i class="uil uil-user-md"></i>
                            </a>

                            <div class="user-box">

                                <div class="profile">

                                    <?php
                                    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'")
                                        or die('query failed');

                                    if (mysqli_num_rows($select) > 0) {
                                        $fetch = mysqli_fetch_assoc($select);
                                    }
                                    if ($fetch['image'] == '') {
                                        echo '<img src="assets/images/default-avatar.png">';
                                    } else {
                                        echo '<img src="uploaded_img/' . $fetch['image'] . '">';
                                    }

                                    ?>
                                    <h3 class="user-name"><?php echo $fetch['name']; ?></h3>
                                    <a href="update_profile.php" class="btn-user">Thông tinn</a>
                                    <a href="Trangchu.php?logout=<?php echo $user_id; ?>" class="delete-btn">Đăng xuất</a>
                                    <p><a href="login.php">Đăng nhập</a> or <a href="register.php">Đăng ký</a> tài khoản khác</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header ends  -->



    <div id="viewport">
        <div id="js-scroll-content">

            <section class="panel" id="home">
                <div class="panel-main active">
                    <div class="container">
                        <div class="row">
                            <h3>Món Khai Vị</h3>
                            <div class="panel-text">
                                <a href="Trangchu.php">Trang chủ</a> &rarr;
                                <a href="nuong.php">Món khai vị</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section style="background-image: url(assets/images/menu-bg.png);" class="our-menu section bg-light repeat-img" id="menu">
                <div class="sec-wp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sec-title text-center mb-5">
                                    <p class="sec-sub-title mb-3">Menu Khai Vị Của ND</p>
                                    <h2 class="h2-title">Thức Dậy Sớm<span>Tươi Ngon <small style="font-size: 24px;">&</small> Sức Khỏe</span></h2>
                                    <div class="sec-title-shape mb-4">
                                        <img src="assets/images/title-shape.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="menu-list-row">
                            <div class="row g-xxl-5 bydefault_show" id="menu-dish">

                                <?php
                                $select_product = mysqli_query($conn, "SELECT * FROM `khaivi`") or die('query failed');

                                if (mysqli_num_rows($select_product) > 0) {
                                    while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                                ?>

                                        <div class="col-lg-4 col-sm-6 dish-box-wp ">

                                            <form action="" method="post" class="box-product" onsubmit="return false">
                                                <div class="dish-box text-center">
                                                    <div class="dist-img">
                                                        <img src="assets/images/dish/<?php echo $fetch_product['image']; ?>" alt="" width="260px" height="260px">
                                                    </div>
                                                    <div class="dish-rating">
                                                        <?php echo $fetch_product['rate']; ?>
                                                        <i class="uil uil-star"></i>
                                                    </div>
                                                    <div class="dish-title">
                                                        <h3 class="h3-title"><?php echo $fetch_product['name']; ?></h3>
                                                    </div>
                                                    <div class="dish-info">
                                                        <ul>
                                                            <li>
                                                                <p>Phân Loại</p>
                                                                <b><?php echo $fetch_product['type']; ?></b>
                                                            </li>
                                                            <li>
                                                                <p>Người ăn</p>
                                                                <b><?php echo $fetch_product['customer']; ?></b>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="dist-bottom-row">
                                                        <ul>
                                                            <li>
                                                                <b>Giá: <?php echo $fetch_product['price']; ?>K</b>
                                                            </li>
                                                            <li>
                                                                <input type="hidden" name="product_quantity" min="1" value="1">
                                                                <!-- <button class="dish-add-btn">
                                                                    <i class="uil uil-plus"></i>
                                                                </button> -->
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                                                    <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                                                    <input type="hidden" name="product_type" value="<?php echo $fetch_product['type']; ?>">
                                                    <input type="hidden" name="product_customer" value="<?php echo $fetch_product['customer']; ?>">
                                                    <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">

                                                    <button type="submit" value="Thêm vào giỏ hàng" name="add_to_cart" class="btn-user">
                                                        submit
                                                    </button>

                                                    <a href="" class="xemThemSP">Chi tiết món ăn</a>

                                                </div>
                                            </form>

                                        </div>
                                <?php
                                    };
                                };
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer  -->
            <footer class="site-footer" id="contact">
                <div class="top-footer section">
                    <div class="sec-wp">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="footer-info">

                                        <div class="footer-logo">
                                            <a href="index.php">
                                                <img src="logo.png" alt="">
                                            </a>
                                        </div>
                                        <p>
                                            ND Restaurant - một nhà hàng online mang phong cách hiện đại, bắt trend nhất hiện nay.
                                        </p>

                                        <div class="social-icon">
                                            <ul>
                                                <li>
                                                    <a href="#"><i class="uil uil-facebook-f"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="uil uil-instagram"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="uil uil-github-alt"></i></a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="uil uil-youtube"></i></a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="footer-table-contact mt-5">
                                            <h3 class="h3-title">Liên Hệ</h3>
                                            <ul>
                                                <li>
                                                    <a href="#">
                                                        <i class="uil uil-envelope-alt"> <span>nguyenkun555@gmail.com</span></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="uil uil-phone"> <span>0397556616</span></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#">
                                                        <i class="uil uil-map-marker-alt"> <span>456 Trần Đại Nghĩa - Ngũ Hành Sơn - Đà Nẵng</span></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="footer-flex-box">

                                        <div class="footer-menu footer-nav-menu">
                                            <h3 class="h3-title">Đường Dẫn</h3>
                                            <ul class="column-2">
                                                <li>
                                                    <a href="#home">Trang chủ</a>
                                                </li>
                                                <li>
                                                    <a href="#about">Thông tin</a>
                                                </li>
                                                <li>
                                                    <a href="#menu">Thực đơn</a>
                                                </li>
                                                <li>
                                                    <a href="#gallery">Trưng bày</a>
                                                </li>
                                                <li>
                                                    <a href="blog">Blog</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="footer-menu">
                                            <h3 class="h3-title">Nhà Hàng</h3>
                                            <ul>
                                                <li>
                                                    <a href="#about">Giới thiệu</a>
                                                </li>
                                                <li>
                                                    <a href="#">Trung tâm trợ giúp</a>
                                                </li>
                                                <li>
                                                    <a href="#">Quy chế</a>
                                                </li>
                                                <li>
                                                    <a href="#">Điều khoản sử dụng</a>
                                                </li>
                                                <li>
                                                    <a href="#">Bảo mật thông tin</a>
                                                </li>
                                                <li>
                                                    <a href="#">Giải quyết khiếu nại</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="footer-table-info">
                                            <h3 class="h3-title">Giờ Mở Cửa</h3>
                                            <ul>
                                                <li>
                                                    <i class="uil uil-clock"></i> Thứ 2 -> Thứ 6 : 7.30 AM -> 22.00 PM
                                                </li>
                                                <li>
                                                    <i class="uil uil-clock"></i> Thứ 7 <small style="font-size: 10px;">&</small> Chủ nhật : 7.00 -> 21.30 PM
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bottom-footer">

                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <div class="copyright-text">
                                    <p>Website kinh doanh &copy; 2023 <span class="name">ND - Restaurant</span> của Nguyên và Doãn.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a class="scrolltop" href="#home" aria-label="back to top"><i class="uil uil-angle-up"></i></a>
                </div>

            </footer>

        </div>
    </div>


    <!-- jquery  -->
    <script src="assets/js/jquery-3.5.1.min.js"></script>

    <!-- bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/popper.min.js"></script>

    <!-- fontawesome  -->
    <script src="assets/js/font-awesome.min.js"></script>

    <!-- swiper slider  -->
    <script src="assets/js/swiper-bundle.min.js"></script>

    <!-- mixitup -- filter  -->
    <script src="assets/js/jquery.mixitup.min.js"></script>

    <!-- fancy box  -->
    <script src="assets/js/jquery.fancybox.min.js"></script>

    <!-- parallax  -->
    <script src="assets/js/parallax.min.js"></script>

    <!-- gsap  -->
    <script src="assets/js/gsap.min.js"></script>

    <!-- scroll trigger  -->
    <script src="assets/js/ScrollTrigger.min.js"></script>
    <!-- scroll to plugin  -->
    <script src="assets/js/ScrollToPlugin.min.js"></script>
    <script src="assets/js/smooth-scroll.js"></script>
    <!-- custom js  -->
    <script src="script.js"></script>


</body>

</html>