<?php
include '../JDBC/config.php';

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

$select_province = mysqli_query($conn, "SELECT * FROM `province`") or die('query failed');

// Lam chuc nang order
if (isset($_POST['dat-hang'])) {
    $orderName = mysqli_real_escape_string($conn, $_POST['name']);
    $orderEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $orderSDT = mysqli_real_escape_string($conn, $_POST['sdt']);
    $orderAddress = mysqli_real_escape_string($conn, $_POST['address']);
    $orderNote = mysqli_real_escape_string($conn, $_POST['textOrder']);
    $orderProvince = $_POST['province'];
    $orderDistrict = $_POST['district'];
    $orderWard = $_POST['wards'];
    $orderTT = $_POST['tt-part'];
    $orderDay = $_POST['date-ship'];
    $orderHour = $_POST['hour'];
    $orderTotal = $_POST['total'];

    $insertOrder = mysqli_query($conn, "INSERT INTO `order_pro` (user_id, email, name, sdt, address, province, district, ward, hour, day, methodCheck, total, note, result)
    VALUES ('$user_id', '$orderEmail', '$orderName', '$orderSDT', '$orderAddress', '$orderProvince', '$orderDistrict', 
    '$orderWard', '$orderHour', '$orderDay', '$orderTT', '$orderTotal', '$orderNote', 'Chưa xác nhận')") or die('query failed');

    if (isset($insertOrder)) {
        $deleteCartOrdered = mysqli_query($conn, "DELETE FROM `cart_new` WHERE user_id = '$user_id'") or die('query failed');
        header('location:../dashboard/Trangchu.php');
    }

    // $select_order = mysqli_query($conn, "SELECT * FROM `order` ")
}

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

    <!-- <div class="preload" data-preaload>
        <div class="circle"></div>
        <p class="text">ND - Restaurant</p>
    </div> -->

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>


    <div id="viewport">
        <div id="js-scroll-content">

            <section class="panel-cart" id="home">
                <div class="panel-main-cart active">
                    <div class="container">
                        <div class="row">
                            <h3>Thanh Toán Hóa Đơn</h3>
                            <div class="panel-text-cart">
                                <a href="../dashboard/Trangchu.php">Trang chủ</a> &rarr;
                                <a href="cart.php">Giỏ hàng</a> &rarr;
                                <a href="order.php">Thanh toán</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="order-box">
                <div class="order-box-main">
                    <div class="container">
                        <div class="row">
                            <form id="myForm" method="post">
                                <div class="col-lg-8">
                                    <div class="order-logo">
                                        <img src="../assets/images/logo.png" alt="" width="250px">
                                    </div>
                                    <div class="order-box-main-real">
                                        <div class="col-lg-6 order-thongTin">
                                            <h6>Thông tin nhận hàng</h6>
                                            <input type="email" placeholder="Email" name="email" required>
                                            <input type="text" placeholder="Họ và tên" name="name" required>
                                            <input type="text" placeholder="Số điện thoại" name="sdt" required>
                                            <input type="text" placeholder="Địa chỉ" name="address" required>

                                            <!-- Chọn tỉnh tp  -->
                                            <select name="province" id="province">
                                                <option value="">Chọn một tỉnh</option>
                                                <!-- populate options with data from your database or API -->
                                                <?php
                                                while ($row = mysqli_fetch_assoc($select_province)) {
                                                ?>
                                                    <option value="<?php echo $row['province_id'] ?>"><?php echo $row['name'] ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>

                                            <!-- Chọn quận huyện  -->
                                            <select id="district" name="district">
                                                <option value="">Chọn một quận/huyện</option>
                                            </select>

                                            <!-- Chọn xã  -->
                                            <select id="wards" name="wards">
                                                <option value="">Chọn một xã</option>
                                            </select>
                                            <label for="textOrder" style="font-size: 12px; font-weight: 500; margin-top: 10px;">Ghi chú (tùy chọn)</label>
                                            <textarea name="textOrder" id="textOrder" cols="20" rows="2" style="margin-top: 0;"></textarea>

                                        </div>
                                        <div class="col-lg-6 order-vanChuyen-thanhToan">
                                            <div class="vanChuyen">
                                                <h6>Vận chuyển</h6>
                                                <div class="vanChuyen-part">
                                                    <input type="radio" name="van-chuyen" value="Giao hàng tận nơi" checked>
                                                    <label>Giao hàng tận nơi</label>
                                                </div>
                                            </div>
                                            <div class="thanh-Toan mt-4">
                                                <h6>Thanh toán</h6>
                                                <div class="tt-part-1">
                                                    <input type="radio" name="tt-part" value="Thu Hộ (COD)">
                                                    <label>Thu hộ (COD)</label>
                                                </div>
                                                <div class="tt-part-2">
                                                    <input type="radio" name="tt-part" value="Chuyển khoản">
                                                    <label>Chuyển khoản</label>
                                                </div>
                                            </div>
                                            <h6 class="mt-4">Thời gian giao hàng</h6>
                                            <div class="bctl-date">
                                                <fieldset class="day">
                                                    <input type="date" name="date-ship" class="ship">
                                                </fieldset>
                                                <fieldset class="hour">
                                                    <select name="hour" class="select-hour">
                                                        <option selected>Chọn thời gian</option>
                                                        <option value="8h00 - 12h00">8h00 - 12h00</option>
                                                        <option value="14h00 - 18h00">14h00 - 18h00</option>
                                                        <option value="19h00 - 21h30">19h00 - 21h30</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="order-left">
                                        <h5 style="margin-left: 20px;">Đơn hàng (
                                            <?php
                                            $select = mysqli_query($conn, "SELECT * FROM `cart_new` WHERE user_id = '$user_id'")
                                                or die('query failed');

                                            echo mysqli_num_rows($select);
                                            ?>
                                            sản phẩm )
                                        </h5>
                                        <div class="order-left-main">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <?php
                                                        $total = 0;
                                                        $select_cart = mysqli_query($conn, "SELECT * FROM `cart_new` WHERE user_id = '$user_id'")
                                                            or die('query failed');
                                                        if (mysqli_num_rows($select_cart) > 0) {
                                                            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                                        ?>
                                                                <td>
                                                                    <div class="product">
                                                                        <div class="product_img">
                                                                            <img src="../assets/images/dish/<?php echo $fetch_cart['image']; ?>" alt="">
                                                                        </div>
                                                                        <span><?php echo $fetch_cart['quantity']; ?></span>
                                                                    </div>
                                                                </td>
                                                                <td colspan="3">
                                                                    <span><?php echo $fetch_cart['name']; ?></span>
                                                                </td>
                                                                <td>
                                                                    <span>
                                                                        <?php
                                                                        $total_sub = $fetch_cart['price'] * $fetch_cart['quantity'];
                                                                        echo number_format($total_sub, 0, ",", ".");
                                                                        ?><u>đ</u>
                                                                    </span>
                                                                </td>
                                                    </tr>
                                            <?php
                                                                $total += $total_sub;
                                                            }
                                                        }
                                                        // else {
                                                        //     echo '<tr><td style="padding:20px; text-transform: capitalize; text-align: center;" colspan="6">Giỏ hàng của bạn trống trơn!</td></tr>';
                                                        // }
                                            ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="order-left-bottom">
                                            <div class="o-l-b-tt">
                                                <span>Tạm tính</span>
                                                <span><?php echo number_format($total, 0, ",", "."); ?><u>đ</u></span>
                                            </div>
                                            <div class="o-l-b-pvc">
                                                <span>Phí vận chuyển</span>
                                                <span>40.000<u>đ</u></span>
                                            </div>
                                        </div>
                                        <div class="order-left-bottom-1">
                                            <div class="o-l-b-tc">
                                                <span>Tổng cộng:</span>
                                                <input type="text" value="<?php echo number_format($total + 40000, 0, ",", "."); ?>">
                                                <input type="hidden" value="<?php echo $total + 40000; ?>" name="total">
                                                <span><u style="font-size: 15px;">đ</u></span>
                                            </div>
                                            <div class="o-l-b-dh">
                                                <a href="cart.php">
                                                    < Quay về giỏ hàng </a>
                                                        <input type="submit" value="ĐẶT HÀNG" name="dat-hang" onclick="return confirm('Bạn chắc chắn muốn đặt hàng ?');">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                                                    <a href="../product/products.php">Thực đơn</a>
                                                </li>
                                                <li>
                                                    <a href="#gallery">Trưng bày</a>
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
    <script src="../JS/orderScript.js"></script>
</body>

</html>