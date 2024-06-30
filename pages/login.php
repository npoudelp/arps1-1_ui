<?php
include_once("../partials/header.php");
?>

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>
    <div class="container">
        <div class="row my-5">
            <div class="col-md-6">
                <!-- <form onsubmit="userLogin()"> -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input require type="text" autofocus class="form-control" id="username" placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input require type="password" class="form-control" id="password" placeholder="Enter password">
                </div>
                <button type="button" onclick="userLogin()" class="btn btn-outline-warning">Login</button>
                <!-- </form> -->
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>

    <script>
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
                        window.location.href = "/pages/index.php";
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