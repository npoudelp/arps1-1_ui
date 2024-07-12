<?php
$base_url = "http://127.0.0.1:8000/";
echo '';
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    <link rel="stylesheet" href="/css/main.css">

    <script>
        // if (!sessionStorage.getItem("user_logged")) {
        //     $.ajax({
        //         url: 'http://127.0.0.1/partials/destroy_session.php',
        //         type: 'post',
        //         data: {
        //             'logged': false
        //         },
        //         success: function(response) {
        //             // localStorage.clear();
        //         }
        //     });
        // } else {
        //     $.ajax({
        //         url: 'http://127.0.0.1/partials/set_session.php',
        //         type: 'post',
        //         data: {
        //             'logged': true
        //         },
        //         success: function(response) {
        //             // localStorage.clear();
        //         }
        //     });
        // }
    </script>
</head>
<?php
?>