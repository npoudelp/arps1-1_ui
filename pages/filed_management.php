<?php
$page_title = "arps | Field Management";
include_once("../partials/header.php");
// header("Location: /pages/index.php");    

?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="container-fluid" style="height: 70vh" id="map"></div>
            </div>
            <div class="col-md-6">
                <button class="btn btn-outline-dark" onclick="clearMap()">
                    Reset
                </button>
                <button class="btn btn-outline-dark" onclick="checkStatus()">checkStatus</button>
                <p id="coordinates"></p>
            </div>
        </div>
    </div>

    <?php
    include_once("../partials/map.php");
    ?>
    <script src="../js/tokenManager.js"></script>
</body>

</html>