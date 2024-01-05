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


if (isset($_POST['item-add-cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['item-number'];

    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'")
        or die('query failed');

    if (mysqli_num_rows($select_cart) > 0) {
        $update_cart = mysqli_query($conn, "UPDATE `cart` SET quantity = quantity + '$product_quantity' WHERE name = '$product_name'") or die('query failed');
        header('location:products.php');
    } else {
        $insert_cart = mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, image, quantity) 
        VALUES ('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
        header('location:products.php');
    }
};

//Chi tiết sản phẩm
$itemPro = mysqli_query($conn, "SELECT * FROM `menu` WHERE `id` = " . $_GET['id']);
$ListItemPro = mysqli_fetch_assoc($itemPro);

//Sản phẩm liên quan
$itemProLQ = mysqli_query($conn, "SELECT * FROM `menu` WHERE `id` != " . $_GET['id'] . " AND id_pro = " . $_GET['id_pro']);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

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

<body>
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
                                <li><a href="../dashboard/about.php">Giới thiệu</a></li>
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
                                    $select = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'")
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
                                    <a href="itemPro.php?logout=<?php echo $user_id; ?>" class="delete-btn">Đăng xuất</a>
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
                            <h3>Tên sản phẩm</h3>
                            <div class="panel-text-cart">
                                <a href="../dashboard/Trangchu.php">Trang chủ</a> &rarr;
                                <a href="products.php">Danh mục sản phẩm</a> &rarr;
                                <a href="products.php">Tên sản phẩm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="itemPro mb-5">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-5 itemPro-left mt-5">
                            <div class="itemPro-left-top">
                                <img src="../assets/images/dish/<?php echo $ListItemPro['image']; ?>" alt="">
                            </div>

                            <div class="row itemPro-left-bottom">
                                <?php
                                $select_item = mysqli_query($conn, "SELECT * FROM `image_pro` WHERE product_id = " . $_GET['id']);
                                if (mysqli_num_rows($select_item)) {
                                    while ($fetch_item = mysqli_fetch_assoc($select_item)) {
                                ?>
                                        <div class="itemPro-left-bottom-img">
                                            <img src="../<?php echo $fetch_item['path']; ?>" alt="">
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-lg-7 itemPro-right mt-5">
                            <div class="itemPro-right-content">
                                <form action="" method="post">
                                    <h4><?php echo $ListItemPro['name']; ?></h4>
                                    <div class="itemPro-right-content-text">

                                        <label>
                                            Loại: <span>
                                                <?php
                                                switch ($ListItemPro['id_pro']) {
                                                    case 1:
                                                        echo "Món khai vị";
                                                        break;

                                                    case 2:
                                                        echo "Món ăn sáng";
                                                        break;

                                                    case 3:
                                                        echo "Món ăn trưa";
                                                        break;

                                                    case 4:
                                                        echo "Món ăn tối";
                                                        break;

                                                    case 5:
                                                        echo "Món lẩu";
                                                        break;

                                                    case 6:
                                                        echo "Món nướng";
                                                        break;
                                                }
                                                ?>
                                            </span> &ensp;
                                            <?php
                                            if (!empty($ListItemPro['new'])) {
                                                echo "Chất: <span>" . $ListItemPro['new'] . "</span>";
                                            }
                                            ?>
                                        </label>


                                        <label>
                                            Giá: <i>
                                                <?php echo number_format($ListItemPro['price'], 0, ",", "."); ?><u>đ</u>
                                                <small>
                                                    <?php
                                                    if (!empty($ListItemPro['price_old'])) {
                                                        echo number_format($ListItemPro['price_old'], 0, ",", ".");
                                                    }
                                                    ?><u>đ</u>
                                                </small>
                                            </i>
                                        </label>

                                        <label>
                                            <?php
                                            if (!empty($ListItemPro['price_old'])) {
                                                $tietKiem = $ListItemPro['price_old'] - $ListItemPro['price'];
                                                echo "Tiết kiệm: <span>" . number_format($tietKiem, 0, ",", ".") . "<u>đ</u></span>";
                                            }
                                            ?>
                                        </label>

                                        <label>Số lượng:</label>

                                        <input type="number" name="item-number" min="1" value="1">


                                        <input type="hidden" name="product_image" value="<?php echo $ListItemPro['image']; ?>">
                                        <input type="hidden" name="product_name" value="<?php echo $ListItemPro['name']; ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $ListItemPro['price']; ?>">

                                        <div class="row item-submit mt-3">
                                            <div class="col-lg-6 p-2">
                                                <button type="submit" name="item-add-cart">
                                                    <span>
                                                        <strong>
                                                            <i class="uil uil-shopping-basket"></i>
                                                        </strong>
                                                    </span>
                                                    <span>
                                                        <small>Thêm vào giỏ</small> <br>
                                                        Giao hàng tận nơi miễn phí
                                                    </span>
                                                </button>
                                            </div>

                                            <div class="col-lg-6 p-2">
                                                <button type="submit" name="item-add-favorite">
                                                    <span>
                                                        <strong>
                                                            <i class="uil uil-heart"></i>
                                                        </strong>
                                                    </span>
                                                    <span>
                                                        <small>Thêm vào giỏ</small> <br>
                                                        Giao hàng tận nơi miễn phí
                                                    </span>
                                                </button>
                                            </div>

                                        </div>

                                    </div>
                                </form>
                            </div>

                            <div class="item-right-bottom">
                                <div class="item-right-bottom-top">
                                    <div class="irbt-img">
                                        <img src="../assets/images/giftbox.webp" width="30" height="30" alt="">
                                    </div>
                                    <div class="irbt-text">
                                        <span>Khuyến mãi đặc biệt !!!</span>
                                    </div>
                                </div>
                                <div class="item-right-bottom-mid">
                                    <div class="irbm-img-text">
                                        <img src="../assets/images/km_product1.webp" alt="">
                                        <span> Áp dung phiếu quà tặng/ Mã giảm giá theo ngành hàng.</span>
                                    </div>

                                    <div class="irbm-img-text">
                                        <img src="../assets/images/km_product1.webp" alt="">
                                        <span> Giảm giá 10% khi mua từ 5 đơn hàng.</span>
                                    </div>

                                    <div class="irbm-img-text">
                                        <img src="../assets/images/km_product3.webp" alt="">
                                        <span> Tặng quà khi mua hàng tại nhà hàng của ND - Restaurant, áp dụng khi mua tại Đà Nẵng và 1 số khu vực khác.</span>
                                    </div>
                                </div>
                                <div class="row item-right-bottom-bottom">
                                    <div class="col-lg-6 mt-4">
                                        <div class="irbb-content">
                                            <div class="irbb-img">
                                                <img src="../assets/images/chinhsach_1.webp" width="40px" height="40px" alt="">
                                            </div>
                                            <div class="irbb-text">
                                                <span>Miễn phí vận chuyển</span> <br>
                                                <span>Áp dụng free ship cho tất cả đơn hàng từ 41900 nghìn.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-4">
                                        <div class="irbb-content">
                                            <div class="irbb-img">
                                                <img src="../assets/images/chinhsach_2.webp" width="40px" height="40px" alt="">
                                            </div>
                                            <div class="irbb-text">
                                                <span>Đổi trả dễ dàng</span> <br>
                                                <span>Đổi ngay trong ngày nếu như bánh không đúng yêu cầu.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-4">
                                        <div class="irbb-content">
                                            <div class="irbb-img">
                                                <img src="../assets/images/chinhsach_3.webp" width="40px" height="40px" alt="">
                                            </div>
                                            <div class="irbb-text active">
                                                <span>Hỗ trợ nhanh chóng</span> <br>
                                                <span>Gọi ngay số Hotline: 19006750 để được hỗ trợ ngay.</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-4">
                                        <div class="irbb-content">
                                            <div class="irbb-img">
                                                <img src="../assets/images/chinhsach_4.webp" width="40px" height="40px" alt="">
                                            </div>
                                            <div class="irbb-text">
                                                <span>Thanh toán đa dạng</span> <br>
                                                <span>Thanh toán khi nhận hàng, Napas, Visa, Chuyển Khoản</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <section class="moTaSP-HDMH mt-5">
                <div class="container">
                    <div class="row M-H-title">
                        <div class="moTaSP">
                            <div class="moTa active1">Mô tả sản phẩm</div> &emsp;
                            <div class="HD">Hướng dẫn mua hàng</div>
                        </div>
                    </div>
                    <div class="row M-H-text mt-2">
                        <div class="M-H-text-1 active2">
                            <span>Thành phần: Trứng gà, bột mỳ, bơ, nước, đường, sô cô la, tinh bột biến tính (E1414),
                                béo thực vật, bột sữa gầy, chất làm dày (E401), hương vani tổng hợp, màu thực phẩm (E160aii),
                                muối.</span>
                        </div>
                        <div class="M-H-text-2">
                            <span>
                                <b>Bước 1:</b> Truy cập website và lựa chọn sản phẩm cần mua. <br>

                                <b>Bước 2:</b> Click và sản phẩm muốn mua, màn hình hiển thị ra pop up với các lựa chọn sau: <br>

                                Nếu bạn muốn tiếp tục mua hàng: Bấm vào phần tiếp tục mua hàng để lựa chọn thêm sản phẩm vào giỏ hàng. <br>

                                Nếu bạn muốn xem giỏ hàng để cập nhật sản phẩm: Bấm vào xem giỏ hàng. <br>

                                Nếu bạn muốn đặt hàng và thanh toán cho sản phẩm này vui lòng bấm vào: Đặt hàng và thanh toán. <br>

                                <b>Bước 3:</b> Lựa chọn thông tin tài khoản thanh toán: <br>

                                Nếu bạn đã có tài khoản vui lòng nhập thông tin tên đăng nhập là email và mật khẩu vào mục đã có tài khoản trên hệ thống. <br>

                                Nếu bạn chưa có tài khoản và muốn đăng ký tài khoản vui lòng điền các thông tin cá nhân để tiếp tục đăng ký tài khoản. Khi có tài khoản bạn sẽ dễ dàng theo dõi được đơn hàng của mình. <br>

                                Nếu bạn muốn mua hàng mà không cần tài khoản vui lòng nhấp chuột vào mục đặt hàng không cần tài khoản. <br>

                                <b>Bước 4:</b> Điền các thông tin của bạn để nhận đơn hàng, lựa chọn hình thức thanh toán và vận chuyển cho đơn hàng của mình: <br>

                                <b></b> Xem lại thông tin đặt hàng, điền chú thích và gửi đơn hàng. <br>

                                Sau khi nhận được đơn hàng bạn gửi chúng tôi sẽ liên hệ bằng cách gọi điện lại để xác nhận lại đơn hàng và địa chỉ của bạn. <br>

                                Trân trọng cảm ơn!
                            </span>
                        </div>
                    </div>

                </div>
            </section>

            <section class="products-sec section mt-5">

                <div class="container">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="sec-title text-center">
                                <p class="sec-sub-title mb-3">Products</p>
                                <h2 class="h2-title">Các sản phẩm liên quan</h2>
                                <div class="sec-title-shape">
                                    <img src="../assets/images/dish/title.webp" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <section class="all-pro">
                <section class="sec-wp">
                    <div class="container">
                        <div class="row pro-slider">
                            <div class="swiper-wrapper menuAll">
                                <?php

                                if (mysqli_num_rows($itemProLQ) > 0) {
                                    while ($ListItemProLQ = mysqli_fetch_assoc($itemProLQ)) {
                                ?>
                                        <div class="col-lg-3 swiper-slide">

                                            <form action="" method="post">

                                                <div class="pro-content">

                                                    <div class="pro-content-img">
                                                        <a href="itemPro.php?id=<?php echo $ListItemProLQ['id']; ?>&id_pro=<?php echo $ListItemProLQ['id_pro']; ?>" title="<?php echo $ListItemProLQ['name']; ?>">
                                                            <img src="../assets/images/dish/<?php echo $ListItemProLQ['image']; ?>">
                                                        </a>
                                                        <div class="pro-content-heart">
                                                            <div class="pro-content-heart-newAndPer ">

                                                                <?php
                                                                if (!empty($ListItemProLQ['rate'])) {
                                                                    echo "<span style='background: red;'>- " . $ListItemProLQ['rate'] . "%</span>";
                                                                }
                                                                ?>

                                                                <?php
                                                                if (!empty($ListItemProLQ['new'])) {
                                                                    echo "<span style='background: rgb(234, 105, 25);'>" . $ListItemProLQ['new'] . "</span>";
                                                                }
                                                                ?>

                                                            </div>
                                                            <i class="uil uil-heart"></i>
                                                        </div>

                                                        <div class="pro-content-icon">

                                                            <input type="hidden" name="product_image" value="<?php echo $ListItemProLQ['image']; ?>">
                                                            <input type="hidden" name="product_name" value="<?php echo $ListItemProLQ['name']; ?>">
                                                            <input type="hidden" name="product_price" value="<?php echo $ListItemProLQ['price']; ?>">


                                                            <button type="submit" name="add_to_cart" style="cursor: pointer;">
                                                                <i class="uil uil-shopping-basket"></i>
                                                            </button>
                                                            <i class="uil uil-search"></i>
                                                        </div>

                                                    </div>
                                                    <div class="pro-content-text">
                                                        <h5>
                                                            <a href="itemPro.php?id=<?php echo $ListItemProLQ['id']; ?>&id_pro=<?php echo $ListItemProLQ['id_pro']; ?>">
                                                                <?php echo $ListItemProLQ['name']; ?>
                                                            </a>
                                                        </h5>
                                                        <div class="pro-content-text-num d-flex justify-content-center">
                                                            <span>
                                                                <?php

                                                                echo number_format($ListItemProLQ['price'], 0, ",", ".");
                                                                ?><u>đ</u> </span>
                                                            <?php
                                                            if (!empty($ListItemProLQ['price_old'])) {
                                                                echo "<span style='margin-left: 10px; margin-top: 4px; font-weight: normal !important; font-size: 13px; color: #666; text-decoration: line-through;'>"
                                                                    . number_format($ListItemProLQ['price_old'], 0, ",", ".") . "<u>đ</u></span>";
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

                            <div class="swiper-button-wp" style="margin-top: 0;">
                                <div class="swiper-button-prev swiper-button">
                                    <i class="uil uil-angle-left"></i>
                                </div>

                                <div class="swiper-button-next swiper-button">
                                    <i class="uil uil-angle-right"></i>
                                </div>
                            </div>

                            <div class="swiper-pagination"></div>
                        </div>


                    </div>
                </section>
            </section>

            <footer class="site-footer" id="contact">
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
                                                    <a href="../dashboard/about.php">Thông tin</a>
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
    <script src="../JS/itemScript.js"></script>


</body>

</html>