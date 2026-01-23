<?php

class Coment extends EncryptToken {

    public  $id_post;
    public  $id_user;
    public  $comentario;
    public  $tipo_post;
    public  $data_og;
    public  $estado;
    public $conection;
    public $notifacar;
    public $tablero;

    function __construct() {
        $this->SetConection();
        $this->notifacar = new Notificacion();
        $this->tablero = new Board();
    }

    function leer_comentarios($id_post, $tipo_post = 'board') {
        $estado = $this->enable();

        if ($tipo_post == 'board') {
            $sql = "select c.*, u.usuario, u.foto_url, u.id_user as usuario_id
                    from comentario as c 
                    inner join users as u on c.usuario_id = u.id_user 
                    where c.id_tablero = ? and c.tipo_post = ? and c.estado = ?  
                    order by c.id_comentario desc";
        } else {
            $sql = "select c.*, u.usuario, u.foto_url, u.id_user as usuario_id
                    from comentario as c 
                    inner join users as u on c.usuario_id = u.id_user 
                    where c.id_tablero = ? and c.tipo_post = ? and c.estado = ? 
                    order by c.id_comentario desc";
        }

        $read = $this->conection->prepare($sql);
        if ($read === false) {
            echo json_encode(['error' => 'Error al preparar la consulta: ' . $this->conection->error]);
            return;
        }
        $read->bind_param('iss', $id_post, $tipo_post, $estado);
        $read->execute();
        $ejecutar = $read->get_result();
        $read->close();

        $comentarios_principales = [];
        $respuestas_index = []; // Índice de respuestas por comentario padre
        
        // Compatible con PHP 7.2 y PHP 8+
        if (PHP_VERSION_ID >= 80000) {
            foreach ($ejecutar as $row) {
                // Convertir objeto a array
                $comentario = [];
                foreach ($row as $prop => $value) {
                    $comentario[$prop] = $value;
                }
                
                // Asegurar que id_comentario sea int
                $id_comentario_int = (int)$comentario['id_comentario'];
                $comentario['id_comentario'] = $id_comentario_int;
                
                // Cargar comentarios hijos (respuestas) y guardarlos en el índice
                $respuestas = $this->cargar_respuestas($id_comentario_int);
                $respuestas_index[$id_comentario_int] = is_array($respuestas) ? $respuestas : [];
                
                // NO incluir comentarios_hijos directamente en el comentario
                // Se incluirán en la respuesta JSON en un índice separado
                $comentarios_principales[] = $comentario;
            }
        } else {
            while ($comentario = $ejecutar->fetch_assoc()) {
                // Asegurar que id_comentario sea int
                $id_comentario_int = (int)$comentario['id_comentario'];
                $comentario['id_comentario'] = $id_comentario_int;
                
                // Cargar comentarios hijos (respuestas) y guardarlos en el índice
                $respuestas = $this->cargar_respuestas($id_comentario_int);
                $respuestas_index[$id_comentario_int] = is_array($respuestas) ? $respuestas : [];
                
                // NO incluir comentarios_hijos directamente en el comentario
                // Se incluirán en la respuesta JSON en un índice separado
                $comentarios_principales[] = $comentario;
            }
        }

        // Retornar estructura con índice de respuestas
        $resultado = [
            'comentarios' => array_values($comentarios_principales),
            'respuestas' => $respuestas_index // Índice: coment_id => array de respuestas
        ];

        echo json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }

