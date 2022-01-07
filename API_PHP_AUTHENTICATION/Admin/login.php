<?PHP
//verificando se a constante root existe
defined('ROOT') or die('Acesso invalido');

//se não existir sessão ela sera iniciada
if (session_status() === PHP_SESSION_NONE) session_start();

?>

<div class="container">
    <div class="row">
        <div class="col-sm-4 offset-sm-4 card bg-light mt-4 ">

            <div class="col-sm-4 offset-sm-4">

                <form action="index.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Usuário</label>
                        <input type="text" name="text_usuario" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="text_senha" class="form-control">
                    </div>

                    <div class="text-center mb-2">
                        <input type="submit" value="Entrar" class="btn btn-primary">
                    </div>

                </form>

                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="row">
                        <div class="col">
                            <div class="text-center alert alert-danger mt-2">
                                <p> <?= $_SESSION['error']; ?> </p>
                                <?php unset($_SESSION['error']); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>



    </div>
</div>