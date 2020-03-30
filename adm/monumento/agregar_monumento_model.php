<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';  

    //Duplicada
    //Obtiene todos los usuarios:
    function allUsuario() {
        $query="SELECT * FROM usuario ORDER BY nombre_usuario;";
        $result=db_query($query);
        if(!$result){
            //que hacer en caso de fallo
            return array();
        }
        else{
            //que hacer en caso de exito
            return $result;
        }
    }
    
    function getSitio1($id) {
        
        $query = "SELECT a.titulo titulo,
                         a.descripcion_corta descripcion_corta,
                         a.latitud latitud,
                         a.longitud longitud,
                         a.estado_actual estado_actual,
                         a.id_usuario_campo usuario_edicion,
                         b.direccion_archivo direccion_archivo,
                         b.titulo titulo_media,
                         b.autor autor_media,
                         b.fuente fuente_media,
                         b.licencia licencia_media,
                         b.link link_media,
                         b.id_multimedia id_multimedia
                  FROM monumento a
                       INNER JOIN multimedia b
                           ON (a.foto_oficial = b.id_multimedia)
                  WHERE a.id_monumento = " . $id;
        
        $result = db_query($query);
        
        if(!$result) {
            //que hacer en caso de fallo
            return false;
        } else {
            //que hacer en caso de exito
            return mysqli_fetch_array($result);
        }
    }
    
    function crearSitio1($titulo,
        $estadoActual,
        $usuarioEdicion,
        $longitud,
        $latitud,
        $descripcionCorta,
        $fotoOficial
        ) {

            $connection = db_connect();
            
            //INICIO DE LA TRANSACCIÓN DE BASE DE DATOS:
            mysqli_begin_transaction($connection, MYSQLI_TRANS_START_READ_WRITE);
            
            $query = "INSERT INTO monumento (titulo,
                                             estado_actual,
                                             id_usuario_owner,
                                             id_usuario_campo,
                                             longitud,
                                             latitud,
                                             descripcion_corta,
                                             foto_oficial) VALUES (".
                                        "'".mysqli_real_escape_string($connection,$titulo)."',".
                                        "'".mysqli_real_escape_string($connection,$estadoActual)."',".
                                        //TODO revisar $uid que tenía esto antes.
                                        mysqli_real_escape_string($connection,$_SESSION['uid']).",".
                                        mysqli_real_escape_string($connection,$usuarioEdicion).",".
                                        mysqli_real_escape_string($connection,$longitud).",".
                                        mysqli_real_escape_string($connection,$latitud).",".
                                        "'".mysqli_real_escape_string($connection,$descripcionCorta)."',".
                                        $fotoOficial.");";
                                        
            $result = mysqli_query($connection, $query);
            
            if ($result) {
                                            
                $result = true;
                                            
                $idMonumento = mysqli_insert_id($connection);
                                            
                //MULTIMEDIA (IMAGEN DE PORTADA)
                /*$query_multimedia = "INSERT INTO multimedia_monumento (id_multimedia, id_monumento) VALUES (" .
                    $fotoOficial . ", " . $idMonumento . ");"
                ;
                                                
                $result = mysqli_query($connection, $query_multimedia);
                */
                                           
                if ($result) {
                    //COMMIT
                    mysqli_commit($connection);
                    return $idMonumento;
                } else {
                    //ROLLBACK
                    mysqli_rollback($connection);
                    return false;
                }
            } else {
                //ROLLBACK
                mysqli_rollback($connection);
                return false;
            }
    }
    
    
    function actualizarSitio1($idSitio,
        $titulo,
        $estadoActual,
        $usuarioEdicion,
        $longitud,
        $latitud,
        $descripcionCorta,
        $idImagenPortada
        ) {
            
            $connection = db_connect();
            
            //INICIO DE LA TRANSACCIÓN DE BASE DE DATOS:
            mysqli_begin_transaction($connection, MYSQLI_TRANS_START_READ_WRITE);
            
            $query = "UPDATE monumento SET 
                     titulo = '".mysqli_real_escape_string($connection,$titulo)."', 
                     estado_actual = '".mysqli_real_escape_string($connection,$estadoActual)."',
                     id_usuario_owner = ".mysqli_real_escape_string($connection,$_SESSION['uid']).",
                     id_usuario_campo = ".mysqli_real_escape_string($connection,$usuarioEdicion).",
                     longitud = ".mysqli_real_escape_string($connection,$longitud).",
                     latitud = ".mysqli_real_escape_string($connection,$latitud).",
                     descripcion_corta = '".mysqli_real_escape_string($connection,$descripcionCorta)."',
                     foto_oficial = ".$idImagenPortada."
                     WHERE id_monumento = ".$idSitio;
            
            
            
            $result = mysqli_query($connection, $query);
            
            if ($result) {
                
                /*$result = true;

                //MULTIMEDIA (IMAGEN DE PORTADA)
                $query_multimedia = "INSERT INTO multimedia_monumento (id_multimedia, id_monumento) VALUES (" .
                    $fotoOficial . ", " . $idSitio . ");"
                        ;
                        
                        $result = mysqli_query($connection, $query_multimedia);
                        
                        
                        if ($result) {*/
                            //COMMIT
                            mysqli_commit($connection);
                            return $idSitio;
                        } else {
                            //ROLLBACK
                            mysqli_rollback($connection);
                            return false;
                        }
            /*} else {
                //ROLLBACK
                mysqli_rollback($connection);
                return false;
            }*/
    }
?>