<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';  

    function allDepartamentos(){
        $query="SELECT * FROM departamento;";
        //Agregar el WHERE para los filtros
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
    
    function allEventos(){
        $query="SELECT * FROM tipo_evento WHERE estado=1;";
        //Agregar el WHERE para los filtros
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
    function allPersonas(){
        $query="SELECT id_persona, nombre FROM persona WHERE estado=1;";
        //Agregar el WHERE para los filtros
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
    
    function allTipoMonumentos(){
        $query="SELECT * FROM tipo_monumento WHERE estado=1;";
        //Agregar el WHERE para los filtros
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
    
    //TODO duplicada
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
    
    //TODO duplicado
    function getCampos(){
        $query="SELECT * FROM campo_adicional WHERE filtrable=1;";
        //Agregar el WHERE para los filtros
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
    function getValorCampo($id_campo){
        $query="SELECT * FROM valor WHERE estado=1 AND id_campo_adicional = ".$id_campo.";";
        //Agregar el WHERE para los filtros
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
    
    function getSitiosExplorar($start, $limit) {
        
        //TODO INNER JOIN?
        $query = "SELECT DISTINCT a.id_monumento,
                         a.titulo titulo,
                         a.descripcion_corta descripcion_corta,
                         b.direccion_archivo direccion_archivo,
                         d.departamento departamento
                  FROM monumento a
                       INNER JOIN multimedia b
                           ON (a.foto_oficial = b.id_multimedia)
                       LEFT OUTER JOIN municipio c
                           ON (a.id_municipio = c.id_municipio)
                       INNER JOIN departamento d
                           ON (c.id_departamento = d.id_departamento)
                       LEFT OUTER JOIN persona_monumento pm 
                           ON (a.id_monumento = pm.id_monumento)
                       LEFT OUTER JOIN periodo_estatal pe
                           ON (a.periodo_estatal = pe.id_periodo)
                  WHERE a.estado_actual = 'Publicado'";
        
        $conditions = array();
        
        if(isset($_GET['dp']) && (count($_GET['dp']) > 0)) {
            
            $conditions[] = "c.id_departamento IN (" . implode(',', $_GET['dp']) . ")";
            
        }
        
        if(isset($_GET['dl']) && (count($_GET['dl']) > 0)) {
            
            $conditions[] = "a.id_tipo_evento IN (" . implode(',', $_GET['dl']) . ")";
            
        }
        
        if(isset($_GET['ts']) && (count($_GET['ts']) > 0)) {
            
            $conditions[] = "a.id_tipo_monumento IN (" . implode(',', $_GET['ts']) . ")";
            
        }
        
        if(!empty(trim($_GET['nm']))) {
            
            $conditions[] = "UPPER(a.titulo) LIKE UPPER('%" . trim($_GET['nm']) . "%')";
            
        }
        
        if(isset($_GET['vic']) && (count($_GET['vic']) > 0)) {
            
            $conditions[] = "pm.id_persona IN (" . implode(',', $_GET['vic']) . ")";
            
        }
        
        if(!empty(trim($_GET['est']))) {
            
            $conditions[] = "a.estado_sitio = '" . trim($_GET['nm']) . "'";
            
        }
        
        if(isset($_GET['gob']) && (count($_GET['gob']) > 0)) {
            
            $conditions[] = "pe.id_periodo IN (" . implode(',', $_GET['gob']) . ")";
            
        }
        
        if (count($conditions) > 0) {
            $query .= " AND " . implode(' AND ', $conditions);
        }
        
        if(($start > -1) && ($limit > -1)) {
            if(!empty($_GET['or'])) {
                
                if($_GET['or'] == "departamento") {
                    $query .= " ORDER BY c.id_departamento limit $start ,$limit ";
                } elseif ($_GET['or'] == "titulo") {
                    $query .= " ORDER BY a.titulo ASC limit $start ,$limit ";
                }
                
            } else {
                $query .= " ORDER BY a.id_monumento ASC limit $start ,$limit ";
            }
        }
        
        
        error_log($query);
        
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