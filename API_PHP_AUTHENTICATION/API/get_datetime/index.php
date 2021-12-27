<?php
$now = new DateTime();

echo json_encode([
    'status'=>"Success",
    'message'=>$now->format('d-m-Y H:i:s')
]);