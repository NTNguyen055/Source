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

if (isset($_POST['update_cart'])) {
    $update_quantity = $_POST['cart_quantity'];
    $update_id = $_POST['cart_id'];

    mysqli_query($conn, "UPDATE `cart_new` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
    $success_msg[] = "Cập nhật lại thành công số lượng sản phẩm!";
}

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart_new` WHERE id = '$remove_id'") or die('query failed');
    header('location:cart.php');
    $success_msg[] = "Xóa thành công sản phẩm!";
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart_new` WHERE user_id = '$user_id'") or die('query_failed');
    header('location:cart.php');
    $success_msg[] = "Xóa thành công tất cả sản phẩm!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>

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
                                <li><a href="../dashboard/about.php">Giới thiệu</a></li>
                                <li><a href="../product/products.php">Thực đơn</a></li>
                                <li><a href="#gallery">Đánh giá</a></li>
                                <li><a href="#blog">BLOG</a></li>
                                <li><a href="#contact">Liên hệ</a></li>
                            </ul>
                        </nav>
                        <div class="header-right">

                            <a href="cart.php" class="header-btn header-cart">
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
                                        echo '<img src="../assets/images/default-avatar.png">';
                                    } else {
                                        echo '<img src="../uploaded_img/' . $fetch['image'] . '">';
                                    }

                                    ?>
                                    <h3 class="user-name"><?php echo $fetch['name']; ?></h3>
                                    <a href="../update_user_admin/update_profile.php" class="btn-user">Thông tinn</a>
                                    <a href="cart.php?logout=<?php echo $user_id; ?>" class="delete-btn">Đăng xuất</a>
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
                        <a href="../product/searchPro.php">
                            <i class="uil uil-search"></i>
                        </a>
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
                            <h3>Giỏ Hàng</h3>
                            <div class="panel-text-cart">
                                <a href="../dashboard/Trangchu.php">Trang chủ</a> &rarr;
                                <a href="cart.php">Giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="box-cart pt-5 pb-5" style="background-color: #fff;">
                <div class="box-cart-main">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8">

                                <table>
                                    <thead>
                                        <tr>
                                            <th colspan="3">Thông tin sản phẩm</th>
                                            <th>Đơn giá</th>
                                            <th>Số lượng</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $total = 0;
                                        $select_cart = mysqli_query($conn, "SELECT * FROM `cart_new` WHERE user_id = '$user_id'")
                                            or die('query failed');
                                        if (mysqli_num_rows($select_cart) > 0) {
                                            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <img src="../assets/images/dish/<?php echo $fetch_cart['image']; ?>" alt="" width="108px" height="108px">
                                                    </td>
                                                    <td colspan="2" class="title-item">
                                                        <span><?php echo $fetch_cart['name']; ?></span> <br>
                                                        <a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-item" onclick="return confirm('Bạn muốn xóa món ăn này khỏi giỏ hàng ?')">Xóa</a>
                                                    </td>
                                                    <td>
                                                        <span style="font-size: 13px;"><?php echo number_format($fetch_cart['price'], 0, ",", "."); ?><u>đ</u></span>
                                                    </td>
                                                    <td>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                                            <input class="number-item" type="number" name="cart_quantity" min="1" value="<?php echo $fetch_cart['quantity']; ?>">
                                                            <input class="update-item" type="submit" name="update_cart" value="Cập nhật">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <span style="font-size: 13px;">
                                                            <?php
                                                            $total_sub = $fetch_cart['price'] * $fetch_cart['quantity'];
                                                            echo number_format($total_sub, 0, ",", ".");
                                                            ?><u>đ</u></span>
                                                    </td>
                                                </tr>
                                        <?php
                                                $total += (float) $total_sub;
                                            }
                                        } else {
                                            echo '<tr><td style="padding:20px; text-transform: capitalize; text-align: center;" colspan="6">Giỏ hàng của bạn trống trơn!</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <div class="tt-xoaAll">
                                    <div class="tongTien">
                                        <span>Tổng tiền: </span>
                                        <span> <?php echo number_format($total, 0, ",", "."); ?><u>đ</u> </span>
                                    </div>
                                    <div class="tt-xoaALl-main">
                                        <a href="cart.php?delete_all" onclick="return confirm('Bạn muốn xóa cả giỏ hàng ư ?');" class="delete-all <?php echo ($total > 1) ? '' : 'disabled'; ?>">Xóa tất cả</a>
                                        <a href="order.php" class="thanhToan <?php echo ($total > 1) ? '' : 'disabled'; ?>">Thanh toán</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="row box-cart-title-left">

                                    <div class="sale">
                                        <marquee scrollamount="10">
                                            <font color="red" style="font-weight: 600;">MIỄN PHÍ</font> giao hàng cho đơn hàng từ 500.000 VNĐ
                                        </marquee>
                                    </div>
                                    <h5>Dịch vụ khách hàng</h5>
                                    <div class="bctl-dichVu">
                                        <span>Bạn cần hỗ trợ từ chúng tôi? Hãy liên hệ ngay</span> <br>
                                        <a href="tel: +84397556616"> <i class="uil uil-phone-volume"></i> 0397556616</a> <br>
                                        <a href=""> <i class="uil uil-facebook-f"></i> Chúng tôi trên Facebook</a> <br>
                                        <span>Giờ mở cửa (7:00 - 22:00)</span>
                                    </div>
                                    <h5>Nhà hàng ND-Res</h5>
                                    <div class="bctl-nhaHang">
                                        <span><i class="uil uil-check"></i> ND-Res chuyên các món nướng BBQ và lẩu</span> <br>
                                        <span><i class="uil uil-check"></i> Món nướng và lâu thơm ngon, đặc sắc và đậm đà hương vị thuần</span> <br>
                                        <span><i class="uil uil-check"></i> Giao hàng <font color="red" style="font-weight: 600;">TOÀN</font> thành phố với nhiều cơ sở</span> <br>
                                    </div>
                                    <div class="bctl-img">
                                        <img src="../assets/images/dish/cart_payment_1.svg" alt="">
                                        <img src="../assets/images/dish/cart_payment_2.svg" alt="">
                                        <img src="../assets/images/dish/cart_payment_3.svg" alt="">
                                        <img src="../assets/images/dish/cart_payment_4.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Footer  -->
            <footer class="site-footer" id="contact">
                <div class="top-footer mb-5 pt-5">
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
                                                    <a href="#about">Thông tin</a>
                                                </li>
                                                <li>
                                                    <a href="../product/products.php">Thực đơn</a>
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
                                                    <a href="#about">Thông tin bên lề</a>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js  -->
    <script src="../JS/cartScript.js"></script>

    <?php include '../JDBC/alert.php'; ?>
</body>

</html>