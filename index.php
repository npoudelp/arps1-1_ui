<?php
include_once("./partials/header.php");
?>

<body>
    <?php
    include_once("./partials/navbar.php");
    ?>
    <div class="container-fluid py-3 bg-light text-success" id="home">
        <div class="row">
            <div class="col-md-6">
                <p class="text-center lead">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Id magni quidem quaerat voluptates, vero architecto laboriosam iusto ducimus perferendis exercitationem debitis maxime corrupti reprehenderit at, temporibus mollitia incidunt iste ullam.
                </p>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>

    <div class="container-fluid py-3 bg-success text-light" id="service">
        <p class="font-weight-bold h3 text-center">Services</p>
        <hr class="border-light">
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <p class="text-center lead">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Id magni quidem quaerat voluptates, vero architecto laboriosam iusto ducimus perferendis exercitationem debitis maxime corrupti reprehenderit at, temporibus mollitia incidunt iste ullam.
                </p>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3 bg-light text-success" id="about">
        <p class="font-weight-bold h3 text-center">About</p>
        <hr class="border-success">
        <div class="col-md-6">
            <p class="text-center lead">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Id magni quidem quaerat voluptates, vero architecto laboriosam iusto ducimus perferendis exercitationem debitis maxime corrupti reprehenderit at, temporibus mollitia incidunt iste ullam.
            </p>
        </div>
        <div class="row">
            <div class="col-md-6"></div>
        </div>
    </div>

    <div class="container-fluid py-3 bg-success text-light" id="contact">
        <p class="font-weight-bold h3 text-center">Contact</p>
        <hr class="border-light">
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <p class="text-center lead">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Id magni quidem quaerat voluptates, vero architecto laboriosam iusto ducimus perferendis exercitationem debitis maxime corrupti reprehenderit at, temporibus mollitia incidunt iste ullam.
                </p>
            </div>
        </div>
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