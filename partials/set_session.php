<?php
if ($_POST['logged'] == true) {
    session_start();
    $_SESSION['logged'] = true;

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