    /**
     * Carga las respuestas (hilos) de un comentario
     */
    public function cargar_respuestas($id_comentario) {
        if (empty($id_comentario) || !is_numeric($id_comentario)) {
            return [];
        }
        
        $estado = $this->enable();
        $id_comentario = (int)$id_comentario;
        
        $sql = "select ch.id_reply, ch.text_coment, ch.user_id, ch.fecha_creacion, 
                       us.usuario, us.foto_url, ch.coment_id
                from reply_coment ch 
                inner join users us on ch.user_id = us.id_user 
                where ch.coment_id = ? and ch.estado = ? 
                order by ch.fecha_creacion asc";

        try {
            // Validar conexión
            if (!$this->conection || !($this->conection instanceof mysqli)) {
                $this->SetConection();
                if (!$this->conection || !($this->conection instanceof mysqli)) {
                    return [];
                }
            }
            
            $stmt = $this->conection->prepare($sql);
            if ($stmt === false) {
                return [];
            }
            
            $stmt->bind_param('is', $id_comentario, $estado);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $respuestas = [];

            // Compatible con PHP 7.2 y PHP 8+
            if (PHP_VERSION_ID >= 80000) {
                foreach ($result as $row) {
                    $respuesta = [];
                    foreach ($row as $prop => $value) {
                        $respuesta[$prop] = $value;
                    }
                    // Asegurar tipos correctos
                    $respuesta['id_reply'] = (int)$respuesta['id_reply'];
                    $respuesta['user_id'] = (int)$respuesta['user_id'];
                    $respuesta['coment_id'] = (int)$respuesta['coment_id'];
                    $respuestas[] = $respuesta;
                }
            } else {
                while ($row = $result->fetch_assoc()) {
                    // Asegurar tipos correctos
                    $row['id_reply'] = (int)$row['id_reply'];
                    $row['user_id'] = (int)$row['user_id'];
                    $row['coment_id'] = (int)$row['coment_id'];
                    $respuestas[] = $row;
                }
            }

            $stmt->close();
            
            return array_values($respuestas);
            
        } catch (Exception $e) {
            error_log("ERROR cargar_respuestas: " . $e->getMessage() . " | id_comentario: " . $id_comentario);
            return [];
        }
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
            $sql = "insert into comentario (id_tablero, usuario_id, texto, fecha_publicacion, tipo_post, data_og, estado)
                    values (?,?,?,?,?,?,?)";
        } else {
            $sql = "insert into comentario (id_post, usuario_id, texto, fecha_publicacion, tipo_post, data_og, estado)
                    values (?,?,?,?,?,?,?)";
        }

