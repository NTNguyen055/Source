<?php
include '../JDBC/config.php';

session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:../Login_leg/login.php');
};

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:../Login_leg/login.php');
};

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart_new` WHERE name = '$product_name' AND user_id = '$user_id'")
        or die('query failed');

    if (mysqli_num_rows($select_cart) > 0) {
        $update_cart = mysqli_query($conn, "UPDATE `cart_new` SET quantity = quantity + '$product_quantity' WHERE name = '$product_name'") or die('query failed');
    } else {
        $insert_cart = mysqli_query($conn, "INSERT INTO `cart_new` (user_id, name, price, image, quantity) 
        VALUES ('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
    }
};

// Menu
$sxField = isset($_GET['field']) ? $_GET['field'] : "";
$sxSort = isset($_GET['sort']) ? $_GET['sort'] : "";
$sxCondition = "";
$param = "";
$sortParam = "";


//Tìm kiếm
$search = isset($_GET['name']) ? $_GET['name'] : "";

if ($search) {
    $search_where = "WHERE `name` LIKE '%" . $search . "%'";
    $param .= "name=" . $search . "&";
    $sortParam = "name=" . $search . "&";
}

//Sắp xếp

if (!empty($sxField) && !empty($sxSort)) {
    $sxCondition = "ORDER BY `menu`.`" . $sxField . "` " . $sxSort;
    $param .= "field=" . $sxField . "&sort=" . $sxSort . "&";
}

//Phân trang
$per_page = !empty($_GET['per_page']) ? $_GET['per_page'] : 16;
$defaultPage = !empty($_GET['page']) ? $_GET['page'] : 1;

$offset = ($defaultPage - 1) * $per_page;

if ($search) {
    $selectMain = mysqli_query($conn, "SELECT * FROM `menu` WHERE `name` LIKE '%" . $search . "%' " . $sxCondition . " LIMIT " . $per_page . " OFFSET " . $offset) or die('query failed');
    $totalPro = mysqli_query($conn, "SELECT * FROM `menu` WHERE `name` LIKE '%" . $search . "%'");
} else {
    $selectMain = mysqli_query($conn, "SELECT * FROM `menu` " . $sxCondition . " LIMIT " . $per_page . " OFFSET " . $offset) or die('query failed');
    $totalPro = mysqli_query($conn, "SELECT * FROM `menu`");
}

$totalPro = $totalPro->num_rows;

$totalPage = ceil($totalPro / $per_page);

//Content đã tìm kiếm
$searchContent = $_GET['input'];

$result = mysqli_query($conn, "SELECT * FROM `menu` WHERE `name` LIKE '%" . $searchContent . "%'");
$resultMain = mysqli_query($conn, "SELECT * FROM `menu` WHERE `name` LIKE '%" . $searchContent . "%' LIMIT 15");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Hóa Đơn</title>

    <!-- for icons  -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- bootstrap  -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <!-- for swiper slider  -->
    <link rel="stylesheet" href="../assets/css/swiper-bundle.min.css">

    <!-- fancy box  -->
    <link rel="stylesheet" href="../assets/css/jquery.fancybox.min.css">

    <!-- custom css  -->
    <link rel="stylesheet" href="../CSS/style.css">

</head>

<body class="body-fixed">

    <!--- #PRELOADER-->

    <div class="preload" data-preaload>
        <div class="circle"></div>
        <p class="text">ND - Restaurant</p>
    </div>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>

    <header class="site-header" style="background-color: rgba(255, 255, 255, 0.8);">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header-logo">
                        <a href="../dashboard/Trangchu.php">
                            <img src="../assets/images/logo.png" width="180" height="70" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="main-navigation">
                        <button class="menu-toggle"><span></span><span></span></button>
                        <nav class="header-menu">
                            <ul class="menu food-nav-menu">
                                <li><a href="../dashboard/Trangchu.php">Trang chủ</a></li>
                                <li><a href="../dashboard/about.php">Thông tin</a></li>
                                <li><a href="products.php">Thực đơn</a></li>
                                <li><a href="#gallery">Đánh giá</a></li>
                                <li><a href="#blog">BLOG</a></li>
                                <li><a href="#contact">Liên hệ</a></li>
                            </ul>
                        </nav>
                        <div class="header-right">
                            <a href="../Cart_order/cart.php" class="header-btn header-cart" title="Giỏ hàng">
                                <i class="uil uil-shopping-bag"></i>
                                <span class="cart-number">
                                    <?php
                                    $select = mysqli_query($conn, "SELECT * FROM `cart_new` WHERE user_id = '$user_id'")
                                        or die('query failed');

                                    echo mysqli_num_rows($select);
                                    ?>
                                </span>
                            </a>

                            <a href="javascript:void(0)" class="header-btn heart" title="Mục yêu thích">
                                <i class="uil uil-search"></i>
                            </a>

                            <a href="javascript:void(0)" class="header-btn user" title="Tài khoản của bạn">
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
                                        echo '<img src="../assets/images/default-avatar.png">';
                                    } else {
                                        echo '<img src="../uploaded_img/' . $fetch['image'] . '">';
                                    }

                                    ?>
                                    <h3 class="user-name"><?php echo $fetch['name']; ?></h3>
                                    <a href="../update_user_admin/update_profile.php" class="btn-user">Thông tinn</a>
                                    <a href="products.php?logout=<?php echo $user_id; ?>" class="delete-btn">Đăng xuất</a>
                                    <p><a href="../Login_leg/login.php">Đăng nhập</a> or <a href="../Login_leg/register.php">Đăng ký</a> tài khoản khác</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-container">
            <div class="search-box">

                <div class="search-box-left">
                    <form action="">
                        <input type="text" placeholder="Tìm kiếm món ăn..." name="search-pro" id="live_search" autocomplete="off">
                        <button type="submit" name="submit_search_pro">
                            <i class="uil uil-search"></i>
                        </button>
                    </form>
                    <div id="searchResult"></div>
                </div>

            </div>
            <div class="search-box-right">
                <i class="uil uil-times"></i>
            </div>

        </div>

    </header>

    <div id="viewport">
        <div id="js-scroll-content">

            <section class="panel-cart" id="home">
                <div class="panel-main-cart active">
                    <div class="container">
                        <div class="row">
                            <h3>Tìm kiếm</h3>
                            <div class="panel-text-cart">
                                <a href="../dashboard/Trangchu.php">Trang chủ</a> &rarr;
                                <a href="products.php">Tìm kiếm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="seactionSearchPro">
                <div class="searchPro">
                    
                    <div class="container-fuild p-5">
                        <div class="row">
                            <h3>Có <?php echo mysqli_num_rows($result); ?> kết quả tìm kiếm phù hợp</h3>
                        </div>
                        <div class="row mt-4">
                            <?php

                            if (mysqli_num_rows($result) > 0) {
                                while ($fetch = mysqli_fetch_assoc($result)) {
                            ?>
                                    <div class="col-6 col-md-4 col-lg-3 col-xl-2 col-fix">

                                        <form action="" method="post">

                                            <div class="pro-content">

                                                <div class="pro-content-img">
                                                    <a href="itemPro.php?id=<?php echo $fetch['id']; ?>&id_pro=<?php echo $fetch['id_pro']; ?>" title="<?php echo $fetch['name']; ?>">
                                                        <img src="../assets/images/dish/<?php echo $fetch['image']; ?>">
                                                    </a>
                                                    <div class="pro-content-heart">
                                                        <div class="pro-content-heart-newAndPer ">

                                                            <?php
                                                            if (!empty($fetch['rate'])) {
                                                                echo "<span style='background: red;'>- " . $fetch['rate'] . "%</span>";
                                                            }
                                                            ?>

                                                            <?php
                                                            if (!empty($fetch['new'])) {
                                                                echo "<span style='background: rgb(234, 105, 25);'>" . $fetch['new'] . "</span>";
                                                            }
                                                            ?>

                                                        </div>
                                                        <i class="uil uil-heart"></i>
                                                    </div>

                                                    <div class="pro-content-icon">

                                                        <input type="hidden" name="product_image" value="<?php echo $fetch['image']; ?>">
                                                        <input type="hidden" name="product_name" value="<?php echo $fetch['name']; ?>">
                                                        <input type="hidden" name="product_price" value="<?php echo $fetch['price']; ?>">


                                                        <button type="submit" name="add_to_cart" style="cursor: pointer;">
                                                            <i class="uil uil-shopping-basket"></i>
                                                        </button>
                                                        <i class="uil uil-search"></i>
                                                    </div>

                                                </div>
                                                <div class="pro-content-text">
                                                    <h5>
                                                        <a href="itemPro.php?id=<?php echo $fetch['id']; ?>&id_pro=<?php echo $fetch['id_pro']; ?>">
                                                            <?php echo $fetch['name']; ?>
                                                        </a>
                                                    </h5>
                                                    <div class="pro-content-text-num d-flex justify-content-center">
                                                        <span>
                                                            <?php
                                                            $number = number_format($fetch['price'], 0, ",", ".");
                                                            echo $number;
                                                            ?><u>đ</u> </span>
                                                        <?php
                                                        if (!empty($fetch['price_old'])) {
                                                            echo "<span style='margin-left: 10px; margin-top: 4px; font-weight: normal !important; font-size: 13px; color: #666; text-decoration: line-through;'>"
                                                                . number_format($fetch['price_old'], 0, ",", ".") . "<u>đ</u></span>";
                                                        }
                                                        ?>
                                                        <input type="number" name="product_quantity" min="1" value="1">

                                                    </div>
                                                </div>
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
            </section>

            <!-- Footer  -->
            <footer class="site-footer" id="contact" style="background: #f3e7cd;">
                <div class="top-footer mt-5 mb-5 pt-5">
                    <div class="sec-wp">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="footer-info">

                                        <div class="footer-logo">
                                            <a href="../dashboard/Trangchu.php">
                                                <img src="../assets/images/logo.png" alt="">
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
                                                        <i class="uil uil-envelope-alt"> <span>nguyenkun555@gmail.com</span> <br> & <span>thanhdoan2903@gmail.com</span></i>
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
                                                    <a href="../dashboard/Trangchu.php">Trang chủ</a>
                                                </li>
                                                <li>
                                                    <a href="../dashboard/about.php">Giới thiệu</a>
                                                </li>
                                                <li>
                                                    <a href="products.php">Thực đơn</a>
                                                </li>
                                                <li>
                                                    <a href="#gallery">Đánh giá</a>
                                                </li>
                                                <li>
                                                    <a href="blog">BLOG</a>
                                                </li>
                                                <li>
                                                    <a href="blog">Liên hệ</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="footer-menu">
                                            <h3 class="h3-title">Nhà Hàng</h3>
                                            <ul>
                                                <li>
                                                    <a href="#">Thông tin bên lề</a>
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

    <!-- Tìm kiếm  -->
    <div class="search"></div>

    <!-- jquery  -->
    <script src="../assets/js/jquery-3.5.1.min.js"></script>

    <!-- bootstrap -->
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>

    <!-- fontawesome  -->
    <script src="../assets/js/font-awesome.min.js"></script>

    <!-- swiper slider  -->
    <script src="../assets/js/swiper-bundle.min.js"></script>

    <!-- mixitup -- filter  -->
    <script src="../assets/js/jquery.mixitup.min.js"></script>

    <!-- fancy box  -->
    <script src="../assets/js/jquery.fancybox.min.js"></script>

    <!-- parallax  -->
    <script src="../assets/js/parallax.min.js"></script>

    <!-- gsap  -->
    <script src="../assets/js/gsap.min.js"></script>

    <!-- scroll trigger  -->
    <script src="../assets/js/ScrollTrigger.min.js"></script>
    <!-- scroll to plugin  -->
    <script src="../assets/js/ScrollToPlugin.min.js"></script>
    <script src="../assets/js/smooth-scroll.js"></script>
    <!-- custom js  -->
    <script src="../JS/searchPro.js"></script>

</body>

</html>