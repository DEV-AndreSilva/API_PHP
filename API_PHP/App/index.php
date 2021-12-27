<?php
//dependencias
require_once 'inc/config.php';
require_once 'inc/api_functions.php';
require_once 'inc/functions.php';

$results = api_request('get_totals', 'GET');

$results = $results['data']['results'];

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

    <?php include_once('inc/views/nav.php'); ?>

    <div class="container">

        <div class="row mt-5">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total de clientes ativos</h5>
                        <p class="card-text"><?= $results[0]['Total'] ?></p>
                        <a href="clientes.php" class="btn btn-primary">Clientes</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total de produtos ativos</h5>
                        <p class="card-text"><?= $results[1]['Total'] ?></p>
                        <a href="produtos.php" class="btn btn-primary">Produtos</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total de clientes inativos</h5>
                        <p class="card-text"><?= $results[2]['Total'] ?></p>
                        <a href="clientes.php" class="btn btn-primary">Clientes</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total de produtos inativos</h5>
                        <p class="card-text"><?= $results[3]['Total'] ?></p>
                        <a href="produtos.php" class="btn btn-primary">Produtos</a>
                    </div>
                </div>
            </div>
        </div>





    </div>

    <script src="assets/bootstrap/bootstrap.bundle.min.js"> </script>
</body>

</html>