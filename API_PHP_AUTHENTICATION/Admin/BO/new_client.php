<?php defined('ROOT') or die('Acesso invalido'); ?>

<?php require_once('BO/navegacao.php');?>

<div class="container">
    <div class="row mt-5">
        <div class="col-sm-6 offset-sm-3">
            <h3 class="text-center">Novo cliente</h3>
            <hr>
            <form class="form">
                <div class="mb-3">
                    <label class="label">Digite o nome do cliente</label>
                    <input class="form-control" type="text" placeholder="nome" name="text_cliente">
                </div>

                <div class="mb-3">
                    <label>Digite o username do cliente</label>
                    <input class="form-control" type="text" placeholder="usuario" name="text_usuario">
                </div>

                <div class="mb-3">
                    <label>Digite a senha do cliente</label>
                    <input class="form-control"type="text" placeholder="senha" name="text_senha">
                </div>
                
                
                <div class="text-center">
                    <input type="submit" class="btn btn-success btn-lg">
                </div>
                
            </form>
            
        </div>
    </div>
</div>
