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
                                <li><a href="about.php" style="color: #ff8243; background: #f3f3f5; box-shadow: inset 8px 8px 12px #e2e2e2, inset -8px -8px 12px #ffffff;">Thông tin</a></li>
                                <li><a href="../product/products.php">Thực đơn</a></li>
                                <li><a href="#gallery">Trưng bày</a></li>
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
                                    <a href="about.php?logout=<?php echo $user_id; ?>" class="delete-btn">Đăng xuất</a>
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
                            <h3>Giới thiệu</h3>
                            <div class="panel-text-cart">
                                <a href="../dashboard/Trangchu.php">Trang chủ</a> &rarr;
                                <a href="about.php">Giới thiệu</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="about mt-5">
                <div class="container">
                    <div class="row">
                        <h2>Giới thiệu</h2>
                        <div class="about-text mt-2">
                            <p>
                                ND - Restaurant là một nhà hàng nằm ẩn mình giữa phố xá nhộn nhịp của thành phố. Với bề dày hơn 10
                                năm kinh nghiệm trong lĩnh vực làm kinh doanh nhà hàng, ND - Restaurant đã nhanh chóng trở thành điểm đến lý tưởng
                                cho những ai đam mê các món ngon, độc lạ và muốn thưởng thức những món đặc sản tại địa phương.
                            </p>
                            <p>
                                Nhà hàng nổi tiếng này tự hào sở hữu một đội ngũ nhân viên tận tâm và giàu kinh nghiệm. Họ không chỉ đảm bảo
                                mang đến cho khách hàng những món ngon được làm tinh tế với sự tỉ mỉ và tình yêu, mà còn luôn sẵn lòng lắng
                                nghe và đáp ứng mọi nhu cầu đặc biệt của khách hàng.
                            </p>
                            <p>
                                Sự phong phú và đa dạng của thực đơn tại ND - Restaurant là một điểm nhấn đáng chú ý. Khách hàng có thể
                                chọn từ một loạt các loại đồ ăn tươi ngon như Beefsteak, BBQ Hàn Quốc, Bánh mì Việt Nam, Đồ ngọt, Sandwich
                                , Bánh xốp mật, Lẩu bò và nhiều món ngon khác nữa. Mỗi món ăn đều được chế biến từ
                                những nguyên liệu tươi ngon nhất và được trang trí tỉ mỉ, mang lại một trải nghiệm thưởng thức thật
                                tuyệt vời.
                            </p>
                            <p>
                                Không chỉ chăm chút vào hương vị, ND - Restaurant cũng đặc biệt quan tâm đến việc thể hiện sự sáng tạo và
                                độc đáo trong từng chi tiết trên các món ăn của mình. Bạn có thể tìm thấy những món ăn được trang
                                trí tinh tế với hình dáng, màu sắc và hoa văn độc đáo. Những điểm nhấn này không chỉ làm cho món ăn thêm
                                đẹp mắt mà còn tạo nên một phong cách riêng biệt cho ND - Restaurant.
                            </p>
                            <p>
                                Khách hàng đã trở thành fan hâm mộ của ND - Restaurant không chỉ vì những món ăn ngon mà còn vì không gian
                                ấm cúng và thoải mái tại nhà hàng. Với thiết kế sang trọng nhưng cổ điển, ND - Restaurant tạo ra một môi trường
                                lý tưởng để thư giãn và thưởng thức các tuyệt phẩm. Bạn có thể ngồi thoải mái, thưởng thức một ly bia, ly nước ngọt
                                và thúc đẩy hương vị món ăn bằng những cuộn giấy nhiệt động mời mọc.
                            </p>
                            <p>
                                ND - Restaurant không chỉ đáng để tham quan mà còn là điểm dừng chân lí tưởng để tìm mua những món ăn ngon
                                nhất. Cho dù bạn muốn tổ chức một bữa tiệc, mua một bữa ăn đặc biệt hay đơn giản là muốn
                                thưởng thức một món ăn nho nhỏ đầy mê hoặc, ND - Restaurant sẽ luôn là sự lựa chọn hàng đầu của bạn.
                            </p>
                            <p>
                                Hãy đến với ND - Restaurant và hãy để những món ăn tuyệt vời của chúng tôi làm cho cuộc sống bạn thêm tinh tế, phong phú và ngon nghẻ.
                            </p>
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
                                                    <a href="">Thông tin bên lề</a>
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
    <script src="../JS/script.js"></script>


</body>

</html>