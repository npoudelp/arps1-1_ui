<?php
$title = "Test";
include_once("./partials/header.php");
?>

<body>
    <div class="container">

    </div>
</body>

<script>
    $.ajax({
        url: "https://www.dhm.gov.np/frontend_dhm/hydrology/getRainfallFilter",
        type: "GET",
        success: function(data) {
            console.log(data);
        }
    });
</script>

</html>