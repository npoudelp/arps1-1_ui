<?php
if ($_POST['logged']) {
    session_start();
    $_SESSION['logged'] = false;
    
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}