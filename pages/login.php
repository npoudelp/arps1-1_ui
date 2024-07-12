<?php
include_once("../partials/header.php");
?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <!-- <form onsubmit="userLogin()"> -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input require type="text" autofocus class="form-control" id="username" placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group mb-3">
                        <input type="password" id="password" class="form-control" placeholder="Enter password">
                        <div class="input-group-append">
                            <span class="input-group-text bg-success" id="showPassword"><i class="bi bi-eye-slash-fill"></i></span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="userLogin()" class="btn btn-outline-success">Login</button>
                <!-- </form> -->
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
    <?php
    include_once("../partials/footer.php");
    ?>
    <script>
        let mouseDown = false;
        $("#showPassword").mousedown(function() {
            $("#password").attr('type', 'text');
            $("#showPassword i").removeClass('bi-eye-slash-fill');
            $("#showPassword i").addClass('bi-eye-fill');
            mouseDown = true;
        }).mouseup(function() {
            if (mouseDown) {
                $("#password").attr('type', 'password');
                $("#showPassword i").removeClass('bi-eye-fill');
                $("#showPassword i").addClass('bi-eye-slash-fill');
                mouseDown = false;
            }
        }).mouseleave(function() {
            if (mouseDown) {
                $("#password").attr('type', 'password');
                $("#showPassword i").removeClass('bi-eye-fill');
                $("#showPassword i").addClass('bi-eye-slash-fill');
                mouseDown = false;
            }
        });


        userLogin = () => {
            let base_url = "http://127.0.0.1:8000/";
            event.preventDefault();
            let username = $("#username").val();
            let password = $("#password").val();

            if (username == "" || password == "") {
                showError("Please fill all the fields");
                return;
            }

            let data = {
                username: username,
                password: password
            }
            $.ajax({
                url: base_url + "api/token/",
                type: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                data: JSON.stringify(data),
                success: function(response, statusText, xhr) {
                    if (xhr.status == 200) {
                        localStorage.setItem("access_token", response.access);
                        localStorage.setItem("refresh_token", response.refresh);
                        window.location.href = "/partials/set_session.php?logged=true";
                    }
                }
            }).fail(function(response) {
                // invaild username or password
                $("#password").val("");
                showError("Invalid username or password");
            });
        }

        $("#username, #password").keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
                userLogin();

            }
        });
    </script>

</body>

</html>