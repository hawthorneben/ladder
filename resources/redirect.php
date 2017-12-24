<?php
    session_start();

    if (!isset($_SESSION['username']))
    {
        echo "<script>window.location='../index?redirect=true';</script>";

        exit();
    }
?>