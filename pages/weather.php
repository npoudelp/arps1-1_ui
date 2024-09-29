<?php
$weather = "active";
include_once("../partials/header.php");

?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-5" id="weather_display">

            </div>
            <div class="col-md-7" id="flood_display">

            </div>
        </div>

    </div>
    <?php
    include_once("../partials/footer.php");
    ?>
    <script src="../js/tokenManager.js"></script>
    <script>
        pinFromWeather = (station) => {
            if (!window.confirm("Are you sure you want to pin " + station + " to navbar?")) {
                return;
            }
            if (station == "0") {
                showError("Please select a station");
                return;
            }
            base_url = "http://127.0.0.1:8000/"
            $.ajax({
                url: base_url + "api/pinned-location/add/",
                type: "POST",
                data: {
                    location: station
                },
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token")
                },
                success: function (response) {
                    localStorage.setItem("location", station);
                    window.location.reload();
                },
                error: function () {
                    showError("An error occurred while fetching weather data");
                }
            })
        }

        $(document).ready(function () {
            base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/scrape/all/',
                type: 'get',
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token")
                },
                success: function (response, status, xhr) {
                    $("#weather_display").empty();
                    html = "<p class='font-weight-bold text-center lead'>Weather Data</p>";
                    html += "<table class='table table-striped'>";
                    html += "<tr><th>Station</th><th>Max Temp</th><th>Min Temp</th><th>Rain</th><th></th></tr>";
                    $.each(response, (i, data) => {
                        $.each(data, (j, weather) => {
                            min = weather[1];
                            max = weather[2];
                            rain = weather[3];
                            color = '';
                            bi = 'bi-pin';
                            if (weather[0] == localStorage.getItem("location")) {
                                color = 'text-danger';
                                bi = 'bi-pin-fill';
                            }
                            html += `
                            <tr><td id='${weather[0]}'>${weather[0]}</td><td>${min}°C</td><td>${max}°C</td><td>${rain}mm<td><span onclick=pinFromWeather('${weather[0]}')><i class='bi ${bi} ${color} lead ml-3'></i></span></td></td></tr>
                            `;
                        });
                    });
                    html += "</table>";
                    $("#weather_display").html(html);
                },
                error: function () {
                    showError("An error occurred while fetching weather data");
                }
            })


            $.ajax({
                // https://www.dhm.gov.np/frontend_dhm/hydrology/getRainfall/1 river status flood monitoring
                // https://103.215.208.77/SASIAFFG_CONSOLE/ wtf is this
                url: "https://www.dhm.gov.np/frontend_dhm/hydrology/getRainfallFilter",
                type: "GET",
                data: {
                    type: "0",
                    mapValue: "all",
                    hour: "1"
                },
                success: function (response) {
                    $("#flood_display").empty();
                    html = "<p class='font-weight-bold text-center lead'>Flood Level Data</p>";
                    html += "<table class='table table-striped'>";
                    html += "<tr class=''><th>Station</th><th>District</th><th>Basin</th><th>Status</th><th></th></tr>";
                    $.each(response.data[0], (i, data) => {
                        console.log(data.district)
                        let color;
                        if (data.status == "WARNING") {
                            color = "bg-warning text-danger";
                        }
                        html += `
                            <tr class='${color}'><td>${data.name}</td><td>${data.district}</td><td>${data.basin}</td><td>${data.status}</td></tr>`
                    });
                    html += "</table>";
                    $("#flood_display").html(html);
                },
                error: function () {
                    console.log("An error occurred while fetching rainfall data");
                }
            })
        })
    </script>
</body>