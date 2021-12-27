<?php
//incluir as dependencias
require_once '../inc/authentication.php';
//analisar o pedido

//emitir a resposta

echo json_encode([
    'status'=>"Success",
    'message'=>"Welcome to the API Statuss"
]);