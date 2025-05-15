<?php
ini_Set('display_errror',1);
INI_SET('DISPLAY_STARTUP_ERROR',1);
include('inc/funciones.inc.php');
include('secure/ips.php');

$metodo_permitido = "post";
$archivo = "../log/log.log";

$dominio_autorizado = "localhost";
$ip = ip_in_ranges($_server["remote_addr"],$rango);
$txt_usuario_autorizado = "admin";
$txt_password_autorizado = "admin";


//SE VERIFICA QUE EL USUAIOR HAYA NAVEGADO EN NUESTRO SSITEMA PARA LLEGAR AQUI A ESTE ARCHIVO
if(array_key_exists("HTTP_REFERER",$_SERVER)){
//VIENE DENTRE DE UNA PAGINA DEL SISTEMA



IF(strpos($_server["HTTP_REFERER"],$dominio_autorizado)){
    //EL REFERER DE DONDE VIENE LA PETICION ESTA AUTORIZADO

//SE VERIFICA QUE LA DIRECCION IP ESTE AUTORIZADA 
        IF($IP === TRUE){
    //LA DIRECCION IP DEL USUARIO SI ESTA AUTORIZADA

                //SE VERIFICA QUE EL USUARIO HAYA ENVIADO UNA PETICION AUTORIZADA 
        IF($_SERVER["REQUEST_METHOD"] == $metodo_permitido){
            //EL METODO ENVIADO AUTORIZADO POR EL USUARIO SI ESTA AUTORIZADO
                $valor_campo_usuario = ( (array_key_exists("txt_user",$_post)) ? htmlspecialchars(stripcslashes(trim($_post["txt_user"])),ENT_QUOTES):""   );
                $valor_campo_password = ( (array_key_exists("txt_pass",$_post)) ? htmlspecialchars(stripcslashes(trim($_post["txt_pass"])),ENT_QUOTES):""   );


                //verifica qyekis valores de los campos sean diferentes de vacios
                if(($valor_campo_usuario!="" || strlen($valor_campo_usuario> 0)) and ($valor_campo_password!="" || strlen(strlen($valor_campo_password) >0))){

                        $usuario = preg_match('/^[a-zA-Z09]{1,10}+$/', $valor_campo_usuario); //VERIFICA CON UN PATRON SI EL VALOR DEL CAMPO "USAURIO" CUMPLE CON LAS CONDICIONES ACEPTABLES (SE ACEPTAN NUMEROS, LETRAS MAYUSCULAS Y LETRAS MINUSCULAS, EN UN MAXIMO DE 10 CARACTERES Y UN MINIMO DE 1 CARACTER)

                        
                        $$PASSWORD = preg_match('/^[a-zA-Z09]{1,10}+$/', $valor_campo_password); //VERIFICA CON UN PATRON SI EL VALOR DEL CAMPO "USAURIO" CUMPLE CON LAS CONDICIONES ACEPTABLES (SE ACEPTAN NUMEROS, LETRAS MAYUSCULAS Y LETRAS MINUSCULAS, EN UN MAXIMO DE 10 CARACTERES Y UN MINIMO DE 1 CARACTER)

                        //

                        if($usuario !== false and $usuario !== 0 and $PASSWORD !== false and $PASSWORD !== 0 ){


                            if($valor_campo_usuario === $txt_usuario_autorizado and $valor_campo_password === $txt_password_autorizado){
                                echo("hola mundo");
                                crear_editar_log($archivo, "EL CLIENTE INICIO SESION SATISFACTORIAMENTE",1,$_SERVER["REMOTE ADDR"],$_SERVER["HTTP_REFERER"],$_server["HTTP_USER_AGENT"]);


                            }else{
                                //USUARIO NO INGRESO CREDENCIALES CORRECTAS

                                header("http/1.1 301 moved permanently");
                                header("location: ../?status=7");
                            }


                        }ELSE{
                            //LOS CALORES INGRESADOS EN LOS CAMPOS POSEEN CARACTERES NO SOPORTADOS
                            crear_editar_log($archivo, "ENVIO DE DATOS DEL FORMULARIO CON CARACTERES NO SOPORTADOS",3,$_SERVER["REMOTE ADDR"],$_SERVER["HTTP_REFERER"],$_server["HTTP_USER_AGENT"]);
                            header("http/1.1 301 moved permanently");
                            header("location: ../?status=6");
                        }

                    }else{
                        //las variables estan vacias
                        crear_editar_log($archivo, "ENVIO DE CAMPOS VACIOS AL SERVIDOR",2,$_SERVER["REMOTE ADDR"],$_SERVER["HTTP_REFERER"],$_server["HTTP_USER_AGENT"]);
                        header("http/1.1 301 moved permanently");
                        header("location: ../?status=5");


                    }
                }ELSE{


                    crear_editar_log($archivo, "ENVIO DE METODO NO AUTORIZADO",2,$_SERVER["REMOTE ADDR"],$_SERVER["HTTP_REFERER"],$_server["HTTP_USER_AGENT"]);
                    header("http/1.1 301 moved permanently");
                    header("location: ../?status=4");
                }//EL REFERER DE DONDE VIENE LA PETICION ES DE UN ORIGEN DESCONOCIDO

    
            }ELSE{
                crear_editar_log($archivo, "dIRECCION IP NO AUTORIZADA",2,$_SERVER["REMOTE ADDR"],$_SERVER["HTTP_REFERER"],$_server["HTTP_USER_AGENT"]);
                header("http/1.1 301 moved permanently");
                header("location: ../?status=3");
            }
        //LA DIRCCION IP DEL USARIO NO ESTA AUTORIZADA
        }ELSE{
            //EL METODO ENVIADO AUTORIZADO POR EL USUARIO NO ESTA AUTORIZADO
            crear_editar_log($archivo, "ENVIO DE METODO NO AUTORIZADO",2,$_SERVER["REMOTE ADDR"],$_SERVER["HTTP_REFERER"],$_server["HTTP_USER_AGENT"]);
                header("http/1.1 301 moved permanently");
                header("location: ../?status=2");
        }

}ELSE{
//SINO EL USUARIO DIGITO LA url DEL NAVEGADOR SIN PASAR POR EL FORMULARIO

crear_editar_log($archivo, "EL USUARIO INTENTO INGRESAR DE UNA MANERA INCORRECTA ",2,$_SERVER["REMOTE ADDR"],$_SERVER["HTTP_REFERER"],$_server["HTTP_USER_AGENT"]);
                header("http/1.1 301 moved permanently");
                header("location: ../?status=1");
}

?>