<?php 

    function getMonumentoCompleto($id){
      $query="SELECT * FROM monumento WHERE id_monumento = ".$id.";";
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


  
    function updateMultimedia($idMedia,
                                $titulo,
                                $autor,
                                $fuente,
                                $licencia,
                                $link,
                                $direccion_archivo,
                                $tipo_archivo){
        
        $connection = db_connect();
        
        $query = "UPDATE multimedia 
                    SET titulo='".mysqli_real_escape_string($connection,$titulo).
                    "', autor='".mysqli_real_escape_string($connection,$autor).
                    "', fuente='".mysqli_real_escape_string($connection,$fuente).
                    "', licencia='".mysqli_real_escape_string($connection,$licencia).
                    "', link='".mysqli_real_escape_string($connection,$link).
                    "', direccion_archivo='".mysqli_real_escape_string($connection,$direccion_archivo).
                    "', tipo='".mysqli_real_escape_string($connection,$tipo_archivo).
                    "' WHERE id_multimedia=".$idMedia.";";
                    
        $result = mysqli_query($connection,$query);
        
        if(!$result){
            //que hacer en caso de fallo
            return false;
        }
        else{
            //que hacer en caso de exito
            return true;
        }	
    }  
  
    function getMultimedia($idMedia){
        $query="SELECT * FROM multimedia WHERE id_multimedia=".$idMedia.";";
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
  
    function updateMonumento($id_monumento,
                             $titulo,
                             $estado_actual,
                             $usuario_campo,
                             $id_tipo_evento,
                             $id_tipo_monumento,
                             $estado_sitio,
                             $id_municipio,
                             $periodo_estatal,
                             $conmemoracion,
                             $personas,
                             $organizacion,
                             $organizacionResponsable,
                             $persona_informacion,
                             $fecha_investigacion,
                             $uid,$direccion,
                             $ubicacion,
                             $como_llegar,
                             $acceso,
                             $longitud,
                             $latitud,
                             $construccion_monumento,
                             $apoyo_monumento,
                             $fecha_creacion,
                             $descripcion,
                             $actividades,
                             $autor_obra,
                             $foto_oficial,
                             $tituloNoticia,
                             $linkNoticia,
                             $fechaNoticia,
                             $fuenteNoticia,
                             $keywords,
                             $multimediaArchivos,
                             $arrayCamposExtra,
                             $idMedia,
                             $descripcion_corta,
                             $is_reportaje) {
                                 
        $caracteristica_cuantitativa = "uno";
        
        if(sizeof($personas)==1){
            $caracteristica_cuantitativa = "uno";
        }
        
        if(sizeof($personas)>1){
            $caracteristica_cuantitativa = "varios";
        } else {
            $caracteristica_cuantitativa = sizeof($personas);
        }
        if($foto_oficial==""||!(isset($foto_oficial))){
            $foto_oficial="NULL";
        }

        if($conmemoracion==""||!(isset($conmemoracion))){
            $conmemoracion="NULL";
        }
        else{
            $conmemoracion="'".$conmemoracion."'";
        }

        if($fecha_investigacion==""||!(isset($fecha_investigacion))){
            $fecha_investigacion="NULL";
        }
        else{
            $fecha_investigacion="'".$fecha_investigacion."'";
        }

        if($fecha_creacion==""||!(isset($fecha_creacion))){
            $fecha_creacion="NULL";
        }
        else{
            $fecha_creacion="'".$fecha_creacion."'";
        }

        

        $query = "UPDATE monumento ".
                 "SET titulo = '".$titulo."', ". 
                 "estado_actual = '".$estado_actual."', ".
                 "id_usuario_owner = ".$uid.", ".
                 "id_usuario_campo = ".$usuario_campo.", ".
                 "id_tipo_evento = ".$id_tipo_evento.", ".
                 "id_tipo_monumento = ".$id_tipo_monumento.", ".
                 "estado_sitio = '".$estado_sitio."', ".
                 "id_municipio = ".$id_municipio.", ".
                 "periodo_estatal = '".$periodo_estatal."', ".
                 "fecha_conmemoracion = ".$conmemoracion.", ".
                 "caracteristica_cuantitativa = '".$caracteristica_cuantitativa."', ".
                 "id_organizacion = ".$organizacion.", ".
                 "id_organizacion_responsable = ".$organizacionResponsable.", ".
                 "persona_informacion = '".$persona_informacion."', ".
                 "fecha_investigacion = ".$fecha_investigacion.", ".
                 "persona_proceso_informacion = ".$uid.", ".
                 "direccion = '".$direccion."', ".
                 "ubicacion = '".$ubicacion."', ".
                 "como_llegar = '".$como_llegar."', ".
                 "acceso = '".$acceso."', ".
                 "longitud = ".$longitud.", ".
                 "latitud = ".$latitud.", ".
                 "construccion_monumento = '".$construccion_monumento."', ".
                 "apoyo_monumento = '".$apoyo_monumento."', ".
                 "fecha_creacion = ".$fecha_creacion.", ".
                 "descripcion = '".$descripcion."', ".
                 "actividades = '".$actividades."', ".
                 "autor_obra = '".$autor_obra."', ".
                 "descripcion_corta = '".$descripcion_corta."', ".
                 "is_reportaje = ".$is_reportaje;
        
        
        if($foto_oficial!=""){
            $query.= ", foto_oficial=".$foto_oficial."";
        }
        
        $query.= " WHERE id_monumento=".$id_monumento.";";
        $result=db_query($query);
        $result2=false;
        $result3=false;
        $result4=false;
        $result5=false;
        $result6=false;
        $result7=false;
        $result8=false;
        

        //VÃ�CTIMAS 
        $query_check_personas = "SELECT * FROM persona_monumento WHERE id_monumento = ".$id_monumento.";";
        $result_check_personas = db_query($query_check_personas);
        $column_personas = array();
        while($row=mysqli_fetch_array($result_check_personas)){
            $column_personas[] = $row['id_persona'];
        }

        $query_insert_personas = "INSERT INTO persona_monumento(id_persona, id_monumento) VALUES ";
        $query_insert_personas_values = "";
        foreach ($personas as $k => $v) {
            if(!in_array($v, $column_personas)){
                $query_insert_personas_values.=" (".$v.",".$id_monumento."),";
            }
        }
        if($query_insert_personas_values!=""){
            $query_insert_personas_values = substr_replace($query_insert_personas_values, ";", -1);
            $query_insert_personas .= $query_insert_personas_values;
            $result2=db_query($query_insert_personas);	
            // to_console($query_insert_personas);
            // $result2=true;
        }
        else{
            $result2 = true;
        }


        $query_delete_personas = "DELETE FROM persona_monumento WHERE id_monumento=".$id_monumento." AND ";
        $query_delete_personas_values = "";
        foreach ($column_personas as $k => $v) {
            if(!in_array($v, $personas)){
                $query_delete_personas_values.=" id_persona =".$v." OR";
            }
        }
        if($query_delete_personas_values!=""){
            $query_delete_personas_values = substr_replace($query_delete_personas_values, ";", -2);
            $query_delete_personas .= $query_delete_personas_values;
            // to_console($query_delete_personas);
            // $result4=true;
            $result3=db_query($query_delete_personas);	
        }
        else{
            $result3 = true;
        }


        //MULTIMEDIA
        $query_check_multimedia = "SELECT * FROM multimedia_monumento WHERE id_monumento = ".$id_monumento.";";
        $result_check_multimedia = db_query($query_check_multimedia);
        $column_multimedia = array();
        while($row=mysqli_fetch_array($result_check_multimedia)){
            $column_multimedia[] = $row['id_multimedia'];
        }

        $query_insert_multimedia = "INSERT INTO multimedia_monumento(id_multimedia, id_monumento) VALUES ";
        $query_insert_multimedia_values = "";
        foreach ($multimediaArchivos as $k => $v) {
            if(!in_array($v, $column_multimedia)){
                $query_insert_multimedia_values.=" (".$v.",".$id_monumento."),";
            }
        }
        if($query_insert_multimedia_values!=""){
            $query_insert_multimedia_values = substr_replace($query_insert_multimedia_values, ";", -1);
            $query_insert_multimedia .= $query_insert_multimedia_values;
            $result4=db_query($query_insert_multimedia);	
            // to_console($query_insert_multimedia);
            // $result2=true;
        }
        else{
            $result4 = true;
        }


        $query_delete_multimedia = "DELETE FROM multimedia_monumento WHERE id_monumento=".$id_monumento." AND ";
        $query_delete_multimedia_values = "";
        foreach ($column_multimedia as $k => $v) {
            if(!in_array($v, $idMedia)){
                $query_delete_multimedia_values.=" id_multimedia =".$v." OR";
            }
        }
        if($query_delete_multimedia_values!=""){
            $query_delete_multimedia_values = substr_replace($query_delete_multimedia_values, ";", -2);
            $query_delete_multimedia .= $query_delete_multimedia_values;
            // to_console($query_delete_multimedia);
            // $result4=true;
            $result5=db_query($query_delete_multimedia);	
        }
        else{
            $result5 = true;
        }

        //KEYWORDS
        //AQUÃ� AL PARECER SE BORRAN TODAS Y SE INSERTAN DE NUEVO...
        $query_delete_kws = "DELETE FROM keywords WHERE id_monumento=".$id_monumento.";";
        $res_delete = db_query($query_delete_kws);
        if ($res_delete){
            $query_kw = "INSERT INTO keywords(id_monumento, keyword) VALUES ";

            $query_insert_kw_values="";
            if(!empty($keywords)){
                foreach ($keywords as $k => $v) {
                    $query_insert_kw_values.=" (".$id_monumento.",'".$v."'),";
                }
            }
            
            if($query_insert_kw_values!=""){
                $query_insert_kw_values = substr_replace($query_insert_kw_values, ";", -1);
                $query_kw .= $query_insert_kw_values;
                $result6=db_query($query_kw);
            }
            else{
                $result6 = true;
            }

        }


        //NOTICIAS
        //AQUÃ� AL PARECER SE BORRAN TODAS Y SE INSERTAN DE NUEVO...
        $query_delete_kws = "DELETE FROM noticia WHERE id_monumento=".$id_monumento.";";
        $res_delete = db_query($query_delete_kws);
        if ($res_delete){
            $query_noticia="INSERT INTO noticia(id_monumento,titulo,link,fecha,fuente) VALUES ";
            $query_insert_noticia_values="";
            to_console($tituloNoticia);
            for ($i = 0; $i < count($tituloNoticia); $i++) {
                if($tituloNoticia[$i]!=" "){
                    $query_insert_noticia_values.=" (".$id_monumento.",'".$tituloNoticia[$i]."','".$linkNoticia[$i]."','".$fechaNoticia[$i]."','".$fuenteNoticia[$i]."'),";
                }
            }
            
            if($query_insert_noticia_values!=""){
                
                $query_insert_noticia_values = substr_replace($query_insert_noticia_values, ";", -1);
                $query_noticia .= $query_insert_noticia_values;
                $result7=db_query($query_noticia);
            }
            else{
                $result7 = true;
            }
        }
        echo "NO borraron las noticias correctamente";

        //CAMPOS EXTRA
        //primero borrar todas las relaciones existentes
        $deleteQuery="DELETE FROM valor_monumento WHERE id_monumento=".$id_monumento;
        db_query($deleteQuery);
        //crear las nuevas relaciones
        //$result7=false;
        $query_valores = "INSERT INTO valor_monumento(id_valor, id_monumento) VALUES ";
        $query_valores_insert="";
        foreach ($arrayCamposExtra as $k => $v) {

            $query_valores_insert.=" (".$v.",".$id_monumento."),";
        }
        if($query_valores_insert!=""){
            $query_valores.=$query_valores_insert;
            $query_valores = substr_replace($query_valores, ";", -1);
            $result8 = db_query($query_valores);
        }
        else{
            $result8=true;
        }
        if(!$result || !$result2 || !$result3 || !$result4 || !$result5 || !$result6 || !$result7 || !$result8){
            //que hacer en caso de fallo
            echo "result " . $result;
            echo "result2 " . $result2;
            echo "result3 " . $result3;
            echo "result4 " . $result4;
            echo "result5 " . $result5;
            echo "result6 " . $result6;
            echo "result7 " . $result7;
            echo "result8 " . $result8;
            
            return false;
        }
        else{
            //que hacer en caso de exito
            return true;
        }
    }  
  
?>