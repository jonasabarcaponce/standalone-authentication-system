<?php 

// Constructor de sitios web Ponce 1.0.0 > Activar 
include('core/set.php');
include('core/core.php');

// Obtenemos modelos de datos a utilizar en este controlador
$Usuarios = new Usuarios();
$Pruebas = new Pruebas();

// Composer autoloader, PHPMailer , TWIG y Librerías complementarias  
require_once './vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./views/cuenta');
$twig = new \Twig\Environment($loader);

// Comienza lógica del controlador > Activar
session_start(); 

$is_processing_data = isset($_GET['user-access']) ? true : false; 

if($is_processing_data) {

    // Información a ser procesada: Prueba de activación 
    
    $prueba = $_GET['user-access'];

    $Prueba = $Pruebas->obtener_informacion($prueba); 

    $verify_data_processed = $Prueba > 1 ? true : false; 

}

// Controlador > Activar es válido independientemente el estaddo de la sesión.

switch($is_processing_data) {
    case true : 

        if($verify_data_processed) {

            $prueba_tipo = $Prueba['tipo']; # 1 para activar, 2 para cambiar contraseña.
            $prueba_estado = $Prueba['activo']; # 0 para expirada, 1 para activa. 
            $prueba_usuario = $Prueba['usuario']; # Obtenemos al usuario para actualizarlo. 
            $usuario_existe = $Usuarios->validar('existencia-identificador',$prueba_usuario,'',''); # True para si existe, false para no existe.

            $is_validated_proof = $prueba_tipo == 1 && $prueba_estado == 1 && $usuario_existe == true ? true : false; 

            if($is_validated_proof) {

                // Procedemos a activar el usuario y expirar la prueba.

                $Usuarios->activar($prueba_usuario);
                $Pruebas->actualizar($prueba,0);

                header('Location: ' . APP_URL . '/inicio?=msg=success-activated');

            } 

            else {
                
                // La prueba ya no está activa, o no es para activar la cuenta.

                header('Location: ' . APP_URL . '/inicio?=error-proof=expired');

            }

        }

        else {
            
            header('Location: ' . APP_URL . '/inicio?=error-proof-not-exist');

        }                
    
    break;
    case false : 

        header('Location: ' . APP_URL . '/inicio');  

    break; 
}