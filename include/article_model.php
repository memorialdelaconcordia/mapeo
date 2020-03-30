<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';  

    //TODO duplicado
    function getMultimediaMonumento($id){
        
        $query = "SELECT multimedia.id_multimedia as id_multimedia,
                         multimedia.autor as autor,
                         multimedia.fuente as fuente,
                         multimedia.licencia as licencia,
                         multimedia.link as link,
                         multimedia.direccion_archivo as direccion_archivo, 
                         multimedia.tipo as tipo, 
                         multimedia.titulo as titulo 
                  FROM multimedia_monumento, 
                       monumento, 
                       multimedia 
                  WHERE multimedia.id_multimedia = multimedia_monumento.id_multimedia 
                  AND multimedia_monumento.id_monumento = monumento.id_monumento 
                  AND monumento.id_monumento = '".$id."';";
        
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
    
    //TODO duplicado
    function getMultimediaMonumento2($id) {
        
        $query = "SELECT multimedia.id_multimedia as id_multimedia,
                         multimedia.autor as autor,
                         multimedia.fuente as fuente,
                         multimedia.licencia as licencia,
                         multimedia.link as link,
                         multimedia.direccion_archivo as direccion_archivo, 
                         multimedia.tipo as tipo, 
                         multimedia.titulo as titulo 
                  FROM multimedia_monumento, 
                       monumento, multimedia 
                  WHERE multimedia.id_multimedia = multimedia_monumento.id_multimedia 
                  AND multimedia_monumento.id_monumento = monumento.id_monumento 
                  AND monumento.id_monumento = '".$id."' ORDER BY rand();";
        
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
    
    //TODO duplicado
    function getVictimas($id){
        $query="SELECT multimedia.id_multimedia as id_multimedia,multimedia.autor as autor,multimedia.fuente as fuente,multimedia.licencia as licencia,multimedia.link as link,multimedia.direccion_archivo as direccion_archivo, multimedia.tipo as tipo, multimedia.titulo as titulo FROM multimedia_monumento, monumento, multimedia WHERE multimedia.id_multimedia = multimedia_monumento.id_multimedia AND multimedia_monumento.id_monumento = monumento.id_monumento AND monumento.id_monumento = '".$id."' ORDER BY rand();";
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
    
    //TODO duplicado
    function getMonumento($id){
        $query="SELECT DISTINCT m.id_monumento as id_monumento, 
                                m.titulo as titulo, 
                                m.identificador as identificador, 
                                m.estado_actual as estado_actual, 
                                mul.direccion_archivo as foto_oficial, 
                                m.id_tipo_monumento as id_tipo_monumento, 
                                m.id_tipo_evento as id_tipo_evento, 
                                m.latitud as latitud, 
                                m.longitud as longitud, 
                                m.estado_sitio as estado_sitio, 
                                m.direccion as direccion, 
                                m.ubicacion as ubicacion, 
                                m.como_llegar as como_llegar, 
                                m.acceso as requisitos,
                                m.descripcion as descripcion, 
                                m.descripcion_corta as descripcion_corta,
                                m.fecha_creacion as fecha_creacion,
                                m.actividades as actividades,
                                m.autor_obra as artista,
                                m.construccion_monumento as construccion_monumento,
                                m.apoyo_monumento as apoyo_monumento,
                                m.fecha_conmemoracion as fecha_conmemoracion,
                                mun.municipio as municipio,
                                dep.departamento as departamento,
                                tm.tipo_monumento as tipo_monumento,
                                o.nombre_organizacion as nombre_organizacion,
                                te.evento as evento,
                                CONCAT(pe.nombre, ' (', pe.periodo, ')') as periodo_estatal
               FROM monumento m 
                  LEFT JOIN multimedia mul 
                      on (m.foto_oficial = mul.id_multimedia) 
                  LEFT JOIN municipio mun
                      on (m.id_municipio = mun.id_municipio)
                  INNER JOIN departamento dep
                      on (mun.id_departamento = dep.id_departamento)
                  LEFT JOIN tipo_monumento tm
                      on (m.id_tipo_monumento = tm.id_tipo_monumento)
                  LEFT JOIN organizacion o
                      on (m.id_organizacion = o.id_organizacion)     
                  LEFT JOIN tipo_evento te
                      on (m.id_tipo_evento = te.id_tipo_evento)
                  LEFT JOIN periodo_estatal pe
                      on (m.periodo_estatal = pe.id_periodo)
               WHERE id_monumento = ".$id.";";
        
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
    
    //TODO duplicado
    function getNoticiasMonumento($id){
        $query="SELECT * FROM noticia WHERE id_monumento = '".$id."';";
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
    
    //TODO duplicado
    function getPersonasMonumento($id){
        
        $query = "SELECT persona.id_persona AS id_persona, 
                         persona.nombre as nombre, 
                         persona.id_sector as id_sector, 
                         persona.id_profesion as id_profesion 
                  FROM persona_monumento, 
                       persona 
                  WHERE persona_monumento.id_persona = persona.id_persona 
                  AND persona_monumento.id_monumento = '".$id."';";
        
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
    
    //TODO duplicado
    function getCampoValorAdicional($id){
        $query="SELECT DISTINCT ca.nombre as campo, v.valor as valor FROM valor_monumento vm LEFT JOIN valor v ON(vm.id_valor=v.id_valor) LEFT JOIN campo_adicional ca ON (v.id_campo_adicional=ca.id_campo_adicional) WHERE vm.id_monumento=".$id." AND ca.estado=1;";
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
    
    //TODO duplicado
    function getTipoMonumento($id_tipo_monumento){
        $query="SELECT * FROM tipo_monumento WHERE id_tipo_monumento=".$id_tipo_monumento;
        $result=db_query($query);
        if(!$result){
            //que hacer en caso de fallo
            return $result;
        }
        else{
            //que hacer en caso de exito
            return $result;
        }
    }
    
    //TODO duplicado
    function getTipoEvento($id_tipo_evento){
        $query="SELECT * FROM tipo_evento WHERE id_tipo_evento=".$id_tipo_evento;
        $result=db_query($query);
        if(!$result){
            //que hacer en caso de fallo
            return $result;
        }
        else{
            //que hacer en caso de exito
            return $result;
        }
    }
    
    //TODO duplicado
    function getMonumentosRelacionados($id_monumento){
        $query="SELECT * FROM monumento WHERE estado_actual ='Publicado' AND id_monumento !=".$id_monumento." ORDER BY rand() LIMIT 5";
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

?>

