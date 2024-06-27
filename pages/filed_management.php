<?php
$page_title = "arps | Field Management";
include_once("../partials/header.php");
?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>


    <div class="container-fluid">
        <div class="row mt-1">
            <div class="col-md-8">
                <div class="container-fluid" style="height: 70vh" id="map"></div>
            </div>
            <div class="col-md-4">



                <!-- filed add form -->

                <div class="">
                    <input type="hidden" name="" id="coordinates">
                    <div class="form-group">
                        <label for="field_name">Field Name</label>
                        <input type="text" maxlength="35" class="form-control" id="field_name" placeholder="Enter field name (required)">
                    </div>
                    <div class="form-group">
                        <label for="nitrogen">Nitrogen Content</label>
                        <input type="number" step="0.001" class="form-control" id="nitrogen" placeholder="Enter nitrogen content">
                    </div>
                    <div class="form-group">
                        <label for="potassium">Potassium Content</label>
                        <input type="number" step="0.001" class="form-control" id="potassium" placeholder="Enter potassium content">
                    </div>
                    <div class="form-group">
                        <label for="phosphorus">Phosphorus Content</label>
                        <input type="number" step="0.001" class="form-control" id="phosphorus" placeholder="Enter phosphorus content">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button type="submit" onclick="addField()" class="btn btn-outline-warning">
                                Add Field
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-danger" onclick="clearMap()">
                                Reset Map
                            </button>
                            <button class="btn btn-outline-dark" onclick="checkStatus()">
                                Check Status
                            </button>
                        </div>
                    </div>
                </div>

                <!-- filed add form ends -->
            </div>
        </div>
    </div>

    <?php
    include_once("../partials/map.php");
    ?>
    <script src="../js/tokenManager.js"></script>

    <script>
        addField = () => {
            let base_url = "http://127.0.0.1:8000/";

            let field_name = $("#field_name").val();
            let field_nitrogen = $("#nitrogen").val();
            let field_potassium = $("#potassium").val();
            let field_phosphorus = $("#phosphorus").val();
            let field_coordinates = $("#coordinates").val();
            if (field_name == "" || field_coordinates == "") {
                showError("Please fill all the required fields");
                return;
            }
            $.ajax({
                url: base_url + "api/field/add/",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token")
                },
                data: {
                    coordinates: field_coordinates,
                    name: field_name,
                    nitrogen: field_nitrogen,
                    potassium: field_potassium,
                    phosphorus: field_phosphorus,
                },
                content_type: "application/json",
                success: function(response) {
                    console.log(response);
                    alert("Field added successfully");
                },
                error: function(err) {
                    console.log(err);
                    alert("Error adding field");
                }
            })
        }

        $("#field_name, #nitrogen, #potassium, #phosphorus").keypress(() => {
            if (event.which == 13) {
                event.preventDefault();
                addField();
            }
        })
    </script>
</body>

</html>