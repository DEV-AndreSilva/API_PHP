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
        $result = api_request('get_client','GET', $params);

        //validando se o cliente que será alterado existe 
        if(!empty($result['data']['results']))
        {
            //recebendo as informações do cliente que será alterado
            $client = $result['data']['results'][0];

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
            header('Location: clientes.php');
            exit;        
        }
    }
    else
    {
        header('Location: clientes.php');
        exit;
    }
}

//verificando se forma de requisição a pagina é POST
if($_SERVER['REQUEST_METHOD']=='POST')
{
    $validar = isset($_POST['id_cliente']) && isset($_POST['text_name'])
    && isset($_POST['text_email']) && isset($_POST['text_telefone']);

    //Verificando se foi passado para a pagina os parâmetros necessários
    if($validar)
    {
        $id = $_POST['id_cliente'];
        $nome = $_POST['text_name'];
        $email = $_POST['text_email'];
        $telefone = $_POST['text_telefone'];
    
        $data = [
            'id'=>$id,
            'nome'=>$nome,
            'email'=>$email,
            'telefone'=>$telefone
        ];
        
        //printData($data);

        $results = api_request('update_client','POST', $data);
        //printData($results);
        
        $status = $results['data']['status'];
        $message = $results['data']['message'];
      
       
        header("Location: clientes_update.php?id=$id&status=$status&message=$message");
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
    <title>App consumidora - Editar Cliente</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    <?php include_once('inc/views/nav.php');?>

    <section class="container">
        <div class="row my-5">
            <div class="col-sm-6 offset-sm-3 card bg-light p-4">
            
                <form action="clientes_update.php" method="POST">
                    <div class="mb-3 text-center">
                        <h3>Formulário - Atualização de Cliente</h3>
                    </div>

                    <input type="hidden" name="id_cliente" class="form-control" value="<?= $client['id_cliente'] ?>">

                    <div class="mb-3">
                        <label>Nome do cliente:</label>
                        <input type="text" name="text_name" class="form-control" value="<?= $client['nome'] ?>">
                    </div>

                    <div class="mb-3">
                        <label>Telefone:</label>
                        <input type="text" name="text_telefone" class="form-control" value="<?= $client['telefone'] ?>">
                    </div>

                    <div class="mb-3">
                        <label>Email:</label>
                        <input type="email" name="text_email" class="form-control" value="<?= $client['email'] ?>">
                    </div>

                    <div class="mb-3 text-center">
                        <input type="submit" name="Atualizar" value="Atualizar" class="btn btn-primary btn-md ">   
                        <a href="clientes.php" class="btn btn-secondary btn-md">Cancelar</a>   
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