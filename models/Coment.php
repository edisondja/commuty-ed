<?php

class Coment extends EncryptToken {

    public int $id_post;
    public int $id_user;
    public string $comentario;
    public string $tipo_post;
    public string $data_og;
    public string $estado;
    public $conection;
    public Notificacion $notifacar;
    public Board $tablero;

    function __construct() {
        $this->SetConection();
        $this->notifacar = new Notificacion();
        $this->tablero = new Board();
    }

    function leer_comentarios($id_post, $tipo_post = 'video') {
        $estado = $this->enable();

        if ($tipo_post == 'board') {
            $sql = "SELECT * FROM comentario AS c 
                    INNER JOIN user AS u ON c.usuario_id = u.id_user 
                    WHERE c.id_tablero = ? AND c.tipo_post = ? 
                    AND c.estado = ?  
                    ORDER BY id_comentario DESC";
        } else {
            $sql = "SELECT * FROM comentario AS c 
                    INNER JOIN user AS u ON c.usuario_id = u.id_user 
                    WHERE c.id_post = ? AND c.tipo_post = ? 
                    AND c.estado = ? 
                    ORDER BY id_comentario DESC";
        }

        $read = $this->conection->prepare($sql);
        $read->bind_param('iss', $id_post, $tipo_post, $estado);
        $read->execute();
        $ejecutar = $read->get_result();

        $data = [];
        foreach ($ejecutar as $key) {
            $key['comentarios_hijos'] = $this->load_childs_coment($key['id_comentario']);
            $data[] = $key;
        }

        echo json_encode($data);
    }

    public function guardar_comentario() {
        $estado = $this->enable();
        $comentario = str_replace("script", "000", $this->comentario);
        $fecha = date('Ymdhis');

        // Capturar el dueño del tablero que recibe el comentario
        $get_id_user_board = $this->tablero->cargar_solo_tablero($this->id_post, 'asoc');

        // Notificar al usuario receptor
        $this->notifacar->id_tablero = $this->id_post;
        $this->notifacar->id_usuario_emisor = $this->id_user;
        $this->notifacar->id_usuario_receptor = $get_id_user_board->id_user;
        $this->notifacar->tipo = 'comentario';
        $this->notifacar->notificar_a_usuario();

        // Determinar la consulta SQL según el tipo de post
        if ($this->tipo_post == 'board') {
            $sql = "INSERT INTO comentario (id_tablero, usuario_id, texto, fecha_publicacion, tipo_post, data_og, estado)
                    VALUES (?,?,?,?,?,?,?)";
        } else {
            $sql = "INSERT INTO comentario (id_post, usuario_id, texto, fecha_publicacion, tipo_post, data_og, estado)
                    VALUES (?,?,?,?,?,?,?)";
        }

        try {
            $execute = $this->conection->prepare($sql);
            $execute->bind_param('iisssss', $this->id_post, $this->id_user, $comentario, $fecha, $this->tipo_post, $this->data_og, $estado);
            $execute->execute();
            echo "Guardado con éxito";
        } catch (Exception $error) {
            echo "Error: " . $error->getMessage();
        } finally {
            if (isset($execute)) {
                $execute->close();
                $this->TrackingLog(date('y-m-d h:i:s') . ' Comentario del user ID: ' . $this->id_user, 'eventos');
            }
        }
    }

    public function eliminar_comentario($id_comentario) {
        $estado = $this->disable();
        $sql = "UPDATE comentario SET estado = ? WHERE id_comentario = ?";

        try {
            $eliminar = $this->conection->prepare($sql);
            $eliminar->bind_param('si', $estado, $id_comentario);
            $eliminar->execute();
        } catch (Exception $e) {
            $this->TrackingLog(date('y-m-d h:i:s') . ' No se pudo eliminar el comentario ' . $e->getMessage(), 'errores');
        }

        $this->TrackingLog(date('y-m-d h:i:s') . ' Comentario eliminado con éxito ' . $id_comentario, 'eventos');
    }

    function cargar_1_comentario_hijo($id, $config = 'json') {
        $sql = "SELECT ch.text_coment, ch.user_id, ch.id_reply_id, ch.fecha_creacion, ch.estado, us.usuario, us.foto_url
                FROM reply_coment AS ch
                INNER JOIN user AS us ON ch.user_id = us.id_user
                WHERE ch.id_reply_id = ?";

        try {
            $cargar = $this->conection->prepare($sql);
            $cargar->bind_param('i', $id);
            $cargar->execute();
            $data = $cargar->get_result()->fetch_object();

            if ($config == 'json') {
                echo json_encode($data);
            } else {
                return $data;
            }
        } catch (Exception $e) {
            $this->TrackingLog(date('y-m-d h:i:s') . ' No se pudo cargar el comentario hijo ' . $e->getMessage(), 'errores');
        }
    }

    function reply_coment($id_coment, $id_user, $text_coment) {
        $estado = $this->enable();
        $fecha = date('Ymdhis');
        $sql = "INSERT INTO reply_coment (coment_id, user_id, text_coment, fecha_creacion, estado) 
                VALUES (?,?,?,?,?)";

        try {
            $insertar = $this->conection->prepare($sql);
            $insertar->bind_param('iisss', $id_coment, $id_user, $text_coment, $fecha, $estado);
            $insertar->execute();
            $id_child_c = $this->conection->insert_id;
            $this->cargar_1_comentario_hijo($id_child_c);
        } catch (Exception $e) {
            $this->TrackingLog(date('y-m-d h:i:s') . ' El usuario ID: ' . $id_user . ' no pudo responder comentario ' . $e->getMessage(), 'errores');
        }

        $this->TrackingLog(date('y-m-d h:i:s') . ' El usuario ID: ' . $id_user . ' Respondió comentario', 'eventos');
    }

    function load_childs_coment($id_coment, $config = 'asoc') {
        $estado = $this->enable();
        $sql = "SELECT ch.text_coment, ch.user_id, ch.id_reply_id, ch.fecha_creacion, ch.estado, us.usuario, us.foto_url 
                FROM reply_coment AS ch 
                INNER JOIN user AS us ON ch.user_id = us.id_user 
                WHERE ch.coment_id = ? AND ch.estado = ? 
                ORDER BY ch.fecha_creacion DESC";

        try {
            $load = $this->conection->prepare($sql);
            $load->bind_param('is', $id_coment, $estado);
            $load->execute();
            $data = $load->get_result();
            $comentarios = [];

            foreach ($data as $key) {
                $comentarios[] = $key;
            }

            if ($config == 'asoc') {
                return $comentarios;
            } else {
                echo json_encode($comentarios);
            }
        } catch (Exception $e) {
            $this->TrackingLog(date('y-m-d h:i:s') . ' El comentario ID: ' . $id_coment . ' No pudo cargar sus comentarios hijos ' . $e->getMessage(), 'errores');
        }
    }

    function delete_coment_reply($id_coment) {
        $estado = $this->disable();
        $sql = "UPDATE reply_coment SET estado = ? WHERE id_reply_id = ?";

        try {
            $delete = $this->conection->prepare($sql);
            $delete->bind_param('si', $estado, $id_coment);
            $delete->execute();
        } catch (Exception $e) {
            $this->TrackingLog(date('y-m-d h:i:s') . ' No se pudo eliminar el comentario hijo ' . $id_coment . ' ' . $e->getMessage(), 'errores');
        }

        $this->TrackingLog(date('y-m-d h:i:s') . ' Comentario hijo eliminado con éxito ID: ' . $id_coment, 'eventos');
    }
}
