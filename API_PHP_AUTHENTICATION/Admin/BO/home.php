<?php
defined('ROOT') or die('Acesso invalido');
require_once('BO/navegacao.php');


//receber os dados dos clientes da API
$bd = new database();
$clientes_API = $bd->EXE_QUERY('SELECT * FROM authentication');

?>

<div class="container mt-5">
    <div class="row">
        <div class="col">

            <div class="row">
                <div class="col-sm-6">
                    <h3>Clientes API</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="index.php?r=new_client" class="btn btn-primary btn-sm">+ Cliente</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <table class="table">
                        <thead class="table-dark">
                            <tr>
                                <th>Cliente</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes_API as $cliente) : ?>
                                <tr>
                                    <td> <?= $cliente['client_name']; ?></td>
                                    <td> <?= $cliente['username']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>