<?php
session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="logo_arps" href="<?php if ($_SESSION['logged']) { ?>
                                            /pages/index.php
                                        <?php
                                    } else {
                                        ?>
                                        /<?php
                                        } ?>
                                        ">ARPS1-1</a>
    <span class="text-light d-lg-none" id="weather"></span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mx-5" id="navbarNav">
        <ul class="navbar-nav">

            <?php
            if ($_SESSION['logged']) {
            ?>
                <li class="nav-item mx-3">
                    <a class="nav-link <?php echo $dashboard; ?>" href="/pages/dashboard.php">Dashboard</a>
                </li>
                <!-- <li class="nav-item mx-3">
                    <a class="nav-link" href="/pages/filed_management.php">Add Field(Older version)</a>
                </li> -->
                <li class="nav-item mx-3">
                    <a class="nav-link <?php echo $assistance; ?>" href="/pages/assistance.php">Get Assistance</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link <?php echo $weather; ?>" href="/pages/weather.php">Weather</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link btn btn-outline-danger" onclick="userLogout()">Logout</a>
                </li>
            <?php
            } else {
            ?>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="#home">Home</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="#service">Service</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link btn btn-outline-success" href="/pages/login.php">Login</a>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
    <span class="text-light d-sm-none d-md-none" id="weather1"></span>
</nav>
<div class="alert alert-dismissible alert-info text-center" style="display: none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="lead" id="alert_diaplay"></span>
</div>




<script>
    $(document).ready(function() {
        let station = localStorage.getItem("location");
        $.ajax({
            url: 'http://127.0.0.1:8000/api/scrape/' + station + '/',
            type: 'get',
            success: function(response, status, xhr) {
                $.each(response, (i, data) => {
                    min = parseInt(data[1]);
                    max = parseInt(data[2]);
                    avg = (min + max) / 2;
                    $('#weather').html(`${station}: ${avg}°C`);
                    $('#weather1').html(`${station}: ${avg}°C`).addClass('d-none d-lg-block');
                });
            }
        });
    });


    userLogout = () => {
        if (window.confirm("Are you sure you want to logout?")) {
            let location = localStorage.getItem("location");
            sessionStorage.clear();
            localStorage.clear();
            localStorage.setItem("location", location);
            $.ajax({
                url: 'http://127.0.0.1/partials/destroy_session.php',
                type: 'post',
                data: {
                    'logged': false
                },
                success: function(response) {
                    window.location.href = "/";
                }
            });

        }
    }
</script>