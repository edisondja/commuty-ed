<?php

class Config extends EncryptToken
{
    public int $configuracion_id;
    public string $dominio;
    public string $nombre_sitio;
    public string $descripcion_slogan;
    public string $descripcion_sitio;
    public string $favicon_url;
    public string $sitio_logo_url;
    public string $copyright_descripcion;
    public string $email_sitio;
    public string $busqueda_descripcion;
    public string $pagina_descripcion;
    public string $titulo_descripcion;
    public string $busqueda_hastag;
    public string $email_remitente;
    public string $nombre_remitente;
    public string $servidor_smtp;
    public string $puerto_smtp;
    public string $usuario_smtp;
    public string $clave_smtp;
    public string $autenticacion_ssl;



    public function __construct()
    {
        $this->SetConection();

    }

    public function VerificarConfiguracion()
    {
        /*
            Este meotod se encarga de validar si ya
            existe una configuracion creada y retornar
            el id de ella
        */

        $sql = 'SELECT * FROM configuracion LIMIT 1';

        try{
            $cargar = $this->conection;
            $data = $cargar->query($sql);
            return $data->num_rows;
            
        }catch(Exception $e){

            echo $e;
        }
    }

    public function Cargar_configuracion($config)
    {
        $sql = 'select * from configuracion limit 1';
        $data = $this->conection->prepare($sql);
        $data->execute();
        $data = $data->get_result();
        $data = mysqli_fetch_object($data);
    
        // Verificar si la consulta no devolvió ningún resultado
        if ($data === null) {
            // Manejar el caso cuando no hay resultados
            if ($config == 'json') {
                echo json_encode(["error" => "No data found"]);
            } elseif ($config == 'asoc') {
                
                $config_d= new stdClass();
                $config_d->sitio_logo_url ='';
                $config_d->favicon_rul  ='';
                
                return $config_d;
            }
        } else {
            // Procesar el resultado como se espera
            if ($config == 'json') {
                echo json_encode($data);
            } elseif ($config == 'asoc') {

                $this->servidor_smtp =$data->servidor_smtp;
                $this->email_remitente =$data->email_remitente;
                $this->clave_smtp =$data->clave_smtp;
                $this->puerto_smtp = $data->puerto_smtp;
                $this->nombre_sitio =$data->nombre_sitio;
                $this->descripcion_slogan = $data->descripcion_slogan;
                $this->descripcion_sitio = $data->descripcion_sitio;
                $this->favicon_url = $data->favicon_url;
                $this->sitio_logo_url = $data->sitio_logo_url;
                $this->copyright_descripcion = $data->copyright_descripcion;
                $this->email_sitio = $data->email_sitio;
                $this->autenticacion_ssl = $data->autenticacion_ssl;
                $this->dominio = $data->dominio;
                
                return $data;
            }
        }
    }

    public function DetectarMultimedias($logo_sitio, $favicon_sitio)
    {
        $fecha_actual = date('ymdis');

        if (isset($logo_sitio['tmp_name'])) {

            $targetFile = "../assets/$fecha_actual";
            $targetFile .= $logo_sitio['name'];

            move_uploaded_file($logo_sitio['tmp_name'], $targetFile);

            $this->sitio_logo_url = str_replace('..', '', $targetFile);
        }

        if (isset($favicon_sitio['tmp_name'])) {

            $targetFile = "../assets/$fecha_actual";
            $targetFile .= $favicon_sitio['name'];

            move_uploaded_file($favicon_sitio['tmp_name'], $targetFile);

            $this->favicon_url = str_replace('..', '', $targetFile);

        } 
    }
    public function Guardar_configuracion()
    {
        

        $sql = 'INSERT INTO configuracion(
                dominio,
                nombre_sitio,
                descripcion_slogan,
                descripcion_sitio,
                copyright_descripcion,
                email_sitio,
                busqueda_descripcion,
                pagina_descripcion,
                titulo_descripcion,
                busqueda_hastag,
                favicon_url,
                sitio_logo_url,
                email_remitente,
                nombre_remitente,
                servidor_smtp,
                puerto_smtp,
                usuario_smtp,
                clave_smtp,
                autenticacion_ssl
            )VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        $guardar = $this->conection->prepare($sql);

        try {


            $guardar->bind_param('sssssssssssssssssss',
                $this->dominio,
                $this->nombre_sitio,
                $this->descripcion_slogan,
                $this->descripcion_sitio,
                $this->copyright_descripcion,
                $this->email_sitio,
                $this->busqueda_descripcion,
                $this->pagina_descripcion,
                $this->titulo_descripcion,
                $this->busqueda_hastag,
                $this->favicon_url,
                $this->sitio_logo_url,
                $this->email_remitente,
                $this->nombre_remitente,
                $this->servidor_smtp,
                $this->puerto_smtp,
                $this->usuario_smtp,
                $this->clave_smtp,
                $this->autenticacion_ssl
            );
            $guardar->execute();
            $guardar->close();

            echo 'Configuracion guardada con exito!';
        } catch (Exception $e) {
            echo $e;
            $guardar->close();
        }
    }


    public function Actualizar_configuracion()
    {


    //    echo $this->favicon_url;
        $sql = "UPDATE configuracion SET
                    dominio =?,
                    nombre_sitio = ?,
                    descripcion_slogan = ?,
                    descripcion_sitio = ?,
                    copyright_descripcion = ?,
                    email_sitio = ?,
                    busqueda_descripcion = ?,
                    pagina_descripcion = ?,
                    titulo_descripcion = ?,
                    busqueda_hastag = ?,
                    favicon_url = ?,
                    sitio_logo_url = ?,
                    email_remitente = ?,
                    nombre_remitente = ?,
                    servidor_smtp = ?, 
                    puerto_smtp = ?,
                    usuario_smtp = ?,
                    clave_smtp = ?,
                    autenticacion_ssl = ?
                    ";

        $actualizar = $this->conection->prepare($sql);


        try {


            $actualizar->bind_param(
                'sssssssssssssssssss',
                $this->dominio,
                $this->nombre_sitio,
                $this->descripcion_slogan,
                $this->descripcion_sitio,
                $this->copyright_descripcion,
                $this->email_sitio,
                $this->busqueda_descripcion,
                $this->pagina_descripcion,
                $this->titulo_descripcion,
                $this->busqueda_hastag,
                $this->favicon_url,
                $this->sitio_logo_url,
                $this->email_remitente,
                $this->nombre_remitente,
                $this->servidor_smtp,
                $this->puerto_smtp,
                $this->usuario_smtp,
                $this->clave_smtp,
                $this->autenticacion_ssl
            );

            $actualizar->execute();
            $actualizar->close();

            echo 'Configuración actualizada con éxito!';
        } catch (Exception $e) {
            echo $e;
            $actualizar->close();
        }
    }   


}

?>

