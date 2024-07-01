<?php
$page_title = "arps | Predictions";
include_once("../partials/header.php");
$districts = ['Arghakhanchi', 'Baglung', 'Baitadi', 'Bajang', 'Banke', 'Bara', 'Bardiya', 'Bhaktapur', 'Chitawan', 'Dadeldhura', 'Dailekh', 'Dang', 'Darchula', 'Dhading', 'Dhankuta', 'Dhanusa', 'Dolkha', 'Dolpa', 'Doti', 'Gorkha', 'Gulmi', 'Humla', 'Ilam', 'Jhapa', 'Jumla', 'Kabhre', 'Kailali', 'Kanchanpur', 'Kaski', 'Kathmandu', 'Lalitpur', 'Lamjung', 'Mahottari', 'Makwanpur', 'Manang', 'Morang', 'Mugu', 'Mustang', 'Myagdi', 'Nawalparasi', 'Nuwakot', 'Okhaldhunga', 'Palpa', 'Panchther', 'Parbat', 'Rasuwa', 'Routahat', 'Rukum', 'Rupandehi', 'Salyan', 'Sankhuwasabha', 'Saptari', 'Sarlahi', 'Sindhuli', 'Solukhumbu', 'Sunsari', 'Surkhet', 'Syangja', 'Tanahun', 'Taplejung', 'Terhathum', 'Udayapur'];

?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>

    <div class="container-fluid my-2">
        <div class="row">
            <div class="col-md-7">

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
                        <span class="btn btn-warning" onclick="predictCrop()">Recomend</span>
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

        function predictCrop() {
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
                    console.log(response);
                    if (xhr.status == 200) {
                        showError(response.crops);
                    } else {
                        alert(response.error);
                    }
                },
                error: function(err) {
                    console.log(err);
                    showError('Something went wrong');
                }
            });

        }
    </script>

</body>

</html>