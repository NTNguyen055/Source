<?php

if (isset($success_msg)) {
   foreach ($success_msg as $success_msg) {
      echo
      '<script type="text/javascript">
         setTimeout(function() {
            swal("' . $success_msg . '", "", "success");
         },700);
      </script>';
   }
}

if (isset($warning_msg)) {
   foreach ($warning_msg as $warning_msg) {
      echo
      '<script type="text/javascript">
         setTimeout(function() {
            swal("' . $warning_msg . '", "", "warning");
         },700);
      </script>';
   }
}

if (isset($error_msg)) {
   foreach ($error_msg as $error_msg) {
      echo
      '<script type="text/javascript">
         setTimeout( funtion() {
            swal("' . $error_msg . '", "", "error");
         }, 3000);
      </script>';
   }
}

if (isset($info_msg)) {
   foreach ($info_msg as $info_msg) {
      echo '<script>swal("' . $info_msg . '", "", "info");</script>';
   }
}
