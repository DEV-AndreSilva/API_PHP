<?php
//dependencias
require_once 'inc/config.php';
require_once 'inc/api_functions.php';
require_once 'inc/functions.php';

//Requisição das informações na API
$results = api_request('get_all_products','GET',['only_active'=>true]);

//Verificando resultado para apresentação ao usuário
if($results['data']['status'] == 'Success')
{
    $products = $results['data']['results'];
}
else{
    $products = [];
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App consumidora - Produtos</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    
    <?php include_once('inc/views/nav.php');?>

    <section class="container">
        <div class="row">
            <div class="col">
            <div class="row">
                <div class="col">
                    <h1>Produtos</h1>
                </div>
                <div class="col text-end align-self-center">
                    <a href="produtos_novo.php" class="btn btn-dark btn-md">Adicionar produto...</a>
                </div>
            </div>
                <hr>
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width='40%' class="text-center">Nome</th>
                            <th width='30%' class="text-center">Quantidade</th>
                            <th width='30%' class="text-center" colspan="2"> Ações</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($products)==0):?>

                            <tr>
                                <td>Nome do produto</td>
                                <td>Quantidade do produto</td>
                            </tr>

                        <?php else:?>

                            <?php foreach($products as $product): ?>

                                <tr class="align-middle">
                                    <td class="text-center"><?=$product['produto']; ?></td>
                                    <td class="text-center"><?=$product['quantidade']; ?></td>
                                    <td class="text-center"> 
                                        <a href="produtos_delete.php?id=<?=$product['id_produto']?>" class="btn btn-danger">&#128465; Delete</a>
                                    </td>
                                    <td class="text-center"> 
                                    <a href="produtos_update.php?id=<?=$product['id_produto']?>" class="btn btn-warning">&#9998; Update</a>
                                    </td>
                                </tr>

                            <?php endforeach;?>

                       
                        <?php endif;?>
                        
                    </tbody>
                </table>
                <p>Total: <strong><?= count($products) ?></strong></p>
            </div>
        </div>

    </section>

<script src="assets/bootstrap/bootstrap.bundle.min.js"> </script>
</body>
</html>