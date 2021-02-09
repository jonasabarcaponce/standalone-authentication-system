<?php 

// Constructor de sitios web Ponce 1.0.0 > Salir 
include('core/set.php');

session_start();

session_destroy(); 

header('Location: ' . APP_URL . '/entrar'); 
