<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';

    function getSitio3($id) {
        
        $query = "SELECT a.id_organizacion id_organizacion,
                         a.persona_informacion persona_informacion,
                         DATE_FORMAT(a.fecha_investigacion, '%m/%d/%Y') fecha_investigacion,
                         a.is_reportaje is_reportaje,
                         a.descripcion descripcion
                  FROM monumento a
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

    function allOrganizacion(){
        $query="SELECT * FROM organizacion ORDER BY nombre_organizacion;";
        $result=db_query($query);
        if(!$result) {
            //que hacer en caso de fallo
            return array();
        } else {
            //que hacer en caso de exito
            return $result;
        }
    }
    
    function crearSitio3($organizacion,
        $personaInformacion,
        $fechaInvestigacion,
        $reportaje,
        $descripcion,
        $idMonumento
        ) {

        $connection = db_connect();
            
        //INICIO DE LA TRANSACCIÓN DE BASE DE DATOS:
        mysqli_begin_transaction($connection, MYSQLI_TRANS_START_READ_WRITE);
        
        if ($organizacion == "") {
            $organizacion = "NULL";
        }

        
        $query = "UPDATE monumento ". 
                 "SET id_organizacion = ".$organizacion.", ".
                 "persona_informacion = '".$personaInformacion."', ".
                 "fecha_investigacion = STR_TO_DATE('".$fechaInvestigacion."', '%d/%m/%Y'), ".
                 "is_reportaje = ".$reportaje.", ".
                 "descripcion = '".$descripcion."' ".
                 "WHERE id_monumento = ".$idMonumento.";";

        $result = mysqli_query($connection, $query);
            
        if ($result) {
                                            
            if ($result) {
                //COMMIT
                mysqli_commit($connection);
                return true;
            } else {
                //ROLLBACK
                error_log("Error1");
                mysqli_rollback($connection);
                return false;
            }

        } else {
            //ROLLBACK
            error_log("Error2");
            mysqli_rollback($connection);
            return false;
        }
    }

    function getMultimediaMonumento($id){
        
        $query="SELECT C.id_multimedia as id_multimedia,
                      C.autor as autor,
                      C.fuente as fuente,
                      C.licencia as licencia,
                      C.link as link,
                      C.direccion_archivo as direccion_archivo,
                      C.tipo as tipo,
                      C.titulo as titulo
              FROM multimedia_monumento A,
                   monumento B,
                   multimedia C
              WHERE A.id_multimedia =  C.id_multimedia
              AND   A.id_monumento = B.id_monumento
              AND   B.id_monumento = ".$id.
              " AND   B.foto_oficial <> C.id_multimedia";
        
        $result = db_query($query);
        
        if(!$result){
            return array();
        } else {
            return $result;
        }
    }
    
    function eliminarMultimedia($idMultimedia, $idMonumento) {
        
        //Obtener el path del archivo...
        $query="SELECT direccion_archivo
              FROM multimedia
              WHERE id_multimedia = ".$idMultimedia.";";
        
        $result = db_query($query);
        
        //TODO verificar $result
        
        $row = mysqli_fetch_array($result);
        
        $direccion_archivo = $row['direccion_archivo'];
        
        error_log("asdfasdfasdf " . $direccion_archivo);
        
        $connection = db_connect();
        
        //INICIO DE LA TRANSACCIÓN DE BASE DE DATOS:
        mysqli_begin_transaction($connection, MYSQLI_TRANS_START_READ_WRITE);
        
        $query = "DELETE FROM multimedia_monumento
                  WHERE id_multimedia = " . $idMultimedia .
                  " AND id_monumento = " . $idMonumento;
        
        $result = mysqli_query($connection, $query);
        
        if ($result) {
            
            $query = "DELETE FROM multimedia
                  WHERE id_multimedia = " . $idMultimedia;
            
            $result = mysqli_query($connection, $query);
            
            if ($result) {
                //COMMIT
                mysqli_commit($connection);
                return true;
            } else {
                //ROLLBACK
                error_log("Error1");
                mysqli_rollback($connection);
                return false;
            }
            
        } else {
            //ROLLBACK
            error_log("Error2");
            mysqli_rollback($connection);
            return false;
        }
        
        //Eliminar el archivo...
        
        
    }
    
    function registrarMultmediaMonumento($idImagen, $idMonumento) {
        
        $connection = db_connect();
        
        //Se eliminan los registros existentes:
        $query = "INSERT INTO multimedia_monumento (id_multimedia, id_monumento) VALUES (" . $idImagen . ", " . $idMonumento . ")";
        
        $result = mysqli_query($connection, $query);
        
        if ($result) {
            //COMMIT
            mysqli_commit($connection);
            return true;
        } else {
            //ROLLBACK
            error_log("Error1");
            mysqli_rollback($connection);
            return false;
        }
    }
    
    function getNoticiasMonumento($id){
        
        $query="SELECT id_noticia,
                       titulo,
                       link,
                       DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha,
                       fuente
              FROM noticia
              WHERE id_monumento = ".$id.";";
        
        $result = db_query($query);
        
        if(!$result){
            return array();
        } else {
            return $result;
        }
    }  
    
    function crearNuevaNoticia($titulo,
        $link,
        $fecha,
        $fuente,
        $idMonumento) {
        
        $connection = db_connect();
        
        $query = "INSERT INTO noticia (titulo,
    										link,
    										fecha,
    										fuente,
    										id_monumento) VALUES ('".
    										mysqli_escape_string($connection, $titulo)."','".
    										mysqli_escape_string($connection, $link)."',".
    										"STR_TO_DATE('".$fecha."', '%d/%m/%Y'),' ".
    										mysqli_escape_string($connection, $fuente)."',".
    										$idMonumento.");";
    										
    	$result = mysqli_query($connection, $query);
    										
		if (!$result) {
		    return false;
		} else {
		    return true;
		}
        
    }
    
    
    function actualizarNoticia($titulo,
        $link,
        $fecha,
        $fuente,
        $idNoticia) {
            
            $connection = db_connect();
            
            $query = "UPDATE noticia 
                      SET titulo = '".mysqli_escape_string($connection, $titulo)."',
                          link = '".mysqli_escape_string($connection, $link)."',
                          fecha = STR_TO_DATE('".$fecha."', '%d/%m/%Y'),
    					  fuente = '".mysqli_escape_string($connection, $fuente)."'
                      WHERE id_noticia = ".$idNoticia;
								
			$result = mysqli_query($connection, $query);
			
			if (!$result) {
			    return false;
			} else {
			    return true;
			}
    										
    }
    
?>