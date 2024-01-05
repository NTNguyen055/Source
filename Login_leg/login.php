<?php

include '../JDBC/config.php';
session_start();

if (isset($_POST['submit'])) {

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, $_POST['password']);

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if (mysqli_num_rows($select) > 0) {
      $row = mysqli_fetch_assoc($select);
      $_SESSION['user_id'] = $row['id'];

      // print_r($row); exit;      
      $insert = mysqli_query($conn, "INSERT INTO `last_user_login`(id, name, email, image)
      VALUES (NULL, '" . $row['name'] . "', '" . $row['email'] . "', '" . $row['image'] ."')") or die('query failed');

      header('location:../dashboard/Trangchu.php');
   } else {
      $warning_msg[] = 'Email hoặc mật khẩu không đúng!';
   }

   $select_admin = mysqli_query($conn, "SELECT * FROM `admin_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if (mysqli_num_rows($select_admin) > 0) {
      $admin_row = mysqli_fetch_assoc($select_admin);
      $_SESSION['admin_id'] = $admin_row['id'];
      header('location:../admin/admin.php');
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng nhập cho dui</title>

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
         <h3>Đăng nhập</h3>
         <input type="email" name="email" placeholder="Email" class="box" required>
         <input type="password" name="password" placeholder="Mật khẩu" class="box" required>
         <input type="submit" name="submit" value="Đăng nhập" class="btn">
         <p>Bạn chưa có 1 tài khoản? <a href="register.php">Đăng ký</a></p>
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