        try {
            $execute = $this->conection->prepare($sql);
            if ($execute === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->conection->error);
            }
            $execute->bind_param('iisssss', $this->id_post, $this->id_user, $comentario, $fecha, $this->tipo_post, $this->data_og, $estado);
            $execute->execute();
            $execute->close();
            echo "Guardado con éxito";
        } catch (Exception $error) {
            echo "Error: " . $error->getMessage();
            $this->TrackingLog(date('y-m-d h:i:s') . ' Error guardando comentario: ' . $error->getMessage(), 'errores');
        }
    }

    public function eliminar_comentario($id_comentario) {
        $estado = $this->disable();
        $sql = "update comentario set estado = ? where id_comentario = ?";

        try {
            $eliminar = $this->conection->prepare($sql);
            if ($eliminar === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->conection->error);
            }
            $eliminar->bind_param('si', $estado, $id_comentario);
            $eliminar->execute();
            $eliminar->close();
        } catch (Exception $e) {
            $this->TrackingLog(date('y-m-d h:i:s') . ' No se pudo eliminar el comentario ' . $e->getMessage(), 'errores');
        }

        $this->TrackingLog(date('y-m-d h:i:s') . ' Comentario eliminado con éxito ' . $id_comentario, 'eventos');
    }

    /**
     * Carga un solo comentario hijo (respuesta) por su ID
     */
    function cargar_1_comentario_hijo($id_reply, $config = 'json') {
        try {
            // Validar conexión
            if (!$this->conection || !($this->conection instanceof mysqli)) {
                throw new Exception('Conexión a la base de datos no válida');
            }
            
            // Primero obtener los datos de reply_coment sin JOIN para evitar problemas con alias
            $sql = "select * from reply_coment where id_reply = ?";
            $stmt = $this->conection->prepare($sql);
            
            if ($stmt === false) {
                // Si falla, puede ser que la columna tenga otro nombre
                $error_msg = $this->conection->error;
                error_log("ERROR preparando consulta reply_coment: " . $error_msg);
                throw new Exception('Error al preparar la consulta: ' . $error_msg);
            }
            $stmt->bind_param('i', $id_reply);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $data = null;
            // Compatible con PHP 7.2 y PHP 8+
            if (PHP_VERSION_ID >= 80000) {
                $obj = $result->fetch_object();
                if ($obj) {
                    $data = [];
                    foreach ($obj as $prop => $value) {
                        $data[$prop] = $value;
                    }
                }
            } else {
                $data = $result->fetch_assoc();
            }
            
            $stmt->close();
            
            // Si usamos SELECT *, necesitamos hacer JOIN con users para obtener usuario y foto_url
            if ($data && !isset($data['usuario'])) {
                $user_id = isset($data['user_id']) ? $data['user_id'] : null;
                if ($user_id) {
                    $sql_user = "select usuario, foto_url from users where id_user = ?";
                    $stmt_user = $this->conection->prepare($sql_user);
                    if ($stmt_user) {
                        $stmt_user->bind_param('i', $user_id);
                        $stmt_user->execute();
                        $result_user = $stmt_user->get_result();
                        if ($result_user && $user_data = $result_user->fetch_assoc()) {
                            $data['usuario'] = $user_data['usuario'];
                            $data['foto_url'] = $user_data['foto_url'];
                        }
                        $stmt_user->close();
                    }
                }
            }

            if ($config == 'json') {
                // Asegurar que siempre retornamos un objeto, no un array vacío
                if ($data && is_array($data) && count($data) > 0) {
                    // Asegurar que id_reply esté presente (puede tener otro nombre)
                    if (!isset($data['id_reply'])) {
                        // Buscar cualquier columna que pueda ser el ID
                        foreach ($data as $key => $value) {
                            if (strpos(strtolower($key), 'id') !== false && strpos(strtolower($key), 'reply') !== false) {
                                $data['id_reply'] = $value;
                                break;
                            }
                        }
                    }
                    echo json_encode($data);
                } else {
                    echo json_encode(['error' => 'No se encontró la respuesta']);
                }
            } else {
                return $data;
            }
        } catch (Exception $e) {
            error_log("ERROR cargar_1_comentario_hijo: " . $e->getMessage());
            $this->TrackingLog(date('y-m-d h:i:s') . ' No se pudo cargar el comentario hijo ' . $e->getMessage(), 'errores');
            if ($config == 'json') {
                echo json_encode(['error' => $e->getMessage()]);
            } else {
                return null;
            }
        }
    }

    /**
     * Guarda una respuesta (hilo) a un comentario
     */
    function reply_coment($id_coment, $id_user, $text_coment) {
        $estado = $this->enable();
        $fecha = date('Ymdhis');
        $sql = "insert into reply_coment (coment_id, user_id, text_coment, fecha_creacion, estado) 
                values (?,?,?,?,?)";

        try {
            // Validar conexión
            if (!$this->conection || !($this->conection instanceof mysqli)) {
                throw new Exception('Conexión a la base de datos no válida');
            }
            
            $insertar = $this->conection->prepare($sql);
            if ($insertar === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->conection->error);
            }
            $insertar->bind_param('iisss', $id_coment, $id_user, $text_coment, $fecha, $estado);
            $insertar->execute();
            $id_reply = $this->conection->insert_id;
            $insertar->close();
            
            // Retornar el comentario hijo creado
            if ($id_reply) {
                $this->cargar_1_comentario_hijo($id_reply, 'json');
            } else {
                echo json_encode(['error' => 'No se pudo obtener el ID del comentario hijo']);
            }
        } catch (Exception $e) {
            error_log("ERROR reply_coment: " . $e->getMessage());
            $this->TrackingLog(date('y-m-d h:i:s') . ' El usuario ID: ' . $id_user . ' no pudo responder comentario ' . $e->getMessage(), 'errores');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Elimina una respuesta (hilo) de un comentario
     */
    function delete_coment_reply($id_reply) {
        $estado = $this->disable();
        $sql = "update reply_coment set estado = ? where id_reply = ?";

        try {
            $delete = $this->conection->prepare($sql);
            if ($delete === false) {
                throw new Exception('Error al preparar la consulta: ' . $this->conection->error);
            }
            $delete->bind_param('si', $estado, $id_reply);
            $delete->execute();
            $delete->close();
        } catch (Exception $e) {
            $this->TrackingLog(date('y-m-d h:i:s') . ' No se pudo eliminar la respuesta ' . $e->getMessage(), 'errores');
        }
    }

    // Mantener compatibilidad con código antiguo
    function load_childs_coment($id_coment, $config = 'asoc') {
        return $this->cargar_respuestas($id_coment);
    }
}

?>
