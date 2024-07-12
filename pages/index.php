<?php
include_once("../partials/header.php");
// header("Location: /pages/index.php");

$stations = [
    "Dhankuta",
    "Bhairahawa",
    "Simara",
    "Lumle",
    "Birendranagar",
    "Kathmandu",
    "Jomsom",
    "Jiri",
    "Biratnagar",
    "Okhaldhunga",
    "Janakpur",
    "Taplejung",
    "Nepalgunj",
    "STATION",
    "Dhangadi",
    "Dadeldhura",
    "Pokhara",
    "Dharan",
    "Jumla",
    "Dipayal",
    "Ghorahi"
];

?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>
    <div class="container py-3">
        <select class="form-control" id="station" aria-label="Select nearest location to your farm">
            <option value="0" selected>Select nearest location</option>
            <?php
            foreach ($stations as $station) {
            ?>
                <option value="<?php echo $station; ?>"><?php echo $station; ?></option>
            <?php
            }
            ?>
        </select>
        <button class="btn btn-outline-success mt-3" onclick="pinLocation()">Get Weather</button>
    </div>
    <?php
    include_once("../partials/footer.php");
    ?>
    <script src="../js/tokenManager.js"></script>
    <script>
        pinLocation = () => {
            let station = $("#station").val();
            if (station == "0") {
                showError("Please select a station");
                return;
            }
            base_url = "http://127.0.0.1:8000/"
            $.ajax({
                url: base_url + "/api/pinned-location/add/",
                type: "POST",
                data: {
                    location: station
                },
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token")
                },
                success: function(response) {

                },
                error: function() {
                    showError("An error occurred while fetching weather data");
                }
            })
        }

        $(document).ready(function() {
            if (!localStorage.getItem("location")) {
                base_url = "http://127.0.0.1:8000/";
                $.ajax({
                    url: base_url + '/api/pinned-location/get/',
                    type: 'get',
                    headers: {
                        Authorization: "Bearer " + localStorage.getItem("access_token")
                    },
                    success: function(response, status, xhr) {
                        localStorage.setItem("location", response.location);
                        window.location.reload();
                    },
                    error: function() {
                        showError("An error occurred while fetching weather data");
                    }
                })
            }else{
                window.location.href = "./dashboard.php";
            }
        })
    </script>
</body>