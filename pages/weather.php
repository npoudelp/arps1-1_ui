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
        </div>

    </div>
    <?php
    include_once("../partials/footer.php");
    ?>
    <script src="../js/tokenManager.js"></script>
    <script>
        pinFromWeather = (station) => {
            if(!window.confirm("Are you sure you want to pin "+ station +" to navbar?")){
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
                success: function(response) {
                    localStorage.setItem("location", station);
                    showError("Location pinned successfully");
                    window.location.reload();
                },
                error: function() {
                    showError("An error occurred while fetching weather data");
                }
            })
        }

        $(document).ready(function() {
            base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + '/api/scrape/all/',
                type: 'get',
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token")
                },
                success: function(response, status, xhr) {
                    $("#weather_display").empty();
                    html = "<p class='font-weight-bold text-center lead'>Today's Weather Data</p>";
                    html += "<table class='table table-striped'>";
                    html += "<tr><th>Station</th><th>Min Temp</th><th>Max Temp</th><th>Rain</th></tr>";
                    $.each(response, (i, data) => {
                        $.each(data, (j, weather) => {
                            min = weather[1];
                            max = weather[2];
                            rain = weather[3];
                            html += `
                            <tr><td id='${weather[0]}'>${weather[0]}</td><td>${min}°C</td><td>${max}°C</td><td>${rain}mm <span onclick=pinFromWeather('${weather[0]}')><i class='bi bi-pin text-green lead ml-3'></i></span></td></tr>
                            `;
                        });
                    });
                    html += "</table>";
                    $("#weather_display").html(html);
                },
                error: function() {
                    showError("An error occurred while fetching weather data");
                }
            })
        })
    </script>
</body>