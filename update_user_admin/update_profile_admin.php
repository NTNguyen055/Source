<?php
include '../JDBC/config.php';

session_start();

$admin_id = $_SESSION['admin_id'];


if (isset($_POST['update_profile'])) {
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

    mysqli_query($conn, "UPDATE `user_form` SET name = '$update_name', email = '$update_email' WHERE id = '$admin_id'")
        or die('query failed');

    $old_pass = $_POST['old_pass'];
    $update_pass = mysqli_real_escape_string($conn, $_POST['update_pass']);
    $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
    $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_pass']);

    if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        if ($old_pass != $update_pass) {
            $message[] = 'Mật khẩu cũ không chính xác!';
        } elseif ($new_pass != $confirm_pass) {
            $message[] = 'Nhập lại mật khẩu mới không đúng!';
        } else {
            mysqli_query($conn, "UPDATE `user_form` SET password = '$confirm_pass' WHERE id = '$admin_id'") or die('query failed');
            $message[] = "Đổi mật khẩu mới thành công!";
        }
    }

    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = '../uploaded_img/' . $update_image;

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Ảnh đại diện quá lớn!';
        } else {
            $update_image_query = mysqli_query($conn, "UPDATE `user_form` SET image = '$update_image' WHERE id = '$admin_id'")
                or die('query failed');

            if ($update_image_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
            }
            $message[] = "Cập nhật ảnh đại diện mới thành công!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin</title>

    <link rel="stylesheet" href="../CSS/styleUser.css">
</head>

<body>

    <div class="update_profile">
        <?php
        $select = mysqli_query($conn, "SELECT * FROM `admin_form` WHERE id = '$admin_id'")
            or die('query failed');

        if (mysqli_num_rows($select) > 0) {
            $fetch = mysqli_fetch_assoc($select);
        }

        ?>

        <form action="" method="post" enctype="multipart/form-data">

            <?php
            if ($fetch['image'] == '') {
                echo '<img src="../assets/images/default-avatar.png">';
            } else {
                echo '<img src="../uploaded_img/' . $fetch['image'] . '">';
            }

            if (isset($message)) {
                foreach ($message as $message) {
                    echo '<div class="message">' . $message . '</div>';
                }
            }
            ?>

            <div class="flex">

                <div class="inputBox">

                    <span>Tên người dùng: </span>
                    <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box">

                    <span>Email của bạn: </span>
                    <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">

                    <span>Ảnh đại diện: </span>
                    <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">

                </div>

                <div class="inputBox">

                    <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>" class="box">

                    <span>Mật khẩu cũ: </span>
                    <input type="password" name="update_pass" placeholder="Nhập mật khẩu cũ" class="box">

                    <span>Mật khẩu mới: </span>
                    <input type="password" name="new_pass" placeholder="Nhập mật khẩu mới" class="box">

                    <span>Nhập lại mật khẩu: </span>
                    <input type="password" name="confirm_pass" placeholder="Nhập lại mật khẩu mới" class="box">
                </div>
            </div>

            <input type="submit" value="Cập nhật" name="update_profile" class="btn">
            <a href="../admin/admin.php" class="delete-btn">Trang chủ</a>

        </form>

    </div>

</body>

</html>