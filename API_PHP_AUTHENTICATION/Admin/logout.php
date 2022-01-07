<?php
if(session_status()===PHP_SESSION_NONE) session_start();

unset($_SESSION['id_admin']);
unset($_SESSION['usuario']);

header('Location: index.php');
exit;