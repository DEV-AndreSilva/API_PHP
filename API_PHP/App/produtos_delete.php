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

        //validando se o produto que será excluido existe 
        if(!empty($result['data']['results']))
        {
            //recebendo as informações do produto que será excluido
            $product = $result['data']['results'][0];
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
    //Verificando se foi passado para a pagina o parametro id_produto     
    if(isset($_POST['id_produto']))
    {
        $data = ['id' => $_POST['id_produto'],'hard_delete'=>'false'];

        //chamando o método de deleção da API
        $results = api_request('delete_product','POST', $data);

       //var_dump( filter_var('true',FILTER_VALIDATE_BOOLEAN));
       //var_dump( filter_var('false',FILTER_VALIDATE_BOOLEAN));
       //die;

        //Redirecionando o usuário para lista de  produto 
        header('Location: produtos.php');
        exit;
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App consumidora - Deletar Produto</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
</head>
<body>
    
    <?php include_once('inc/views/nav.php');?>

    <section class="container">
    <div class="row my-5">
            <div class="col-sm-6 offset-sm-3 card bg-light p-4">
            
                <form method="POST" action="produtos_delete.php">

                
                    <div class="mb-3 text-center">
                        <h3>Deletar Produto</h3>
                    </div>

                    <div class="mb-3">
                        <label> <b> Nome do produto:</b></label>
                        <label><?= $product['produto'] ?></label>
                    </div>

                    <div class="mb-3">
                        <label><b>Quantidade em estoque:</b></label>
                        <label><?= $product['quantidade'] ?></label>
                    </div>

                    <input type="hidden" name="id_produto" value=<?php echo $product['id_produto'] ?>>

                    <div class="mb-3 text-center">
                        <a href="produtos.php" class="btn btn-success btn-md">Cancelar</a>   
                        <input type="submit" name="Deletar" value="Confirmar" class="btn btn-danger btn-md ">   
                    </div>
            
                </form>
            </div>
          
        </div>
    </section>

<script src="assets/bootstrap/bootstrap.bundle.min.js"> </script>
</body>
</html>