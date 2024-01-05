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
                <li class="navi-1 nv">
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
            <div class="main-1">
                <div class="main-1-cardBox">

                    <a class="main-1-card" href="user.php">
                        <div>
                            <div class="numbers">
                                <?php
                                $select = mysqli_query($conn, "SELECT * FROM `user_form`")
                                    or die('query failed');
                                echo mysqli_num_rows($select);
                                ?>
                            </div>
                            <div class="cardName">Tài khoản</div>
                        </div>
                        <div class="iconBx">
                            <ion-icon name="people-outline"></ion-icon>
                        </div>
                    </a>

                    <a class="main-1-card" href="booking.php">
                        <div>
                            <div class="numbers">
                                <?php
                                $number_pro = mysqli_query($conn, "SELECT * FROM `booking`");
                                echo mysqli_num_rows($number_pro);
                                ?>
                            </div>
                            <div class="cardName">Đơn đặt bàn</div>
                        </div>
                        <div class="iconBx">
                            <ion-icon name="cart-outline"></ion-icon>
                        </div>
                    </a>

                    <a class="main-1-card" href="order.php">
                        <div>
                            <div class="numbers">
                                <?php
                                $select = mysqli_query($conn, "SELECT * FROM `order_pro`")
                                    or die('query failed');
                                echo mysqli_num_rows($select);
                                ?>
                            </div>
                            <div class="cardName">Đơn đặt hàng</div>
                        </div>
                        <div class="iconBx">
                            <ion-icon name="bag-check-outline"></ion-icon>
                        </div>
                    </a>

                    <a class="main-1-card">
                        <div>
                            <div class="numbers">
                                <?php
                                $total = 0;
                                $select_order_pro = mysqli_query($conn, "SELECT * FROM `order_pro`")
                                    or die('query failed');

                                if (mysqli_num_rows($select_order_pro) > 0) {
                                    while ($fetch_order_earn = mysqli_fetch_assoc($select_order_pro)) {
                                ?>
                                <?php
                                        $total += $fetch_order_earn['total'];
                                    }
                                } else {
                                    echo $total;
                                }
                                ?>
                                <?php
                                $totalFloat = (float)$total;
                                if ($totalFloat >= 1000 && $totalFloat < 1000000) {
                                    $totalFloat = $totalFloat / 1000;
                                    echo number_format($totalFloat, 0, ",", ".") . "K";
                                }
                                if ($totalFloat >= 1000000 && $totalFloat < 1000000000) {
                                    $totalFloat = $totalFloat / 1000000;
                                    echo $totalFloat . "M";
                                }
                                if ($totalFloat >= 1000000000) {
                                    $totalFloat = $totalFloat / 1000000000;
                                    echo $totalFloat . "B";
                                }

                                ?>
                            </div>
                            <div class="cardName">Doanh thu</div>
                        </div>
                        <div class="iconBx">
                            <ion-icon name="cash-outline"></ion-icon>
                        </div>
                    </a>
                </div>

                <div class="details">
                    <div class="main-recent">
                        <div class="recentOrders" style="margin-bottom: 20px;">

                            <div class="cardHeader">
                                <h2>Đặt hàng gần đây</h2>
                                <a href="order.php" class="btn">Tất cả</a>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <td>Tên khách hàng</td>
                                        <td>Giá tiền</td>
                                        <td>Thanh toán</td>
                                        <td>Trạng thái</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        $select = mysqli_query($conn, "SELECT * FROM `order_pro` ORDER BY id DESC LIMIT 5") or die("query_failed");
                                        if (mysqli_num_rows($select) > 0) {
                                            while ($fetch = mysqli_fetch_assoc($select)) {
                                        ?>
                                                <td><?php echo $fetch['name']; ?></td>
                                                <td><?php echo number_format($fetch['total'], 0, ",", "."); ?>đ</td>
                                                <td><?php echo $fetch['methodCheck'] ?></td>
                                                <td>
                                                    <?php
                                                    if ($fetch['result'] != "Đã chốt đơn") {
                                                        echo "<span class='span-1'> " . $fetch['result'] . "</span>";
                                                    } else {
                                                        echo "<span class='span-2'> " . $fetch['result'] . "</span>";
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

                        <div class="recentOrders">

                            <div class="cardHeader">
                                <h2>Đặt bàn gần đây</h2>
                                <a href="booking.php" class="btn">Tất cả</a>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <td>Tên khách hàng</td>
                                        <td>Số người</td>
                                        <td>Khung giờ</td>
                                        <td>Ngày đến</td>
                                        <td>Trạng thái</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        $select = mysqli_query($conn, "SELECT * FROM `booking` ORDER BY id DESC LIMIT 5") or die("query_failed");
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

                                    </tr>
                            <?php
                                            }
                                        }
                            ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="div">
                        <div class="recentUser">
                            <div class="cardHeader">
                                <h2>10 đăng nhập web gần nhất</h2>
                            </div>

                            <table>
                                <?php
                                $select_lastLogin = mysqli_query($conn, "SELECT * FROM `last_user_login` ORDER BY id DESC LIMIT 10");
                                if (mysqli_num_rows($select_lastLogin) > 0) {
                                    while ($fetch_lastLogin = mysqli_fetch_assoc($select_lastLogin)) {
                                ?>
                                        <tr>
                                            <td>
                                                <div class="imgBx">
                                                    <img src="../uploaded_img/<?= (!empty($fetch_lastLogin['image']) ? $fetch_lastLogin['image'] : "default-avatar.png") ?>" alt="">
                                                </div>
                                            </td>
                                            <td>
                                                <h4><?php echo $fetch_lastLogin['name'] ?> <br> <span><?php echo $fetch_lastLogin['email']; ?></span></h4>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="../assets/js/jquery-3.5.1.min.js"></script>

    <script src="../JS/adminScript.js"></script>
</body>

</html>