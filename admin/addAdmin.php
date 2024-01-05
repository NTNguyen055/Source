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

if (isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')) {
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $galleryImages = array();
        $image = "";

        if ($_GET['action'] == 'add') {
            $image = $_FILES['image']['name'];
        } else if ($_GET['action'] == 'edit') {
            $image = $_POST['image'];
            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['name'];
            }
        }

        if (!isset($image) && !empty($_POST['image'])) {
            $image = $_POST['image'];
        }

        if ($_GET['action'] == 'edit' && !empty($_GET['id'])) { //Cập nhật lại sản phẩm
            $oldPassword = $_POST['old_password'];
            $password = $_POST['password'];
            $cfPassword = $_POST['cf_password'];
            $email = $_POST['email'];

            $select = mysqli_query($conn, "SELECT * FROM `admin_form` WHERE `id` = " . $_GET['id']);
            $admin_form = $select->fetch_assoc();

            if (empty($oldPassword)) {
                $warning_msg[] = "Bạn chưa nhập mật khẩu cũ!";
            } else if ($oldPassword != $admin_form['password']) {
                $warning_msg[] = "Mật khẩu cũ không chính xác!";
            } else if (empty($cfPassword)) {
                $warning_msg[] = "Bạn chưa nhập mật khẩu mới!";
            } else if (empty($password)) {
                $warning_msg[] = "Bạn chưa nhập lại mật khẩu mới!";
            } else if (!empty($cfPassword) && $cfPassword != $password) {
                $warning_msg[] = "Nhập lại mật khẩu không chính xác!";
            } else if (empty($_POST['name'])) {
                $warning_msg[] = "Bạn chưa nhập tên sử dụng!";
            } else if (empty($_POST['email'])) {
                $warning_msg[] = "Bạn chưa nhập email!";
            }
            if (empty($warning_msg)) {
                $result = mysqli_query($conn, "UPDATE `admin_form` SET `name` = '" . $_POST['name'] . "', `email` = '" . $_POST['email'] . "', `password` = '$password', `image` =  '$image' WHERE `admin_form`.`id` = " . $_GET['id']);
                $success_msg[] = 'Đã sửa tài khoản thành công!';
            }
        } else if ($_GET['action'] == 'add') { //Thêm sản phẩm

            $password = $_POST['password'];
            $cfPassword = $_POST['cf_password'];
            $email = $_POST['email'];

            if ($password != $cfPassword) {
                $warning_msg[] = "Nhập lại mật khẩu không chính xác!";
            }
            $select = mysqli_query($conn, "SELECT * FROM `admin_form` WHERE email = '$email'") or die('query failed');
            if (mysqli_num_rows($select) > 0) {
                $warning_msg[] = 'Email đã tồn tại tài khoản!';
            } else if (empty($_POST['password'])) {
                $warning_msg[] = "Bạn chưa nhập mật khẩu!";
            } else if (empty($_POST['cf_password'])) {
                $warning_msg[] = "Bạn chưa nhập lại mật khẩu!";
            } else if (empty($_POST['name'])) {
                $warning_msg[] = "Bạn chưa nhập tên sử dụng!";
            } else if (empty($_POST['email'])) {
                $warning_msg[] = "Bạn chưa nhập email!";
            } else if (empty($_FILES['image']['name']) && $_GET['action'] == 'add') {
                $warning_msg[] = 'Bạn chưa thêm ảnh đại diện';
            } else if (!empty($_FILES['image']['name'])) {
                if ($_FILES['image']['size'] > 2000000) {
                    $warning_msg[] = 'Ảnh vượt quá kích cỡ cho phép!';
                }
            }

            if (empty($warning_msg)) {
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploaded_img/' . $image);
                $insert = mysqli_query($conn, "INSERT INTO `admin_form` (name, email, password, image) 
                VALUES ('" . $_POST['name'] . "', '$email', '" . $_POST['password'] . "', '$image')") or die('query failed');
                $success_msg[] = 'Đã thêm thành công 1 tài khoản mới!';
            }
        }
    } else {
        $warning_msg[] = "Bạn phải nhập đầy đủ thông tin bắt buộc!";
    }
}

