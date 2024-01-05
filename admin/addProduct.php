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

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $delete = mysqli_query($conn, "DELETE FROM `image_pro` WHERE id = '$remove_id'") or die('query failed');

    if ($delete) {
        $success_msg[] = 'Xóa sản phẩm thành công!';
    }
}

if (isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')) {
    if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['price']) && !empty($_POST['price'])) {
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

        if (empty($_POST['name'])) {
            $warning_msg[] = "Bạn phải nhập tên sản phẩm!";
        } else if (empty($_POST['price'])) {
            $warning_msg[] = "Bạn phải nhập giá sản phẩm!";
        } else if (!empty($_POST['price']) && is_numeric(str_replace('.', '', $_POST['price'])) == false) {
            $warning_msg[] = "Giá nhập không hợp lệ!";
        } else if (!empty($_POST['type']) && $_GET['action'] == 'add') {
            switch ($_POST['type']) {
                case "Món khai vị":
                    $_POST['type'] = 1;
                    break;

                case "Món ăn sáng":
                    $_POST['type'] = 2;
                    break;

                case "Món ăn trưa":
                    $_POST['type'] = 3;
                    break;

                case "Món ăn tối":
                    $_POST['type'] = 4;
                    break;

                case "Món lẩu":
                    $_POST['type'] = 5;
                    break;

                case "Món nướng":
                    $_POST['type'] = 6;
                    break;
            }
        }

        if (!isset($image) && !empty($_POST['image'])) {
            $image = $_POST['image'];
        }

        if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
            $uploadedFiles = $_FILES['gallery'];
            $result = uploadFiles($uploadedFiles);
            if (!empty($result['errors'])) {
                $error = $result['errors'];
            } else {
                $galleryImages = $result['uploaded_files'];
            }
        }

        if (!empty($_POST['gallery_image'])) {
            $galleryImages = array_merge($galleryImages, $_POST['gallery_image']);
        }

        if (!isset($error)) {
            if ($_GET['action'] == 'edit' && !empty($_GET['id'])) { //Cập nhật lại sản phẩm
                if (!empty($_POST['type'])) {
                    switch ($_POST['type']) {
                        case "Món khai vị":
                            $_POST['type'] = 1;
                            break;

                        case "Món ăn sáng":
                            $_POST['type'] = 2;
                            break;

                        case "Món ăn trưa":
                            $_POST['type'] = 3;
                            break;

                        case "Món ăn tối":
                            $_POST['type'] = 4;
                            break;

                        case "Món lẩu":
                            $_POST['type'] = 5;
                            break;

                        case "Món nướng":
                            $_POST['type'] = 6;
                            break;
                    }
                    print($_POST['type']);
                    exit;
                }
                //Cập nhật sản phẩm
                if (empty($warning_msg)) {
                    $result = mysqli_query($conn, "UPDATE `menu` SET `name` = '" . $_POST['name'] . "',`image` =  '" . $image . "',
                        `price` = " . str_replace('.', '', $_POST['price']) . ", `id_pro` = " . $_POST['type'] . ", `content` = '" . $_POST['content'] . "' WHERE `menu`.`id` = " . $_GET['id']);
                    $success_msg[] = "Cập nhật thông tin thành công!";
                }
                if (!isset($result)) {
                    $warning_msg[] = "Bạn không thay đổi gì cả!";
                } else {
                    if ($result) {
                        if (!empty($galleryImages)) {
                            $product_id = ($_GET['action'] == 'edit' && !empty($_GET['id'])) ? $_GET['id'] : $conn->insert_id;
                            $insertValues = "";
                            foreach ($galleryImages as $path) {
                                if (empty($insertValues)) {
                                    $insertValues = "(NULL, " . $product_id . ", '" . $path . "', " . time() . ", " . time() . ")";
                                } else {
                                    $insertValues .= ",(NULL, " . $product_id . ", '" . $path . "', " . time() . ", " . time() . ")";
                                }
                            }
                            $result = mysqli_query($conn, "INSERT INTO `image_pro` (`id`, `product_id`, `path`, `create_time`, `last_update`) VALUES " . $insertValues . ";");
                        }
                    } else {
                        $warning_msg[] = 'Đã có lỗi xảy ra!';
                    }
                }
            } else if ($_GET['action'] == 'add') { //Thêm sản phẩm

                $select = mysqli_query($conn, "SELECT * FROM `menu` WHERE name = '" . $_POST['name'] . "' OR image = '$image'") or die('query failed');
                if (mysqli_num_rows($select) > 0) {
                    $warning_msg[] = 'Tên hoặc ảnh đã tồn tại!';
                } else if ($image == "" && $_GET['action'] == 'add') {
                    $warning_msg[] = 'Bạn chưa thêm ảnh chính cho sản phẩm!';
                } else if ($image != "") {
                    if ($_FILES['image']['size'] > 2000000) {
                        $warning_msg[] = 'Ảnh vượt quá kích cỡ cho phép!';
                    }
                }

                if (empty($warning_msg)) {
                    $insert = "INSERT INTO `menu` (id_pro, name, price, image, new, content)
                    VALUES(" . $_POST['type'] . ", '" . $_POST['name'] . "', " . str_replace('.', '', $_POST['price']) . ", '$image', 'New', '" . $_POST['content'] . "')";

                    move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/dish/' . $image);
                    $insert = mysqli_query($conn, $insert) or die('query failed');
                    $success_msg[] = 'Đã thêm thành công sản phẩm!';
                }
                if ($insert) {
                    if (!empty($galleryImages)) {
                        $product_id = ($_GET['action'] == 'edit' && !empty($_GET['id'])) ? $_GET['id'] : $conn->insert_id;
                        $insertValues = "";
                        foreach ($galleryImages as $path) {
                            if (empty($insertValues)) {
                                $insertValues = "(NULL, " . $product_id . ", '" . $path . "', " . time() . ", " . time() . ")";
                            } else {
                                $insertValues .= ",(NULL, " . $product_id . ", '" . $path . "', " . time() . ", " . time() . ")";
                            }
                        }
                        $result = mysqli_query($conn, "INSERT INTO `image_pro` (`id`, `product_id`, `path`, `create_time`, `last_update`) VALUES " . $insertValues . ";");
                        $success_msg[] = 'Thêm sản phẩm thành công!';
                    }
                } else {
                    $warning_msg[] = 'Đã có lỗi xảy ra!';
                }
            }
        }
    } else {
        $warning_msg[] = "Bạn phải nhập đầy đủ thông tin bắt buộc!";
    }
}

