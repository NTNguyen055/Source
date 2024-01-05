<?php

include '../JDBC/config.php';

if (isset($_POST['submit'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, $_POST['password']);
   $cpass = mysqli_real_escape_string($conn, $_POST['cpassword']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if (mysqli_num_rows($select) > 0) {
      $warning_msg[] = 'Đã tồn tại tài khoản!';
   } else {
      if ($pass != $cpass) {
         $warning_msg[] = 'Nhập lại mật khẩu không chính xác!';
      } elseif ($image_size > 2000000) {
         $warning_msg[] = 'Ảnh vượt quá kích cỡ cho phép!';
      } else {
         $insert = mysqli_query($conn, "INSERT INTO `user_form`(name, email, password, image)
        VALUES('$name', '$email', '$pass', '$image')") or die('query failed');

         if ($insert) {
            move_uploaded_file($image_tmp_name, $image_folder);
            $success_msg[] = 'Đăng ký tài khoản thành công!';
            header('location:../Login_leg/login.php');
         } else {
            $warning_msg[] = 'Đăng ký thất bại!';
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng ký cho dui</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../CSS/styleUser.css">

</head>

<body>

   <!--- #PRELOADER-->

   <div class="preload" data-preaload>
      <div class="circle"></div>
      <p class="text">ND - Restaurant</p>
   </div>

   <div class="form-container">

      <form action="" method="post" enctype="multipart/form-data">
         <h3>Đăng ký</h3>

         <input type="text" name="name" placeholder="Tên người dùng" class="box" required>
         <input type="email" name="email" placeholder="Nhập email" class="box" required>
         <input type="password" name="password" placeholder="Mật khẩu" class="box" required>
         <input type="password" name="cpassword" placeholder="Nhập lại mật khẩu" class="box" required>
         <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
         <input type="submit" name="submit" value="Đăng ký" class="btn">
         <p>Bạn đã có 1 tài khoản? <a href="login.php">Đăng nhập</a></p>
      </form>

   </div>

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