<?php 

// Constructor de sitios web Ponce 1.0.0 > Recuperar 
include('core/set.php');
include('core/core.php');

// Obtenemos modelos de datos a utilizar en este controlador
$Usuarios = new Usuarios();
$Pruebas = new Pruebas();

// Composer autoloader, PHPMailer , TWIG y Librerías complementarias  
require_once './vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./views/cuenta');
$twig = new \Twig\Environment($loader);

// Comienza lógica del controlador > Recuperar
session_start(); 

$is_logged_in = isset($_SESSION['user-session']) ? true : false;
$is_processing_data = isset($_GET['user-access']) ? true : false; 

if($is_processing_data) {

    // Información a ser procesada: Prueba de activación 
    
    $prueba = $_GET['user-access'];

    $Prueba = $Pruebas->obtener_informacion($prueba); 

    $verify_data_processed = $Prueba > 1 ? true : false; 

}

switch($is_logged_in) {
    case true : 

        header('Location: ' . APP_URL . '/inicio'); 

    break;
    case false : 

        switch($is_processing_data) {
            case true : 

                if($verify_data_processed) {

                    $prueba_tipo = $Prueba['tipo']; # 1 para activar, 2 para cambiar contraseña.
                    $prueba_estado = $Prueba['activo']; # 0 para expirada, 1 para activa. 
                    $prueba_usuario = $Prueba['usuario']; # Obtenemos al usuario para actualizarlo. 

                    $is_validated_proof = $prueba_tipo == 2 && $prueba_estado == 1 ? true : false; 

                    if($is_validated_proof) {

                        // Procedemos a restablecer la contraseña y expirar la prueba.

                    } 

                    else {
                        
                        // La prueba ya no está activa, o no es para restablecer la contraseña.

                    }

                }

                else {
                    
                    // La prueba no existe.

                }                
            
            break;
            case false : 

                // Renderizamos la página de > Recuperar 

                $params = array(
                    'app_name' => APP_NAME,
                    'app_url' => APP_URL
                );
                
                echo $twig->render('cuenta-recuperar.html',$params);     

            break; 
        }

    break; 
}