if (!empty($_GET['id'])) {
    $result = mysqli_query($conn, "SELECT * FROM `menu` WHERE `id` = " . $_GET['id']);
    $product = $result->fetch_assoc();
    $gallery = mysqli_query($conn, "SELECT * FROM `image_pro` WHERE `product_id` = " . $_GET['id']);
    if (!empty($gallery) && !empty($gallery->num_rows)) {
        while ($row = mysqli_fetch_array($gallery)) {
            $product['gallery'][] = array(
                'id' => $row['id'],
                'path' => $row['path']
            );
        }
    }
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
                        <h3><?= !empty($_GET['id']) ? ((!empty($_GET['task']) && $_GET['task'] == "copy") ? "Copy sản phẩm" : "Sửa sản phẩm") : "Thêm sản phẩm" ?></h3>
                    </div>
                    <form action="<?= (!empty($product) && !isset($_GET['task'])) ? "?action=edit&id=" . $_GET['id'] : "?action=add" ?>" method="post" enctype="multipart/form-data">
                        <div class="content-box-main">
                            <div class="content-box-add">
                                <button type="submit">Lưu</button>
                            </div>
                            <div class="content-infor-pro">
                                <div class="cip-text">
                                    <label for="">Tên sản phẩm<font color="red">*</font>:</label>
                                    <div class="input-value">
                                        <input type="text" name="name" value="<?= (!empty($product) ? $product['name'] : "") ?>">
                                    </div>
                                </div>
                                <div class="cip-text">
                                    <label for="">Phân loại<font color="red">*</font>:</label>
                                    <div class="input-value">
                                        <select name="type" id="">
                                            <?php
                                            if (!empty($product)) {
                                            ?>
                                                <option selected value="<?php
                                                                        switch ($product['id_pro']) {
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
                                                                        ?>">
                                                    <?php
                                                    switch ($product['id_pro']) {
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
                                                </option>
                                            <?php
                                            };
                                            ?>
                                            <option value="Món khai vị">Món khai vị</option>
                                            <option value="Món ăn sáng">Món ăn sáng</option>
                                            <option value="Món ăn trưa">Món ăn trưa</option>
                                            <option value="Món ăn tối">Món ăn tối</option>
                                            <option value="Món lẩu">Món lẩu</option>
                                            <option value="Món nướng">Món nướng</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="cip-text">
                                    <label for="">Giá sản phẩm<font color="red">*</font>:</label>
                                    <div class="input-value">
                                        <input type="text" name="price" value="<?= (!empty($product) ? number_format($product['price'], 0, ",", ".") : "") ?>">
                                    </div>
                                </div>
                                <?php if (!empty($product) && !empty($product['price_old'])) { ?>
                                    <div class="cip-text">
                                        <label for="">Giá trước khi giảm (Nếu có giảm giá)<font color="red">*</font>:</label>
                                        <div class="input-value">
                                            <input class="old_price" type="text" name="price" value="<?php
                                                                                                        if (!empty($product['price_old'])) {
                                                                                                            echo number_format($product['price_old'], 0, ",", ".");
                                                                                                        }
                                                                                                        ?>">
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="cip-text">
                                    <label for="">Ảnh chính<font color="red">*</font>:</label>
                                    <div>
                                        <?php if (!empty($product['image'])) { ?>
                                            <img src="../assets/images/dish/<?php echo $product['image']; ?>" alt="" width="148" height="128"> <br>
                                            <input type="hidden" name="image" value="<?= $product['image'] ?>">

                                        <?php } ?>
                                        <input type="file" name="image">
                                    </div>
                                </div>
                                <div class="cip-text">
                                    <label for="">Ảnh mô tả:</label>
                                    <div class="img-decrice">
                                        <?php if (!empty($product['gallery'])) { ?>
                                            <ul>
                                                <?php foreach ($product['gallery'] as $image) { ?>
                                                    <li>
                                                        <img src="../<?= $image['path'] ?>" width="128" height="128">
                                                        <a href="gallery_delete.php?id=<?= $image['id'] ?>">Xóa</a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                        <input multiple="" type="file" name="gallery[]">
                                    </div>
                                </div>
                                <div class="cip-text">
                                    <label for="">Nội dung:</label>
                                    <div>
                                        <textarea name="content" id="" cols="50" rows="10"><?php if (!empty($_GET['id'])) {
                                                                                                echo $product['content'];
                                                                                            } ?></textarea>
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