<?php
include_once("./partials/header.php");
?>

<body>
    <?php
    include_once("./partials/navbar.php");
    ?>
    <div class="p-5">
        <a href="./test.php" class="">scrape</a>
    </div>

    <?php
    include_once("./partials/footer.php");
    ?>


    <script>
        $.ajax({
            url: "https:www.dhm.gov.np/frontend_dhm/hydrology/getRainfallWatchMapBySeriesId",
            type: "GET",
            success: function(data) {
                console.log(data);
            }
        });
    </script>
</body>