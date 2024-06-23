<?php
session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand mx-5 text-warning" href="<?php if ($_SESSION['logged']) { ?>
                                            /pages/index.php
                                        <?php
                                                    } else {
                                        ?>
                                        /<?php
                                                    } ?>
                                        ">ARPS1-1</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mx-5" id="navbarNav">
        <ul class="navbar-nav">

            <li class="nav-item mx-3">
                <a class="nav-link" href="#">Home</a>
            </li>
            <?php
            if ($_SESSION['logged']) {
            ?>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="/pages/filed_management.php">Manage Field</a>
                </li><?php
                    }
                        ?>
            <li class="nav-item mx-3">
                <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item mx-3">
                <a class="nav-link" href="#">Services</a>
            </li>
            <li class="nav-item mx-3">
                <a class="nav-link" href="#">Contact</a>
            </li>
            <?php
            if ($_SESSION['logged']) {
            ?>
                <li class="nav-item mx-3">
                    <a class="nav-link btn btn-outline-danger" onclick="userLogout()">Logout</a>
                </li><?php
                    } else {
                        ?>
                <li class="nav-item mx-3">
                    <a class="nav-link btn btn-outline-warning" href="/pages/login.php">Login</a>
                </li>
            <?php
                    }
            ?>

        </ul>
    </div>
</nav>

<div class="alert alert-dismissible alert-info text-center" style="display: none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span class="lead" id="alert_diaplay"></span>
</div>


<script>
    userLogout = () => {
        sessionStorage.clear();
        localStorage.clear();
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
</script>