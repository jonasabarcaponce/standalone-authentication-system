<?php 

// Constructor de sitios web Ponce 1.0.0 > Completar 
include('core/set.php');
include('core/core.php');

// Obtenemos modelos de datos a utilizar en este controlador
$Usuarios = new Usuarios();
$Pruebas = new Pruebas();
$Ajustes = new Ajustes();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Composer autoloader, PHPMailer , TWIG y Librerías complementarias  
require_once './vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./views/cuenta');
$twig = new \Twig\Environment($loader);
$stripe = new \Stripe\StripeClient('sk_test_B4pvjxb2wk5WOP5T8mdSZOvb00wJMNprvA');


// Comienza lógica del controlador > Completar
session_start(); 

$is_logged_in = isset($_SESSION['user-session']) ? true : false;
$is_processing_data = isset($_POST['user-data-post']) ? true : false; 
$is_login_validated = $Usuarios->validar('sesion-actual',$_SESSION['user-session'],'','');

if($is_processing_data) {

    // Información a ser procesada: registro del usuario

    $nombre = $_POST['user-data-name'];
    $correo = $_POST['user-data-mail'];

    $negocio = $_POST['business-data-name'];
    $concepto = $_POST['business-data-concepto'];
    $monto = $_POST['business-data-monto'];
    $terminal = (int)$_POST['business-data-fast'];

    $validate_nombre = isset($nombre) ? true : false;
    $validate_correo = isset($correo) ? true : false;

    $verify_data_processed = $validate_nombre === true && $validate_correo === true ? true : false; 
}

switch($is_logged_in) {
    case true : 

        if($is_login_validated) {

            // Obtenemos la información del usuario 
            $user_data = $Usuarios->obtener_informacion($_SESSION['user-session']);
            
            $usuario_identificador = $user_data['identificador'];
            $usuario_whatsapp = $user_data['whatsapp'];
            $usuario_nombre = $user_data['nombre'];
            $usuario_correo = $user_data['correo'];
            $usuario_estado = $user_data['estado'];

            // Verificamos que el usuario ya esté registrado aunque esté incompleto.
            if($usuario_estado > 0 ) {

                if($is_processing_data) {

                    if($verify_data_processed) {

                        $customer = $stripe->customers->create([
                            'description' => $usuario_identificador,
                            'email' => $correo,
                            'name' => $nombre,
                            'phone' => '+52' . $usuario_whatsapp
                        ]);

                        // Completamos al usuario
                        $completar_usuario = $Usuarios->completar($usuario_identificador,$customer['id'],$nombre,$correo);

                        $completar_ajustes = $Ajustes->crear($usuario_identificador,$negocio);

                        if($completar_usuario && $completar_ajustes) {

                            $token = md5($usuario_identificador . bin2hex(random_bytes(5))); 

                            $crear_token = $Pruebas->crear(1,$token,$usuario_identificador);

                            if($crear_token) {

                                $emailParams = array(
                                    'app_url' => APP_URL,
                                    'app_email' => APP_EMAIL,
                                    'email_title' => 'Hola, ' . $nombre . '!',
                                    'email_body' => 'Confirma tu correo elecctrónico para comenzar a usar Ponce Builder',
                                    'email_disclaimer' => 'No compartas este correo con nadie más',
                                    'email_button_link' =>  APP_URL . '/activar/' . $token,
                                    'email_button_text' => 'Confirmar'
                                );
                                
                                
                                // Instantiation and passing `true` enables exceptions
                                $mail = new PHPMailer(true);
    
                                try {
                                    
                                    // Server settings
                                    $mail->isSMTP();
                                    $mail->Host       = APP_EMAIL_SERVER;
                                    $mail->SMTPAuth   = true;
                                    $mail->Username   = APP_EMAIL_USER;
                                    $mail->Password   = APP_EMAIL_PASSWORD;
                                    $mail->SMTPSecure = false;
                                    $mail->Port       = 26;
                                    $mail->CharSet = 'UTF-8';
    
                                    //Recipients
                                    $mail->setFrom('hola@quire.mx', APP_NAME);
                                    $mail->addAddress($correo, $nombre);
                                    $mail->addBCC(APP_EMAIL_USER);
    
                                    // Content
                                    $mail->isHTML(true);
                                    $mail->Subject = 'Confirma tu correo electrónico';
                                    $mail->Body    = $twig->render('email.html',$emailParams);
                                    $mail->AltBody = 'Confirma tu correo electrónico';
    
                                    $mail->send();
    
                                    // Si se completó correctamente redirigimos a controlador > Primeros Pasos
    
                                    header('Location: ' . APP_URL . '/primeros-pasos');
    
                                } catch (Exception $e) {
    
                                    header('Location: ' . APP_URL . '/primeros-pasos?msg=error-mail');
    
                                }

                            } 
                            
                            else {
                                
                                // Como el registro fue incorrecto, redirigimos y mostramos el error.
                                header('Location: ' . APP_URL . '/primeros-pasos?msg=error-token');

                            }
                            
                        } 

                        else {

                            // Como el registro fue incorrecto, redirigimos y mostramos el error.
                            header('Location: ' . APP_URL . '/primeros-pasos?msg=error-completando');

                        }
    
                    }

                } 
                
                else {

                    
                    // Renderizamos vista > Completar
                    $params = array(
                        'app_name' => APP_NAME,
                        'app_url' => APP_URL,
                        'user_name' => 'Bienvenido',
                        'user_status' => $usuario_estado,
                    );
                    
                    echo $twig->render('cuenta-completar.html',$params);

                }

            }

            else {

                // Redigimos a primeros pasos
                header('Location: ' . APP_URL . '/inicio'); 

            }
            
        }
        
        else {

            // Cerramos la sesión y obligamos al usuario a entrar
            session_destroy();
            header('Location: ' . APP_URL . '/entrar'); 

        }

    break;
    case false : 

        session_destroy();
        header('Location: ' . APP_URL . '/entrar'); 

    break; 
}