<style>
    .page {
        margin-top: 30px;
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
    }

    .page a {
        position: relative;
        width: 50px;
        height: 35px;
        border-radius: 10px;
        border: 1px solid black;
        margin-left: 10px;
        text-align: center;
    }

    .page a span {
        position: absolute;
        transform: translate(-50%, 20%);
        color: black;
    }

    .page .active {
        background: rgb(232, 124, 57);
        border: none;
        width: 50px;
        height: 35px;
        text-align: center;
        border-radius: 10px;
        margin-left: 10px;
    }

    .page .active span {
        color: white;
        position: absolute;
        transform: translate(-50%, 20%);
    }
</style>

<div class="page">

    <?php
    if ($defaultPage > 3) {
        $first_page = 1;
    ?>
        <a href="?<?=$param?>per_page=<?= $per_page ?>&page=<?= $first_page ?>">
            <span>First</span>
        </a>
    <?php
    }
    if ($defaultPage > 1) {
        $prev_page = $defaultPage - 1;
    ?>
        <a href="?<?=$param?>per_page=<?= $per_page ?>&page=<?= $prev_page ?>">
            <span>&larr;</span>
        </a>
    <?php
    }
    ?>

    <?php for ($num = 1; $num <= $totalPage; $num++) {
    ?>
        <?php if ($num != $defaultPage) {
        ?>
            <?php if ($num > $defaultPage - 3 && $num < $defaultPage + 3) {
            ?>
                <a href="?<?=$param?>per_page=<?= $per_page ?>&page=<?= $num ?>">
                    <span>
                        <?= $num ?>
                    </span>
                </a>
            <?php
            }
            ?>
        <?php } else { ?>
            <strong class="active">
                <span>
                    <?= $num ?>
                </span>
            </strong>
        <?php
        }
        ?>
    <?php
    }
    ?>

    <?php
    if ($defaultPage < $totalPage - 1) {
        $next_page = $defaultPage + 1;
    ?>
        <a href="?<?=$param?>per_page=<?= $per_page ?>&page=<?= $next_page ?>">
            <span>&rarr;</span>
        </a>
    <?php
    }
    ?>

    <?php
    if ($defaultPage < $totalPage - 3) {
        $end_page = $totalPage;
    ?>
        <a href="?<?=$param?>per_page=<?= $per_page ?>&page=<?= $end_page ?>">
            <span>
                Last
            </span>
        </a>
    <?php
    }
    ?>

</div>

<!-- <a href="?per_page=16&page=1" class="active">
        <span>1</span>
    </a> -->