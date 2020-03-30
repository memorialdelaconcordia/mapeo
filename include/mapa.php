<?php
    include "include/globals.php";
    /* Se comenta por estar obsoleto:
	
		$con=mysql_connect($host,$username,$password) or die (mysql_error());
		$db=mysql_select_db($db_name,$con) or die (mysql_error());
    */

	function db_connect() {
		//Tomado de: https://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17
	
		// Define connection as a static variable, to avoid connecting more than once 
		static $connection;

		// Try and connect to the database, if a connection has not been established yet
		if(!isset($connection)) {
			$host="localhost"; 				// Host 
			$username="DB_USERNAME"; 				// Mysql username
			$password="DB_PASSWORD"; 			// Mysql password
			$db_name="DB_NAME"; 				// Database			
			
			$connection = mysqli_connect($host, $username, $password, $db_name);
		}

		// If connection was not successful, handle the error
		if($connection === false) {
			die("Connection error: " . mysqli_connect_error());
		}
		
		mysqli_set_charset($connection,"utf8");

		return $connection;
	}	
	
	function mediapath(){
    	global $root_path;
		return "'http://".$root_path."multimedia/'";
    }
	
    function db_query($query){
		// Connect to the database
		$connection = db_connect();
		
        $result = mysqli_query($connection, $query);
        if(!$result){
            echo "Error for ".$query."<br>".mysqli_error()."<br>";
        }
        return $result;
    }
	
    function getInfo() {
        $query = "SELECT * FROM static_page WHERE nombre = 'Info';";
        
        $result = db_query($query);
        if(!$result) {
            // TODO
        } else {
            /* fetch associative array */
            $row = $result->fetch_assoc();
            
            return $row["contenido"];
        }
    }
    
    function getMenu($opcionesAdicionales){
		$result=allPagina();
		$menu="<ul>";
		while($row=mysqli_fetch_assoc($result)){
			if($row['estado']=='activo'){
				$menu=$menu.'<li><a href="/pageview.php?id='.$row['id_static_page'].'">'.$row['nombre'].'</a></li>';
			}
		}
		$menu=$menu.$opcionesAdicionales."</ul>";
		return $menu;
	}
	
	function allPagina(){
		$query="SELECT * FROM static_page";
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
	
    function to_console( $data ) {

        if ( is_array( $data ) )
            $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
        else
            $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

        echo $output;
    }

    function puntos_gps(){
        $query="SELECT * FROM monumento;";
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

    function thumbnails($filter){
        $query="SELECT * FROM monumento JOIN multimedia ON (monumento.foto_oficial=multimedia.id_multimedia);";
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


    function getMunicipios($departamentos){
        $query="SELECT * FROM municipio";
        if(sizeof($departamentos)==1){
            $query.="WHERE id_departamento = ".$departamentos[0];
        }
        elseif (sizeof($departamentos)>1) {
            $query.= "WHERE id_departamento = ".$departamentos[0];
            for ($i=1; $i<sizeof($departamentos); $i++) { 
                $query.=" OR id_departamento = ".$departamentos[$i];
            }
        }
        $query.=";";
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

    function allMunicipios(){
        $query="SELECT * FROM municipio,departamento WHERE municipio.id_departamento = departamento.id_departamento ORDER BY departamento, municipio;";
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

    function allEstadoSitios(){
        $query="SELECT DISTINCT estado_sitio FROM monumento;";
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


    function allPeriodos(){
        $query="SELECT DISTINCT periodo_estatal FROM monumento;";
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
    
	function busqueda($camposf, $camposa) {
	    
	    if (isset($camposf['busqueda-simple'])) {
	        
	        $busqueda = mb_strtoupper($camposf['busqueda-simple']);
	        
	        error_log("en mayúsculas: ");
	        error_log($busqueda);
	        
	        $query = "SELECT DISTINCT m.id_monumento as id_monumento, 
                         m.titulo as titulo, 
                         m.descripcion as descripcion,
                         m.latitud as latitud,
                         m.longitud as longitud,
                         mul.direccion_archivo as foto_oficial,
                         m.id_tipo_monumento as id_tipo_monumento,
                         m.is_reportaje as is_reportaje,
                         m.descripcion_corta as descripcion_corta
                  FROM monumento m
                       INNER JOIN multimedia mul
                           ON (m.foto_oficial = mul.id_multimedia)
                       LEFT OUTER JOIN municipio mp
                           ON (m.id_municipio = mp.id_municipio)
                           INNER JOIN departamento d 
                               ON (mp.id_departamento = d.id_departamento)
                       LEFT JOIN tipo_monumento tm 
                           ON (m.id_tipo_monumento = tm.id_tipo_monumento)
                       LEFT JOIN keywords ks 
                           ON (m.id_monumento = ks.id_monumento)
                       LEFT JOIN tipo_evento te
                           ON (m.id_tipo_evento = te.id_tipo_evento)
        		       LEFT JOIN persona_monumento pm 
                           ON (m.id_monumento = pm.id_monumento)
        		           INNER JOIN persona p 
                               ON (pm.id_persona = p.id_persona)
                  WHERE m.estado_actual = 'Publicado'";
	        
	        //TODO SQL Injection
	        $conditions[] = "UPPER(m.titulo) LIKE '%" . $busqueda . "%'";
	        //Departamento:
	        $conditions[] = "UPPER(d.departamento) LIKE '%" . $busqueda . "%'";
	        //Tipo de sitio de memoria:
	        $conditions[] = "UPPER(tm.tipo_monumento) LIKE '%" . $busqueda . "%'";
	        //Tipo de delito:
	        $conditions[] = "UPPER(te.evento) LIKE '%" . $busqueda . "%'";
	        //Keywords
	        $conditions[] = "UPPER(ks.keyword) LIKE '%" . $busqueda . "%'";
	        //Víctimas
	        $conditions[] = "UPPER(p.nombre) LIKE '%" . $busqueda . "%'";
	        $query .= " AND (" . implode(' OR ', $conditions) . ")";
	        
	        
	    } else {
	        
	        
	        $query="SELECT DISTINCT m.id_monumento as id_monumento,
		                        m.titulo as titulo,
                                m.descripcion as descripcion,
                                m.latitud as latitud,
                                m.longitud as longitud,
		                        mul.direccion_archivo as foto_oficial,
                                m.id_tipo_monumento as id_tipo_monumento,
                                m.is_reportaje as is_reportaje,
                                m.descripcion_corta as descripcion_corta
		        FROM monumento m LEFT JOIN tipo_evento te ON (m.id_tipo_evento=te.id_tipo_evento)
        		LEFT JOIN tipo_monumento tm ON (tm.id_tipo_monumento=m.id_tipo_monumento)
        		LEFT JOIN keywords ks ON (ks.id_monumento=m.id_monumento)
        		LEFT JOIN persona_monumento pm ON (pm.id_monumento=m.id_monumento)
        		LEFT JOIN persona p ON (p.id_persona=pm.id_persona)
        		LEFT JOIN sector s ON (s.id_sector=p.id_sector)
        		LEFT JOIN profesion pn ON (pn.id_profesion=p.id_profesion)
        		LEFT JOIN valor_monumento vm ON (vm.id_monumento=m.id_monumento)
        		LEFT JOIN valor v ON (v.id_valor=vm.id_valor)
        		LEFT JOIN campo_adicional ca ON (ca.id_campo_adicional=v.id_campo_adicional)
        		LEFT JOIN organizacion o ON (o.id_organizacion=m.id_organizacion)
        		LEFT JOIN municipio mp ON (mp.id_municipio=m.id_municipio)
        		LEFT JOIN departamento d ON (d.id_departamento=mp.id_departamento)
        		LEFT JOIN pais ps ON (d.id_pais=ps.id_pais)
        		LEFT JOIN genero g ON (g.id_genero=p.id_genero)
        		LEFT JOIN noticia n ON (n.id_monumento=m.id_monumento)
        		LEFT JOIN multimedia mul ON (mul.id_multimedia=m.foto_oficial)";
	        
	        if(isset($camposf['busqueda'])) { $keytext=$camposf['busqueda']; }
	        if(isset($camposf['filter-delito'])) { $delito=$camposf['filter-delito']; }
	        if(isset($camposf['filter-monumento'])) {$monumento=$camposf['filter-monumento']; }
	        if(isset($camposf['filter-departamento'])) {$departamento=$camposf['filter-departamento']; }
	        if(isset($camposf['municipio'])) {$municipio=$camposf['municipio']; }
	        if(isset($camposf['filter-victimas'])) {$persona=$camposf['filter-victimas']; }
	        if(isset($camposf['filter-estado'])) {$estado=$camposf['filter-estado']; }
	        if(isset($camposf['filter-periodo'])) {$periodo=$camposf['periodo']; }
	        if(isset($camposf['is-reportaje'])) {$is_reportaje=$camposf['is-reportaje']; }
	        
	        $ando=false;
	        //agregando la busqueda del codigo
	        $query.="WHERE m.estado_actual='Publicado'";
	        $ando=true;
	        
	        //agregando la busqueda de keywords
	        if(isset($keytext) and ($keytext!='')){
	            if($ando){
	                $query.=' AND ';
	            }
	            else{
	                $query.=' WHERE ';
	            }
	            $ando=true;
	            $query.="(m.titulo LIKE '%".$keytext."%'";
	            $words=explode(" ",$keytext);
	            for($i=0;$i<count($words);++$i){
	                $query.=" OR ks.keyword LIKE '%".$words[$i]."%' ";
	            }
	            $query.=")";
	        }
	        //agregando la busqueda de reportajes
	        if(isset($is_reportaje) and ($is_reportaje!='')){
	            if($ando){
	                $query.=' AND ';
	            }
	            else{
	                $query.=' WHERE ';
	            }
	            $ando=true;
	            $query.="(m.is_reportaje=1)";
	        }
	        //agregando la busqueda de delitos
	        if(isset($delito) and (count($delito)!=0)){
	            if($ando){
	                $query.=' AND ';
	            }
	            else{
	                $query.=' WHERE ';
	            }
	            $query.='(';
	            $ando=true;
	            for($i = 0; $i < count($delito); ++$i) {
	                $query.='te.id_tipo_evento='.$delito[$i];
	                if($i!=count($delito)-1){
	                    $query.=" OR ";
	                }
	                else{
	                    $query.=")";
	                }
	            }
	        }
	        //agregando la busqueda de monumentos
	        if(isset($monumento) and (count($monumento)!=0)){
	            if($ando){
	                $query.=' AND ';
	            }
	            else{
	                $query.=' WHERE ';
	            }
	            $query.='(';
	            $ando=true;
	            for($i = 0; $i < count($monumento); ++$i) {
	                $query.='tm.id_tipo_monumento='.$monumento[$i];
	                if($i!=count($monumento)-1){
	                    $query.=" OR ";
	                }
	                else{
	                    $query.=")";
	                }
	            }
	        }
	        //agregando la busqueda de departamento
	        if(isset($departamento) and (count($departamento)!=0)){
	            if($ando){
	                $query.=' AND ';
	            }
	            else{
	                $query.=' WHERE ';
	            }
	            $query.='(';
	            $ando=true;
	            for($i = 0; $i < count($departamento); ++$i) {
	                $query.='d.id_departamento='.$departamento[$i];
	                if($i!=count($departamento)-1){
	                    $query.=" OR ";
	                }
	                else{
	                    $query.=")";
	                }
	            }
	        }
	        //agregando la busqueda de municipio
	        if(isset($municipio) and (count($municipio)!=0)){
	            if($ando){
	                $query.=' AND ';
	            }
	            else{
	                $query.=' WHERE ';
	            }
	            $query.='(';
	            $ando=true;
	            for($i = 0; $i < count($municipio); ++$i) {
	                $query.='mp.id_municipio='.$municipio[$i];
	                if($i!=count($municipio)-1){
	                    $query.=" OR ";
	                }
	                else{
	                    $query.=")";
	                }
	            }
	        }
	        //agregando la busqueda de personas
	        if(isset($persona) and (count($persona)!=0)){
	            if($ando){
	                $query.=' AND ';
	            }
	            else{
	                $query.=' WHERE ';
	            }
	            $query.='(';
	            $ando=true;
	            for($i = 0; $i < count($persona); ++$i) {
	                $query.='p.id_persona='.$persona[$i];
	                if($i!=count($persona)-1){
	                    $query.=" OR ";
	                }
	                else{
	                    $query.=")";
	                }
	            }
	        }
	        //agregando la busqueda de estados
	        if(isset($estado) and (count($estado)!=0)){
	            if($ando){
	                $query.=' AND ';
	            }
	            else{
	                $query.=' WHERE ';
	            }
	            $query.='(';
	            $ando=true;
	            for($i = 0; $i < count($estado); ++$i) {
	                $query.="m.estado_sitio LIKE '%".$estado[$i]."%'";
	                if($i!=count($estado)-1){
	                    $query.=" OR ";
	                }
	                else{
	                    $query.=")";
	                }
	            }
	        }
	        //agregando la busqueda de periodo
	        if(isset($periodo) and (count($periodo)!=0)){
	            if($ando){
	                $query.=' AND ';
	            }
	            else{
	                $query.=' WHERE ';
	            }
	            $query.='(';
	            $ando=true;
	            for($i = 0; $i < count($periodo); ++$i) {
	                $query.="m.periodo_estatal LIKE '%".$periodo[$i]."%'";
	                if($i!=count($periodo)-1){
	                    $query.=" OR ";
	                }
	                else{
	                    $query.=")";
	                }
	            }
	        }
	        //agregando busqueda de campos adicionales
	        if((count($camposa)!=0)&&(isset($camposa))){
	            $k=0;
	            foreach ($camposa as $key => $value) {
	                for($i = 0; $i < count($value); ++$i) {
	                    if(($ando)&&($k==0)&&($i==0)){
	                        $query.=' AND ';
	                        $query.='(';
	                    }
	                    else if(($k==0)&&($i==0)){
	                        $query.=' WHERE ';
	                        $query.='(';
	                        $ando=true;
	                    }
	                    $query.="(v.id_valor=".$value[$i].")";
	                    if(($k==count($camposa)-1)&&($i==count($value)-1)){
	                        $query.=")";
	                    }
	                    else{
	                        $query.=' OR ';
	                    }
	                }
	                ++$k;
	            }
	        }
	        $query.=';';
	        
	    }

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
