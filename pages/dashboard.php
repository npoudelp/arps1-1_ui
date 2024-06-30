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

                <!-- form from other page -->
                <div class="" id="fieldAddForm">
                    <input type="hidden" readonly="true" name="" id="coordinates_value">
                    <div class="form-group">
                        <label for="field_name">Field Name</label>
                        <input type="text" maxlength="35" class="form-control" id="field_name" placeholder="Enter field name (required)">
                    </div>
                    <div class="form-group">
                        <label for="crop_name">Crop Planted</label>
                        <input type="text" maxlength="35" class="form-control" id="crop_name" placeholder="Enter crop name">
                    </div>
                    <div class="form-group">
                        <label for="nitrogen">Nitrogen Content</label>
                        <input type="number" step="0.001" class="form-control" id="nitrogen_content" placeholder="Enter nitrogen content">
                    </div>
                    <div class="form-group">
                        <label for="potassium">Potassium Content</label>
                        <input type="number" step="0.001" class="form-control" id="potassium_content" placeholder="Enter potassium content">
                    </div>
                    <div class="form-group">
                        <label for="phosphorus">Phosphorus Content</label>
                        <input type="number" step="0.001" class="form-control" id="phosphorus_content" placeholder="Enter phosphorus content">
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
                <!-- other page form ends here -->


                <div class="container" id="field_display">
                    <input type="hidden" name="" id="coordinates">
                    <p>
                        <span class="font-weight-bold">Field Name:</span>
                        <input class="form-control" type="text" id="name"></input>
                    </p>
                    <p>
                        <span class="font-weight-bold">Planted Crop:</span>
                        <input class="form-control" type="text" id="crop"></input>
                    </p>
                    <p>
                        <span class="font-weight-bold">Nitrogen Content:</span>
                        <input class="form-control" type="text" id="nitrogen"></input>
                    </p>
                    <p>
                        <span class="font-weight-bold">Phosphorus Content:</span>
                        <input class="form-control" type="text" id="phosphorus"></input>
                    </p>
                    <p>
                        <span class="font-weight-bold">Potassium Content:</span>
                        <input class="form-control" type="text" id="potassium"></input>
                    </p>
                    <p>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-outline-dark" id="allowUpdate" onclick="allowUpdate()">
                                Edit
                            </button>
                            <button class="btn btn-outline-warning" id="update" onclick="updateField()">
                                Update
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-dark" id="viewDetails" onclick="viewDetails()">
                                Get Details
                            </button>
                        </div>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-warning my-1 p-2"></div>

    <script>
        hideNonEssentials = () => {
            $("#fieldAddForm").hide();
            $("#field_display").hide();
            $("#update").hide();
            $("#name, #crop, #nitrogen, #phosphorus, #potassium").prop('readonly', true).css('border', 'none');
        }

        allowUpdate = () => {
            $("#update").show();
            $("#allowUpdate").hide();
            $("#name, #crop, #nitrogen, #phosphorus, #potassium").prop('readonly', false).css('border', '1px solid black');
        }

        updateField = () => {
            let id = $("#update").val();
            let coordinates = $("#coordinates").val();
            let name = $("#name").val();
            let crop = $("#crop").val();
            let nitrogen = $("#nitrogen").val();
            let phosphorus = $("#phosphorus").val();
            let potassium = $("#potassium").val();
            if (nitrogen == "") {
                nitrogen = 0;
            }
            if (phosphorus == "") {
                phosphorus = 0;
            }
            if (potassium == "") {
                potassium = 0;
            }
            if (name == "" || coordinates == "") {
                showError("Please fill all the fields");
                return;
            }

            let data = {
                coordinates: coordinates,
                name: name,
                crop: crop,
                nitrogen: nitrogen,
                phosphorus: phosphorus,
                potassium: potassium
            }
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/field/update/' + id + '/',

                type: 'PUT',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(response) {
                    $("#update").hide();
                    $("#fieldAddForm").hide();
                    $("#name, #crop, #nitrogen, #phosphorus, #potassium").prop('readonly', true).css('border', 'none');
                    $("#allowUpdate").show();
                    showError("Field Updated Successfully");
                }
            });
        }

        viewDetails = () => {
            let id = $("#update").val();
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'field/get/id/' + id + '/',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(response) {
                    $("#update").hide();
                    $("#fieldAddForm").hide();
                    console.log(response);
                    $("#name").val(response.name);
                    $("#crop").val(response.crop);
                    $("#nitrogen").val(response.nitrogen);
                    $("#phosphorus").val(response.phosphorus);
                    $("#potassium").val(response.potassium);
                }
            });
        }

        $("#name, #crop, #nitrogen, #phosphorus, #potassium").keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
                updateField();
            }
        });

        hideNonEssentials();
        // otehr page js
        addField = () => {
            let base_url = "http://127.0.0.1:8000/";

            let field_name = $("#field_name").val();
            let crop_name = $("#crop_name").val();
            let field_nitrogen = $("#nitrogen_content").val();
            let field_potassium = $("#potassium_content").val();
            let field_phosphorus = $("#phosphorus_content").val();
            let field_coordinates = $("#coordinates_value").val();
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
                    crop: crop_name,
                    name: field_name,
                    nitrogen: field_nitrogen,
                    potassium: field_potassium,
                    phosphorus: field_phosphorus,
                },
                content_type: "application/json",
                success: function(response) {
                    showError("Field added successfully");
                    window.location.reload();
                },
                error: function(err) {
                    showError("Error adding field");
                }
            })
        }

        $("#field_name, #nitrogen, #potassium, #phosphorus").keypress(() => {
            if (event.which == 13) {
                event.preventDefault();
                addField();
            }
        })
        // other page js ends here
    </script>
    <script src="../js/tokenManager.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDdZfRDtgrw9k6rtBu5di_Da8aUeRmFtbM&map_ids=1ad12e178890838&callback=initMap&libraries=drawing"></script>
    <script src="../js/mapForDashboard.js"></script>

    <script>



    </script>
</body>