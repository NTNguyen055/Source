<?php
include '../JDBC/config.php';

session_start();
$user_id = $_SESSION['user_id'];

// echo $user_id;
if (!isset($user_id)) {
    header('location:../Login_leg/login.php');
};

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:../Login_leg/login.php');
};

if (isset($_GET['removeOrder'])) {
    $remove_id = $_GET['removeOrder'];
    $delete = mysqli_query($conn, "DELETE FROM `order_pro` WHERE id = '$remove_id'") or die('query failed');

    if ($delete) {
        $success_msg[] = 'Xóa đơn đặt hàng này thành công!';
    }
}

if (isset($_GET['removeBook'])) {
    $remove_id = $_GET['removeBook'];
    $delete = mysqli_query($conn, "DELETE FROM `booking` WHERE id = '$remove_id'") or die('query failed');

    if ($delete) {
        $success_msg[] = 'Xóa đơn đặt bàn này thành công!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Website</title>
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

    <!-- start of header  -->
    <header class="site-header" style="background-color: rgba(255, 255, 255, 0.8);">

        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header-logo">
                        <a href="Trangchu.php">
                            <img src="../assets/images/logo.png" width="180" height="70" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="main-navigation">
                        <button class="menu-toggle"><span></span><span></span></button>
                        <nav class="header-menu">
                            <ul class="menu food-nav-menu">
                                <li><a href="Trangchu.php">Trang chủ</a></li>
                                <li><a href="about.php">Giới thiệu</a></li>
                                <li><a href="../product/products.php">Thực đơn</a></li>
                                <li><a href="#gallery">Đánh giá</a></li>
                                <li><a href="#blog">BLOG</a></li>
                                <li><a href="#contact">Liên hệ</a></li>
                            </ul>
                        </nav>
                        <div class="header-right">

                            <a href="../Cart_order/cart.php" class="header-btn header-cart">
                                <i class="uil uil-shopping-bag"></i>
                                <span class="cart-number">
                                    <?php
                                    $select = mysqli_query($conn, "SELECT * FROM `cart_new` WHERE user_id = '$user_id'")
                                        or die('query failed');

                                    echo mysqli_num_rows($select);
                                    ?>
                                </span>
                            </a>

                            <a href="javascript:void(0)" class="header-btn heart" title="Tìm kiếm tại đây">
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
                                    <a href="Trangchu.php?logout=<?php echo $user_id; ?>" class="delete-btn">Đăng xuất</a>
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
    <!-- header ends  -->

    <!-- Panel chính  -->
    <div id="viewport">
        <div id="js-scroll-content">

            <section class="panel-cart" id="home">
                <div class="panel-main-cart active">
                    <div class="container">
                        <div class="row">
                            <h3>Thông tin đơn</h3>
                            <div class="panel-text-cart">
                                <a href="../dashboard/Trangchu.php">Trang chủ</a> &rarr;
                                <a href="inforBookAndOrder.php">Thông tin đơn</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="UserBookingAndOrder mt-5 mb-5">
                <div class="container">
                    <div class="row ShowUBAO">
                        <div class="recentOrders" style="margin-bottom: 20px;">

                            <div class="cardHeader">
                                <h3>Đơn đặt hàng của bạn</h3>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <td>Tên người nhận hàng</td>
                                        <td>Số điện thoại</td>
                                        <td>Giá tiền</td>
                                        <td>Thanh toán</td>
                                        <td>Trạng thái</td>
                                        <td>Chức năng</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        $select = mysqli_query($conn, "SELECT * FROM `order_pro` ORDER BY id DESC") or die("query_failed");
                                        if (mysqli_num_rows($select) > 0) {
                                            while ($fetch = mysqli_fetch_assoc($select)) {
                                        ?>
                                                <td><?php echo $fetch['name']; ?></td>
                                                <td><?php echo $fetch['sdt']; ?></td>
                                                <td><?php echo number_format($fetch['total'], 0, ",", "."); ?>đ</td>
                                                <td><?php echo $fetch['methodCheck'] ?></td>
                                                <td>
                                                    <?php
                                                    if ($fetch['result'] != "Đã chốt đơn") {
                                                        echo "<span class='span-1'> " . $fetch['result'] . "</span>";
                                                    } else {
                                                        echo "<span class='span-2'> " . $fetch['result'] . "</span>";
                                                    }
                                                    ?> </td>
                                                <td>
                                                    <?php
                                                    if ($fetch['result'] != "Đã chốt đơn") {
                                                        echo "<a href='' title='Ấn vào để xem thông tin'>Xem</a>";
                                                        echo "<a href='' title='Ấn vào để sửa thông tin'>Sửa</a>";
                                                        echo "<a href='inforBookAndOrder.php?removeOrder=". $fetch['id'] ."' title='Ấn vào để xóa'>Xóa</a>";
                                                    } else {
                                                        echo "<a href='' title='Ấn vào để sửa thông tin'>Xem</a>";
                                                    }
                                                    ?>
                                                </td>

                                    </tr>
                            <?php
                                            }
                                        }
                            ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="recentOrders mt-4">

                            <div class="cardHeader">
                                <h3>Đơn đặt bàn của bạn</h3>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <td>Tên người nhận bàn</td>
                                        <td>Số người</td>
                                        <td>Khung giờ</td>
                                        <td>Ngày đến</td>
                                        <td>Trạng thái</td>
                                        <td>Chức năng</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        $select = mysqli_query($conn, "SELECT * FROM `booking` ORDER BY id DESC") or die("query_failed");
                                        if (mysqli_num_rows($select) > 0) {
                                            while ($fetch = mysqli_fetch_assoc($select)) {
                                        ?>
                                                <td><?php echo $fetch['name']; ?></td>
                                                <td><?php echo $fetch['person']; ?></td>
                                                <td><?php echo $fetch['hour']; ?></td>
                                                <td><?php echo $fetch['day']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($fetch['result'] != "Đã chốt đơn") {
                                                        echo "<span class='span-1'> " . $fetch['result'] . "</span>";
                                                    } else {
                                                        echo "<span class='span-2'> " . $fetch['result'] . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($fetch['result'] != "Đã chốt đơn") {
                                                        echo "<a href='' title='Ấn vào để xem thông tin'>Xem</a>";
                                                        echo "<a href='' title='Ấn vào để sửa thông tin'>Sửa</a>";
                                                        echo "<a href='inforBookAndOrder.php?removeBook=". $fetch['id'] ."' title='Ấn vào để xóa'>Xóa</a>";
                                                    } else {
                                                        echo "<a href='' title='Ấn vào để sửa thông tin'>Xem</a>";
                                                    }
                                                    ?>
                                                </td>

                                    </tr>
                            <?php
                                            }
                                        }
                            ?>
                                </tbody>
                            </table>
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
                                            <a href="Trangchu.php">
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
                                                    <a href="Trangchu.php">Trang chủ</a>
                                                </li>
                                                <li>
                                                    <a href="about.php">Giới thiệu</a>
                                                </li>
                                                <li>
                                                    <a href="../product/products.php">Thực đơn</a>
                                                </li>
                                                <li>
                                                    <a href="">Đánh giá</a>
                                                </li>
                                                <li>
                                                    <a href="">BLOG</a>
                                                </li>
                                                <li>
                                                    <a href="">Liên hệ</a>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <!-- custom js  -->
    <script src="../JS/script.js"></script>

    <?php include '../JDBC/alert.php'; ?>

</body>

</html>