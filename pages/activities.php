<?php
$page_title = "Activities | Dashboard";
$dashboard = "active";
include_once("../partials/header.php");
if ((!isset($_REQUEST['id'])) || (!isset($_REQUEST['crop']))) {
    header("Location: ./dashboard.php");
}
$field = $_REQUEST['id'];
$crop = $_REQUEST['crop'];
?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-7">
                <table class="table table-striped">
                    <tr>
                        <th colspan="2" class="text-center">Current Field Details</th>
                    <tr>
                        <th>Field Name</th>
                        <td id="name"></td>
                    </tr>
                    <tr>
                        <th>Selected Crop</th>
                        <td id="crop"></td>
                    </tr>
                    <tr>
                        <th>Crop Status</th>
                        <td id="cropStatus"></td>
                    </tr>
                    <tr>
                        <th>Nitrogen Content</th>
                        <td id="nitrogen"></td>
                    </tr>
                    <tr>
                        <th>Phosphorus Content</th>
                        <td id="phosphorus"></td>
                    </tr>
                    <tr>
                        <th>Potassium Content</th>
                        <td id="potassium"></td>
                    </tr>
                    <tr>
                        <th>PH Value</th>
                        <td id="ph"></td>
                    </tr>

                </table>
            </div>
            <div class="col-md-5" id="activityHistory">

            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <!-- tab for plantation pest fertilizer irrigation harvest -->
                <p class="lead font-weight-bold">Update Field Activities</p>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="plantation-tab" data-bs-toggle="tab"
                            data-bs-target="#plantation" type="button" role="tab" aria-controls="plantation"
                            aria-selected="true">Plantation</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pest-tab" data-bs-toggle="tab" data-bs-target="#pest" type="button"
                            role="tab" aria-controls="pest" aria-selected="false">Pest Control</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="fertilizer-tab" data-bs-toggle="tab" data-bs-target="#fertilizer"
                            type="button" role="tab" aria-controls="fertilizer" aria-selected="false">Fertilizer
                            Added</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="irrigation-tab" data-bs-toggle="tab" data-bs-target="#irrigation"
                            type="button" role="tab" aria-controls="irrigation"
                            aria-selected="false">Irrigation</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="harvest-tab" data-bs-toggle="tab" data-bs-target="#harvest"
                            type="button" role="tab" aria-controls="harvest" aria-selected="false">Harvest</button>
                    </li>
                </ul>

                <!-- form for plantation -->
                <div id="plantation" class="my-3">
                    <button class="btn btn-success" id="plantButton" onclick="plantCrop()">Plant <?php echo $crop; ?> in
                        this farm</button>
                </div>
                <!-- form for pest -->
                <div id="pest" class="my-3">
                    <label for="name">Name of Pest Control</label>
                    <input type="text" class="form-control" id="pest_name" placeholder="Enter Name of Pest Control">
                    <label for="quantity">Quantity (KG)</label>
                    <input type="number" step="0.01" class="form-control" id="pest_quantity"
                        placeholder="Enter Quantity">
                    <br>
                    <button class="btn btn-outline-dark" onclick="addPest()">Submit</button>
                </div>
                <!-- form for fertilizer -->
                <div id="fertilizer" class="my-3">
                    <label for="name">Name of Fertilizer</label>
                    <input type="text" class="form-control" id="fertilizer_name" placeholder="Enter Name of Fertilizer">
                    <label for="quantity">Quantity (KG)</label>
                    <input type="number" step="0.01" class="form-control" id="fertilizer_quantity"
                        placeholder="Enter Quantity">
                    <br>
                    <button class="btn btn-outline-dark" onclick="addFertillizer()">Submit</button>
                </div>
                <!-- form for irrigation -->
                <div id="irrigation" class="my-3">
                    <label for="quantity">Type</label>
                    <select class="form-control" id="irrigation_type">
                        <option value="null">--Select Irrigation Type--</option>
                        <option value="complete">Complete</option>
                        <option value="partial">Partial</option>
                    </select>
                    <br>
                    <button class="btn btn-outline-dark" onclick="addIrrigation()">Submit</button>
                </div>
                <!-- form for harvest -->
                <div id="harvest" class="my-3">
                    <label for="quantity">Quantity (KG)</label>
                    <input type="number" step="0.01" class="form-control" id="harvest_quantity"
                        placeholder="Enter Quantity">
                    <br>
                    <button class="btn btn-outline-dark" onclick="addHarvest()">Submit</button>
                </div>
            </div>
            <div class="col-md-5">
                <!-- qr portion starts here -->
                <main>
                    <div id="display" onclick="download()" class="m-3"></div>
                    <!-- name of this div is colled in QRCode parameter -->

                    <span id="qr_tag" class="text-muted font-italic" class="m-5">Click QR code to download it</span>
                </main>
            </div>
        </div>

        <!-- visualization -->
        <div class="row">
            <div class="col">
                <canvas id="disp" style="width:100%;"></canvas>
            </div>
        </div>
    </div>



    <script>
        // chart start here
        let canvas_used = false;
        let myLineChart;
        // Creating line chart
        let labelsF = [];
        let dataSetF = [];
        let labelsP = [];
        let dataSetP = [];

        let isShownF = false;
        let isShownP = false;

        show_fChart = () => {
            if (canvas_used == true) {
                myLineChart.destroy();
                canvas_used = false;
            }
            if (isShownF == false) {
                labelsF.reverse();
                dataSetF.reverse();
            }
            isShownF = true;
            canvas_used = true;
            let ctx =
                document.getElementById('disp').getContext('2d');
            myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsF,
                    datasets: [
                        {
                            label: 'Fertilizer',
                            data: dataSetF,
                            borderColor: 'green',
                            borderWidth: 2,
                            fill: false,
                        },

                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date',
                                font: {
                                    padding: 4,
                                    size: 20,
                                    weight: 'bold',
                                    family: 'Arial'
                                },
                                color: 'black'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Quantity',
                                font: {
                                    size: 20,
                                    weight: 'bold',
                                    family: 'Arial'
                                },
                                color: 'black'
                            },
                            beginAtZero: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Values',
                            }
                        }
                    }
                }

            })
        }
        show_pChart = () => {
            if (canvas_used == true) {
                myLineChart.destroy();
                canvas_used = false;
            }
            if (isShownP == false) {
                labelsP.reverse();
                dataSetP.reverse();
            }
            isShownP = true;
            canvas_used = true;
            let ctx =
                document.getElementById('disp').getContext('2d');
            myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsP,
                    datasets: [
                        {
                            label: 'Pest Control',
                            data: dataSetP,
                            borderColor: 'green',
                            borderWidth: 2,
                            fill: false,
                        },

                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date',
                                font: {
                                    padding: 4,
                                    size: 20,
                                    weight: 'bold',
                                    family: 'Arial'
                                },
                                color: 'black'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Quantity',
                                font: {
                                    size: 20,
                                    weight: 'bold',
                                    family: 'Arial'
                                },
                                color: 'black'
                            },
                            beginAtZero: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Values',
                            }
                        }
                    }
                }

            })
        }
        // chart ends here

        var generated = false;
        var latestPlantationDate;
        $("#qr_tag").hide();

        gen_qr = () => {
            generated = true;
            $("#display").html("");
            latestPlantationDate = document.getElementById("pDate").innerText;
            let qr_data = "Field Name: " + document.getElementById("name").innerText +
                "\n-" +
                "\nSelected Crop: " + document.getElementById("crop").innerText +
                "\nCrop Status: " + document.getElementById("cropStatus").innerText +
                "\nLatest Plantation: " + latestPlantationDate +
                "\n-" +
                "\nNitrogen: " + document.getElementById("nitrogen").innerText +
                "\nPhosphorus: " + document.getElementById("phosphorus").innerText +
                "\nPotassium: " + document.getElementById("potassium").innerText +
                "\nPH: " + document.getElementById("ph").innerText;
            var qrcode = new QRCode("display", qr_data); //generating qr
            $("#qr_tag").show();
        }

        setTimeout(gen_qr, 750);

        download = () => { //this function downlods the qr image generated
            if (generated) {
                var image_attr = document.getElementById("display").getElementsByTagName("img");
                var qrUrl = image_attr[0].src;

                const link = document.createElement('a');
                console.log(link);
                link.href = qrUrl;
                link.download = qrUrl;
                document.body.appendChild(link);
                link.click();
            } else {
                alert("You have not generated the qr code.")
            }
        }
    </script>
    <!-- qr portion ends here  -->

    <?php
    include_once("../partials/footer.php");
    ?>


    <script>
        getPlantationHistory = () => {
            if (canvas_used == true) {
                myLineChart.destroy();
                canvas_used = false;
            }
            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/plantation/get/' + id + '/',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function (response) {
                    let html = "<table class='table table-striped'><tr><th>Plantation Date</th><th>Crop</th></tr>";
                    response.forEach((item) => {
                        let dt = item.date.replace("T", ", ");
                        dt = dt.split(".")[0];
                        html += "<tr><td id='pDate'>" + dt + "</td><td>" + item.crop + "</td></tr>";
                    });
                    html += "</table>";
                    $("#activityHistory").html(html);
                }
            });
        }

        getIrrigationHistory = () => {
            if (canvas_used == true) {
                myLineChart.destroy();
                canvas_used = false;
            }
            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/irrigation/get/' + id + '/',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function (response) {
                    let html = "<table class='table table-striped'><tr><th>Irrigation Date</th><th>Type</th></tr>";
                    response.forEach((item) => {
                        let dt = item.date.replace("T", ", ");
                        dt = dt.split(".")[0];
                        html += "<tr><td>" + dt + "</td><td>" + item.type + "</td></tr>";
                    });
                    html += "</table>";
                    $("#activityHistory").html(html);
                }
            });
        }

        let fertilizerAdded = false;
        getFertilizerHistory = () => {
            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/fertilizer/get/' + id + '/',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function (response) {
                    let html = "<table class='table table-striped'><tr><th>Fertilizer Date</th><th>Name</th><th>Quantity</th></tr>";
                    response.forEach((item) => {
                        let dt = item.date.replace("T", ", ");
                        dt = dt.split(".")[0];
                        if (fertilizerAdded == false) {
                            labelsF.push(dt.split(",")[0]);
                            dataSetF.push(item.quantity);
                        }
                        html += "<tr><td>" + dt + "</td><td>" + item.name + "</td><td>" + item.quantity + " KG</td></tr>";
                    });
                    html += "</table>";
                    $("#activityHistory").html(html);
                    fertilizerAdded = true;
                    setTimeout(show_fChart, 750);
                }
            });
        }

        getHarvestHistory = () => {
            if (canvas_used == true) {
                myLineChart.destroy();
                canvas_used = false;
            }
            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/harvest/get/' + id + '/',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function (response) {
                    let html = "<table class='table table-striped'><tr><th>Harvest Date</th><th>Crop</th><th>Quantity</th></tr>";
                    response.forEach((item) => {
                        let dt = item.date.replace("T", ", ");
                        dt = dt.split(".")[0];
                        html += "<tr><td>" + dt + "</td><td>" + item.crop + "</td><td>" + item.quantity + " KG</td></tr>";
                    });
                    html += "</table>";
                    $("#activityHistory").html(html);
                }
            });
        }


        let pestAdded = false;
        getPestHistory = () => {
            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/pest-control/get/' + id + '/',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function (response) {
                    let html = "<table class='table table-striped'><tr><th>Pest Control Date</th><th>Name</th><th>Quantity</th></tr>";
                    response.forEach((item) => {
                        let dt = item.date.replace("T", ", ");
                        dt = dt.split(".")[0];
                        if (pestAdded == false) {
                            labelsP.push(dt.split(",")[0]);
                            dataSetP.push(item.quantity);
                        }
                        html += "<tr><td>" + dt + "</td><td>" + item.name + "</td><td>" + item.quantity + " KG</td></tr>";
                    });
                    html += "</table>";
                    $("#activityHistory").html(html);
                    pestAdded = true;
                    setTimeout(show_pChart, 750);
                }
            });
        }



        addHarvest = () => {
            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            let quantity = $("#harvest_quantity").val();
            if (!window.confirm("Please verify data before proceeding\nQuantity to harvest: " + quantity)) {
                return;
            }
            if (quantity == "") {
                showError("All fields are required");
                return;
            }
            $.ajax({
                url: base_url + 'api/harvest/add/',
                type: 'POST',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                data: {
                    field: id,
                    crop: "<?php echo $_REQUEST['crop']; ?>",
                    quantity: quantity
                },
                success: function (response, status, xhr) {
                    if (xhr.status == 200) {
                        showError("Harvest added successfully");
                        $("#harvest_quantity").val("");
                        setTimeout(() => {
                            getHarvestHistory();
                            viewDetails();
                        }, 700);
                    } else {
                        console.log(response)
                    }
                },
                error: function (response, textStatus, errorThrown) {
                    if (response.status == 400) {
                        showError(response.responseJSON.error);
                    } else {
                        showError("An error occured");
                    }
                }
            });
        }


        addIrrigation = () => {
            if (!window.confirm("Please verify data before proceeding\nIrrigation type: " + $("#irrigation_type").val())) {
                return;
            }

            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            let type = $("#irrigation_type").val();
            if (type == "null") {
                showError("Select an irrigation type");
                return;
            }
            $.ajax({
                url: base_url + 'api/irrigation/add/',
                type: 'POST',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                data: {
                    field: id,
                    type: type
                },
                success: function (response, status, xhr) {
                    if (xhr.status == 200) {
                        showError("Irrigation added successfully");
                        $("#irrigation_type").val("null");
                        setTimeout(() => {
                            getIrrigationHistory();
                        }, 700);
                    } else {
                        console.log(response)
                    }
                },
                error: function (response, textStatus, errorThrown) {
                    if (response.status == 400) {
                        showError(response.responseJSON.error);
                    } else {
                        showError("An error occured");
                    }
                }
            });
        }


        addFertillizer = () => {
            if (!window.confirm("Please verify data before proceeding\nFertilizer name: " + $("#fertilizer_name").val() + "\nQuantity: " + $("#fertilizer_quantity").val())) {
                return;
            }

            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            let field_name = $("#fertilizer_name").val();
            let quantity = $("#fertilizer_quantity").val();
            if (field_name == "" || quantity == "") {
                showError("All fields are required");
                return;
            }
            $.ajax({
                url: base_url + 'api/fertilizer/add/',
                type: 'POST',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                data: {
                    field: id,
                    name: field_name,
                    quantity: quantity
                },
                success: function (response, status, xhr) {
                    if (xhr.status == 200) {
                        showError("Fertilizer added successfully");
                        $("#fertilizer_name").val("");
                        $("#fertilizer_quantity").val("");
                        setTimeout(() => {
                            getFertilizerHistory();
                        }, 700);
                    } else {
                        console.log(response)
                    }
                },
                error: function (response, textStatus, errorThrown) {
                    if (response.status == 400) {
                        showError(response.responseJSON.error);
                    } else {
                        showError("An error occured");
                    }
                }
            });
        }

        addPest = () => {
            if (!window.confirm("Please verify data before proceeding\nPest name: " + $("#pest_name").val() + "\nQuantity: " + $("#pest_quantity").val())) {
                return;
            }

            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            let field_name = $("#pest_name").val();
            let quantity = $("#pest_quantity").val();
            if (field_name == "" || quantity == "") {
                showError("All fields are required");
                return;
            }
            $.ajax({
                url: base_url + 'api/pest-control/add/',
                type: 'POST',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                data: {
                    field: id,
                    name: field_name,
                    quantity: quantity
                },
                success: function (response, status, xhr) {
                    if (xhr.status == 200) {
                        showError("Pest control added successfully");
                        $("#pest_name").val("");
                        $("#pest_quantity").val("");
                        setTimeout(() => {
                            getPestHistory();
                        }, 700);
                    } else {
                        console.log(response)
                    }
                },
                error: function (response, textStatus, errorThrown) {
                    if (response.status == 400) {
                        showError(response.responseJSON.error);
                    } else {
                        showError("An error occured");
                    }
                }
            });
        }

        plantCrop = () => {
            if (!window.confirm("Please verify data before proceeding\nCrop to plant: " + "<?php echo $_REQUEST['crop']; ?>")) {
                return;
            }

            let id = <?php echo $_REQUEST['id']; ?>;
            let crop = "<?php echo $_REQUEST['crop']; ?>";
            if (crop == "") {
                showError("Please add a crop to field details to plant it");
                return;
            }
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/plantation/add/',
                type: 'POST',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                data: {
                    field: id,
                    crop: crop
                },
                success: function (response, status, xhr) {
                    if (xhr.status == 200) {
                        showError("Crop planted successfully");
                        setTimeout(() => {
                            getPlantationHistory();
                            viewDetails();
                            setTimeout(gen_qr, 1000);
                        }, 700);
                    } else {
                        console.log(response)
                    }
                },
                error: function (response, textStatus, errorThrown) {
                    if (response.status == 400) {
                        showError(response.responseJSON.error);
                    } else {
                        showError("An error occured");

                    }
                }
            });
        }


        $(document).ready(() => {
            viewDetails();
            getPlantationHistory();
            $("#plantation").show();
            $("#pest").hide();
            $("#fertilizer").hide();
            $("#irrigation").hide();
            $("#harvest").hide();
            $('#myTab li button').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
                if (this.id == "plantation-tab") {
                    $("#plantation").show();
                    $("#pest").hide();
                    $("#fertilizer").hide();
                    $("#irrigation").hide();
                    $("#harvest").hide();
                    getPlantationHistory();
                } else if (this.id == "pest-tab") {
                    $("#plantation").hide();
                    $("#pest").show();
                    $("#fertilizer").hide();
                    $("#irrigation").hide();
                    $("#harvest").hide();
                    getPestHistory();
                } else if (this.id == "fertilizer-tab") {
                    $("#plantation").hide();
                    $("#pest").hide();
                    $("#fertilizer").show();
                    $("#irrigation").hide();
                    $("#harvest").hide();
                    getFertilizerHistory();
                } else if (this.id == "irrigation-tab") {
                    $("#plantation").hide();
                    $("#pest").hide();
                    $("#fertilizer").hide();
                    $("#irrigation").show();
                    $("#harvest").hide();
                    getIrrigationHistory();
                } else if (this.id == "harvest-tab") {
                    $("#plantation").hide();
                    $("#pest").hide();
                    $("#fertilizer").hide();
                    $("#irrigation").hide();
                    $("#harvest").show();
                    getHarvestHistory();
                }

            });

            $("#fertilizer_quantity, #fertilizer_name").keypress(function (event) {
                if (event.which == 13) {
                    event.preventDefault();
                    addFertillizer();
                }
            });
            $("#pest_quantity, #pest_name").keypress(function (event) {
                if (event.which == 13) {
                    event.preventDefault();
                    addPest();
                }
            });
            $("#irrigation_type").keypress(function (event) {
                if (event.which == 13) {
                    event.preventDefault();
                    addIrrigation();
                }
            });
            $("#harvest_quantity").keypress(function (event) {
                if (event.which == 13) {
                    event.preventDefault();
                    addHarvest();
                }
            });


        });

        viewDetails = () => {
            let isPlant = false;
            let id = <?php echo $_REQUEST['id']; ?>;
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/field/get/id/' + id + '/',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function (response) {
                    var fieldStatus;
                    if (response.harvested == true) {
                        fieldStatus = "Not Planted";
                    } else {
                        fieldStatus = "Planted";
                    }
                    $("#name").text(response.name);
                    let url = '/pages/prediction.php?id=' + id;
                    let toAppend = "<a href='" + url + "' class='ml-2'><i class='bi bi-pen text-danger'></a>";
                    $("#crop").text(response.crop);
                    $("#crop").append(toAppend);
                    $("#cropStatus").text(fieldStatus);
                    $("#nitrogen").text(response.nitrogen + " KG/HA");
                    $("#phosphorus").text(response.phosphorus + " KG/HA");
                    $("#potassium").text(response.potassium + " KG/HA");
                    $("#ph").text(response.ph);
                    if (response.crop != "") {
                        isPlant = true;
                    }
                    if (!isPlant) {
                        $("#plantation").text("No crop added to plant");
                        $("#plantation").addClass("btn btn-danger");
                        $("#plantation").attr("onclick", "showError('Please add a crop to field details to plant it')");
                    }
                    if (response.harvested == false) {
                        $("#planataion button").text("Crop already planted");
                        $("#plantation button").attr('disabled', 'true').attr("onclick", "showError('Crop already planted')");
                    } else {
                        $("#plantation button").text("Plant <?php echo $_REQUEST['crop']; ?> in this farm");
                        $("#plantation button").attr('disabled', false);
                    }
                },
                error: function (response, textStatus, errorThrown) {
                    if (response.status == 404) {
                        showError(response.responseJSON.error);
                    } else {
                        showError("An error occured");

                    }
                }
            });
        }





    </script>
    <script src="../js/tokenManager.js"></script>
</body>