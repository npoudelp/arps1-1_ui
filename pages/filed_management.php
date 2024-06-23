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
        <div class="row mt-1">
            <div class="col-md-8">
                <div class="container-fluid" style="height: 70vh" id="map"></div>
            </div>
            <div class="col-md-4">

                <button class="btn btn-outline-dark" onclick="checkStatus()">checkStatus</button>

                <!-- filed add form -->

                <div class="">
                    <input type="hidden" name="" id="coordinates">
                    <div class="form-group">
                        <label for="field_name">Field Name</label>
                        <input type="text" maxlength="35" class="form-control" id="field_name" placeholder="Enter field name">
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
                        <label for="weight">Weight</label>
                        <input type="number" step="0.001" class="form-control" id="weight" placeholder="Enter weight">
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
            let field_name = $("#field_name").val();
            let nitrogen = $("#nitrogen").val();
            let potassium = $("#potassium").val();
            let weight = $("#weight").val();
            let coordinates = $("#coordinates").val();

            if (field_name == "" || coordinates == "") {

                return;
            }

            $.ajax({
                url: "/api/field/add.php",
                type: "POST",
                data: {
                    field_name: $("#field_name").val(),
                    nitrogen: $("#nitrogen").val(),
                    potassium: $("#potassium").val(),
                    weight: $("#weight").val(),
                    coordinates: $("#coordinates").val()
                },
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
    </script>
</body>

</html>