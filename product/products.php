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

//Sắp xếp
if (!empty($sxField) && !empty($sxSort)) {
    $sxCondition = "ORDER BY `menu`.`" . $sxField . "` " . $sxSort;
    $param .= "field=" . $sxField . "&sort=" . $sxSort . "&";
}

//Tìm kiếm
$search = isset($_GET['name']) ? $_GET['name'] : "";

if ($search) {
    $search_where = "WHERE `name` LIKE '%" . $search . "%'";
    $param .= "name=" . $search . "&";
    $sortParam = "name=" . $search . "&";
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
                                <li><a href="../dashboard/about.php">Giới thiệu</a></li>
                                <li><a href="products.php" style="color: #ff8243; background: #f3f3f5; box-shadow: inset 8px 8px 12px #e2e2e2, inset -8px -8px 12px #ffffff;">Thực đơn</a></li>
                                <li><a href="#gallery">Đánh giá</a></li>
                                <li><a href="#blog">BLOG</a></li>
                                <li><a href="#contact">Liên hệ</a></li>
                            </ul>
                        </nav>
                        <div class="header-right">
                            <!-- <form action="#" class="header-search-form for-des">
                                <input type="search" class="form-input" placeholder="Search Here...">
                                <button type="submit">
                                    <i class="uil uil-search"></i>
                                </button>
                            </form> -->

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
                            <h3>Tất cả sản phẩm</h3>
                            <div class="panel-text-cart">
                                <a href="../dashboard/Trangchu.php">Trang chủ</a> &rarr;
                                <a href="products.php">Sản phẩm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="products-sec section mt-3 mb-5">

                <div class="book-table-shape">
                    <img src="../assets/images/table-leaves-shape.png" alt="">
                </div>

                <div class="book-table-shape book-table-shape2">
                    <img src="../assets/images/table-leaves-shape.png" alt="">
                </div>

                <div class="container">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="sec-title text-center">
                                <p class="sec-sub-title mb-3">Menu</p>
                                <h2 class="h2-title">Các mục của thực đơn</h2>
                                <div class="sec-title-shape mb-4">
                                    <img src="../assets/images/dish/title.webp" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <section class="products">
                <section class="sec-wp">
                    <div class="container">
                        <div class="row pro-slider">
                            <div class="swiper-wrapper">

                                <div class="col-6 col-md-4 col-xl-3 col-fix swiper-slide">
                                    <div class="pro-box">
                                        <div class="pro-box-img">
                                            <img src="../assets/images/dish/sang.webp" alt="">
                                            <div class="pro-box-text">
                                                <h4>Ăn sáng</h4>
                                                <a href="">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-xl-3 col-fix swiper-slide">
                                    <div class="pro-box">
                                        <div class="pro-box-img">
                                            <img src="../assets/images/dish/trua.jpg" alt="">
                                            <div class="pro-box-text">
                                                <h4>Ăn trưa</h4>
                                                <a href="">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-xl-3 col-fix swiper-slide">
                                    <div class="pro-box">
                                        <div class="pro-box-img">
                                            <img src="../assets/images/dish/toi.jpg" alt="">
                                            <div class="pro-box-text">
                                                <h4>Ăn tối</h4>
                                                <a href="">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-xl-3 col-fix swiper-slide">
                                    <div class="pro-box">
                                        <div class="pro-box-img">
                                            <img src="../assets/images/dish/lau6.jpg" alt="">
                                            <div class="pro-box-text">
                                                <h4>Các món lẩu</h4>
                                                <a href="">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-xl-3 col-fix swiper-slide">
                                    <div class="pro-box">
                                        <div class="pro-box-img">
                                            <img src="../assets/images/dish/nuong7.jpg" alt="">
                                            <div class="pro-box-text">
                                                <h4>Các món nướng</h4>
                                                <a href="">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-xl-3 col-fix swiper-slide">
                                    <div class="pro-box">
                                        <div class="pro-box-img">
                                            <img src="../assets/images/dish/pro-khaivi.jpg" alt="">
                                            <div class="pro-box-text">
                                                <h4>Tiệc khai vị</h4>
                                                <a href="">Xem ngay</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="swiper-button-wp">
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

            <section class="products-sec section mt-5">

                <div class="container">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="sec-title text-center">
                                <p class="sec-sub-title mb-3">Products</p>
                                <h2 class="h2-title">Các sản phẩm hiện có</h2>
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
                        <div class="row d-flex justify-content-between">
                            <div class="col-lg-3 dm-bl">
                                <div class="pro-dm">
                                    <h5>Danh mục sản phẩm</h5>
                                    <div class="tatCa chose">
                                        <span>Tất cả món ăn</span>
                                        <span>&rarr;</span>
                                    </div>
                                    <div class="khaiVi">
                                        <span>Món khai vị</span>
                                        <span>&rarr;</span>
                                    </div>
                                    <div class="sang">
                                        <span>Món ăn sáng</span>
                                        <span>&rarr;</span>
                                    </div>
                                    <div class="trua">
                                        <span>Món ăn trưa</span>
                                        <span>&rarr;</span>
                                    </div>
                                    <div class="toi">
                                        <span>Món ăn tối</span>
                                        <span>&rarr;</span>
                                    </div>
                                    <div class="lau">
                                        <span>Đại tiệc lẩu</span>
                                        <span>&rarr;</span>
                                    </div>
                                    <div class="nuong">
                                        <span>Nướng & Nướng BBQ</span>
                                        <span>&rarr;</span>
                                    </div>
                                </div>

                                <div class="pro-bl mt-4">
                                    <h4>Bộ lọc sản phẩm</h4>
                                    <span>Giúp bạn tìm sản phẩm nhanh hơn</span>
                                </div>

                                <div class="pro-sx mt-2 timKiem">
                                    <form method="get">
                                        <input type="text" placeholder="Tìm sản phẩm..." name="name" value="<?= isset($_GET['name']) ? $_GET['name'] : "" ?>">
                                        <button type="submit">
                                            <i class="uil uil-search" style="color: white;"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="pro-sx mt-2">
                                    <select class="sort-box" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == '' && isset($_GET['sort']) && $_GET['sort'] == '') { ?> selected <?php } ?> value="?<?= $sortParam ?>field=&sort=">Sắp xếp mặc định</option>

                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == 'name' && isset($_GET['sort']) && $_GET['sort'] == 'asc') { ?> selected <?php } ?> value="?<?= $sortParam ?>field=name&sort=asc">Từ A -> Z</option>

                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == 'name' && isset($_GET['sort']) && $_GET['sort'] == 'desc') { ?> selected <?php } ?> value="?<?= $sortParam ?>field=name&sort=desc">Từ Z -> A</option>

                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == 'asc') { ?> selected <?php } ?> value="?<?= $sortParam ?>field=price&sort=asc">Giá tăng dần</option>

                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == 'desc') { ?> selected <?php } ?> value="?<?= $sortParam ?>field=price&sort=desc">Giá giảm dần</option>
                                    </select>
                                </div>

                                <div class="pro-mg mt-4">
                                    <h5>Chọn mức giá</h5>
                                    <div class="pro-mg-content">
                                        <label for="">
                                            <input type="checkbox" name="chonGia"> Từ 10.000đ - 50.000đ
                                        </label>

                                        <label for="">
                                            <input type="checkbox" name="chonGia"> Từ 50.00đ - 100.000đ
                                        </label>

                                        <label for="">
                                            <input type="checkbox" name="chonGia"> Từ 100.00đ - 200.000đ
                                        </label>

                                        <label for="">
                                            <input type="checkbox" name="chonGia"> Từ 200.00đ - 500.000đ
                                        </label>

                                        <label for="">
                                            <input type="checkbox" name="chonGia"> Trên 500.000đ
                                        </label>

                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-9 col-12 menuAll menu-main">
                                <div class="row">

                                    <?php

                                    if (mysqli_num_rows($selectMain) > 0) {
                                        while ($fetchMain = mysqli_fetch_assoc($selectMain)) {
                                    ?>
                                            <div class="col-6 col-md-4 col-xl-3 col-fix">

                                                <form action="" method="post">

                                                    <div class="pro-content">

                                                        <div class="pro-content-img">
                                                            <a href="itemPro.php?id=<?php echo $fetchMain['id']; ?>&id_pro=<?php echo $fetchMain['id_pro']; ?>" title="<?php echo $fetchMain['name']; ?>">
                                                                <img src="../assets/images/dish/<?php echo $fetchMain['image']; ?>">
                                                            </a>
                                                            <div class="pro-content-heart">
                                                                <div class="pro-content-heart-newAndPer ">

                                                                    <?php
                                                                    if (!empty($fetchMain['rate'])) {
                                                                        echo "<span style='background: red;'>- " . $fetchMain['rate'] . "%</span>";
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    if (!empty($fetchMain['new'])) {
                                                                        echo "<span style='background: rgb(234, 105, 25);'>" . $fetchMain['new'] . "</span>";
                                                                    }
                                                                    ?>

                                                                </div>
                                                                <i class="uil uil-heart"></i>
                                                            </div>

                                                            <div class="pro-content-icon">

                                                                <input type="hidden" name="product_image" value="<?php echo $fetchMain['image']; ?>">
                                                                <input type="hidden" name="product_name" value="<?php echo $fetchMain['name']; ?>">
                                                                <input type="hidden" name="product_price" value="<?php echo $fetchMain['price']; ?>">


                                                                <button type="submit" name="add_to_cart" style="cursor: pointer;">
                                                                    <i class="uil uil-shopping-basket"></i>
                                                                </button>
                                                                <i class="uil uil-search"></i>
                                                            </div>

                                                        </div>
                                                        <div class="pro-content-text">
                                                            <h5>
                                                                <a href="itemPro.php?id=<?php echo $fetchMain['id']; ?>&id_pro=<?php echo $fetchMain['id_pro']; ?>">
                                                                    <?php echo $fetchMain['name']; ?>
                                                                </a>
                                                            </h5>
                                                            <div class="pro-content-text-num d-flex justify-content-center">
                                                                <span>
                                                                    <?php
                                                                    $number = number_format($fetchMain['price'], 0, ",", ".");
                                                                    echo $number;
                                                                    ?><u>đ</u> </span>
                                                                <?php
                                                                if (!empty($fetchMain['price_old'])) {
                                                                    echo "<span style='margin-left: 10px; margin-top: 4px; font-weight: normal !important; font-size: 13px; color: #666; text-decoration: line-through;'>"
                                                                        . number_format($fetchMain['price_old'], 0, ",", ".") . "<u>đ</u></span>";
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

                                    <?php

                                    include 'page.php';

                                    ?>

                                </div>
                            </div>

                            <div class="col-lg-9 menu-khaivi">
                                <div class="row">

                                    <?php
                                    $selectKV = mysqli_query($conn, "SELECT * FROM `menu` WHERE id_pro = 1") or die('query failed');

                                    if (mysqli_num_rows($selectKV) > 0) {
                                        while ($fetchKV = mysqli_fetch_assoc($selectKV)) {
                                    ?>
                                            <div class="col-6 col-md-4 col-xl-3 col-fix">

                                                <form action="" method="post">

                                                    <div class="pro-content">

                                                        <div class="pro-content-img">
                                                            <a href="itemPro.php?id=<?php echo $fetchKV['id']; ?>&id_pro=<?php echo $fetchKV['id_pro']; ?>" title="<?php echo $fetchKV['name']; ?>">
                                                                <img src="../assets/images/dish/<?php echo $fetchKV['image']; ?>">
                                                            </a>
                                                            <div class="pro-content-heart">

                                                                <div class="pro-content-heart-newAndPer ">

                                                                    <?php
                                                                    if (!empty($fetchKV['rate'])) {
                                                                        echo "<span style='background: red;'>- " . $fetchKV['rate'] . "%</span>";
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    if (!empty($fetchKV['new'])) {
                                                                        echo "<span style='background: rgb(234, 105, 25);'>" . $fetchKV['new'] . "</span>";
                                                                    }
                                                                    ?>

                                                                </div>

                                                                <i class="uil uil-heart"></i>
                                                            </div>

                                                            <div class="pro-content-icon">

                                                                <input type="hidden" name="product_image" value="<?php echo $fetchKV['image']; ?>">
                                                                <input type="hidden" name="product_name" value="<?php echo $fetchKV['name']; ?>">
                                                                <input type="hidden" name="product_price" value="<?php echo $fetchKV['price']; ?>">


                                                                <button type="submit" name="add_to_cart" style="cursor: pointer;">
                                                                    <i class="uil uil-shopping-basket"></i>
                                                                </button>
                                                                <i class="uil uil-search"></i>
                                                            </div>

                                                        </div>
                                                        <div class="pro-content-text">
                                                            <h5>
                                                                <a href="itemPro.php?id=<?php echo $fetchKV['id']; ?>&id_pro=<?php echo $fetchKV['id_pro']; ?>">
                                                                    <?php echo $fetchKV['name']; ?>
                                                                </a>
                                                            </h5>
                                                            <div class="pro-content-text-num d-flex justify-content-center">
                                                                <span><?php echo number_format($fetchKV['price'], 0, ",", "."); ?><u>đ</u> </span>
                                                                <?php
                                                                if (!empty($fetchKV['price_old'])) {
                                                                    echo "<span style='margin-left: 10px; margin-top: 4px; font-weight: normal !important; font-size: 13px; color: #666; text-decoration: line-through;'>"
                                                                        . number_format($fetchKV['price_old'], 0, ",", ".") . "<u>đ</u></span>";
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

                            <div class="col-lg-9 menu-sang">
                                <div class="row">

                                    <?php
                                    $selectSang = mysqli_query($conn, "SELECT * FROM `menu` WHERE id_pro = 2") or die('query failed');

                                    if (mysqli_num_rows($selectSang) > 0) {
                                        while ($fetchSang = mysqli_fetch_assoc($selectSang)) {
                                    ?>
                                            <div class="col-6 col-md-4 col-xl-3 col-fix">

                                                <form action="" method="post">

                                                    <div class="pro-content">
                                                        <div class="pro-content-img">
                                                            <a href="itemPro.php?id=<?php echo $fetchSang['id']; ?>&id_pro=<?php echo $fetchSang['id_pro']; ?>" title="<?php echo $fetchSang['name']; ?>">
                                                                <img src="../assets/images/dish/<?php echo $fetchSang['image']; ?>">
                                                            </a>
                                                            <div class="pro-content-heart">
                                                                <div class="pro-content-heart-newAndPer ">

                                                                    <?php
                                                                    if (!empty($fetchSang['rate'])) {
                                                                        echo "<span style='background: red;'>- " . $fetchSang['rate'] . "%</span>";
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    if (!empty($fetchSang['new'])) {
                                                                        echo "<span style='background: rgb(234, 105, 25);'>" . $fetchSang['new'] . "</span>";
                                                                    }
                                                                    ?>

                                                                </div>

                                                                <i class="uil uil-heart"></i>
                                                            </div>
                                                            <div class="pro-content-icon">

                                                                <input type="hidden" name="product_image" value="<?php echo $fetchSang['image']; ?>">
                                                                <input type="hidden" name="product_name" value="<?php echo $fetchSang['name']; ?>">
                                                                <input type="hidden" name="product_price" value="<?php echo $fetchSang['price']; ?>">


                                                                <button type="submit" name="add_to_cart" style="cursor: pointer;">
                                                                    <i class="uil uil-shopping-basket"></i>
                                                                </button>
                                                                <i class="uil uil-search"></i>
                                                            </div>
                                                        </div>
                                                        <div class="pro-content-text">
                                                            <h5>
                                                                <a href="itemPro.php?id=<?php echo $fetchSang['id']; ?>&id_pro=<?php echo $fetchSang['id_pro']; ?>">
                                                                    <?php echo $fetchSang['name']; ?>
                                                                </a>
                                                            </h5>
                                                            <div class="pro-content-text-num d-flex justify-content-center">
                                                                <span><?php echo number_format($fetchSang['price'], 0, ",", "."); ?><u>đ</u> </span>
                                                                <?php
                                                                if (!empty($fetchSang['price_old'])) {
                                                                    echo "<span style='margin-left: 10px; margin-top: 4px; font-weight: normal !important; font-size: 13px; color: #666; text-decoration: line-through;'>"
                                                                        . number_format($fetchSang['price_old'], 0, ",", ".") . "<u>đ</u></span>";
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

                            <div class="col-lg-9 menu-trua">
                                <div class="row">

                                    <?php
                                    $selectTrua = mysqli_query($conn, "SELECT * FROM `menu` WHERE id_pro = 3") or die('query failed');

                                    if (mysqli_num_rows($selectTrua) > 0) {
                                        while ($fetchTrua = mysqli_fetch_assoc($selectTrua)) {
                                    ?>
                                            <div class="col-6 col-md-4 col-xl-3 col-fix">

                                                <form action="" method="post">

                                                    <div class="pro-content">
                                                        <div class="pro-content-img">
                                                            <a href="itemPro.php?id=<?php echo $fetchTrua['id']; ?>&id_pro=<?php echo $fetchTrua['id_pro']; ?>" title="<?php echo $fetchTrua['name']; ?>">
                                                                <img src="../assets/images/dish/<?php echo $fetchTrua['image']; ?>">
                                                            </a>
                                                            <div class="pro-content-heart">
                                                                <div class="pro-content-heart-newAndPer ">

                                                                    <?php
                                                                    if (!empty($fetchTrua['rate'])) {
                                                                        echo "<span style='background: red;'>- " . $fetchTrua['rate'] . "%</span>";
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    if (!empty($fetchTrua['new'])) {
                                                                        echo "<span style='background: rgb(234, 105, 25);'>" . $fetchTrua['new'] . "</span>";
                                                                    }
                                                                    ?>

                                                                </div>
                                                                <i class="uil uil-heart"></i>
                                                            </div>
                                                            <div class="pro-content-icon">

                                                                <input type="hidden" name="product_image" value="<?php echo $fetchTrua['image']; ?>">
                                                                <input type="hidden" name="product_name" value="<?php echo $fetchTrua['name']; ?>">
                                                                <input type="hidden" name="product_price" value="<?php echo $fetchTrua['price']; ?>">


                                                                <button type="submit" name="add_to_cart" style="cursor: pointer;">
                                                                    <i class="uil uil-shopping-basket"></i>
                                                                </button>
                                                                <i class="uil uil-search"></i>
                                                            </div>
                                                        </div>
                                                        <div class="pro-content-text">
                                                            <h5>
                                                                <a href="itemPro.php?id=<?php echo $fetchTrua['id']; ?>&id_pro=<?php echo $fetchTrua['id_pro']; ?>">
                                                                    <?php echo $fetchTrua['name']; ?>
                                                                </a>
                                                            </h5>
                                                            <div class="pro-content-text-num d-flex justify-content-center">
                                                                <span><?php echo number_format($fetchTrua['price'], 0, ",", "."); ?><u>đ</u> </span>
                                                                <?php
                                                                if (!empty($fetchTrua['price_old'])) {
                                                                    echo "<span style='margin-left: 10px; margin-top: 4px; font-weight: normal !important; font-size: 13px; color: #666; text-decoration: line-through;'>"
                                                                        . number_format($fetchTrua['price_old'], 0, ",", ".") . "<u>đ</u></span>";
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

                            <div class="col-lg-9 menu-toi ">
                                <div class="row">

                                    <?php
                                    $selectToi = mysqli_query($conn, "SELECT * FROM `menu` WHERE id_pro = 4") or die('query failed');

                                    if (mysqli_num_rows($selectToi) > 0) {
                                        while ($fetchToi = mysqli_fetch_assoc($selectToi)) {
                                    ?>
                                            <div class="col-6 col-md-4 col-xl-3 col-fix">

                                                <form action="" method="post">

                                                    <div class="pro-content">
                                                        <div class="pro-content-img">
                                                            <a href="itemPro.php?id=<?php echo $fetchToi['id']; ?>&id_pro=<?php echo $fetchToi['id_pro']; ?>" title="<?php echo $fetchToi['name']; ?>">
                                                                <img src="../assets/images/dish/<?php echo $fetchToi['image']; ?>">
                                                            </a>
                                                            <div class="pro-content-heart">
                                                                <div class="pro-content-heart-newAndPer ">

                                                                    <?php
                                                                    if (!empty($fetchToi['rate'])) {
                                                                        echo "<span style='background: red;'>- " . $fetchToi['rate'] . "%</span>";
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    if (!empty($fetchToi['new'])) {
                                                                        echo "<span style='background: rgb(234, 105, 25);'>" . $fetchToi['new'] . "</span>";
                                                                    }
                                                                    ?>

                                                                </div>
                                                                <i class="uil uil-heart"></i>
                                                            </div>
                                                            <div class="pro-content-icon">

                                                                <input type="hidden" name="product_image" value="<?php echo $fetchToi['image']; ?>">
                                                                <input type="hidden" name="product_name" value="<?php echo $fetchToi['name']; ?>">
                                                                <input type="hidden" name="product_price" value="<?php echo $fetchToi['price']; ?>">


                                                                <button type="submit" name="add_to_cart" style="cursor: pointer;">
                                                                    <i class="uil uil-shopping-basket"></i>
                                                                </button>
                                                                <i class="uil uil-search"></i>
                                                            </div>
                                                        </div>
                                                        <div class="pro-content-text">
                                                            <h5>
                                                                <a href="itemPro.php?id=<?php echo $fetchToi['id']; ?>&id_pro=<?php echo $fetchToi['id_pro']; ?>">
                                                                    <?php echo $fetchToi['name']; ?>
                                                                </a>
                                                            </h5>
                                                            <div class="pro-content-text-num d-flex justify-content-center">
                                                                <span><?php echo number_format($fetchToi['price'], 0, ",", "."); ?><u>đ</u> </span>
                                                                <?php
                                                                if (!empty($fetchToi['price_old'])) {
                                                                    echo "<span style='margin-left: 10px; margin-top: 4px; font-weight: normal !important; font-size: 13px; color: #666; text-decoration: line-through;'>"
                                                                        . number_format($fetchToi['price_old'], 0, ",", ".") . "<u>đ</u></span>";
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

                            <div class="col-lg-9 menu-lau ">
                                <div class="row">

                                    <?php
                                    $selectLau = mysqli_query($conn, "SELECT * FROM `menu` WHERE id_pro = 5") or die('query failed');

                                    if (mysqli_num_rows($selectLau) > 0) {
                                        while ($fetchLau = mysqli_fetch_assoc($selectLau)) {
                                    ?>
                                            <div class="col-6 col-md-4 col-xl-3 col-fix">

                                                <form action="" method="post">

                                                    <div class="pro-content">
                                                        <div class="pro-content-img">
                                                            <a href="itemPro.php?id=<?php echo $fetchLau['id']; ?>&id_pro=<?php echo $fetchLau['id_pro']; ?>" title="<?php echo $fetchLau['name']; ?>">
                                                                <img src="../assets/images/dish/<?php echo $fetchLau['image']; ?>">
                                                            </a>
                                                            <div class="pro-content-heart">
                                                                <div class="pro-content-heart-newAndPer ">

                                                                    <?php
                                                                    if (!empty($fetchLau['rate'])) {
                                                                        echo "<span style='background: red;'>- " . $fetchLau['rate'] . "%</span>";
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    if (!empty($fetchLau['new'])) {
                                                                        echo "<span style='background: rgb(234, 105, 25);'>" . $fetchLau['new'] . "</span>";
                                                                    }
                                                                    ?>

                                                                </div>
                                                                <i class="uil uil-heart"></i>
                                                            </div>
                                                            <div class="pro-content-icon">
                                                                <input type="hidden" name="product_image" value="<?php echo $fetchLau['image']; ?>">
                                                                <input type="hidden" name="product_name" value="<?php echo $fetchLau['name']; ?>">
                                                                <input type="hidden" name="product_price" value="<?php echo $fetchLau['price']; ?>">


                                                                <button type="submit" name="add_to_cart" style="cursor: pointer;">
                                                                    <i class="uil uil-shopping-basket"></i>
                                                                </button>
                                                                <i class="uil uil-search"></i>
                                                            </div>
                                                        </div>
                                                        <div class="pro-content-text">
                                                            <h5>
                                                                <a href="itemPro.php?id=<?php echo $fetchLau['id']; ?>&id_pro=<?php echo $fetchLau['id_pro']; ?>">
                                                                    <?php echo $fetchLau['name']; ?>
                                                                </a>
                                                            </h5>
                                                            <div class="pro-content-text-num d-flex justify-content-center">
                                                                <span><?php echo number_format($fetchLau['price'], 0, ",", "."); ?><u>đ</u> </span>
                                                                <?php
                                                                if (!empty($fetchLau['price_old'])) {
                                                                    echo "<span style='margin-left: 10px; margin-top: 4px; font-weight: normal !important; font-size: 13px; color: #666; text-decoration: line-through;'>"
                                                                        . number_format($fetchLau['price_old'], 0, ",", ".") . "<u>đ</u></span>";
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

                            <div class="col-lg-9 menu-nuong ">
                                <div class="row">

                                    <?php
                                    $selectNuong = mysqli_query($conn, "SELECT * FROM `menu` WHERE id_pro = 6") or die('query failed');

                                    if (mysqli_num_rows($selectNuong) > 0) {
                                        while ($fetchNuong = mysqli_fetch_assoc($selectNuong)) {
                                    ?>
                                            <div class="col-6 col-md-4 col-xl-3 col-fix">

                                                <form action="" method="post">

                                                    <div class="pro-content">
                                                        <div class="pro-content-img">
                                                            <a href="itemPro.php?id=<?php echo $fetchNuong['id']; ?>&id_pro=<?php echo $fetchNuong['id_pro']; ?>" title="<?php echo $fetchNuong['name']; ?>">
                                                                <img src="../assets/images/dish/<?php echo $fetchNuong['image']; ?>">
                                                            </a>
                                                            <div class="pro-content-heart">
                                                                <div class="pro-content-heart-newAndPer ">

                                                                    <?php
                                                                    if (!empty($fetchNuong['rate'])) {
                                                                        echo "<span style='background: red;'>- " . $fetchNuong['rate'] . "%</span>";
                                                                    }
                                                                    ?>

                                                                    <?php
                                                                    if (!empty($fetchNuong['new'])) {
                                                                        echo "<span style='background: rgb(234, 105, 25);'>" . $fetchNuong['new'] . "</span>";
                                                                    }
                                                                    ?>

                                                                </div>

                                                                <i class="uil uil-heart"></i>
                                                            </div>
                                                            <div class="pro-content-icon">
                                                                <input type="hidden" name="product_image" value="<?php echo $fetchNuong['image']; ?>">
                                                                <input type="hidden" name="product_name" value="<?php echo $fetchNuong['name']; ?>">
                                                                <input type="hidden" name="product_price" value="<?php echo $fetchNuong['price']; ?>">


                                                                <button type="submit" name="add_to_cart" style="cursor: pointer;">
                                                                    <i class="uil uil-shopping-basket"></i>
                                                                </button> <i class="uil uil-search"></i>
                                                            </div>
                                                        </div>
                                                        <div class="pro-content-text">
                                                            <h5>
                                                                <a href="itemPro.php?id=<?php echo $fetchNuong['id']; ?>&id_pro=<?php echo $fetchNuong['id_pro']; ?>">
                                                                    <?php echo $fetchNuong['name']; ?>
                                                                </a>
                                                            </h5>
                                                            <div class="pro-content-text-num d-flex justify-content-center">
                                                                <span><?php echo number_format($fetchNuong['price'], 0, ",", "."); ?><u>đ</u></span>
                                                                <?php
                                                                if (!empty($fetchNuong['price_old'])) {
                                                                    echo "<span style='margin-left: 10px; margin-top: 4px; font-weight: normal !important; font-size: 13px; color: #666; text-decoration: line-through;'>"
                                                                        . number_format($fetchNuong['price_old'], 0, ",", ".") . "<u>đ</u></span>";
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
                    </div>
                </section>
            </section>

            <!-- Footer  -->
            <footer class="site-footer" id="contact">
                <div class=" top-footer mt-5 mb-5 pt-5">
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
    <!-- custom js  -->
    <script src="../JS/proScript.js"></script>

</body>

</html>