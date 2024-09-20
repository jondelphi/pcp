<?php
session_start();
unset($_SESSION['pcpAPI']);
header('location:../index.php');
?>