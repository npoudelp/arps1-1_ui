<?php
if ($_POST['logged'] == true) {
    session_start();
    $_SESSION['logged'] = true;

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if($_REQUEST['logged'] == true){
    session_start();
    $_SESSION['logged'] = true;
    header('Location: http://127.0.0.1/pages/');
}