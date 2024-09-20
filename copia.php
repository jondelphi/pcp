
<?php
session_start();
require('DAO/config.php');

require('vendor/autoload.php');

/* $client = new GearmanClient();
$client->addServer();
print $client->do("reverse", "Hello World!"); */

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="content/icone.png">
    <title><?php echo ($_SESSION['pcpAPI']['titulo']) ?></title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap-icons.css">
   
   
   
</head>
<body>
   <?php 
   $loop = React\EventLoop\Factory::create();
   echo "inicio<br>";
   $loop->addTimer(0,function() use ($loop){
    sleep(3);
    echo "meio<br";
   });

   $loop->run();
   echo "fim<br>";
   ?>
   

    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>


    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
    </script>
   
</body>
</html>