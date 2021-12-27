<?php
//dependencias
require_once 'inc/config.php';
require_once 'inc/api_functions.php';
require_once 'inc/functions.php';


//verificando se forma de requisição a pagina é GET
if($_SERVER['REQUEST_METHOD']=='GET')
{
    //validando se for passado um id para pagina
    if(isset($_GET['id']))
    {
        $params = ['id'=>$_GET['id']];
        $result = api_request('get_product','GET', $params);

        //validando se o produto que será alterado existe 
        if(!empty($result['data']['results']))
        {
            //recebendo as informações do produto que será alterado
            $produtc = $result['data']['results'][0];

            if(isset($_GET['status']) && isset($_GET['message']))
            {
                $status = $_GET['status'];
                $message = $_GET['message'];

                if($status=='Error')
                {
                    $error_message = $message;
                }
                else if($status=='Success')
                {
                    $success_message = $message;   
                }
            }
           
        }
        else
        {
            header('Location: produtos.php');
            exit;        
        }
    }
    else
    {
        header('Location: produtos.php');
        exit;
    }
}

//verificando se forma de requisição a pagina é POST
if($_SERVER['REQUEST_METHOD']=='POST')
{
    $validar = isset($_POST['id_produto']) && isset($_POST['text_product'])
    && isset($_POST['text_quantidade']);

    //Verificando se foi passado para a pagina o parametro id_produto
    if($validar)
    {
        $id = $_POST['id_produto'];
        $produto = $_POST['text_product'];
        $quantidade = $_POST['text_quantidade'];
    
        $data = [
            'id'=>$id,
            'produto'=>$produto,
            'quantidade'=>$quantidade
        ];
        
        $results = api_request('update_product','POST', $data);
      
        $status = $results['data']['status'];
        $message = $results['data']['message'];
      
       
        header("Location: produtos_update.php?id=$id&status=$status&message=$message");
        exit;
    }
    else
    {
        $error_message = 'Falta informações ';
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App consumidora - Editar Produto</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    <?php include_once('inc/views/nav.php');?>

    <section class="container">
        <div class="row my-5">
            <div class="col-sm-6 offset-sm-3 card bg-light p-4">
            
                <form action="produtos_update.php" method="POST">
                    <div class="mb-3 text-center">
                        <h3>Formulário - Atualização de Produto</h3>
                    </div>

                    <input type="hidden" name="id_produto" class="form-control" value="<?= $produtc['id_produto'] ?>">

                    <div class="mb-3">
                        <label>Nome do Produto:</label>
                        <input type="text" name="text_product" class="form-control" value="<?= $produtc['produto'] ?>">
                    </div>

                    <div class="mb-3">
                        <label>Quantidade:</label>
                        <input type="text" name="text_quantidade" class="form-control" value="<?= $produtc['quantidade'] ?>">
                    </div>

                    <div class="mb-3 text-center">
                        <input type="submit" name="Atualizar" value="Atualizar" class="btn btn-primary btn-md ">   
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