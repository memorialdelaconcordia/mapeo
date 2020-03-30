<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';  

    function getNoticias($start, $limit) {
        
        $query = "SELECT a.titulo,
                         a.texto,
                         a.autor,
                         a.fecha
                  FROM noticia_web a
                  WHERE a.estado = 1"; //Estado: publicada.
        
        if(($start > -1) && ($limit > -1)) {
            $query .= " ORDER BY a.fecha DESC limit $start ,$limit ";
        }

        $result = db_query($query);
        
        if(!$result){
            //que hacer en caso de fallo
            return array();
        }
        else{
            //que hacer en caso de exito
            return $result;
        }
    }
  
    function getSitiosRecientes() {
        
        $query = "SELECT a.titulo,
                         a.id_monumento
                  FROM monumento a
                  WHERE a.estado_actual = 'Publicado'
                  ORDER by fecha_creacion DESC LIMIT 6";
        
        $result = db_query($query);
        
        if(!$result){
            //que hacer en caso de fallo
            return array();
        }
        else{
            //que hacer en caso de exito
            return $result;
        }
    }
    
?>