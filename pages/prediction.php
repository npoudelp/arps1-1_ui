<?php
$page_title = "arps | Predictions";

if (!isset($_REQUEST['id'])) {
    header('Location: /pages/dashboard.php');
}

include_once("../partials/header.php");
$districts = ['Arghakhanchi', 'Baglung', 'Baitadi', 'Bajang', 'Banke', 'Bara', 'Bardiya', 'Bhaktapur', 'Chitawan', 'Dadeldhura', 'Dailekh', 'Dang', 'Darchula', 'Dhading', 'Dhankuta', 'Dhanusa', 'Dolkha', 'Dolpa', 'Doti', 'Gorkha', 'Gulmi', 'Humla', 'Ilam', 'Jhapa', 'Jumla', 'Kabhre', 'Kailali', 'Kanchanpur', 'Kaski', 'Kathmandu', 'Lalitpur', 'Lamjung', 'Mahottari', 'Makwanpur', 'Manang', 'Morang', 'Mugu', 'Mustang', 'Myagdi', 'Nawalparasi', 'Nuwakot', 'Okhaldhunga', 'Palpa', 'Panchther', 'Parbat', 'Rasuwa', 'Routahat', 'Rukum', 'Rupandehi', 'Salyan', 'Sankhuwasabha', 'Saptari', 'Sarlahi', 'Sindhuli', 'Solukhumbu', 'Sunsari', 'Surkhet', 'Syangja', 'Tanahun', 'Taplejung', 'Terhathum', 'Udayapur'];

?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>

    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-md-7" id="crops">

            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-8">
                        <select name="" id="district" class="form-control">
                            <option value="null">Select district of your field</option>
                            <?php
                            foreach ($districts as $d) {
                                echo "<option value=\"$d\">$d</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <span class="btn btn-success" onclick="predictCrop()">Recomend</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/tokenManager.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $("#district option").each(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        predictCrop = () => {
            let district = $('#district').val();
            let id = <?php echo $_REQUEST['id']; ?>;
            if (district == 'null') {
                showError('Please select district');
                return;
            }
            const base_url = 'http://127.0.0.1:8000/';
            $.ajax({
                url: base_url + 'api/recomend-crop/',
                type: 'POST',
                data: {
                    district: district,
                    id: id
                },
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(response, status, xhr) {
                    if (xhr.status == 200) {
                        $("#crops").empty();
                        $("#crops").append("<h3 class='lead font-weight-bold'>Most suitabel to least suitable crops from the top</h3>");
                        response.crops.forEach((crop, i) => {
                            i = i + 1;
                            $("#crops").append(" <p class='lead'>" +
                                "<div class='row'>" +
                                "<div class='col-6'>" +
                                "<p class='lead' id='cropName'>" + i + ": " + crop + "</p>" +
                                "</div>" +
                                "<div class='col-6'>" +
                                "<span id='aboutCrop' class='btn btn-dark' onclick=aboutCrop('" + crop + "')>" +
                                "About Crop" +
                                "</span> " +
                                " <span id='addCrop' class='btn btn-outline-success' onclick=addToFarm('" + crop + "')>" +
                                "Add To Farm" +
                                "</span>" +
                                "</div>" +
                                "</div>" +
                                "</p>");
                        });

                    } else {
                        showError('Error occured, try again later');
                    }
                },
                error: function(err) {
                    console.log(err);
                    showError('Farm data not availabale, update farm data first');
                }
            });

        }

        addToFarm = (crop) => {
            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = 'http://127.0.0.1:8000/';
            let data = {
                crop: crop
            };
            $.ajax({
                url: base_url + 'api/field/update/' + id + '/',
                type: 'PUT',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(response) {
                    showError(crop + " crop added to field");
                }
            });
        }

        aboutCrop = (crop) => {
            window.location.href = './assistance.php?crop=' + crop;
        }

        $("#prediction").keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
                predictCrop();
            }
        });
    </script>

</body>

</html>