<?php
//dependencias
require_once 'inc/config.php';
require_once 'inc/api_functions.php';
require_once 'inc/functions.php';

//Requisição das informações a API
$results = api_request('get_all_clients','GET',['only_active'=>true]);

//Verificação do retorno dessa informações
if($results['data']['status'] == 'Success')
{
    $clients = $results['data']['results'];
}
else{
    $clients = [];
}



//printData($clients);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App consumidora - Clientes</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    
    <?php include_once('inc/views/nav.php');?>

    <section class="container">
        <div class="row">
            <div class="col">

            <div class="row">
                <div class="col">
                    <h1>Clientes</h1>
                </div>
                <div class="col text-end align-self-center">
                    <a href="clientes_novo.php" class="btn btn-dark btn-md">Adicionar cliente...</a>
                </div>
            </div>
                
                <hr>
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width='30%' class="text-center">Nome</th>
                            <th width='25%' class="text-center">E-mail</th>
                            <th width='15%' class="text-center">Telefone</th>
                            <th width='30%' class="text-center" colspan="2"> Ações</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($clients)==0):?>

                            <tr>
                                <td class="text-center">Nome do cliente</td>
                                <td class="text-center">Email do cliente</td>
                                <td class="text-center">Telefone do cliente</td>
                                <td></td>
                                <td></td>
                            </tr>

                        <?php else:?>

                            <?php foreach($clients as $client): ?>

                                <tr class="align-middle" >
                                    <td class="text-center "><?=$client['nome']; ?></td>
                                    <td class="text-center"><?=$client['email']; ?></td>
                                    <td class="text-center"><?=$client['telefone']; ?></td>
                                    <td class="text-center"> 
                                        <a href="clientes_delete.php?id=<?=$client['id_cliente']?>" class="btn btn-danger btn-md">&#128465; Delete</a>
                                    </td>
                                    <td class="text-center"> 
                                    <a href="clientes_update.php?id=<?=$client['id_cliente']?>" class="btn btn-warning btn-md"> &#9998; Update</a>
                                    </td>
                                </tr>

                            <?php endforeach;?>
                            
                        
                        <?php endif;?>
                        
                    </tbody>
                </table>
                <p>Total: <strong><?= count($clients) ?></strong></p>
            </div>
        </div>

    </section>

<script src="assets/bootstrap/bootstrap.bundle.min.js"> </script>
</body>
</html>