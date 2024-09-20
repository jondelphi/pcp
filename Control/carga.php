<?php
session_start();
$_SESSION['pcpAPI']['pagina'] = 'View/carga.php';
$_SESSION['pcpAPI']['titulo'] = 'Braslar- PCP';
if ($_POST) {
    if ($_POST['diainicial'] < $_POST['diafinal']) {
        $_SESSION['pcpAPI']['data'] = true;
        $_SESSION['pcpAPI']['dia1'] = $_POST['diainicial'];
        $_SESSION['pcpAPI']['dia2'] = $_POST['diafinal'];
    }
}

header('location:../index.php');
