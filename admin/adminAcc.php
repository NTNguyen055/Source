<?php
include '../JDBC/config.php';
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

//Xóa sản phẩm
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $delete = mysqli_query($conn, "DELETE FROM `admin_form` WHERE id = '$remove_id'") or die('query failed');

    if ($delete) {
        $success_msg[] = 'Xóa tài khoản thành công!';
    }
}

// Menu
$sxField = isset($_GET['field']) ? $_GET['field'] : "";
$sxSort = isset($_GET['sort']) ? $_GET['sort'] : "";

$sxCondition = "";
$param = "";
$sortParam = "";

//Sắp xếp
if (!empty($sxField) && !empty($sxSort)) {
    $sxCondition = "ORDER BY `admin_form`.`" . $sxField . "` " . $sxSort;
    $param .= "field=" . $sxField . "&sort=" . $sxSort . "&";
}

//Tìm kiếm
$search = isset($_GET['name']) ? $_GET['name'] : "";

if ($search) {
    $param .= "name=" . $search . "&";
    $sortParam = "name=" . $search . "&";
}

//Phân trang
$per_page = !empty($_GET['per_page']) ? $_GET['per_page'] : 10;
$defaultPage = !empty($_GET['page']) ? $_GET['page'] : 1;

$offset = ($defaultPage - 1) * $per_page;

if ($search) {
    $selectMain = mysqli_query($conn, "SELECT * FROM `admin_form` WHERE `name` LIKE '%" . $search . "%' " . $sxCondition . " LIMIT " . $per_page . " OFFSET " . $offset) or die('query failed');
    $totalPro = mysqli_query($conn, "SELECT * FROM `admin_form` WHERE `name` LIKE '%" . $search . "%'");
} else {
    $selectMain = mysqli_query($conn, "SELECT * FROM `admin_form` " . $sxCondition . " LIMIT " . $per_page . " OFFSET " . $offset) or die('query failed');
    $totalPro = mysqli_query($conn, "SELECT * FROM `admin_form`");
}

$totalPro = $totalPro->num_rows;

$totalPage = ceil($totalPro / $per_page);

$select = mysqli_query($conn, "SELECT * FROM `admin_form`");
$row = mysqli_num_rows($select);

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

                <div class="search">
                    <form method="get">
                        <input type="text" placeholder="Tìm kiếm tên user tại đây!" name="name" value="<?= isset($_GET['name']) ? $_GET['name'] : "" ?>">
                        <button type="submit">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </form>
                </div>

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
                        <h3>Danh sách người quản trị</h3>
                    </div>
                    <div class="content-box-main">
                        <div class="content-box-add">
                            <a href="addAdmin.php">
                                <span>Thêm Admin</span>
                            </a>
                        </div>
                        <div class="content-box-menu">
                            <h3>Một số chức năng: </h3>
                            <div class="cbm-main">
                                <div class="cbm-text-1">
                                    <label for="">Sắp xếp: </label>
                                    <select class="sort-box" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == '' && isset($_GET['sort']) && $_GET['sort'] == '') { ?> selected <?php } ?> value="?<?= $sortParam ?>">Mặc định</option>

                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == 'name' && isset($_GET['sort']) && $_GET['sort'] == 'asc') { ?> selected <?php } ?> value="?<?= $sortParam ?>field=name&sort=asc">Từ A -> Z</option>

                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == 'name' && isset($_GET['sort']) && $_GET['sort'] == 'desc') { ?> selected <?php } ?> value="?<?= $sortParam ?>field=name&sort=desc">Từ Z -> A</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="content-box-pro">
                            <div class="content-box-pro-title">
                                <div class="cbpt-text">
                                    <span>ID</span>
                                </div>
                                <div class="cbpt-text">
                                    <span>Ảnh đại diện</span>
                                </div>
                                <div class="cbpt-text ct-1">
                                    <span>Tên người dùng</span>
                                </div>
                                <div class="cbpt-text">
                                    <span>Email</span>
                                </div>
                                <div class="cbpt-text">
                                    <span>Mật khẩu</span>
                                </div>
                                <div class="cbpt-text">
                                    <span>Sửa</span>
                                </div>
                                <div class="cbpt-text">
                                    <span>Xóa</span>
                                </div>
                            </div>

                            <?php
                            if (mysqli_num_rows($selectMain) > 0) {
                                while ($fetch = mysqli_fetch_assoc($selectMain)) {
                            ?>
                                    <div class="content-box-pro-main">
                                        <div class="content-box-pro-1">
                                            <div class="cbp-content">
                                                <span><?php echo $fetch['id']; ?></span>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-2">
                                            <div class="cbp-content">
                                                <img src="../uploaded_img/<?= (!empty($fetch['image']) ? $fetch['image'] : "default-avatar.png") ?>" alt="" height="108px">
                                            </div>
                                        </div>
                                        <div class="content-box-pro-3 cbp-1">
                                            <div class="cbp-content">
                                                <span><?php echo $fetch['name']; ?></span>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-4">
                                            <div class="cbp-content">
                                                <span><?php echo $fetch['email']; ?></span>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-5">
                                            <div class="cbp-content">
                                                <span><?php echo $fetch['password']; ?></span>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-7">
                                            <div class="cbp-content">
                                                <a href="addAdmin.php?id=<?php echo $fetch['id']; ?>">Sửa</a>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-6">
                                            <div class="cbp-content">
                                                <a href="adminAcc.php?remove=<?php echo $fetch['id']; ?>" onclick="return confirm('Bạn muốn xóa tài khoản này?')">Xóa</a>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            };
                            ?>
                        </div>
                        <?php

                        include '../product/page.php';

                        ?>
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