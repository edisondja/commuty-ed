<?php



global $conexion;
$conexion = new mysqli(HOST_BD, USER_BD, PASSWORD_BD, NAME_DB);

class Prop 
{


    public  $fecha;         

    public function titleList($title)
    {
        $titulo_listo = str_replace(' ', '_', $title);

        return $titulo_listo;
    }


    /*
        Registra todo los log de lo que hacen los usuarios en la plataforma
        y seguimiento del programa, esta implementacion de seguimiento se utiliza
        para auditorias si es necesario.
    */


    public function save_tracking($tracking,$tipo){

        global $conexion;
        $fecha = date('ymdiss');
        $sql = "intert into logs_i(fecha_log,tracking,tipo)VALUES(?,?,?)";

        try{
            $save  = $conexion->prepare($sql);
            $save->bind_param('sss',$fecha,$tracking,$tipo);
            $save->execute();
        }catch(Exception $e){

            $this->TrackingLog('no se estan guardando los logs en la base de datos '.$fecha,'errores');
        }

        $conexion->close();

    }


    public function TrackingLog($mensaje,$opcion){


        switch ($opcion) {
            
            
            case 'errores':
                
                file_put_contents("../traking/errores.txt",$mensaje.PHP_EOL,FILE_APPEND);
             //   $this->save_tracking($mensaje,'errores');

            break;
            
            case 'usuarios':
                
                file_put_contents("../traking/usuarios.txt",$mensaje.PHP_EOL,FILE_APPEND);
                //$this->save_tracking($mensaje,'usuarios');

            break;


            case 'alertas':

                file_put_contents("../traking/alertas.txt",$mensaje.PHP_EOL,FILE_APPEND);
               // $this->save_tracking($mensaje,'alertas');

            break;

            case 'eventos':

                file_put_contents("../traking/eventos.txt",$mensaje.PHP_EOL,FILE_APPEND);
              //  $this->save_tracking($mensaje,'eventos');

            break;
            
            
            case 'bitacora':
                /*
                    Enviar mail con los posibles errores registrados en el sistema que 
                    resultan de gravedad esto notificara a los administradores de manera inmediata
                    tiendo en cuanta que la configuracion del servidor SMTP del correo este correctamente
                    configurada
                */


            break;

        }


    }

    public function detectar_archivo($tipo)
    {
        $tipo_archivo = $tipo;
        $tipo_arc = explode('/', $tipo_archivo);
        $tipo_arc = $tipo_arc[1]; //aqui esta capturada la fuente en el segundo indice

        return $tipo_arc;
    }

    public function limitarTexto($texto, $limite = 15)
    {
        // Verifica si la longitud del texto es mayor que el límite
        if (mb_strlen($texto) > $limite) {
            // Corta el texto al límite especificado y añade '...' al final
            $textoCortado = mb_substr($texto, 0, $limite);
        } else {
            // Si el texto es menor o igual al límite, se devuelve el texto original
            $textoCortado = $texto;
        }

        return $textoCortado;
    }

    public function __construct()
    {
    }

    public function disable()
    {
        return 'inactivo';
    }

    public function enable()
    {
        return 'activo';
    }

    public function banned()
    {
        return 'baneado';
    }
}
