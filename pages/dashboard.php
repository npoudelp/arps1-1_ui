<?php
include_once("../partials/header.php");

?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>


    <div class="container-fluid">
        <div class="row mt-1">
            <div class="col-md-8">
                <div class="container-fluid" style="height: 70vh" id="mapDashboard"></div>
            </div>
            <div class="col-md-4">

            </div>
        </div>
    </div>


    <script src="../js/tokenManager.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDdZfRDtgrw9k6rtBu5di_Da8aUeRmFtbM&map_ids=1ad12e178890838&callback=initMap&libraries=drawing"></script>
    <script src="../js/mapForDashboard.js"></script>

    <script>
        

        // after window is ready in jquery
        // $(document).ready(function() {
        //     getAllFields();
        // });
    </script>
</body>