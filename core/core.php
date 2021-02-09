<?php 

// Core de Constructor de sitios web Ponce 1.0.0

class Conectar {

    public function a_one_smart_pay() {

        // Switch de Producción: use true para producción, use false para desarrollo. 
        $production_database_switch = false; 

        if($production_database_switch) {

            # Producción 
            $db_name = 'production_database';
            $db_usuario = 'production_username';
            $db_password = 'production_pasword';
            
        } 
        
        else {

            # Desarrollo 
            $db_name = 'developer_database';
            $db_usuario = 'developer_username';
            $db_password = 'developer_password'; 

        }

        $db_solicitud = 'mysql:host=localhost;dbname='.$db_name.';charset=utf8';

        $conexion = new PDO($db_solicitud,$db_usuario,$db_password);

        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conexion; 

    }

}

class Usuarios {

    public function crear($identificador,$whatsapp,$password,$status) {
        
        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        $Tipo = 1; // 0 = Clientes, 1 = Negocios

        $Solicitud = "INSERT into usuarios (identificador,whatsapp,password,estado,tipo) values (:identificador,:whatsapp,:password,:estado,:tipo)";

        $Consulta = $Conn->prepare($Solicitud);

        $Consulta->bindParam(':identificador',$identificador);
        $Consulta->bindParam(':whatsapp',$whatsapp);
        $Consulta->bindParam(':password',$password);
        $Consulta->bindParam(':estado',$status);
        $Consulta->bindParam(':tipo',$Tipo);

        $whatsapp_en_uso = $this->validar('whatsapp-en-uso',$whatsapp,'','');

        switch($whatsapp_en_uso) {
            case true : 

                return false;

            break;
            case false : 

                if(!$Consulta) {

                    return false;

                } else {

                    $Consulta->execute();
                    return true;

                }    
            
            break;
        }

    }

    public function completar($usuario,$stripe,$nombre,$correo) {

        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        $Solicitud = "UPDATE usuarios SET nombre=:nombre, stripe=:stripe, correo=:correo, estado=1 WHERE identificador=:usuario";

        $Consulta = $Conn->prepare($Solicitud);

        $Consulta->bindParam(':nombre',$nombre);
        $Consulta->bindParam(':stripe',$stripe);
        $Consulta->bindParam(':correo',$correo);
        $Consulta->bindParam(':usuario',$usuario);

        $email_en_uso = $this->validar('email-en-uso','','',$correo);

        switch($email_en_uso) {
            case true : 

                return false;

            break;
            case false : 

                if(!$Consulta) {

                    return false;

                } else {

                    $Consulta->execute();
                    return true;

                }    
            
            break;
        }

    }

    public function validar($peticion,$whatsapp,$password,$correo) {

        $Resultados = null;
        
        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        switch($peticion) {
            case 'identificacion-correcta' : 
            
                $Solicitud = "SELECT * FROM usuarios WHERE whatsapp=:whatsapp AND password=:password AND tipo=1";

                $Consulta = $Conn->prepare($Solicitud);

                $Consulta->bindParam(':whatsapp',$whatsapp);
                $Consulta->bindParam(':password',$password);
            
            break;
            case 'sesion-actual' : 
            
                $Solicitud = "SELECT * FROM usuarios WHERE whatsapp=:whatsapp AND tipo=1";

                $Consulta = $Conn->prepare($Solicitud);

                $Consulta->bindParam(':whatsapp',$whatsapp);
            
            break;
            case 'email-en-uso' : 
            
                $Solicitud = "SELECT * FROM usuarios WHERE correo=:correo";

                $Consulta = $Conn->prepare($Solicitud);

                $Consulta->bindParam(':correo',$correo);
                  
            break;
            case 'whatsapp-en-uso' : 
            
                $Solicitud = "SELECT * FROM usuarios WHERE whatsapp=:whatsapp";

                $Consulta = $Conn->prepare($Solicitud);

                $Consulta->bindParam(':whatsapp',$whatsapp);
            
            break;
            case 'existencia-identificador' :      

                $Solicitud = "SELECT * FROM usuarios WHERE identificador=:whatsapp";

                $Consulta = $Conn->prepare($Solicitud);

                $Consulta->bindParam(':whatsapp',$whatsapp);
            
            break;
        }

        $Consulta->execute();

        $Resultados = $Consulta->fetch(PDO::FETCH_ASSOC); 

        if($Resultados >1) {

            return true;

        } else {

            return false;

        }

    }

    public function obtener_informacion($whatsapp) {

        $Resultados = null;

        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        $Solicitud = "SELECT * from usuarios where whatsapp=:whatsapp";
        
        $Consulta = $Conn->prepare($Solicitud);

        $Consulta->bindParam(':whatsapp',$whatsapp);

        $Consulta->execute();

        $Resultados = $Consulta->fetch(PDO::FETCH_ASSOC);

        return $Resultados;

    }

    public function activar($usuario) {
        
        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        $Solicitud = "UPDATE usuarios SET estado=0 WHERE identificador=:usuario";

        $Consulta = $Conn->prepare($Solicitud);

        $Consulta->bindParam(':usuario',$usuario);

        if(!$Consulta) {

            return false;

        } else {

            $Consulta->execute();
            return true;

        }    

    }

}

class Pruebas {

    public function crear($tipo,$prueba,$usuario) {
        
        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        $Solicitud = "INSERT into pruebas (tipo,prueba,usuario) VALUES (:tipo,:prueba,:usuario)";

        $Consulta = $Conn->prepare($Solicitud);

        $Consulta->bindParam(':tipo',$tipo);
        $Consulta->bindParam(':prueba',$prueba);
        $Consulta->bindParam(':usuario',$usuario);

        if(!$Consulta) {

            return false;

        } else {

            $Consulta->execute();
            return true;

        }    

    }

    public function obtener_informacion($prueba) {

        $Resultados = null; 

        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        $Solicitud = "SELECT * from pruebas where prueba=:prueba";
        
        $Consulta = $Conn->prepare($Solicitud);

        $Consulta->bindParam(':prueba',$prueba);

        $Consulta->execute();

        $Resultados = $Consulta->fetch(PDO::FETCH_ASSOC);

        return $Resultados;

    }

    public function actualizar($prueba,$estado) {

        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        $Solicitud = "UPDATE pruebas SET activo=:estado WHERE prueba=:prueba";

        $Consulta = $Conn->prepare($Solicitud);

        $Consulta->bindParam(':estado',$estado);
        $Consulta->bindParam(':prueba',$prueba);

        if(!$Consulta) {

            return false;

        } else {

            $Consulta->execute();
            return true;

        }    

    }

}

class Ajustes {

    public function crear($usuario,$negocio) {
        
        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        $Solicitud = "INSERT into ajustes_negocios (usuario,negocio) values (:usuario,:negocio)";

        $Consulta = $Conn->prepare($Solicitud);

        $Consulta->bindParam(':usuario',$usuario);
        $Consulta->bindParam(':negocio',$negocio);

        if(!$Consulta) {

            return false;

        } else {

            $Consulta->execute();
            return true;

        }    

    }

    public function obtener_informacion($usuario) {

        $Resultados = null;

        $Conectar = new Conectar();
        $Conn = $Conectar->a_one_smart_pay();

        $Solicitud = "SELECT * from ajustes_negocios where usuario=:usuario";
        
        $Consulta = $Conn->prepare($Solicitud);

        $Consulta->bindParam(':usuario',$usuario);

        $Consulta->execute();

        $Resultados = $Consulta->fetch(PDO::FETCH_ASSOC);

        return $Resultados;

    }

}