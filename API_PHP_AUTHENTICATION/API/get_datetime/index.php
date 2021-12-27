<?php

//incluir as dependencias
require_once '../inc/authentication.php';


$now = new DateTime();


echo json_encode([
    'status'=>"Success",
    'message'=>$now->format('d-m-Y H:i:s'),
    'data'=>[$_GET]
]    
);

die;