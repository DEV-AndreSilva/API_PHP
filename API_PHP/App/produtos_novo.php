<?php
//dependencias
require_once 'inc/config.php';
require_once 'inc/api_functions.php';
require_once 'inc/functions.php';

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $produto = $_POST['text_produto'];
    $quantidade = $_POST['text_quantidade'];
   
    $data = [
        'produto'=>$produto,
        'quantidade'=>$quantidade
    ];

    $results = api_request('create_product','POST', $data);

    //apresentação de mensagem ao usuário

    if($results['data']['status'] == 'Error')
    {
        $error_message = $results['data']['message'];
    }
    else if($results['data']['status']='Success')
    {
        $success_message = $results['data']['message'];
    }

}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App consumidora - Novo Produto</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    <?php include_once('inc/views/nav.php');?>

    <section class="container">
        <div class="row my-5">
            <div class="col-sm-6 offset-sm-3 card bg-light p-4">
            
                <form action="produtos_novo.php" method="POST">
                    <div class="mb-3 text-center">
                        <h3>Formulário - Cadastro de Produto</h3>
                    </div>

                    <div class="mb-3">
                        <label>Nome do produto:</label>
                        <input type="text" name="text_produto" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Quantidade em estoque:</label>
                        <input type="text" name="text_quantidade" class="form-control">
                    </div>

                    <div class="mb-3 text-center">
                        <input type="submit" name="Enviar" class="btn btn-primary btn-md ">   
                        <a href="produtos.php" class="btn btn-secondary btn-md">Cancelar</a>   
                    </div>
                </form>
                
                <?php if(isset($error_message)):  ?>

                    <div class="alert alert-danger p-2 text-center">
                        <?=$error_message;?>
                    </div>
                    <?php unset($error_message)?>

                <?php endif;?>

                <?php if(isset($success_message)):?>

                    <div class="alert alert-success p-2 text-center">
                        <?=$success_message;?>
                    </div>
                    <?php unset($success_message)?>

                <?php endif;?>
                
            </div>
        </div>
    </section>

<script src="assets/bootstrap/bootstrap.bundle.min.js"> </script>
</body>
</html>