if (!empty($_GET['id'])) {
    $result = mysqli_query($conn, "SELECT * FROM `admin_form` WHERE `id` = " . $_GET['id']);
    $admin_form = $result->fetch_assoc();
}

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
                        <a href="admin.php"><ion-icon name="home-outline"></ion-icon></a>
                    </span>
                    <span class="title"><a href="admin.php">Trang chủ</a></span>
                </li>
                <li class="navi-2 nv">
                    <span class="icon">
                        <a href="user.php"><ion-icon name="people-outline"></ion-icon></a>
                    </span>
                    <span class="title"><a href="user.php">Tài khoản</a></span>

                </li>
                <li class="navi-3">
                    <span class="icon">
                        <a href="products.php"><ion-icon name="restaurant-outline"></ion-icon></a>
                    </span>
                    <span class="title"><a href="products.php">Thực đơn</a></span>
                </li>
                <li class="navi-4">
                    <span class="icon">
                        <a href=""><ion-icon name="camera-outline"></ion-icon></a>
                    </span>
                    <span class="title"><a href="">Blog</a></span>
                </li>
                <li class="navi-5">
                    <span class="icon">
                        <a href=""><ion-icon name="accessibility-outline"></ion-icon></a>
                    </span>
                    <span class="title"><a href="">Chefs & Slider</a></span>
                </li>
                <li class="navi-6">
                    <span class="icon">
                        <a href=""><ion-icon name="chatbubble-outline"></ion-icon></a>
                    </span>
                    <span class="title"><a href="">Comment & Sub</a></span>
                </li>
                <li class="navi-7">
                    <span class="icon">
                        <a href="../dashboard/Trangchu.php"><ion-icon name="logo-snapchat"></ion-icon></a>
                    </span>
                    <span class="title"><a href="../dashboard/Trangchu.php">ND - Restaurant</a></span>
                </li>
                <li>
                    <span class="icon">
                        <a href="admin.php?logout=<?php echo $admin_id; ?>"><ion-icon name="log-out-outline"></ion-icon></a>
                    </span>
                    <span class="title"><a href="admin.php?logout=<?php echo $admin_id; ?>">Đăng xuất</a></span>
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
                        <a href="../update_user_admin/update_profile_admin.php" class="btn-user">Thông tinn</a>
                        <a href="admin.php?logout=<?php echo $admin_id; ?>" class="delete-btn">Đăng xuất</a>
                        <p><a href="../Login_leg/login.php">Đăng nhập</a> or <a href="../Login_leg/register.php">Đăng ký</a> tài khoản khác</p>
                    </div>

                </div>

            </div>
            <div class="content-main">
                <div class="content-box">
                    <div class="content-box-title">
                        <h3><?= !empty($_GET['id']) ?  "Sửa tài khoản admin" : "Thêm tài khoản admin" ?></h3>
                    </div>
                    <form action="<?= (!empty($admin_form) && !isset($_GET['task'])) ? "?action=edit&id=" . $_GET['id'] : "?action=add" ?>" method="post" enctype="multipart/form-data">
                        <div class="content-box-main">
                            <div class="content-box-add">
                                <button type="submit">Lưu</button>
                            </div>
                            <div class="content-infor-pro">

                                <div class="cip-text">
                                    <label for="">Tên sử dụng<font color="red">*</font>:</label>
                                    <div class="input-value">
                                        <input type="text" name="name" value="<?= (!empty($admin_form) ? $admin_form['name'] : "") ?>">
                                    </div>
                                </div>

                                <div class="cip-text">
                                    <label for="">Email<font color="red">*</font>:</label>
                                    <div class="input-value">
                                        <input type="email" name="email" value="<?= (!empty($admin_form) ? $admin_form['email'] : "") ?>">
                                    </div>
                                </div>

                                <?php
                                if (!empty($admin_form)) {
                                ?>
                                    <div class="cip-text">
                                        <label for="">Nhập mật khẩu cũ<font color="red">*</font>:</label>
                                        <div class="input-value">
                                            <input type="password" name="old_password" value="">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                                <div class="cip-text">
                                    <label for=""><?= !empty($_GET['id']) ?  "Mật khẩu mới" : "Mật khẩu" ?><font color="red">*</font>:</label>
                                    <div class="input-value">
                                        <input type="password" name="password" value="">
                                    </div>
                                </div>

                                <div class="cip-text">
                                    <label for="">Nhập lại mật khẩu<font color="red">*</font>:</label>
                                    <div class="input-value">
                                        <input type="password" name="cf_password" value="">
                                    </div>
                                </div>

                                <div class="cip-text">
                                    <label for="">Ảnh đại diện<font color="red">*</font>:</label>
                                    <div>
                                        <?php if (!empty($admin_form['image'])) { ?>
                                            <img src="../uploaded_img/<?php echo $admin_form['image']; ?>" alt="" width="148" height="128"> <br>
                                            <input type="hidden" name="image" value="<?= $admin_form['image'] ?>">

                                        <?php } ?>
                                        <input type="file" name="image">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
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