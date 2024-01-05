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
    $delete = mysqli_query($conn, "DELETE FROM `menu` WHERE id = '$remove_id'") or die('query failed');

    if ($delete) {
        $success_msg[] = 'Xóa sản phẩm thành công!';
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
    $sxCondition = "ORDER BY `menu`.`" . $sxField . "` " . $sxSort;
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
    $selectMain = mysqli_query($conn, "SELECT * FROM `menu` WHERE `name` LIKE '%" . $search . "%' " . $sxCondition . " LIMIT " . $per_page . " OFFSET " . $offset) or die('query failed');
    $totalPro = mysqli_query($conn, "SELECT * FROM `menu` WHERE `name` LIKE '%" . $search . "%'");
} else {
    $selectMain = mysqli_query($conn, "SELECT * FROM `menu` " . $sxCondition . " LIMIT " . $per_page . " OFFSET " . $offset) or die('query failed');
    $totalPro = mysqli_query($conn, "SELECT * FROM `menu`");
}

$totalPro = $totalPro->num_rows;

$totalPage = ceil($totalPro / $per_page);

$select = mysqli_query($conn, "SELECT * FROM `menu`");
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
                <li class="navi-2">
                    <span class="icon">
                        <a href="user.php"><ion-icon name="people-outline"></ion-icon></a>
                    </span>
                    <span class="title"><a href="user.php">Tài khoản</a></span>

                </li>
                <li class="navi-3 nv">
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
                        <input type="text" placeholder="Tìm kiếm tên sản phẩm tại đây!" name="name" value="<?= isset($_GET['name']) ? $_GET['name'] : "" ?>">
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
                        <h3>Danh sách <?php echo $row; ?> sản phẩm</h3>
                    </div>
                    <div class="content-box-main">
                        <div class="content-box-add">
                            <a href="addProduct.php">
                                <span>Thêm sản phẩm</span>
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

                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == 'asc') { ?> selected <?php } ?> value="?<?= $sortParam ?>field=price&sort=asc">Giá tăng dần</option>

                                        <option <?php if (isset($_GET['field']) && $_GET['field'] == 'price' && isset($_GET['sort']) && $_GET['sort'] == 'desc') { ?> selected <?php } ?> value="?<?= $sortParam ?>field=price&sort=desc">Giá giảm dần</option>
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
                                    <span>Ảnh</span>
                                </div>
                                <div class="cbpt-text">
                                    <span>Tên sản phẩm</span>
                                </div>
                                <div class="cbpt-text">
                                    <span>Phân loại</span>
                                </div>
                                <div class="cbpt-text">
                                    <span>Giá tiền</span>
                                </div>
                                <div class="cbpt-text">
                                    <span>Copy</span>
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
                                                <img src="../assets/images/dish/<?php echo $fetch['image']; ?>" alt="" height="108px">
                                            </div>
                                        </div>
                                        <div class="content-box-pro-3">
                                            <div class="cbp-content">
                                                <span><?php echo $fetch['name']; ?></span>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-4">
                                            <div class="cbp-content">
                                                <span>
                                                    <?php
                                                    switch ($fetch['id_pro']) {
                                                        case 1:
                                                            echo "Khai vị ";
                                                            break;

                                                        case 2:
                                                            echo "Món sáng ";
                                                            break;

                                                        case 3:
                                                            echo "Món trưa ";
                                                            break;

                                                        case 4:
                                                            echo "Món tối ";
                                                            break;

                                                        case 5:
                                                            echo "Món lẩu ";
                                                            break;

                                                        case 6:
                                                            echo "Món nướng ";
                                                            break;
                                                    }
                                                    if (!empty($fetch['new']) && !empty($fetch['rate'])) {
                                                        echo "- New - Giảm: " . $fetch['rate'] . "%";
                                                    }

                                                    if (!empty($fetch['new']) && empty($fetch['rate'])) {
                                                        echo "- New";
                                                    }
                                                    if (empty($fetch['new']) && !empty($fetch['rate'])) {
                                                        echo "- Giảm: " . $fetch['rate'] . "%";
                                                    }
                                                    ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-5">
                                            <div class="cbp-content">
                                                <span>Pre: <?php echo number_format($fetch['price'], 0, ",", "."); ?><u>đ</u>
                                                    <?php
                                                    if (!empty($fetch['price_old'])) {
                                                        echo " - Old: " . number_format($fetch['price_old'], 0, ",", ".") . "<u>đ</u>";
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-6">
                                            <div class="cbp-content">
                                                <a href="">Copy</a>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-7">
                                            <div class="cbp-content">
                                                <a href="addProduct.php?id=<?php echo $fetch['id']; ?>">Sửa</a>
                                            </div>
                                        </div>
                                        <div class="content-box-pro-6">
                                            <div class="cbp-content">
                                                <a href="products.php?remove=<?php echo $fetch['id']; ?>" onclick="return confirm('Bạn muốn xóa món ăn này khỏi menu?')">Xóa</a>
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