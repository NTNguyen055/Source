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

// Lam dat ban
if (isset($_POST['book-table'])) {
    $bookName = mysqli_real_escape_string($conn, $_POST['book-name']);
    $bookSdt = mysqli_real_escape_string($conn, $_POST['book-sdt']);
    $bookTime = $_POST['book-time'];
    $bookEmail = mysqli_real_escape_string($conn, $_POST['book-email']);
    $bookDate = $_POST['book-date'];
    $bookPerson = $_POST['book-person'];
    $bookNote = $_POST['book-note'];

    $select_book = mysqli_query($conn, "SELECT * FROM `booking` WHERE name = '$bookName' AND user_id = '$user_id'") or die('query failed');


    mysqli_query($conn, "INSERT INTO `booking` (user_id, name, sdt, email, person, hour, day, note, result) 
        VALUE ('$user_id', '$bookName', '$bookSdt', '$bookEmail', '$bookPerson', '$bookTime', '$bookDate', '$bookNote', 'Chưa xác nhận')") or die('query failed');
    $success_msg[] = "Đặt bàn thành công!";
    
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
    <header class="site-header">

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
                                <li><a href="Trangchu.php" style="color: #ff8243; background: #f3f3f5; box-shadow: inset 8px 8px 12px #e2e2e2, inset -8px -8px 12px #ffffff;">Trang chủ</a></li>
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

            <section class="main-banner" id="home">
                <div class="js-parallax-scene">
                    <div class="banner-shape-1 w-100" data-depth="0.30">
                        <img src="../assets/images/berry.png" alt="">
                    </div>
                    <div class="banner-shape-2 w-100" data-depth="0.25">
                        <img src="../assets/images/leaf.png" alt="">
                    </div>
                </div>
                <div class="sec-wp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="banner-text">
                                    <h1 class="h1-title">
                                        Welcome to our
                                        <span>ND</span>
                                        restaurant.
                                    </h1>
                                    <p> ND Restaurant - một nhà hàng online mang phong cách hiện đại, bắt trend nhất hiện nay.
                                        Bên cạnh đó ND mang đến cho khách hàng 1 trải nghiệm trọn vẹn và dễ dàng nhất.
                                    </p>
                                    <div class="banner-btn mt-4">
                                        <a href="../product/products.php" class="sec-btn">Check our Menu</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">

                                <?php
                                $select_slide_main = mysqli_query($conn, "SELECT * FROM `slider` WHERE id = 1") or die("query failded");
                                $fetch_ssm = mysqli_fetch_assoc($select_slide_main);
                                ?>
                                <div class="banner-img-wp">
                                    <div class="banner-img" style="background-image: url(../assets/images/<?php echo $fetch_ssm['image']; ?>);">
                                    </div>
                                </div>
                                <div class="banner-img-text mt-4 m-auto">
                                    <h5 class="h5-title"><?php echo $fetch_ssm['name']; ?></h5>
                                    <p><?php echo $fetch_ssm['text']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="brands-sec">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="brand-title mb-5">
                                <h5 class="h5-title">Đáng tin cậy với nhiều khu vực</h5>
                            </div>
                            <div class="brands-row">

                                <?php
                                $select_brands = mysqli_query($conn, "SELECT * FROM `slider` WHERE id_slider = 2") or die("query failed");

                                if (mysqli_num_rows($select_brands) > 0) {
                                    while ($fetch_brands = mysqli_fetch_assoc($select_brands)) {
                                ?>

                                        <div class="brands-box">
                                            <img src="../assets/images/brands/<?php echo $fetch_brands['image']; ?>" alt="" width="280" height="265">
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

            <!-- Giới thiệu  -->
            <section class="about-sec section" id="about">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="sec-title text-center mb-5">

                                <?php
                                $select_about = mysqli_query($conn, "SELECT * FROM `slider` WHERE id = 3") or die("query failed");
                                $fetch_about = mysqli_fetch_assoc($select_about);
                                ?>
                                <p class="sec-sub-title mb-3">About ND</p>
                                <h2 class="h2-title">Khám phá <span>câu chuyện của ND</span></h2>

                                <div class="sec-title-shape mb-4">
                                    <img src="../assets/images/title-shape.svg" alt="">
                                </div>
                                <p>
                                    <?php echo $fetch_about['text']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 m-auto">
                            <div class="about-video">
                                <div class="about-video-img" style="background-image: url(../assets/images/<?php echo $fetch_about['image']; ?>);"></div>
                                <div class="play-btn-wp">
                                    <a href="../assets/images/video.mp4" data-fancybox="video" class="play-btn">
                                        <i class="uil uil-play"></i>
                                    </a>
                                    <span>Xem Thú Dị</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Đặt bàn ăn  -->
            <!-- Booking bàn ăn và slider  -->
            <section class="book-table section bg-light">
                <div class="book-table-shape">
                    <img src="../assets/images/table-leaves-shape.png" alt="">
                </div>

                <div class="book-table-shape book-table-shape2">
                    <img src="../assets/images/table-leaves-shape.png" alt="">
                </div>



                <div class="sec-wp">
                    <div class="booking">
                        <div class="container">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="sec-title text-center mb-5">
                                        <p class="sec-sub-title mb-3">Đặt Bàn</p>
                                        <h2 class="h2-title">Thời Gian Mở</h2>
                                        <div class="sec-title-shape mb-4">
                                            <img src="../assets/images/title-shape.svg" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-center">

                                <div class="col-lg-5">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="book-title">
                                            <h3>Đặt Bàn Online</h3>
                                            <p>
                                                <?php 
                                                $select = mysqli_query($conn, "SELECT * FROM `booking`");
                                                if (mysqli_num_rows($select) < 10) {
                                                    echo "Nơi gửi yêu cầu đặt bàn trước và chúng tôi sẽ chuẩn bị cho bạn";
                                                } else {
                                                    echo "Chúng tôi hiện tại đã hết bàn! Mong quý khách thông cảm!";
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <div class="book-user">
                                            <div class="book-user-text">

                                                <span>Tên người đặt: </span>
                                                <input type="text" name="book-name" class="book" required>

                                                <span>Số điện thoại: </span>
                                                <input type="text" name="book-sdt" class="book" required>

                                                <span>Giờ: </span>
                                                <input type="time" name="book-time" class="book" required>

                                            </div>
                                            <div class="book-user-text">
                                                <span>Email: </span>
                                                <input type="email" name="book-email" class="book" required>

                                                <span>Ngày: </span>
                                                <input type="date" name="book-date" class="book" required>

                                                <span>Số người: (từ 1 đến 20)</span>
                                                <input type="number" name="book-person" value="1" class="book" required min="1" max="10">
                                            </div>
                                        </div>
                                        <div class="book-note">
                                            <textarea name="book-note" id="" cols="30" rows="10"></textarea>
                                            <?php 
                                             $select = mysqli_query($conn, "SELECT * FROM `booking`");
                                             if (mysqli_num_rows($select) < 10) {
                                                echo "<input type='submit' name='book-table' value='Book A Table'>";
                                             }
                                             else {
                                                echo "<input class='booking-btn-full' type='submit' name='book-table' value='Hết bàn'>";
                                             }
                                            ?>
                                        </div>
                                    </form>

                                </div>

                                <div class="col-lg-3">
                                    <div class="book-title">
                                        <h3>Các Khung Giờ</h3>
                                        <p>Chỉ có 1 chút thay đổi nhỏ vào cuối tuần</p>
                                    </div>
                                    <div class="book-box">
                                        <div class="book-location-text">
                                            <h6>Vị Trí</h6>
                                            <span>456 Đ. Trần Đại Nghĩa - Hòa Hải - Ngũ Hành Sơn - Đà Nẵng City</span>
                                        </div>
                                        <div class="book-breakfast-text">
                                            <h6>Bữa Sáng</h6>
                                            <span>Thứ 2 đến Chủ Nhật</span> <br>
                                            <span>7:00 am -> 10:00 am</span>
                                        </div>
                                        <div class="book-lunch-text">
                                            <h6>Bữa Trưa và Chiều</h6>
                                            <span>Thứ 2 đến Chủ Nhật</span> <br>
                                            <span>10:30 am -> 5:30 pm</span>
                                        </div>
                                        <div class="book-dinner-text">
                                            <h6>Bữa Tối</h6>
                                            <span>Thứ 2 đến Chủ Nhật</span> <br>
                                            <span>6:00 pm -> 21:00 pm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <!-- slider  -->
                        <div class="row" id="gallery">
                            <div class="col-lg-10 m-auto">
                                <div class="book-table-img-slider" id="icon">
                                    <div class="swiper-wrapper">

                                        <?php
                                        $select_slider = mysqli_query($conn, "SELECT * FROM `slider` WHERE id_slider = 3") or die("query_failed");

                                        if (mysqli_num_rows($select_slider) > 0) {
                                            while ($fetch_slider = mysqli_fetch_assoc($select_slider)) {
                                        ?>
                                                <a href="../assets/images/<?php echo $fetch_slider['image']; ?>" data-fancybox="table-slider" class="book-table-img back-img 
                                        swiper-slide" style="background-image: url(../assets/images/<?php echo $fetch_slider['image']; ?>);"></a>
                                        <?php
                                            };
                                        };
                                        ?>

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
                        </div>
                    </div>
                </div>
            </section>


            <!-- Đội ngũ đầu bết  -->
            <section class="our-team section">
                <div class="sec-wp">
                    <div class="container">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sec-title text-center mb-5">
                                    <p class="sec-sub-title mb-3">Đội Ngũ Chiefs</p>
                                    <h2 class="h2-title">Gặp gỡ Chúng Tôi</h2>
                                    <div class="sec-title-shape mb-4">
                                        <img src="../assets/images/title-shape.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row team-slider">
                            <div class="swiper-wrapper">

                                <?php
                                $select_chefs = mysqli_query($conn, "SELECT * FROM `chefs`") or die("query failed");

                                if (mysqli_num_rows($select_chefs) > 0) {
                                    while ($fetch_chefs = mysqli_fetch_assoc($select_chefs)) {
                                ?>

                                        <div class="col-lg-4 swiper-slide">
                                            <div class="team-box text-center">
                                                <div class="team-img back-img" style="background-image: url(../assets/images/chef/<?php echo $fetch_chefs['image']; ?>);">

                                                </div>
                                                <h3 class="h3-title"><?php echo $fetch_chefs['name']; ?></h3>
                                                <div class="social-icon">
                                                    <ul>
                                                        <li>
                                                            <a href="<?php echo $fetch_chefs['link']; ?>"><i class="uil uil-facebook-f"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)"><i class="uil uil-instagram"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)"><i class="uil uil-youtube"></i></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    };
                                };
                                ?>
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
                </div>
            </section>


            <!-- Bình luận dữ hen  -->
            <section class="testimonials section bg-light">
                <div class="sec-wp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sec-title text-center mb-5">
                                    <p class="sec-sub-title mb-3">Bình Luận 24/7</p>
                                    <h2 class="h2-title">Mọi người nói gì về <span>ND - Restaurant</span></h2>
                                    <div class="sec-title-shape mb-4">
                                        <img src="../assets/images/title-shape.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="testimonials-img">
                                    <img src="../assets/images/testimonial-img.png" alt="">
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="testimonials-box">
                                            <div class="testimonial-box-top">
                                                <div class="testimonials-box-img back-img" style="background-image: url(../assets/images/testimonials/t1.jpg);">
                                                </div>
                                                <div class="star-rating-wp">
                                                    <div class="star-rating">
                                                        <span class="star-rating__fill" style="width:85%"></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="testimonials-box-text">
                                                <h3 class="h3-title">
                                                    L.Messi
                                                </h3>
                                                <p>Nhà hàng này đỉnh kout lắm ấy. Top 1 thế giới rồi, vượt ra cả vũ trụ,... Không thể khác được</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="testimonials-box">
                                            <div class="testimonial-box-top">
                                                <div class="testimonials-box-img back-img" style="background-image: url(../assets/images/testimonials/t2.jpg);">
                                                </div>
                                                <div class="star-rating-wp">
                                                    <div class="star-rating">
                                                        <span class="star-rating__fill" style="width:80%"></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="testimonials-box-text">
                                                <h3 class="h3-title">
                                                    C.Ronaldo
                                                </h3>
                                                <p>Ngon hơn đồ ăn căn tin và bên ngoài trường em... Chấp luôn tất cả các nhà hàng ở Đà Nẵng này.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="testimonials-box">
                                            <div class="testimonial-box-top">
                                                <div class="testimonials-box-img back-img" style="background-image: url(../assets/images/testimonials/t3.jpg);">
                                                </div>
                                                <div class="star-rating-wp">
                                                    <div class="star-rating">
                                                        <span class="star-rating__fill" style="width:89%"></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="testimonials-box-text">
                                                <h3 class="h3-title">
                                                    Z.Ibrahimuvic
                                                </h3>
                                                <p>Chúa tể nhà hàng. Kẻ nắm giữ công thức mỹ vị, ông thần món ăn, vị vua không ngai
                                                    của G.Ramsay, hoàng đế món ăn lề đường,...</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="testimonials-box">
                                            <div class="testimonial-box-top">
                                                <div class="testimonials-box-img back-img" style="background-image: url(../assets/images/testimonials/t4.jpg);">
                                                </div>
                                                <div class="star-rating-wp">
                                                    <div class="star-rating">
                                                        <span class="star-rating__fill" style="width:100%"></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="testimonials-box-text">
                                                <h3 class="h3-title">
                                                    Neymar JR
                                                </h3>
                                                <p>Chấp mọi loại phốt, bật tính năng hút review, lôi kéo drama, ăn toàn bú fame,...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- <section class="faq-sec section-repeat-img" style="background-image: url(../assets/images/faq-bg.png);">
                <div class="sec-wp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sec-title text-center mb-5">
                                    <p class="sec-sub-title mb-3">COMMENT</p>
                                    <h2 class="h2-title">Nơi Hội Tụ Nói Xàm</h2>
                                    <div class="sec-title-shape mb-4">
                                        <img src="../assets/images/title-shape.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="faq-now">

                            <div class="faq-box">
                                <h4 class="h4-title"></h4>
                                <p></p>
                            </div>

                            <div class="faq-box">
                                <h4 class="h4-title"></h4>
                                <p></p>
                            </div>

                            <div class="faq-box">
                                <h4 class="h4-title"></h4>
                                <p></p>
                            </div>

                            <div class="faq-box">
                                <h4 class="h4-title"></h4>
                                <p></p>
                            </div>

                            <div class="faq-box">
                                <h4 class="h4-title"></h4>
                                <p></p>
                            </div>

                            <div class="faq-box">
                                <h4 class="h4-title"></h4>
                                <p></p>
                            </div>

                        </div>
                    </div>
                </div>
            </section> -->

            <div class="bg-pattern bg-light repeat-img" style="background-image: url(../assets/images/blog-pattern-bg.png);">
                <section class="blog-sec section" id="blog">
                    <div class="sec-wp">
                        <div class="container">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="sec-title text-center mb-5">
                                        <p class="sec-sub-title mb-3">Blog Our</p>
                                        <h2 class="h2-title">Trải Nghiệm Tuyệt Vời Nhất</h2>
                                        <div class="sec-title-shape mb-4">
                                            <img src="../assets/images/title-shape.svg" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <?php
                                $select_blog = mysqli_query($conn, "SELECT * FROM `blog` LIMIT 3") or die("query failed");

                                if (mysqli_num_rows($select_blog) > 0) {
                                    while ($fetch_blog = mysqli_fetch_assoc($select_blog)) {
                                ?>

                                        <div class="col-lg-4">
                                            <div class="blog-box">

                                                <div class="blog-img back-img" style="background-image: url(../assets/images/blog/<?php echo $fetch_blog['image']; ?>);"></div>
                                                <div class="blog-text">
                                                    <p class="blog-date"></p>
                                                    <a href="#" class="h4-title"></a>
                                                    <p><?php echo $fetch_blog['text']; ?></p>
                                                    <a href="#" class="sec-btn">Xem Thêm</a>
                                                </div>

                                            </div>
                                        </div>

                                <?php
                                    };
                                };
                                ?>
                            </div>

                        </div>
                    </div>
                </section>

                <!-- New bản tin -->
                <section class="newsletter-sec section pt-0">
                    <div class="sec-wp">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8 m-auto">
                                    <div class="newsletter-box text-center back-img white-text" style="background-image: url(../assets/images/news.jpg);">

                                        <div class="bg-overlay dark-overlay"></div>
                                        <div class="sec-wp">

                                            <div class="newsletter-box-text">
                                                <h2 class="h2-title">Subscribe cho nhà hàng</h2>
                                                <p>Mọi người có thể gửi tài khoản Email về cho chúng tôi để ủng hộ thêm độ uy tín và Sub cho cửa hàng.
                                                    Từ việc đó tạo thêm cho chúng tôi nhiều động lực để phát triển mạnh hơn, phát huy những điều tốt và khắc phục các vấn đề còn thiếu xót.
                                                </p>
                                            </div>
                                            <form action="#" class="newsletter-form">
                                                <input type="email" class="form-input" placeholder="Vui lòng nhập Email tại đây!" required>
                                                <button type="submit" class="sec-btn primary-btn">Hoàn Thành</button>
                                            </form>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>

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