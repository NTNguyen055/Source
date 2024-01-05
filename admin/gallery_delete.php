<?php
include '../JDBC/config.php';
include '../JDBC/funtion.php';
session_start();

// $user_id = $_SESSION['user_id'];
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../Login_leg/login.php');
};

if (isset($_GET['logout'])) {
    unset($admin_id);
    session_destroy();
    header('location:../Login_leg/login.php');
};


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>

    <link rel="stylesheet" href="../CSS/adminCSS.css">

</head>

<body>

    <div class="preload" data-preaload>
        <div class="circle"></div>
        <p class="text">ND - Restaurant</p>
    </div>

    <div class="contai">
        <div class="navi">
            <ul>
                <li>
                    <span class="icon">
                        <ion-icon name="logo-github"></ion-icon>
                    </span>
                    <span class="title">Admin Form</span>
                </li>
                <li class="navi-1">
                    <span class="icon">
                        <ion-icon name="home-outline"></ion-icon>
                    </span>
                    <span class="title"><a href="admin.php">Trang chủ</a></span>
                </li>
                <li class="navi-2">
                    <span class="icon">
                        <ion-icon name="people-outline"></ion-icon>
                    </span>
                    <span class="title"><a href="">Tài khoản</a></span>

                </li>
                <li class="navi-3 nv">
                    <span class="icon">
                        <ion-icon name="restaurant-outline"></ion-icon>
                    </span>
                    <span class="title"><a href="products.php">Thực đơn</a></span>
                </li>
                <li class="navi-4">
                    <span class="icon">
                        <ion-icon name="camera-outline"></ion-icon>
                    </span>
                    <span class="title"><a href="">Blog</a></span>
                </li>
                <li class="navi-5">
                    <span class="icon">
                        <ion-icon name="accessibility-outline"></ion-icon>
                    </span>
                    <span class="title"><a href="">Chefs & Slider</a></span>
                </li>
                <li class="navi-6">
                    <span class="icon">
                        <ion-icon name="chatbubble-outline"></ion-icon>
                    </span>
                    <span class="title"><a href="">Comment & Sub</a></span>
                </li>
                <li class="navi-7">
                    <span class="icon">
                        <ion-icon name="logo-snapchat"></ion-icon>
                    </span>
                    <span class="title"><a href="">ND - Restaurant</a></span>
                </li>
                <li>
                    <span class="icon">
                        <ion-icon name="log-out-outline"></ion-icon>
                    </span>
                    <span class="title"><a href="">Đăng xuất</a></span>
                </li>
            </ul>
        </div>
        <div class="main">
            <div class="top">

                <div class="small-menu">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <!-- <div class="search">
                    <label>
                        <input type="text" placeholder="Search here!">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div> -->

                <div class="user">
                    <ion-icon name="person-circle-outline"></ion-icon>
                </div>

                <div class="user-box">

                    <div class="profile">

                        <?php
                        $select = mysqli_query($conn, "SELECT * FROM `admin_form` WHERE id = '$admin_id'")
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
                        <a href="admin.php?logout=<?php echo $admin_id; ?>" class="delete-btn">Đăng xuất</a>
                        <p><a href="../Login_leg/login.php">Đăng nhập</a> or <a href="../Login_leg/register.php">Đăng ký</a> tài khoản khác</p>
                    </div>
                </div>
            </div>
            <div class="content-main">
                <div class="content-box">
                    <div class="content-box-title">
                        <h3>Xóa sản phẩm</h3>
                    </div>

                    <div class="box-content">
                        <?php
                        $error = false;

                        if (isset($_GET['id']) && !empty($_GET['id'])) {
                            $result = mysqli_query($conn, "DELETE FROM `image_pro` WHERE `id` = " . $_GET['id']);
                            if (!$result) {
                                $error = "Không thể xóa ảnh trong thư viện.";
                            }
                            mysqli_close($conn);
                            if ($error !== false) {
                        ?>
                                <div class="box-content-1">
                                    <h2>Thông báo</h2>
                                    <h4><?= $error ?>!</h4>
                                    <a href="javascript:window.history.go(-1)">Quay lại</a>
                                </div>
                            <?php } else { ?>
                                <div class="box-content-1">
                                    <h3>Xóa thư viện ảnh của sản phẩm thành công!</h3>
                                    <a href="javascript:window.history.go(-1)">Quay lại</a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="../assets/js/jquery-3.5.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script src="../JS/adminScript.js"></script>

    <?php include '../JDBC/alert.php'; ?>

</body>

</html>