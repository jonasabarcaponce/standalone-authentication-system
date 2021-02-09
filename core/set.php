<?php 

// Quire Pay 

// Switch de Producción: use true para producción, use false para desarrollo. 

$production_switch = false; 
$production_tracking_services = true; 

if ($production_switch) {

    // Definimos la URL principal y el nombre del sitio web 
    define('APP_URL','https://yourappurl.com');
    define('APP_NAME','Your app name');

    // Apagamos la capacidad de mostrar errores
    ini_set('display_errors', false);
    ini_set('display_startup_errors', false);

    error_reporting(0);
    
} 

else {
    
    // Definimos la URL principal y el nombre del sitio web 
    define('APP_URL','http://localhost/your-app-url');
    define('APP_NAME','Your app name (Developer mode)');

    // Encendemos la capacidad de mostrar errores
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);

    error_reporting(E_ALL);

}

// Configuramos la duración de la sesión.
$session_duration = 86400 * 30;
ini_set('session.cookie_lifetime', $session_duration);

// Seleccionamos la zona horaria a utilizar
date_default_timezone_set('America/Mexico_City');

// Definimos algunos patrones
define('USER_PATTERN','/^(\w){5,15}$/');
define('PASS_PATTERN','/^(?=.*\d)(?=.*[a-z])(?=.*[a-zA-Z]).{8,}$/');

define('APP_EMAIL','youremailaddress@domain.com');
define('APP_EMAIL_USER','youremailaddress@domain.com');
define('APP_EMAIL_PASSWORD','youremailpassword');
define('APP_EMAIL_SERVER','your.emailserver.com');