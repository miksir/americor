<?php

/* @var $oldValue string */
/* @var $newValue string */
/* @var $footerDatetime string */
/* @var $eventText string */
/* @var $username string */
?>

    <div class="bg-success ">
        <?php echo $eventText . " " .
            "<span class='badge badge-pill badge-warning'>" . $oldValue  . "</span>" .
            " &#8594; " .
            "<span class='badge badge-pill badge-success'>" . $newValue . "</span>";
        ?>

        <span><?= \app\widgets\DateTime\DateTime::widget(['dateTime' => $footerDatetime]) ?></span>
    </div>

<?php if (isset($username)): ?>
    <div class="bg-info"><?= $username ?></div>
<?php endif; ?>
