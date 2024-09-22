<?php
include_once("./partials/header.php");
?>

<body>
    <?php
    include_once("./partials/navbar.php");
    ?>
    <div class="container-fluid py-3 bg-light text-dark" id="home">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <p class="text-center h3">
                        <span class="border-bottom">
                            Building Technology To Feed The World
                        </span>
                    </p>
                    <p class="lead text-center">
                        A technology created upto the needs of farmers. An intelligent platform that assists the farmers to
                        plan and manage fields and crops. Harvesting the data from the field and providing the farmers with
                        the best possible solution to increase the productivity.
                    </p>
                </div>
                <div class="col-md-4">
                    <img src="./images/crop_view.jpg" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 bg-dark text-light" id="service">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="./images/ai.jpg" alt="" class="img-fluid">
                </div>
                <div class="col-md-8">
                    <p class="text-center h3">
                        <span class="border-bottom">
                            Get Querries Resolved
                        </span>
                    </p>
                    <p class="text-center lead">
                        With the integration of Google Gemini, using Large Language Model any kind of querry from the
                        farmses are resolved by artificial intelligence.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 bg-light text-dark" id="about">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <p class="text-center h3">
                        <span class="border-bottom">
                            Technology in Field
                        </span>
                    </p>
                    <p class="text-center lead">
                        Logging and tracking agricultural activities, monitoring the field and crop health, and providing
                        essentail assistance from selection of crop to resolving the queries of the farmers. It feels like
                        having a virtual assistant in the field.
                    </p>
                </div>
                <div class="col-md-4">
                    <img src="./images/graph1.jpg" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 bg-dark text-light" id="contact">
        
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