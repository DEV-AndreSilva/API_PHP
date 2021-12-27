<?php
//dependencias
require_once 'inc/config.php';
require_once 'inc/api_functions.php';


$results = api_request('get_all_clients','GET', ['only_active'=>true]);

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App consumidora</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    
    <?php include_once('inc/views/nav.php');?>

<script src="assets/bootstrap/bootstrap.bundle.min.js"> </script>
</body>
</html>