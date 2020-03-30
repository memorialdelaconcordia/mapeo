<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Sitio.php';
    

    function allTipoEvento() {
        $query="SELECT * FROM tipo_evento WHERE estado=1 ORDER BY evento;";
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

    function allTiposMonumento() {
        $query="SELECT * FROM tipo_monumento WHERE estado=1 ORDER BY tipo_monumento;";
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
    
    function allPeriodoEstatal() {
        $query = "SELECT id_periodo, nombre, periodo FROM periodo_estatal ORDER BY id_periodo ASC;";
        $result = db_query($query);
        if(!$result){
            //Fallo:
            return array();
        }
        else{
            return $result;
        }
    } 
    
    function municipiosDepto() {
        $query="SELECT * FROM municipio, departamento WHERE municipio.id_departamento = departamento.id_departamento ORDER BY municipio;";
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
    
    function allPersonas() {
        $query="SELECT * FROM persona WHERE estado=1 ORDER BY nombre;";
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
    

    function allGenero(){
        $query="SELECT * FROM genero ORDER BY genero;";
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
    
    function allSector(){
        $query="SELECT * FROM sector WHERE estado=1 ORDER BY sector;";
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
    
    function allProfesion(){
        $query="SELECT * FROM profesion WHERE estado=1 ORDER BY profesion;";
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
    

    function allPais(){
        $query="SELECT * FROM pais ORDER BY pais";
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
    
    function allOrganizacion() {
        $query="SELECT * FROM organizacion ORDER BY nombre_organizacion;";
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
    
    function allCampoActivo(){
        $query="SELECT * FROM campo_adicional WHERE estado = 1 ORDER BY nombre;";
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
    
    function allValorCampoActivo($id_campo_valor) {
        $query="SELECT * FROM valor WHERE estado = 1 AND id_campo_adicional=".$id_campo_valor." ORDER BY valor;";
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

 
    function getSitio2($id) {
        
        //keywords
        //victimas
        //campos
        
        $sitio = new Sitio();
        
        $query = "SELECT a.id_tipo_evento id_tipo_evento,
                         a.periodo_estatal periodo_estatal,
                         DATE_FORMAT(a.fecha_conmemoracion, '%m/%d/%Y') fecha_conmemoracion,
                         a.id_tipo_monumento id_tipo_monumento,
                         a.estado_sitio estado_sitio,
                         a.id_municipio id_municipio,
                         a.direccion direccion,
                         a.ubicacion ubicacion,
                         a.como_llegar como_llegar,
                         a.acceso acceso,
                         DATE_FORMAT(a.fecha_creacion, '%m/%d/%Y') fecha_creacion,
                         a.construccion_monumento construccion_monumento,
                         a.apoyo_monumento apoyo_monumento,
                         a.id_organizacion_responsable id_organizacion_responsable,
                         a.actividades actividades,
                         a.autor_obra autor_obra
                  FROM monumento a
                  WHERE a.id_monumento = " . $id;
        
        $result = db_query($query);
        
        if(!$result) {
            //que hacer en caso de fallo
            return false;
        } else {
            
            $sitio->datosGenerales = mysqli_fetch_array($result);
            
            $query = "SELECT a.keyword
                  FROM keywords a
                  WHERE a.id_monumento = " . $id;
            
            $result = db_query($query);
            
            if(!$result) {
                return false;
            } else {
             
                $sitio->keywords = $result;
                
                $query = "SELECT b.id_persona,
                                 b.nombre
                  FROM persona_monumento a,
                       persona b
                  WHERE a.id_persona = b.id_persona 
                    AND a.id_monumento = " . $id;
                
                $result = db_query($query);
                
                if(!$result) {
                    return false;
                } else {
                
                    $sitio->victimas = $result;
                    
                    $query = "SELECT valor.id_campo_adicional id_campo_adicional,
                                     valor.id_valor id_valor
                              FROM valor,
                                   valor_monumento
                              WHERE valor_monumento.id_valor = valor.id_valor
                                AND valor_monumento.id_monumento = '".$id."'";
                    
                    $result = db_query($query);
                    
                    if(!$result) {
                        return false;
                    } else {
                        
                        $sitio->camposAdicionales = $result;
                        
                        return $sitio;
                    }
                }
            }
        }
    }
    
    function crearSitio2($keywords,
        $tipoEvento,
        $tipoMonumento,
        $estadoSitio,
        $municipio,
        $periodoEstatal,
        $conmemoracion,
        $victimas,
        $direccion,
        $ubicacion,
        $comoLlegar,
        $acceso,
        $camposAdicionales,
        $fechaCreacion,
        $construccionMonumento,
        $apoyoMonumento,
        $organizacionResponsable,
        $actividades,
        $autorObra,
        $idMonumento
        ) {

        $connection = db_connect();
            
        //INICIO DE LA TRANSACCIÓN DE BASE DE DATOS:
        mysqli_begin_transaction($connection, MYSQLI_TRANS_START_READ_WRITE);
            
        $query = "UPDATE monumento ". 
                 "SET id_tipo_evento = ".$tipoEvento.", ".
                 "id_tipo_monumento = ".$tipoMonumento.", ".
                 "estado_sitio = '".$estadoSitio."', ".
                 "id_municipio = ".$municipio.", ".
                 "periodo_estatal = '".$periodoEstatal."', ".
                 "fecha_conmemoracion = STR_TO_DATE('".$conmemoracion."', '%d/%m/%Y'), ".
                 "fecha_creacion = STR_TO_DATE('".$fechaCreacion."', '%d/%m/%Y'), ".
                 "direccion = '".$direccion."', ".
                 "ubicacion = '".$ubicacion."', ".
                 "como_llegar = '".$comoLlegar."', ".
                 "acceso = '".$acceso."', ".
                 "construccion_monumento = '".$construccionMonumento."', ".
                 "apoyo_monumento = '".$apoyoMonumento."', ".
                 "id_organizacion_responsable = ".$organizacionResponsable.", ".
                 "actividades = '".$actividades."', ".
                 "autor_obra = '".$autorObra."' ".
                 "WHERE id_monumento = ".$idMonumento.";";
               
        error_log(print_r($query,true));
        
        $result = mysqli_query($connection, $query);
            
        if ($result) {
                                            
            $result = true;

            /********************************/
            /**** VÍCTIMAS ******************/
            
            //Se eliminan los registros existentes: 
            $query = "DELETE FROM persona_monumento WHERE id_monumento = " . $idMonumento . ";";
            
            $result = mysqli_query($connection, $query);
            
            if ($result) {
                
                $result = true;
                
                if(sizeof($victimas) > 0) {
                    
                    $query_values = array();
                    foreach ($victimas as $victima) {
                        $query_values[] = "(" . $victima . "," . $idMonumento . ")";
                    }
                    
                    $query = "INSERT INTO persona_monumento (id_persona, id_monumento) VALUES " . implode(",", $query_values);

                    $result = mysqli_query($connection, $query);
                }
                
                if ($result) {
                    
                    $result = true;
                    
                    /********************************/
                    /**** KEYWORDS ******************/
                    //Se eliminan los registros existentes:
                    $query = "DELETE FROM keywords WHERE id_monumento = " . $idMonumento . ";";
                    
                    $result = mysqli_query($connection, $query);
                    
                    if ($result) {
                    
                        $result = true;
                        
                        if(sizeof($keywords) > 0) {
                            
                            $query_values = array();
                            foreach ($keywords as $keyword) {
                                $query_values[] = "(" . $idMonumento . ", '" . $keyword . "')";
                            }
                            
                            $query = "INSERT INTO keywords (id_monumento, keyword) VALUES " . implode(",", $query_values);
                            
                            $result = mysqli_query($connection, $query);
                        }
                        
                        if ($result) {
                            
                            $result = false;
                           
                            /********************************/
                            /**** CAMPOS ADICIONALES ******************/
                            
                            //Se eliminan los registros existentes:
                            $query = "DELETE FROM valor_monumento WHERE id_monumento = " . $idMonumento . ";";
                            
                            $result = mysqli_query($connection, $query);
                            
                            if ($result) {
                                
                                $result = true;
                                
                                if(sizeof($camposAdicionales) > 0) {
                                    
                                    $query_values = array();
                                    foreach ($camposAdicionales as $campo => $valor) {
                                        $query_values[] = "(" . $valor . ", " . $idMonumento . ")";
                                    }
                                    
                                    $query = "INSERT INTO valor_monumento (id_valor, id_monumento) VALUES " . implode(",", $query_values);
                                    
                                    $result = mysqli_query($connection, $query);
                                }
                                
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
                                //ROLLBACK - Error en el DELETE FROM valor_monumento.
                                error_log("Error2");
                                mysqli_rollback($connection);
                                return false;
                            }
                        } else {
                            //ROLLBACK - Error en el INSERT INTO keywords.
                            error_log("Error3");
                            mysqli_rollback($connection);
                            return false;
                        }
                        
                    } else {
                        //ROLLBACK - Error en el DELETE FROM keywords.
                        error_log("Error4");
                        mysqli_rollback($connection);
                        return false;
                    }
                                        
                } else {
                    //ROLLBACK - Error en el INSERT INTO persona_monumento.
                    error_log("Error5");
                    mysqli_rollback($connection);
                    return false;
                }

            } else {
                //ROLLBACK - Error en el DELETE FROM persona_monumento.
                error_log("Error6");
                mysqli_rollback($connection);
                return false;
            }

        } else { 
            //ROLLBACK - Error en el UPDATE monumento.
            error_log("Error7");
            mysqli_rollback($connection);
            return false;
        }
    }

?>