<?php 

// Constructor de sitios web Ponce 1.0.0 > Registro 
include('core/set.php');
include('core/core.php');

// Obtenemos modelos de datos a utilizar en este controlador
$Usuarios = new Usuarios();

// Composer autoloader, PHPMailer , TWIG y Librerías complementarias  
require_once './vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./views/cuenta');
$twig = new \Twig\Environment($loader);

// Comienza lógica del controlador > Registro
session_start();

$is_logged_in = isset($_SESSION['user-session']) ? true : false;
$is_processing_data = isset($_POST['user-identification']) ? true : false; 

if($is_processing_data) {

    // Información a ser procesada: registro del usuario

    $whatsapp = $_POST['user-identification-whatsapp'];
    $password = $_POST['user-identification-password'];

    $validate_whatsapp = isset($whatsapp) && preg_match(USER_PATTERN,$whatsapp) === 1 ? true : false;
    $validate_password = isset($password) && preg_match(PASS_PATTERN,$password) === 1 ? true : false;

    $verify_data_processed = $validate_whatsapp === true && $validate_password === true ? true : false; 

}

switch($is_logged_in) {
    case true : 

        header('Location: ' . APP_URL . '/inicio'); 

    break;
    case false : 

        switch($is_processing_data) {
            case true : 

                // Procesar la información recibida para el registro del usuario
                $password = md5($password);  

                // Creamos los identificadores únicos
                $identificador = md5($whatsapp . '~' . uniqid()); 

                if($verify_data_processed) {

                    // Registramos al usuario
                    $registrar_usuario = $Usuarios->crear($identificador,$whatsapp,$password,2);

                }

                if($registrar_usuario) {

                    $_SESSION['user-session'] = $whatsapp;
                    header('Location: ' . APP_URL . '/primeros-pasos');

                } 

                else {

                    // Como el registro fue incorrecto, redirigimos y mostramos el error.
                    header('Location: ' . APP_URL . '/registrate?msg=warning-invalid-register');

                }
            
            break;
            case false : 

                // Renderizamos la página de > Registro 

                $params = array(
                    'app_name' => APP_NAME,
                    'app_url' => APP_URL
                );
                
                echo $twig->render('cuenta-registro.html',$params);

            break; 
        }

    break; 
}