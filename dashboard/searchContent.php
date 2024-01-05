<?php
include '../JDBC/config.php';

if (isset($_POST['input'])) {

    $input = $_POST['input'];

    $result = mysqli_query($conn, "SELECT * FROM `menu` WHERE name LIKE '%{$input}%'");
    $row = mysqli_num_rows($result);

    $resultMain = mysqli_query($conn, "SELECT * FROM `menu` WHERE name LIKE '%{$input}%' LIMIT 5");

    if (mysqli_num_rows($resultMain) > 0) {
?>

        <div class="search-box-bottom">

            <span class="sbb-title">Có <?php echo $row; ?> sản phẩm</span>
            <?php
            while ($searchResult = mysqli_fetch_assoc($resultMain)) {

                $id =  $searchResult['id'];
                $id_pro = $searchResult['id_pro'];
                $name = $searchResult['name'];
                $image = $searchResult['image'];
                $price = $searchResult['price'];
                $price_old = $searchResult['price_old'];

            ?>

                <div class="sbb-content">
                    <a class="sbb-img" href="../product/itemPro.php?id=<?php echo $id; ?>&id_pro=<?php echo $id_pro; ?>">
                        <img src="../assets/images/dish/<?php echo $image; ?>" alt="">
                    </a>
                    <div class="sbb-text">
                        <div class="sbb-text-title">
                            <a href="../product/itemPro.php?id=<?php echo $id; ?>&id_pro=<?php echo $id_pro; ?>"><?php echo $name; ?></a>
                        </div>
                        <div class="sbb-text-main">
                            <span>
                                <?php
                                echo number_format($price, 0, ",", ".");
                                ?>
                                <u>đ</u>
                            </span>
                            <?php
                            if (!empty($price_old)) {
                                echo "<small>"
                                    . number_format($price_old, 0, ",", ".") . "<u>đ</u></small>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="sbb-all">
                <a href="../product/searchPro.php?input=<?php echo $input; ?>">Xem tất cả</a>
            </div>

        </div>
<?php
    } else {
        echo "<h6 class='text-danger text-center mt-3'>Không có sản phẩm!<h6>";
    }
}
?>