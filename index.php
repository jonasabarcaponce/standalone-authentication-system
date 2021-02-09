<?php 

// Constructor de sitios web Ponce 1.0.0 > Inicio 
include('core/set.php');
include('core/core.php');

// Obtenemos modelos de datos a utilizar en este controlador
$Usuarios = new Usuarios();
$Ajustes = new Ajustes();

// Composer autoloader, PHPMailer , TWIG y Librerías complementarias  
require_once './vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./views');
$twig = new \Twig\Environment($loader);

// Comienza lógica del controlador > Inicio
session_start(); 

$is_logged_in = isset($_SESSION['user-session']) ? true : false;

$is_login_validated = $Usuarios->validar('sesion-actual',$_SESSION['user-session'],'','');

$is_processing_data = isset($_POST['user-data-post']) ? true : false; 


switch($is_logged_in) {
    case true : 

        if($is_login_validated) {

            // Obtenemos la información del usuario 
            $user_data = $Usuarios->obtener_informacion($_SESSION['user-session']);
            
            $usuario_identificador = $user_data['identificador'];
            $usuario_whatsapp = $user_data['whatsapp'];
            $usuario_correo = $user_data['correo'];
            $usuario_estado = $user_data['estado'];

            $usuario_nombre = explode(' ',trim($user_data['nombre']));
            $usuario_nombre = $usuario_nombre[0];

            // Obtenemos la información del negocio
            $business_data = $Ajustes->obtener_informacion($usuario_identificador);

            // Verificamos que el usuario ya esté completo y verificado 
            if($usuario_estado == 0 ) {

                // Guardamos una cookie con el nombre del usuario.
                setcookie('user_name', $usuario_nombre, time() + (86400 * 30), "/"); // 86400 = 1 día

                if($is_processing_data) {
                    
                    // Procesar datos de formulario
                    
                } 
                
                else {

                    // Renderizamos vista > Inicio
                    $params = array(
                        'app_name' => APP_NAME,
                        'app_url' => APP_URL,
                        'user_name' => $usuario_nombre,
                        'user_mail' => $usuario_correo,
                    );
                    
                    echo $twig->render('index.html',$params);

                }

            }

            else {

                // Redigimos a primeros pasos
                header('Location: ' . APP_URL . '/primeros-pasos'); 

            }
            
        }
        
        else {

            // Cerramos la sesión y obligamos al usuario a entrar
            session_destroy();
            header('Location: ' . APP_URL . '/entrar'); 

        }

    break;
    case false : 

        // Cerramos la sesión y obligamos al usuario a entrar
        session_destroy();
        header('Location: ' . APP_URL . '/entrar'); 

    break; 
    
}