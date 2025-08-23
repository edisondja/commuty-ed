<?php

    class Favorito extends EncryptToken{

        public int $id_favorito;
        public int $id_post;
        public int $id_usuario;


        public function __construct() {
            


        }

        public function guardar_en_favorito(){

            $estado = $this->enable();
            $sql = "insert into favoritos(id_favorito,id_post,id_usuario)VALUES(?,?,?,?)";
            $guardar = $this->conection->prepare($sql);
            $guardar->bind_param('iiis',
                $this->id_favorito,
                $this->id_post,
                $this->id_usuario,
                $estado
            
            );

            $guardar->execute();

            $guardar->close();

        }

        public function cargar_mis_favoritos($config='asoc'){

            /*El parametro asoc es que retorna un array asociativo por defecto
                en caso de querer formato JSON colocas el formato
            */
            $sql = "select * from favoritos where id_usuario=? and estado=?";
            $estado = $this->enable();
            $cargar = $this->conection->prepare($sql);
            $cargar->bind_param('is',$this->id_usuario,$estado);
            $data = $cargar->get_result();

            if($config=='asoc'){
                
                return $data;

            }else if($config=='json'){

                
                $data = json_encode($data);
                echo $data;

            }

        }


        public function eliminar_favorito(){
            
            $estado = $this->disable();

            $sql = "update favoritos set estado=";


        }







    }


?>