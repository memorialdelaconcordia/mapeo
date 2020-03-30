<?php 
    /*
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    */
    session_start();
    $basedir = realpath(__DIR__);
    include "../../include/globals.php";
    include( '../../include/db.php');
    include( '../../include/thumbnails.php');

    if(!in_array(2, $_SESSION['rol']) || !in_array(3, $_SESSION['rol'])){
        header("location: http://".$root_path."adm/login.php");
    }

    if(isset($_GET['id'])){
        $idMonumento = $_GET['id'];
        $monumento = mysqli_fetch_array(getMonumentoCompleto($idMonumento));
        $multimedia = getMultimediaMonumento($idMonumento);
        if(!($_SESSION['uid']==$monumento['id_usuario_owner'] || $_SESSION['uid']==$monumento['id_usuario_campo'] || in_array(2, $_SESSION['rol']))){
            header("location: http://".$root_path."adm/login.php");

        }
    }
    else{
		$idMonumento=-1;
        echo 'Oops';
    }
    $fileNames=array();
    if(isset($_GET['op'])){
        if($_GET['op']=='3'){
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $multimediaArchivos = array();
                $cantArchivos = sizeof($_FILES["fileSelect"]["name"]);
                $cantEdit = sizeof($_POST["idMedia"]);
                $counterEdit=0;
                $foto_oficial=$_POST["foto_oficial"];
                //Arregrlar foto oficial
                if ($cantEdit==0){
                    $counterEdit=1;
                }
                $errorUpload = false;
                for($i=0;$i<$cantArchivos;$i++){
                    if ($counterEdit>=$cantEdit){
                        if($_POST['fileType'][$i]=="imagen"){
                        // if(isset($_FILES["fileSelect"]["error"][$i])){
                            if($_FILES["fileSelect"]["error"][$i] > 0){
                                if ($_FILES["fileSelect"]["error"][$i]!=4){
                                    echo "Error: ".$_FILES["fileSelect"]["error"][$i]."<br>";
                                    $errorUpload = true;
                                    break;
                                }
                            } 
                            else{
                                if(!empty($_FILES["fileSelect"]["name"][$i])){
                                    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "PNG" => "image/PNG","JPG" => "image/JPG", "JPEG" => "image/JPEG");
                                    $filename = $_FILES["fileSelect"]["name"][$i];
                                    $filetype = $_FILES["fileSelect"]["type"][$i];
                                    $filesize = $_FILES["fileSelect"]["size"][$i];
                                    
                                    // Extension
                                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                                    if(!array_key_exists($ext, $allowed)){
                                        echo "Error: Seleccione un formato de imagen válido.";
                                        $errorUpload = true;
                                        break;
                                    }
                                    
                                    
                                    //50MB max
                                    $maxsize = 50 * 1024 * 1024; 
                                    if($filesize > $maxsize){
                                        echo "Error: El archivo es muy grande.";
                                        $errorUpload = true;
                                        break;
                                    }
                                    
                                    // Verify MYME type of the file
                                    if(in_array($filetype, $allowed)){
                                        if(file_exists("upload/" . $_FILES["fileSelect"]["name"][$i])){
                                            echo $_FILES["fileSelect"]["name"][$i]." ya existe en la base de datos, cambie el nombre.";
                                            $errorUpload = true;
                                            break;
                                        } else{

                                            if(move_uploaded_file($_FILES["fileSelect"]["tmp_name"][$i], $_SERVER['DOCUMENT_ROOT'].'/multimedia/'.str_replace(' ', '_', $_FILES["fileSelect"]["name"][$i]))){
                                                array_push($fileNames,str_replace(' ', '_', $_FILES["fileSelect"]["name"][$i]));
                                                echo 'Archivo agregado exitosamente en http://'.$root_path.'multimedia/'.str_replace(' ', '_', $_FILES["fileSelect"]["name"][$i]);
                                                $titulo="";
                                                $archivo = 'http://'.$root_path.'multimedia/comprimidos/'.str_replace(' ', '_', $_FILES["fileSelect"]["name"][$i]);
                                                $autor="";
                                                $fuente="";
                                                $licencia="";
                                                $link="";
                                                $tipo_archivo=$_POST['fileType'][$i];
                                                if($_POST['tituloMedia'][$i]!=" "){
                                                    $titulo=htmlentities($_POST['tituloMedia'][$i],ENT_QUOTES);
                                                }
                                                if($_POST['autorMedia'][$i]!=" "){
                                                    $autor=htmlentities($_POST['autorMedia'][$i],ENT_QUOTES);
                                                }
                                                if($_POST['fuenteMedia'][$i]!=" "){
                                                    $fuente=htmlentities($_POST['fuenteMedia'][$i],ENT_QUOTES);
                                                }
                                                if($_POST['licenciaMedia'][$i]!=" "){
                                                    $licencia=htmlentities($_POST['licenciaMedia'][$i],ENT_QUOTES);
                                                }
                                                if($_POST['linkMedia'][$i]!=" "){
                                                    $link=htmlentities($_POST['linkMedia'][$i],ENT_QUOTES);
                                                }


                                                $idArchivo = crearMultimedia($titulo,$autor,$fuente,$licencia,$link,$archivo,$tipo_archivo);
                                                $multimediaArchivos[] = $idArchivo;
                                                if ($i==0) {
                                                    $foto_oficial=$idArchivo;
                                                }
                                            }
                                            else{
                                                echo $_SERVER['DOCUMENT_ROOT'];
                                                echo "Error: Ocurrió un error subiendo el archivo, trate de nuevo. "; 
                                                $errorUpload = true;
                                                break;
                                            }
                                        } 
                                    } 
                                    else{

                                        echo "Error: Ocurrió un error subiendo el archivo, trate de nuevo. ";
                                        $errorUpload = true;
                                        break;
                                    }
                                }     
                            }
                        }   
                        else{
                            //to_console($_POST['fileType'][$i]);
                            $titulo="";
                            $archivo = "";
                            $autor="";
                            $fuente="";
                            $licencia="";
                            $link="";
                            $tipo_archivo=$_POST['fileType'][$i];
                            if($_POST['tituloMedia'][$i]!=" "){
                                $titulo=htmlentities($_POST['tituloMedia'][$i],ENT_QUOTES);
                            }
                            if($_POST['autorMedia'][$i]!=" "){
                                $autor=htmlentities($_POST['autorMedia'][$i],ENT_QUOTES);
                            }
                            if($_POST['fuenteMedia'][$i]!=" "){
                                $fuente=htmlentities($_POST['fuenteMedia'][$i],ENT_QUOTES);
                            }
                            if($_POST['licenciaMedia'][$i]!=" "){
                                $licencia=htmlentities($_POST['licenciaMedia'][$i],ENT_QUOTES);
                            }
                            if($_POST['linkMedia'][$i]!=" "){
                                $link=$_POST['linkMedia'][$i];
                            }

                            $idArchivo = crearMultimedia($titulo,$autor,$fuente,$licencia,$link,$archivo,$tipo_archivo);
                            $multimediaArchivos[] = $idArchivo;
                            if ($i==0) {
                                $foto_oficial=$idArchivo;
                            }
                        }
                    }
                    else{
                        //Para los archivos existentes
                        $counterEdit+=1;
                        if($_POST['fileType'][$i]=="imagen"){
                        // if(isset($_FILES["fileSelect"]["error"][$i])){
                            if($_FILES["fileSelect"]["error"][$i] > 0 && $_FILES["fileSelect"]["error"][$i] < 4){
                                if ($_FILES["fileSelect"]["error"][$i]!=4){
                                    echo "Error: ".$_FILES["fileSelect"]["error"][$i]."<br>";
                                    $errorUpload = true;
                                    break;
                                }
                            }
                            else{
                                if(!empty($_FILES["fileSelect"]["name"][$i])){
                                    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "PNG" => "image/PNG","JPG" => "image/JPG", "JPEG" => "image/JPEG");
                                    $filename = $_FILES["fileSelect"]["name"][$i];
                                    $filetype = $_FILES["fileSelect"]["type"][$i];
                                    $filesize = $_FILES["fileSelect"]["size"][$i];
                                    
                                    // Extension
                                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                                    if(!array_key_exists($ext, $allowed)){
                                        echo "Error: Seleccione un formato de imagen válido.";
                                        $errorUpload = true;
                                        break;
                                    }
                                    
                                    
                                    //50MB max
                                    $maxsize = 50 * 1024 * 1024; 
                                    if($filesize > $maxsize){
                                        echo "Error: El archivo es muy grande.";
                                        $errorUpload = true;
                                        break;
                                    }
                                    
                                    // Verify MYME type of the file
                                    if(in_array($filetype, $allowed)){
                                        if(file_exists("upload/" . $_FILES["fileSelect"]["name"][$i])){
                                            echo $_FILES["fileSelect"]["name"][$i]." ya existe en la base de datos, cambie el nombre.";
                                            $errorUpload = true;
                                            break;
                                        } else{

                                            if(move_uploaded_file($_FILES["fileSelect"]["tmp_name"][$i], $_SERVER['DOCUMENT_ROOT'].'/multimedia/'.str_replace(' ', '_', $_FILES["fileSelect"]["name"][$i]))){
                                                echo 'Archivo agregado exitosamente en http://'.$root_path.'multimedia/'.str_replace(' ', '_', $_FILES["fileSelect"]["name"][$i]);
                                                $titulo="";
                                                $archivo = 'http://'.$root_path.'multimedia/comprimidos/'.str_replace(' ', '_', $_FILES["fileSelect"]["name"][$i]);
                                                $autor="";
                                                $fuente="";
                                                $licencia="";
                                                $link="";
                                                array_push($fileNames,str_replace(' ', '_', $_FILES["fileSelect"]["name"][$i]));
                                                $tipo_archivo=$_POST['fileType'][$i];
                                                if($_POST['tituloMedia'][$i]!=" "){
                                                    $titulo=htmlentities($_POST['tituloMedia'][$i],ENT_QUOTES);
                                                }
                                                if($_POST['autorMedia'][$i]!=" "){
                                                    $autor=htmlentities($_POST['autorMedia'][$i],ENT_QUOTES);
                                                }
                                                if($_POST['fuenteMedia'][$i]!=" "){
                                                    $fuente=htmlentities($_POST['fuenteMedia'][$i],ENT_QUOTES);
                                                }
                                                if($_POST['licenciaMedia'][$i]!=" "){
                                                    $licencia=htmlentities($_POST['licenciaMedia'][$i],ENT_QUOTES);
                                                }
                                                if($_POST['linkMedia'][$i]!=" "){
                                                    $link=$_POST['linkMedia'][$i];
                                                }


                                                $updateMedia = updateMultimedia($_POST['idMedia'][$i],$titulo,$autor,$fuente,$licencia,$link,$archivo,$tipo_archivo);
                                            }
                                            else{
                                                echo $_SERVER['DOCUMENT_ROOT'];
                                                echo "Error: Ocurrió un error subiendo el archivo, trate de nuevo. "; 
                                                $errorUpload = true;
                                                break;
                                            }
                                        } 
                                    } 
                                    else{

                                        echo "Error: Ocurrió un error subiendo el archivo, trate de nuevo. ";
                                        $errorUpload = true;
                                        break;
                                    }
                                }
                                else{
                                    //Cuando no se agrega un archivo nuevo
                                    $archivo="";
                                    $resultArchivo = getMultimedia($_POST["idMedia"][$i]);
                                    while($row=mysqli_fetch_array($resultArchivo)){
                                        $archivo = $row["direccion_archivo"];
                                    }
                                    // to_console($archivo);
                                    $titulo="";
                                    $autor="";
                                    $fuente="";
                                    $licencia="";
                                    $link="";
                                    $tipo_archivo=$_POST['fileType'][$i];
                                    if($_POST['tituloMedia'][$i]!=" "){
                                        $titulo=htmlentities($_POST['tituloMedia'][$i],ENT_QUOTES);
                                    }
                                    if($_POST['autorMedia'][$i]!=" "){
                                        $autor=htmlentities($_POST['autorMedia'][$i],ENT_QUOTES);
                                    }
                                    if($_POST['fuenteMedia'][$i]!=" "){
                                        $fuente=htmlentities($_POST['fuenteMedia'][$i],ENT_QUOTES);
                                    }
                                    if($_POST['licenciaMedia'][$i]!=" "){
                                        $licencia=htmlentities($_POST['licenciaMedia'][$i],ENT_QUOTES);
                                    }
                                    if($_POST['linkMedia'][$i]!=" "){
                                        $link=$_POST['linkMedia'][$i];
                                    }
                                    $updateMedia = updateMultimedia($_POST['idMedia'][$i],$titulo,$autor,$fuente,$licencia,$link,$archivo,$tipo_archivo);

                                    //Fin cuando no se agrega un Archivo nuevo
                                }
                            }
                        }
                        else{
                            $archivo="";
                            $resultArchivo = getMultimedia($_POST["idMedia"][$i]);
                            while($row=mysqli_fetch_array($resultArchivo)){
                                $archivo = $row["direccion_archivo"];
                            }
                            $titulo="";
                            $autor="";
                            $fuente="";
                            $licencia="";
                            $link="";
                            $tipo_archivo=$_POST['fileType'][$i];
                            if($_POST['tituloMedia'][$i]!=" "){
                                        $titulo=htmlentities($_POST['tituloMedia'][$i],ENT_QUOTES);
                                    }
                            if($_POST['autorMedia'][$i]!=" "){
                                $autor=htmlentities($_POST['autorMedia'][$i],ENT_QUOTES);
                            }
                            if($_POST['fuenteMedia'][$i]!=" "){
                                $fuente=htmlentities($_POST['fuenteMedia'][$i],ENT_QUOTES);
                            }
                            if($_POST['licenciaMedia'][$i]!=" "){
                                $licencia=htmlentities($_POST['licenciaMedia'][$i],ENT_QUOTES);
                            }
                            if($_POST['linkMedia'][$i]!=" "){
                                $link=$_POST['linkMedia'][$i];
                            }

                            $updateMedia = updateMultimedia($_POST['idMedia'][$i],$titulo,$autor,$fuente,$licencia,$link,$archivo,$tipo_archivo);
                        }
                        $multimediaArchivos[]=$_POST["idMedia"][$i];
                    }
                }
                if(!$errorUpload){
                    // print_r($multimediaArchivos);
                    $camposDefault = array('fileSelect','fileType','tituloMedia','autorMedia','fuenteMedia','licenciaMedia','linkMedia','idMedia','titulo','estado_actual','usuario_campo','id_tipo_evento','id_tipo_monumento','estado_sitio','id_municipio','periodo_estatal','conmemoracion','personas','organizacion','organizacionResponsable','persona_informacion','fecha_investigacion','direccion','ubicacion','como_llegar','acceso','longitud','latitud','construccion_monumento','apoyo_monumento','fecha_creacion','descripcion','descripcion-corta','is_reportaje','actividades','autor_obra','multimedia', 'identificador','keywords','tituloNoticia','linkNoticia','fechaNoticia','fuenteNoticia', 'idNoticia','foto_oficial');
                    $arrayCamposExtra = array();
                     foreach (array_keys($_POST) as $campo) {
						if(!in_array($campo, $camposDefault)){
							$arrayCamposExtra[$campo] = $_POST[$campo];
						}
                    }
                    // print_r($arrayCamposExtra);

                    $personas = array();
                    if (isset($_POST['personas'])){
                        $personas = $_POST['personas'];
                    }
                    $keywords=array();
                    if(isset($_POST['keywords'])){
                       if(isset($_POST['keywords'])){
                          foreach($_POST['keywords'] as $i){
                               $keywords[]=htmlentities($i,ENT_QUOTES);
                           }
                       }
                    }


                    $idMedia=array();
                    if(isset($_POST['idMedia'])){
                       $idMedia=$_POST['idMedia'];
                    }

                    $tituloNoticia=array();
                    $linkNoticia=array();
                    $fechaNoticia=array();
                    $fuenteNoticia=array();
                    if(isset($_POST['tituloNoticia'])){
                        foreach($_POST['tituloNoticia'] as $i){
                            $tituloNoticia[]=htmlentities($i,ENT_QUOTES);
                        }
                        
                    }
                    if(isset($_POST['linkNoticia'])){
                        foreach($_POST['linkNoticia'] as $i){
                            $linkNoticia[]=htmlentities($i,ENT_QUOTES);
                        }
                    }
                    if(isset($_POST['fechaNoticia'])){
                        foreach($_POST['fechaNoticia'] as $i){
                            $fechaNoticia[]=htmlentities($i,ENT_QUOTES);
                        }
                    }
                    if(isset($_POST['fuenteNoticia'])){
                        foreach($_POST['fuenteNoticia'] as $i){
                            $fuenteNoticia[]=htmlentities($i,ENT_QUOTES);
                        }
                    }

                    $is_reportaje=0;
                    if(isset($_POST['is_reportaje'])){
                        $is_reportaje=$_POST['is_reportaje'];
                    }
                    
                    // //if(isset($_POST['titulo'])&&isset($_POST['estado_actual'])&&isset($_POST['id_municipio'])&&isset($_POST['id_tipo_monumento'])&&isset($_POST['personas'])&&isset($_POST['organizacion_informacion'])&&isset($_POST['persona_informacion'])&&isset($_POST['persona_proceso_informacion'])&&isset($_POST['fuente'])&&isset($_POST['organizacion_informante'])&&isset($_POST['proceso_informacion'])&&isset($_POST['nombre_informante'])&&isset($_POST['datepicker'])&&isset($_POST['direccion'])&&isset($_POST['ubicacion'])&&isset($_POST['como_llegar'])&&isset($_POST['acceso'])&&isset($_POST['longitud'])&&isset($_POST['latitud'])&&isset($_POST['construccion_monumento'])&&isset($_POST['apoyo_monumento'])&&isset($_POST['periodo_estatal'])&&$archivo!=""){

                    to_console($is_reportaje);
					//$result=updateMonumento($_GET['id'],$_POST['titulo'],$_POST['estado_actual'],$_POST['usuario_campo'],$_POST['id_tipo_evento'],$_POST['id_tipo_monumento'],$_POST['estado_sitio'],$_POST['id_municipio'],$_POST['periodo_estatal'],$_POST['conmemoracion'],$_POST['personas'],$_POST['organizacion'],$_POST['persona_informacion'],$_POST['fecha_investigacion'],$_SESSION['uid'],$_POST['direccion'],$_POST['ubicacion'],$_POST['como_llegar'],$_POST['acceso'],$_POST['longitud'],$_POST['latitud'],$_POST['construccion_monumento'],$_POST['apoyo_monumento'],$_POST['fecha_creacion'],$_POST['descripcion'],$_POST['actividades'],$_POST['autor_obra'],$foto_oficial,$_POST['tituloNoticia'],$_POST['linkNoticia'],$_POST['fechaNoticia'],$_POST['fuenteNoticia'],$keywords,$multimediaArchivos,$arrayCamposExtra,$idMedia,$_POST['descripcion-corta'],$_POST['is_reportaje']);
		
                    $result=updateMonumento(
                        $_GET['id'],
                        htmlentities($_POST['titulo'],ENT_QUOTES),
                        htmlentities($_POST['estado_actual'],ENT_QUOTES),
                        htmlentities($_POST['usuario_campo'],ENT_QUOTES),
                        htmlentities($_POST['id_tipo_evento'],ENT_QUOTES),
                        htmlentities($_POST['id_tipo_monumento'],ENT_QUOTES),
                        htmlentities($_POST['estado_sitio'],ENT_QUOTES),
                        htmlentities($_POST['id_municipio'],ENT_QUOTES),
                        htmlentities($_POST['periodo_estatal'],ENT_QUOTES),
                        htmlentities($_POST['conmemoracion'],ENT_QUOTES),
                        $personas,
                        htmlentities($_POST['organizacion'],ENT_QUOTES),
                        htmlentities($_POST['organizacionResponsable'],ENT_QUOTES),
                        htmlentities($_POST['persona_informacion'],ENT_QUOTES),
                        htmlentities($_POST['fecha_investigacion'],ENT_QUOTES),
                        $_SESSION['uid'],
                        htmlentities($_POST['direccion'],ENT_QUOTES),
                        htmlentities($_POST['ubicacion'],ENT_QUOTES),
                        htmlentities($_POST['como_llegar'],ENT_QUOTES),
                        htmlentities($_POST['acceso'],ENT_QUOTES),
                        htmlentities($_POST['longitud'],ENT_QUOTES),
                        htmlentities($_POST['latitud'],ENT_QUOTES),
                        htmlentities($_POST['construccion_monumento'],ENT_QUOTES),
                        htmlentities($_POST['apoyo_monumento'],ENT_QUOTES),
                        htmlentities($_POST['fecha_creacion'],ENT_QUOTES),
                        htmlentities($_POST['descripcion'],ENT_QUOTES),
                        htmlentities($_POST['actividades'],ENT_QUOTES),
                        htmlentities($_POST['autor_obra'],ENT_QUOTES),
                        $foto_oficial,
                        $tituloNoticia,
                        $linkNoticia,
                        $fechaNoticia,
                        $fuenteNoticia,
                        $keywords,
                        $multimediaArchivos,
                        $arrayCamposExtra,
                        $idMedia,
                        htmlentities($_POST['descripcion-corta'],ENT_QUOTES),
                        $is_reportaje
                        );

				    $monumento = mysqli_fetch_array(getMonumentoCompleto($idMonumento));
                    $multimedia = getMultimediaMonumento($idMonumento);
                    
                    if($result) {
                        $alerta = 'ActualizacionExitosa';
                    } else {
                        $alerta = 'ActualizacionError';
                    }
                }
                
            }
        }
    }
createThumbsFromArray("../../multimedia/","../../multimedia/thumbnails/",200,$fileNames);
createThumbsFromArray("../../multimedia/","../../multimedia/comprimidos/",2048,$fileNames);
                

?>

<html>
<head>

    <title>Editar Sitio de Memoria</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo 'http://'.$root_path.'css/datepicker.css';?>">
    <link rel="stylesheet" href="<?php echo 'http://'.$root_path.'css/select2.css';?>">
    <link rel="stylesheet" href="<?php echo 'http://'.$root_path.'css/bootstrap.css';?>">
	<link rel="stylesheet" href="<?php echo 'http://'.$root_path.'css/dashboard.css';?>">
    
    <script src="<?php echo 'http://'.$root_path.'js/jquery.js';?>"></script>
	<script src="<?php echo 'http://'.$root_path.'js/jquery.validate.js';?>"></script>
	<script src="<?php echo 'http://'.$root_path.'js/messages_es.js';?>"></script>	
    <script src="<?php echo 'http://'.$root_path.'js/bootstrap.js';?>"></script>
    <script src="<?php echo 'http://'.$root_path.'js/bootstrap-datepicker.js';?>"></script>
    <script src="<?php echo 'http://'.$root_path.'js/select2.js';?>"></script>
    <script src="<?php echo 'http://'.$root_path.'js/tinymce/tinymce.min.js';?>"></script>
    
    <script
        src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBpgsy7CfpdHm9PGvS68s0M2-z-p65YPH8">
    </script>

    <script>
    var latBase = <?php echo $monumento['latitud'];?>;
    
    var lngBase = <?php echo $monumento['longitud'];?>;
    var myCenter=new google.maps.LatLng(latBase,lngBase);
    var markers = [];
    function initialize()
    {
    

    var mapProp = {
      center:myCenter,
      zoom:10,
      mapTypeId:google.maps.MapTypeId.ROADMAP
      };

      map = new google.maps.Map(document.getElementById("mapa"),mapProp);
      placeMarker(myCenter);
      google.maps.event.addListener(map, 'click', function(event) {
        placeMarker(event.latLng);
      });
    }



    function placeMarker(location) {
        deleteMarkers();
          var marker = new google.maps.Marker({
        position: location,
        map: map,
      });
      var latDoc = document.getElementById("latitud");
      latDoc.value = location.lat();
      var lngDoc = document.getElementById("longitud");
      lngDoc.value = location.lng();
      markers.push(marker);
    }

    function setAllMap(map) {
          for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
          }
    }

    // Removes the markers from the map, but keeps them in the array.
    function deleteMarkers() {
         setAllMap(null);
         markers = [];
    }

    function updateLocation(){
        var latDoc = document.getElementById("latitud").value;
        var lngDoc = document.getElementById("longitud").value;
        deleteMarkers();
        var newCenter=new google.maps.LatLng(latDoc,lngDoc);
        var mapProp = {
          center:newCenter,
          zoom:10,
          mapTypeId:google.maps.MapTypeId.ROADMAP
          };

          map = new google.maps.Map(document.getElementById("mapa"),mapProp);
          placeMarker(newCenter);
          google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
          });
    }



    google.maps.event.addDomListener(window, 'load', initialize);
    </script>

	<!-- Script de validación de los campos de las formas presentes en esta página: -->
	<script type="text/javascript">	
	
		$(document).ready(function () {
	
			//Validación de la forma de creación de nuevos sitios:
			$('#edicionSitio').validate({ //Initialize the plugin...
				rules: {
					titulo: {
						required: true,
						normalizer: function(value) {
							return $.trim(value);
						}						
					},
					identificador: {
						required: true,
						normalizer: function(value) {
							return $.trim(value);
						}									
					},
					latitud: {
						required: true,
						number: true					
					},
					longitud: {
						required: true,
						number: true							
					}
				},			
				submitHandler: function (form) { 
					//if(validarCodigo()) {
						form.submit();
					//} else {
					//	return false;
					//}	
				},
				//https://stackoverflow.com/questions/18754020/bootstrap-3-with-jquery-validation-plugin
				highlight: function(element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight: function(element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement: 'span',
				errorClass: 'help-block',
				errorPlacement: function(error, element) {
					if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
						element.parent().parent().append(error);
					} else {
						error.insertAfter(element);
					}
				}				
			});
			
			//Validación de la forma de adición de nuevas víctimas:
			$('#agregarPersona').validate({ //Initialize the plugin...
				rules: {
					nombrePersona: {
						required: true,
						normalizer: function(value) {
							return $.trim(value);
						}						
					}
				},			
				submitHandler: function (form) {
					var $form = $( form )					
					url = $form.attr( 'action' );
					var posting = $.post( url, { 
													nombrePersona: $('#nombrePersona').val(), 
													menordeedad: $('#menordeedad').val(), 
													genero: $('#genero').val(), 
													id_sector: $('#id_sector').val(), 
													id_profesion: $('#id_profesion').val(), 
													id_pais: $('#id_pais').val() 
												}
					);
					/* Alerts the results: */
					posting.done(function( data ) {
						alert('¡Persona agregada exitosamente!');
						var obj = jQuery.parseJSON(data);
						var $victimas = $('#personas');
						$victimas.append($("<option></option>")
											.attr("value", obj.idVictima)
											.text(obj.nombreVictima));
						$victimasSel2 = $("#personas").select2();
						$victimasSel2Values = $victimasSel2.val() || [];
						$victimasSel2Values.push(obj.idVictima);
						$victimasSel2.val($victimasSel2Values).trigger("change");
						$('#modalAgregarPersona').modal('hide');
					});				
				},
				//https://stackoverflow.com/questions/18754020/bootstrap-3-with-jquery-validation-plugin
				highlight: function(element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight: function(element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement: 'span',
				errorClass: 'help-block',
				errorPlacement: function(error, element) {
					if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
						element.parent().parent().append(error);
					} else {
						error.insertAfter(element);
					}
				}				
			});			
			
			//Validación de la forma de adición de nuevas organizaciones:
			$('#agregarOrganizacion').validate({ //Initialize the plugin...
				rules: {
					nombreOrganizacion: {
						required: true,
						normalizer: function(value) {
							return $.trim(value);
						}						
					},
					emailOrganizacion: {
						email: true				
					}					
				},			
				submitHandler: function (form) {
					var $form = $( form ),
					url = $form.attr( 'action' );
					var posting = $.post( url, { 
													nombreOrganizacion: $('#nombreOrganizacion').val(), 
													personaOrganizacion: $('#personaOrganizacion').val(), 
													telefonoOrganizacion: $('#telefonoOrganizacion').val(), 
													id_sector: $('#id_sector').val(), 
													emailOrganizacion: $('#emailOrganizacion').val() 
												});

					/* Alerts the results:  */
					posting.done(function( data ) {
						alert('¡Organización agregada exitosamente!');
						var obj = jQuery.parseJSON(data);
						var $org = $("#organizacion");
						$org.append($("<option></option>")
										.attr("value", obj.idOrganizacion)
										.text(obj.nombreOrganizacion));
						var $orgResponsable = $("#organizacionResponsable");
						$orgResponsable.append($("<option></option>")
							.attr("value", obj.idOrganizacion).text(obj.nombreOrganizacion));
							
						$('#modalAgregarOrganizacion').modal('hide');
					});
				},
				//https://stackoverflow.com/questions/18754020/bootstrap-3-with-jquery-validation-plugin
				highlight: function(element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight: function(element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement: 'span',
				errorClass: 'help-block',
				errorPlacement: function(error, element) {
					if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
						element.parent().parent().append(error);
					} else {
						error.insertAfter(element);
					}
				}				
			});					
			
		});		
	</script>	
	
	
</head>

<body>

    <?php
        include '../include/header.php';
    ?>
	
	<div class="container-fluid">	
		<div class="row">
			
			<!-- Sidebar: -->
			<?php
				include '../../include/sidebar.php';
			?>
			
			<!-- Sección principal: -->
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">                
			
            
				<?php
					if(isset($alerta)) {
						if($alerta == 'ActualizacionExitosa') {
				?>		
							<div class="alert alert-success alert-dismissible" role="alert">
							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							  <strong>¡Éxito!</strong> Sitio actualizado exitosamente.
							</div>				
				<?php
						} else if($alerta == 'ActualizacionError') {
				?>		
							<div class="alert alert-danger alert-dismissible" role="alert">
							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							  <strong>Error.</strong> Ocurrió un error al actualizar el sitio.
							</div>																					
				<?php
						} 
					}
				?>					            
            
            
                <a href="http://mapeo.memorialparalaconcordia.org/article.php?id=<?php echo $_GET['id'] ?>" target="_blank" class="btn btn-default">Vista actual</a>
                
				<form id="edicionSitio" name="submitform" method="post" action="<?php echo 'editarMonumento.php?op=3&id='.$_GET['id'];?>" enctype="multipart/form-data">
                    
					<h3>Información General del Sitio:</h3>
                    
					<div class="form-group">
						<label for="titulo">Título</label>
						<input class="form-control" name="titulo" id="titulo" type="text" value="<?php echo $monumento['titulo'];?>"/>
                    </div>
					<div class="form-group">
						<label for="identificador">Código</label>
						<input class="form-control" name="identificador" id="identificador" type="text" value="<?php echo $monumento['identificador'];?>" disabled/>
					</div>
						
                    <script>
                    $(document).ready(function() {
                        $(".keywords").select2({
                            tags: true,
                            tokenSeparators: [',', ' ']
                        });
                    });
                    </script>
					
                    <div class="form-group">
						<label for="keywords">Palabras clave</label>
						<select class="keywords form-control" name="keywords[]" id="keywords" multiple="multiple">
                        <?php 
                            $kws = getKeywordsMonumento($idMonumento);
                            while($row_kw=mysqli_fetch_array($kws)){
                                echo '<option value="'.$row_kw["keyword"].'" selected="selected">'.$row_kw["keyword"]."</option>";
                            }
                        ?>
						</select>
                    </div>

                    <div class="form-group">
						<label for="estado_actual">Estado de la publicación</label>
						<select class="form-control" name="estado_actual" id="estado_actual" <?php if(!in_array(2, $_SESSION['rol'])){echo "disabled";} ?>>
							<option value="Pendiente" <?php if ($monumento['estado_actual']=='Pendiente' || !in_array(2, $_SESSION['rol'])) {echo ' selected="selected"';}?>>Pendiente de aprobación</option>
							<option value="Publicado" <?php if ($monumento['estado_actual']=='Publicado' && in_array(2, $_SESSION['rol'])) {echo ' selected="selected"';}?>>Publicado</option>
							<option value="Inactivo" <?php if ($monumento['estado_actual']=='Inactivo' && in_array(2, $_SESSION['rol'])) {echo ' selected="selected"';}?>>Inactivo</option>
						</select>
                    </div>

					<div class="form-group">
						<label for="usuario_campo">Usuario con permisos de edición</label>
                    
						<script>
							$(document).ready(function() {
								  $(".usuarioCampo").select2();
							});                    
						</script>

						<select class="usuarioCampo form-control" name="usuario_campo" id="usuario_campo" <?php if(!in_array(2, $_SESSION['rol'])){echo "disabled";} ?>>
							  <?php
								$result=allUsuario();
								while($row=mysqli_fetch_array($result)){
									echo '<option value='.$row["id_usuario"];
									if($monumento['id_usuario_campo']==$row["id_usuario"]){ echo ' selected="selected"';}
									echo ">".$row["nombre_usuario"]."</option>";
								}
							?>
						</select>
                    </div>
					
                    <script>
						//Para obtener el valor del campo:
						//TODO: ¿esto para qué es?
						$('#edicionSitio').submit(function() {
							$('select').removeAttr('disabled');
						});
                    </script>

                    <div class="form-group">
						<label for="id_tipo_evento">Tipo de Delito</label>
						<select class="form-control" name="id_tipo_evento" id="id_tipo_evento">
							<?php
								$result=allTipoEvento();
								while($row=mysqli_fetch_array($result)){
									echo '<option value='.$row["id_tipo_evento"].'';
									if($monumento['id_tipo_evento']==$row["id_tipo_evento"]){ echo ' selected="selected"';}
									echo ">".$row["evento"]."</option>";
								}
							?>
						</select>
					</div>

                    <div class="form-group">
						<label for="id_tipo_monumento">Tipo de Sitio de Memoria</label>
						<select class="form-control" name="id_tipo_monumento" id="id_tipo_monumento">
							<?php
								$result=allTiposMonumento();
								while($row=mysqli_fetch_array($result)){
									echo '<option value='.$row["id_tipo_monumento"].'';
									if($monumento['id_tipo_monumento']==$row["id_tipo_monumento"]){ echo ' selected="selected"';}
									echo ">".$row["tipo_monumento"]."</option>";
								}
							?>
						</select>
					</div>

                    <div class="form-group">
						<label for="estado_sitio">Estado del sitio</label>
						<select class="form-control" name="estado_sitio" id="estado_sitio">
							<option value="Buen estado"<?php if ($monumento['estado_sitio']=='Buen estado') {echo ' selected="selected"';}?>>Buen estado</option>
							<option value="Mal estado"<?php if ($monumento['estado_sitio']=='Mal estado') {echo ' selected="selected"';}?>>Mal estado</option>
							<option value="Destruído"<?php if ($monumento['estado_sitio']=='Destruído') {echo ' selected="selected"';}?>>Destruído</option>
						</select>
					</div>
						
					<div class="form-group">
						<label for="id_municipio">Municipio</label>
                    
						<script>
							$(document).ready(function() {
								  $(".municipios").select2();
							});
						</script>

                    
						<select class="municipios form-control" name="id_municipio" id="id_municipio">
							<?php
								$municipios=municipiosDepto();
								while($row=mysqli_fetch_array($municipios)){
									echo '<option value='.$row["id_municipio"].'';
									if($monumento['id_municipio']==$row["id_municipio"]){ echo ' selected="selected"';}
									echo ">".$row["departamento"]." - ".$row["municipio"]."</option>";
								}
							?>
						</select>
                    </div>


					
                    <div class="form-group">
						<label for="inputSitio">Período de gobierno</label>
						<select class="form-control" name="periodo_estatal" id="periodo_estatal">
							<option value="José Miguel Ramón Ydígoras Fuentes - (1958-1963)"<?php if (strpos($monumento['periodo_estatal'], 'Fuentes') !== false) {echo ' selected="selected"';}?>>José Miguel Ramón Ydígoras Fuentes - (1958-1963)</option>
							<option value="Enrique Peralta Azurdia - (1963-1966)"<?php if (strpos($monumento['periodo_estatal'], 'Peralta') !== false) {echo ' selected="selected"';}?>>Enrique Peralta Azurdia - (1963-1966)</option>
							<option value="Julio César Méndez Montenegro - (1966-1970)"<?php if (strpos($monumento['periodo_estatal'], 'Montenegro') !== false) {echo ' selected="selected"';}?>>Julio César Méndez Montenegro - (1966-1970)</option>
							<option value="Carlos Manuel Arana Osorio - (1970-1974)"<?php if (strpos($monumento['periodo_estatal'], 'Arana') !== false) {echo ' selected="selected"';}?>>Carlos Manuel Arana Osorio - (1970-1974)</option>
							<option value="Kjell Eugenio Laugerud García - (1974-1978)"<?php if (strpos($monumento['periodo_estatal'], 'Eugenio') !== false) {echo ' selected="selected"';}?>>Kjell Eugenio Laugerud García - (1974-1978)</option>
							<option value="Fernando Romeo Lucas García - (1978-1982)"<?php if (strpos($monumento['periodo_estatal'], 'Lucas') !== false) {echo ' selected="selected"';}?>>Fernando Romeo Lucas García - (1978-1982)</option>
							<option value="José Efraín Ríos Montt - (1982-1983)"<?php if (strpos($monumento['periodo_estatal'], 'Montt') !== false) {echo ' selected="selected"';}?>>José Efraín Ríos Montt - (1982-1983)</option>
							<option value="Óscar Humberto Mejía Víctores - (1983-1986)"<?php if (strpos($monumento['periodo_estatal'], 'Humberto') !== false) {echo ' selected="selected"';}?>>Óscar Humberto Mejía Víctores - (1983-1986)</option>
							<option value="Vinicio Cerezo - (1986-1991)"<?php if (strpos($monumento['periodo_estatal'], 'Cerezo') !== false) {echo ' selected="selected"';}?>>Vinicio Cerezo - (1986 - 1991)</option>
							<option value="Jorge Serrano Elías - (1991-1993)"<?php if (strpos($monumento['periodo_estatal'], 'Serrano') !== false) {echo ' selected="selected"';}?>>Jorge Serrano Elías - (1991-1993)</option>
							<option value="Gustavo Espina - (1993)"<?php if (strpos($monumento['periodo_estatal'], 'Espina') !== false) {echo ' selected="selected"';}?>>Gustavo- Espina - (1993)  </option>
							<option value="Ramiro de León Carpio - (1993-1996)"<?php if (strpos($monumento['periodo_estatal'], 'Carpio') !== false) {echo ' selected="selected"';}?>>Ramiro de León Carpio - (1993-1996)</option>
							<option value="Álvaro Arzú - (1996-2000)"<?php if (strpos($monumento['periodo_estatal'], 'lvaro Arz') !== false) {echo ' selected="selected"';}?>>Álvaro Arzú - (1996-2000)</option>
							<option value="No se sabe/otro"<?php if (strpos($monumento['periodo_estatal'], 'No se sabe') !== false) {echo ' selected="selected"';}?>>No se sabe/otro</option>
						</select>
                    </div> 
                    
                    <div class="form-group">
						<label for="conmemoracion">Fecha de conmemoración</label>                  
						<div class="hero-unit">
							<input class="form-control" type="text" id="conmemoracion" name="conmemoracion" style="color:black" value="<?php echo $monumento['fecha_conmemoracion'];?>">
						</div>
						<!--Script para datepicker-->
						<script type="text/javascript">
							$(document).ready(function () {
								$('#conmemoracion').datepicker({
									format: "yyyy-mm-dd"
								});  
							});
						</script>
					</div>

					<div class="form-group">
						<label for="personas">Víctimas nombradas</label>
                    
						<script>
							$(document).ready(function() {
								  $(".personas").select2();
							});
						</script>

						<select class="personas form-control" name="personas[]" id="personas" multiple="multiple">
							<?php
								$result=allPersonas();
								while($row=mysqli_fetch_array($result)){
									echo '<option value="'.$row["id_persona"].'"';
									$personas = getPersonasMonumento($idMonumento);
									while($row_persona=mysqli_fetch_array($personas)){
										if($row_persona['id_persona']==$row["id_persona"]){ echo ' selected="selected"';}
									}
									echo '>'.$row["nombre"]."</option>";
								}
							?>
						</select>
						<br>
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAgregarPersona">
                          Agregar nueva víctima
						</button>
					</div>
					
					<hr class="featurette-divider">
                    
                    <h3>Fuente</h3>
                    
					<div class="form-group">	
						<label for="organizacion">Fuente inicial</label>
						<script>
							$(document).ready(function() {
								$(".organizacion").select2();
							});
						</script>
						<select class="organizacion form-control" name="organizacion" id="organizacion">
							<?php
								$result=allOrganizacion();
								while($row=mysqli_fetch_array($result)){
									echo '<option value='.$row["id_organizacion"].'';
									if($monumento['id_organizacion']==$row["id_organizacion"]){ echo ' selected="selected"';}
									echo ">".$row["nombre_organizacion"]."</option>";
								}
							?>
						</select><br>
                  
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAgregarOrganizacion">
							Agregar una organización
						</button>
                    </div>

					<div class="form-group">	
						<label for="organizacionResponsable">Organización Responsable</label>
						<script>
							$(document).ready(function() {
								$(".organizacionResponsable").select2();
							});
						</script>

						<select class="organizacionResponsable form-control" name="organizacionResponsable" id="organizacionResponsable">
							<?php
								$result=allOrganizacion();
								while($row=mysqli_fetch_array($result)){
									echo '<option value='.$row["id_organizacion"].'';
									if($monumento['id_organizacion_responsable']==$row["id_organizacion"]){ echo ' selected="selected"';}
									echo ">".$row["nombre_organizacion"]."</option>";
								}
							?>
						</select><br>
						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAgregarOrganizacion">
							Agregar una organización
						</button>
                    </div>

                    <div class="form-group">
						<label for="persona_informacion">Persona que dio la información</label>
						<input class="form-control" name="persona_informacion" id="persona_informacion" value="<?php echo $monumento['persona_informacion'];?>" type="text" />
					</div>
						
                    <div class="form-group">
						<label for="inputSitio">Fecha de investigación</label>
						<div class="hero-unit">
							<input class="form-control" type="text" id="fecha_investigacion" name="fecha_investigacion" style="color:black" value="<?php echo $monumento['fecha_investigacion'];?>">
						</div>
						<!--Script para datepicker-->
						<script type="text/javascript">
							$(document).ready(function () {
								$('#fecha_investigacion').datepicker({
									format: "yyyy-mm-dd"
								});  
							});
						 </script>
					</div>

					<hr class="featurette-divider">	

                    <h3>Ubicación</h3>

                    <div class="form-group">
						<label for="direccion">Dirección</label>
						<input class="form-control" name="direccion" id="direccion" type="text" value="<?php echo $monumento['direccion'];?>"/>
					</div>		
                    <div class="form-group">
						<label for="ubicacion">Ubicación</label>
						<input class="form-control" name="ubicacion" id="ubicacion" type="text" value="<?php echo $monumento['ubicacion'];?>"/>
                    </div>	
					<div class="form-group">
						<label for="como_llegar">Cómo llegar</label>
						<input class="form-control" name="como_llegar" id="como_llegar" type="text" value="<?php echo $monumento['como_llegar'];?>"/>
                    </div>	
					<div class="form-group">
						<label for="acceso">Acceso</label>
						<input class="form-control" name="acceso" id="acceso" type="text" value="<?php echo $monumento['acceso'];?>"/>
                    </div>	                    
                    <div class="form-group">
						<label>Posición (Click en el mapa para fijar la posición)</label>
						<div class="form-group row">
							<div class="col-md-2">
								<input class="form-control" name="longitud" id="longitud" type="double" placeholder="longitud" value="<?php echo $monumento['longitud'];?>"/>
							</div>
							<div class="col-md-2">
								<input class="form-control" name="latitud" id="latitud" type="double" placeholder="latitud" value="<?php echo $monumento['latitud'];?>"/>
							</div>	
						</div>
						<button type="button" onclick="updateLocation(); return false;" class="btn btn-default">Actualizar coordenadas en mapa</button>
					</div>
					
                    <div id="mapa" style="width:500px;height:380px;"></div>
                    
					<hr class="featurette-divider">	 
					
                    <h3>Sobre el Sitio</h3>
					
                    <div class="form-group">
						<label for="construccion_monumento">Construído por</label>
						<input class="form-control" name="construccion_monumento" id="construccion_monumento" type="text" value="<?php echo $monumento['construccion_monumento'];?>"/>
                    </div>
					<div class="form-group">
						<label for="apoyo_monumento">Apoyo de</label>
						<input class="form-control" name="apoyo_monumento" id="apoyo_monumento" type="text" value="<?php echo $monumento['apoyo_monumento'];?>"/>
                    </div>
					<div class="form-group">
						<label for="fecha_creacion">Fecha de creación</label>                   
						<div class="hero-unit">
							<input class="form-control" type="text" id="fecha_creacion" name="fecha_creacion" style="color:black" value="<?php echo $monumento['fecha_creacion'];?>">
						</div>
						<!--Script para datepicker-->
						<script type="text/javascript">
							$(document).ready(function () {
								$('#fecha_creacion').datepicker({
									format: "yyyy-mm-dd"
								});  
							});
						 </script>
                    </div>

					<div class="form-group">	
						<label for="descripcion-corta">Descripción corta del sitio</label>
						<textarea class="form-control" rows="4" name="descripcion-corta" id="descripcion-corta" ><?php echo $monumento['descripcion_corta'] ?></textarea>
                    </div> 
					<div class="form-group"> 
						<label for="is_reportaje">Tiene reportaje: </label>
						<input type="checkbox" id="is_reportaje" name="is_reportaje" value="1" <?php echo ($monumento['is_reportaje']==1) ? 'checked' : ''; ?>>
					</div>
                    <div class="form-group">
						<label for="descripcion">Descripción del sitio</label>
						<textarea class="form-control mceEditor" rows="8" name="descripcion" id="descripcion"><?php echo $monumento['descripcion'];?></textarea>
						<script>
							tinyMCE.init({
									// mode : "specific_textareas",
									// editor_selector : "mceEditor",
									// selector: "textarea",
									// plugins: [
									//     "advlist autolink lists link image charmap print preview anchor",
									//     "searchreplace visualblocks code fullscreen",
									//     "insertdatetime media table contextmenu paste"
									// ],
									// toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
									mode : "specific_textareas",
									editor_selector : "mceEditor",
									selector: "textarea#descripcion",
									
									language_url: <?php echo '"http://'.$root_path.'js/tinymce/langs/es.js"'?>,
									language:"es",
									plugins: [
										"advlist autolink lists link image charmap print preview hr anchor pagebreak",
										"searchreplace wordcount visualblocks visualchars code fullscreen",
										"media nonbreaking save table contextmenu directionality",
										"template paste textcolor colorpicker textpattern imagetools jbimages"
									],
									toolbar1: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
									toolbar2: "preview media | forecolor | code jbimages",
									templates: [
										{title: 'Test template 1', content: 'Test 1'},
										{title: 'Test template 2', content: 'Test 2'}
									],
									relative_urls : false,
							});
						</script>
					</div>	
                    
					<div class="form-group">
						<label for="actividades">Actividades</label>
						<input class="form-control" name="actividades" id="actividades" type="text" value="<?php echo $monumento['actividades'];?>"/>
                    </div>
					<div class="form-group">
						<label for="autor_obra">Autor de la obra</label>
						<input class="form-control" name="autor_obra" id="autor_obra" type="text" value="<?php echo $monumento['autor_obra'];?>"/>
                    </div>

					<hr class="featurette-divider">	 
					
                    <h3>Multimedia</h3>
                    
                    <div id="divMultimedia">
						<?php
							echo '<div id="multimedia0">';

							$officialExists = false;
                            while($row_multimedia=mysqli_fetch_array($multimedia)){
								if($row_multimedia['id_multimedia']==$monumento["foto_oficial"]){
									$officialExists = true;
									echo '<div class="form-group">';
									echo '<label for="fileSelect">Imagen Principal (máximo 2 MB)</label><br>';
									echo '<img src="'.$row_multimedia['direccion_archivo'].'" class="img-thumbnail" width="500" height="500">';
									echo '<input class="form-control" name="foto_oficial" id="foto_oficial" type="hidden" value="'.$row_multimedia['id_multimedia'].'"/>';
									echo '<input type="file" name="fileSelect[]" class="mediaImage" id="fileSelect">';
									echo '<input type="hidden" name="fileType[]" id="fileType0" value="imagen">';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="tituloMedia">Título</label>';
									echo '<input class="form-control" name="tituloMedia[]" id="tituloMedia" type="text" value="'.$row_multimedia['titulo'].'"/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="autorMedia">Autor</label>';
									echo '<input class="form-control" name="autorMedia[]" id="autorMedia" type="text" value="'.$row_multimedia['autor'].'"/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="fuenteMedia">Fuente</label>';
									echo '<input class="form-control" name="fuenteMedia[]" id="fuenteMedia" type="text" value="'.$row_multimedia['fuente'].'"/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="licenciaMedia">Licencia</label>';
									echo '<input class="form-control" name="licenciaMedia[]" id="licenciaMedia" type="text" value="'.$row_multimedia['licencia'].'"/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="linkMedia">Link (u objeto incrustado)</label>';
									echo '<input class="form-control" name="linkMedia[]" id="linkMedia" type="text" value="'.$row_multimedia['link'].'"/>';
									echo '</div>';
									echo '<input class="form-control" name="idMedia[]" id="idMedia" type="hidden" value="'.$row_multimedia['id_multimedia'].'"/>';
									echo '<button type="button" class="btn btn-default" onclick="addMedia()" data-toggle="tooltip" title="Agregar nuevo elemento multimedia">';
									echo '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
									echo '</button><br><br>';
									break;
                                }
                            }
							
                            if(!$officialExists){
								echo '<div class="form-group">';
								echo '<label for="fileSelect">Imagen Principal (máximo 2 MB)</label>';
								echo '<input class="form-control" name="foto_oficial" id="foto_oficial" type="hidden" value="NULL"/>';
								echo '<input type="hidden" name="fileType[]" id="fileType0" value="imagen">';
								echo '<input type="file" name="fileSelect[]" class="mediaImage" id="fileSelect">';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="tituloMedia">Título</label>';
								echo '<input class="form-control" name="tituloMedia[]" id="tituloMedia" type="text" value=" "/>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="autorMedia">Autor</label>';
								echo '<input class="form-control" name="autorMedia[]" id="autorMedia" type="text" value=" "/>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="fuenteMedia">Fuente</label>';
								echo '<input class="form-control" name="fuenteMedia[]" id="fuenteMedia" type="text" value=" "/>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="licenciaMedia">Licencia</label>';
								echo '<input class="form-control" name="licenciaMedia[]" id="licenciaMedia" type="text" value=" "/>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="linkMedia">Link (u objeto incrustado)</label>';
								echo '<input class="form-control" name="linkMedia[]" id="linkMedia" type="text" value=" " />';
								echo '</div>';
								echo '<button type="button" class="btn btn-default" onclick="addMedia()" data-toggle="tooltip" title="Agregar nuevo elemento multimedia">';
								echo '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
								echo '</button>';
								echo '<br><br>';
                            }
                            echo '</div>';
                                
								
							$multimediaNumber=1;
							
                            // $multimedia = getMultimediaMonumento($idMonumento);
                            while($row_multimedia=mysqli_fetch_array($multimedia)){
                                if($row_multimedia['id_multimedia']!=$monumento["foto_oficial"]){
									echo '<div id="multimedia'.$multimediaNumber.'">';
									echo '<div class="form-group">';
									echo '<label for="fileSelect">Archivo extra #'.($multimediaNumber).' (máximo 2 MB para fotos)</label><br>';
									if($row_multimedia['tipo']=='imagen'){
										echo '<img src="'.$row_multimedia['direccion_archivo'].'" class="img-thumbnail" width="500" height="500">';
									} else {
										echo '<div class="multimedia-container">'.$row_multimedia['link'].'</div>';
									}
									//Revisar esta línea:
									echo '<input type="file" name="fileSelect[]" class="mediaImage" id="fileSelect" '.($row_multimedia['tipo']!='imagen'?'style="display:none"':'').'>';								
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="fileType">Tipo de archivo</label>';
									echo '<select class="form-control" name="fileType[]" id="fileType'.($multimediaNumber).'" onChange="selectFileOption(this)">';
									echo '<option value="imagen" '.($row_multimedia['tipo']=='imagen'?'selected="selected"':'').'>Imagen</option>';
									echo '<option value="video" '.($row_multimedia['tipo']=='video'?'selected="selected"':'').'>Video</option>';
									echo '<option value="audio" '.($row_multimedia['tipo']=='audio'?'selected="selected"':'').'>Audio</option>';
									echo '</select>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="tituloMedia">Título</label>';
									echo '<input class="form-control" name="tituloMedia[]" id="tituloMedia" type="text" value="'.$row_multimedia['titulo'].'"/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="autorMedia">Autor</label>';
									echo '<input class="form-control" name="autorMedia[]" id="autorMedia" type="text" value="'.$row_multimedia['autor'].'"/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="fuenteMedia">Fuente</label>';
									echo '<input class="form-control" name="fuenteMedia[]" id="fuenteMedia" type="text" value="'.$row_multimedia['fuente'].'"/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="licenciaMedia">Licencia</label>';
									echo '<input class="form-control" name="licenciaMedia[]" id="licenciaMedia" type="text" value="'.$row_multimedia['licencia'].'"/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="linkMedia">Link (u objeto incrustado)</label>';
									echo '<textarea class="form-control" name="linkMedia[]" id="linkMedia" rows="4">'.$row_multimedia['link'].'</textarea>';
									echo '</div>';
									echo '<input class="form-control" name="idMedia[]" id="idMedia" type="hidden" value="'.$row_multimedia['id_multimedia'].'"/>';
									echo '<button type="button" class="btn btn-default" onClick="addMedia()">';
									echo '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
									echo '</button>';
									echo '<button type="button" class="btn btn-default" onClick="removeMedia()">';
									echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
									echo '</button><br><br>';
									echo '</div>';
									
									$multimediaNumber+=1;
                                }
                            }
                        ?>
                    </div>
                    
                    <script>
                        function addMedia(){
                            var multimediatxt = '<div id="multimedia'+$('#divMultimedia').children().size()+'">' +
													'<div class="form-group">' +
														'<label>Archivo extra #'+$('#divMultimedia').children().size()+' (máximo 2 MB para fotos)</label><br>' +
														'<label for="fileType'+$('#divMultimedia').children().size()+'">Tipo de archivo</label>' +
														'<select class="form-control" name="fileType[]" id="fileType'+$('#divMultimedia').children().size()+'" onChange="selectFileOption(this)">' +
															'<option value="imagen" selected="selected">Imagen</option>' +
															'<option value="video">Video</option>' +
															'<option value="audio">Audio</option>' +
														'</select><br>' +
                                                        '<div class="form-group">' +
                                                            '<input type="file" name="fileSelect[]" id="fileSelect" class="mediaImage" required="required">' +
                                                        '</div>' +   
													'</div>' +
													'<div class="form-group">' +	
														'<label for="tituloMedia">Título</label>' +
														'<input class="form-control" name="tituloMedia[]" id="tituloMedia" type="text" value=" "/>' +
													'</div>' +
													'<div class="form-group">' +	
														'<label for="autorMedia">Autor</label>' +
														'<input class="form-control" name="autorMedia[]" id="autorMedia" type="text" />' +
													'</div>' +
													'<div class="form-group">' +		
														'<label for="fuenteMedia">Fuente</label>' +
														'<input class="form-control" name="fuenteMedia[]" id="fuenteMedia" type="text" />' +
													'</div>' +
													'<div class="form-group">' +	
														'<label for="licenciaMedia">Licencia</label>' +
														'<input class="form-control" name="licenciaMedia[]" id="licenciaMedia" type="text" />' +
													'</div>' +
													'<div class="form-group">' +	
														'<label for="linkMedia">Link (u objeto incrustado)</label>' + 
														'<textarea class="form-control" name="linkMedia[]" id="linkMedia" rows="4"></textarea>' +
													'</div>' +	
													'<button type="button" class="btn btn-default" onClick="addMedia()">' +
														'<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>' +
													'</button>' +
													'<button type="button" class="btn btn-default" onClick="removeMedia()">' +
														'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' +
													'</button><br><br>' +
												'</div>';

							$("#divMultimedia").append(multimediatxt);

                        }
						
                        function removeMedia(){
                            var numMedia = $('#divMultimedia').children().size() -1;
                            // var element = document.getElementById("multimedia"+numMedia);
                            // element.parentNode.removeChild(element);
                            $("#multimedia"+numMedia).remove();

                        }
						
                        function selectFileOption(el){
                            el.value=($(this).value);
                            //7 es el numero de elemento que es el fileSelector
                            if (el.value=="imagen"){
                                el.parentNode.children[7].style.display="block";
                            }
                            else{
                                el.parentNode.children[7].style.display="none";
                            }
                        }

                        $('body').on('change','.mediaImage', function() {

                          //this.files[0].size gets the size of your file.
                          if(this.files[0].size>2000000){
                            alert("El archivo seleccionado excede 2 MB en tamaño, por favor comprima la imagen.");
                          }                          
                        });

                    </script>

					<hr class="featurette-divider">	 

                    <h3>Noticias</h3>
                    <div id="divNoticias">
						<?php 
							$noticiasMonumento = getNoticiasMonumento($idMonumento);

							$noticiaInit = false;
							while($row_noticia=mysqli_fetch_array($noticiasMonumento)){
								$noticiaInit = true;
								echo '<div id="noticia0">';
								echo '<div class="form-group">';
								echo '<label for="tituloNoticia">Título</label>';
								echo '<input class="form-control" name="tituloNoticia[]" id="tituloNoticia" type="text" value="'.$row_noticia['titulo'].' "/>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="linkNoticia">Link</label>';
								echo '<input class="form-control" name="linkNoticia[]" id="linkNoticia" type="text" value="'.$row_noticia['link'].'"/>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="fechaNoticia">Fecha</label>';
								echo '<input class="form-control" name="fechaNoticia[]" id="fechaNoticia" type="text" style="color:black" value="'.$row_noticia['fecha'].'"readonly>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="fuenteNoticia">Fuente</label>';
								echo '<input class="form-control" name="fuenteNoticia[]" id="fuenteNoticia" type="text" value="'.$row_noticia['fuente'].'"/>';
								echo '</div>';
								echo '<input class="form-control" name="idNoticia[]" id="idNoticia" type="hidden" value="'.$row_noticia['id_noticia'].'"/>';
								echo '<button type="button" class="btn btn-default btn-addnoticia" onclick="addNoticia()">';
								echo '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
								echo '</button>';
								echo '</div>';
								break;
							}
							
							if(!$noticiaInit){
								echo '<div id="noticia0">';
								echo '<div class="form-group">';
								echo '<label for="tituloNoticia">Título</label>';
								echo '<input class="form-control" name="tituloNoticia[]" id="tituloNoticia" type="text" value=" "/>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="linkNoticia">Link</label>';
								echo '<input class="form-control" name="linkNoticia[]" id="linkNoticia" type="text" value=" "/>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="fechaNoticia">Fecha</label>';
								echo '<input class="form-control" name="fechaNoticia[]" id="fechaNoticia" type="text" style="color:black" value="'.date("Y-m-d").'" readonly>';
								echo '</div>';
								echo '<div class="form-group">';
								echo '<label for="fuenteNoticia">Fuente</label>';
								echo '<input class="form-control" name="fuenteNoticia[]" id="fuenteNoticia" type="text" value=" "/>';
								echo '</div>';
								echo '<button type="button" class="btn btn-default btn-addnoticia" onclick="addNoticia()">';
								echo '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
								echo '</button>';
								echo '</div>';
							}
							
							$noticiaNum=0;
							mysqli_data_seek($noticiasMonumento, 0);
                        
							while($row_noticia=mysqli_fetch_array($noticiasMonumento)){
								if($noticiaNum!=0){
									echo '<div id="noticia'.$noticiaNum.'">';
									echo '<div class="form-group">';
									echo '<label for="tituloNoticia">Título</label>';
									echo '<input class="form-control" name="tituloNoticia[]" id="tituloNoticia" type="text" value="'.$row_noticia['titulo'].' "/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="linkNoticia">Link</label>';
									echo '<input class="form-control" name="linkNoticia[]" id="linkNoticia" type="text" value="'.$row_noticia['link'].'"/>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="fechaNoticia">Fecha</label>';
									echo '<input class="form-control" name="fechaNoticia[]" id="fechaNoticia" type="text" style="color:black" value="'.$row_noticia['fecha'].'"readonly>';
									echo '</div>';
									echo '<div class="form-group">';
									echo '<label for="fuenteNoticia">Fuente</label>';
									echo '<input class="form-control" name="fuenteNoticia[]" id="fuenteNoticia" type="text" value="'.$row_noticia['fuente'].'"/>';
									echo '</div>';
									echo '<input class="form-control" name="idNoticia[]" id="idNoticia" type="hidden" value="'.$row_noticia['id_noticia'].'"/>';
									echo '<button type="button" class="btn btn-default btn-addnoticia" onclick="addNoticia()">';
									echo '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>';
									echo '</button>';
									echo '<button type="button" class="btn btn-default" onClick="removeNoticia()">';
									echo '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
									echo '</button>';
									echo '</div>';
								}
								$noticiaNum+=1;
							}
						?>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $('#fechaNoticia').datepicker({
                                format: "yyyy-mm-dd"
                            });  
                        });

                        function addNoticia(){
                            var noticiatxt = '<div id="noticia'+$('#divNoticias').children().size()+'">' +
												'<div class="form-group">' +
													'<label for="tituloNoticia">Título</label>' +
													'<input class="form-control" name="tituloNoticia[]" id="tituloNoticia" type="text" value=" "/>' +
												'</div>' +
												'<div class="form-group">' +	
													'<label for="linkNoticia">Link</label>' +
													'<input class="form-control" name="linkNoticia[]" id="linkNoticia" type="text" value=" "/>' +
												'</div>' +
												'<div class="form-group">' +
													'<label for="fechaNoticia">Fecha</label>' +
													'<input class="form-control" name="fechaNoticia[]" id="fechaNoticia'+$('#divNoticias').children().size()+'" type="text" style="color:black" value="<?php echo date("Y-m-d")?>"readonly>' +
												'</div>' +
												'<div class="form-group">' +
													'<label for="fuenteNoticia">Fuente</label>' +
													'<input class="form-control" name="fuenteNoticia[]" id="fuenteNoticia" type="text" value=" "/>' +
												'</div>' +
												'<button type="button" class="btn btn-default btn-addnoticia" onclick="addNoticia()">' +
													'<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>' +
												'</button>' +
												'<button type="button" class="btn btn-default" onClick="removeNoticia()">' +
													'<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' +
												'</button>' +
											'</div>';
                            $("#divNoticias").append(noticiatxt);
                            $('#fechaNoticia'+($('#divNoticias').children().size()-1)).datepicker({
                                 format: "yyyy-mm-dd"
                             }); 
                        }
						
                        function removeNoticia(){
                            var numNoticia = $('#divNoticias').children().size() -1;
                            $("#noticia"+numNoticia).remove();

                        }
                    </script>

					<hr class="featurette-divider">	 
					
                    <h3>Campos adicionales</h3>
                    <?php
						$result=allCampoActivo();
						while($row=mysqli_fetch_array($result)){
							echo '<div class="form-group">';
							echo '<label for="'.$row["id_campo_adicional"].'">'.$row["nombre"].'</label>';
							echo '<select class="form-control" name="'.$row["id_campo_adicional"]. '"id="'.$row["id_campo_adicional"].'">';
							$idCampo = $row["id_campo_adicional"];
							$resultValores = allValorCampoActivo($idCampo);
							while($rowVal=mysqli_fetch_array($resultValores)){
								echo '<option value='.$rowVal["id_valor"];
								$camposExtra = getCamposExtraMonumento($idMonumento);
								while($rowextra = mysqli_fetch_array($camposExtra)){
									if($rowextra['id_valor']==$rowVal['id_valor']){
										echo ' selected="selected" ';
									}
								}
								echo ">".$rowVal["valor"]."</option>";
							}
							echo '</select>';
							echo '</div>';
						}
                    ?>
                    <br>
                    <button type="submit" class="btn btn-primary">Actualizar Sitio de Memoria</button>
                    <button type="submit" class="btn btn-primary" style="position:fixed;top:10%;right:10%;">Actualizar Sitio de Memoria</button>
					<button type="button" class="btn btn-default" onclick="window.location='gestionMonumento.php';">Cancelar</button>
                </form>

            </div>
        </div>    
    </div>

    <div class="modal fade" id="modalAgregarPersona" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Agregar Persona</h4>
				</div>
				<div class="modal-body">
					<form name="agregarPersona" id="agregarPersona" method="post" action="../../include/agregarPersona.php">
						<div class="form-group">
							<label for="nombrePersona">Nombre</label>
							<input class="form-control" name="nombrePersona" id="nombrePersona" type="text" />
						</div>
						<div class="form-group">
							<p><label class="control-label"> Menor de edad</label></p>
							<label class="radio-inline">
								<input type="radio" name="menordeedad" id="menordeedad" value="1">Sí
							</label>
							<label class="radio-inline">
								<input type="radio" name="menordeedad" id="menordeedad" value="0" CHECKED>No
							</label>
						</div>
                        <div class="form-group">						
							<label for="genero">Género</label>
							<select class="form-control" name="genero" id="genero">
								<?php
									$result=allGenero();
									while($row=mysqli_fetch_array($result)){
										echo '<option value='.$row["id_genero"].">".$row["genero"]."</option>";
									}
								?>
							</select>
						</div>
						<div class="form-group">	
							<label for="id_sector">Sector</label>
							<select class="form-control" name="id_sector" id="id_sector">
                            <?php
                                $result=allSector();
                                while($row=mysqli_fetch_array($result)){
                                    echo '<option value='.$row["id_sector"].">".$row["sector"]."</option>";
                                }
                            ?>
							</select>
						</div>
                        <div class="form-group">	
							<label for="id_profesion">Profesion</label>
							<select class="form-control" name="id_profesion" id="id_profesion">
								<?php
									$result=allProfesion();
									while($row=mysqli_fetch_array($result)){
										echo '<option value='.$row["id_profesion"].">".$row["profesion"]."</option>";
									}
								?>
							</select>
						</div>
                        <div class="form-group">	
							<label for="id_pais">País de nacionalidad</label>
							<select class="form-control" name="id_pais" id="id_pais">
								<?php
									$result=allPais();
									while($row=mysqli_fetch_array($result)){
										echo '<option value='.$row["id_pais"].">".$row["pais"]."</option>";
									}
								?>
							</select>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							<button type="submit" class="btn btn-primary" id="agregarPersona">Agregar Persona</button>
						</div>
					</form>
					
					<script type='text/javascript'>
						//Para limpiar los controles del modal dialog al cerrarse:
						$('#modalAgregarPersona').on('hidden.bs.modal', function () {
							$('.modal-body').find('input').val('');
						});
						
					</script>
				</div>
			</div>
		</div>
    </div>



    <div class="modal fade" id="modalAgregarOrganizacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Agregar Organización</h4>
				</div>
				<div class="modal-body">
					<form name="agregarOrganizacion" id="agregarOrganizacion" method="post" action="../../include/agregarOrganizacion.php">
                        <div class="form-group">
							<label for="nombreOrganizacion">Nombre de la organización</label>
							<input class="form-control" name="nombreOrganizacion" id="nombreOrganizacion" type="text" />
                        </div>
						<div class="form-group">
							<label for="personaOrganizacion">Persona responsable de la organización</label>
							<input class="form-control" name="personaOrganizacion" id="personaOrganizacion" type="text" />
                        </div>
						<div class="form-group">
							<label for="telefonoOrganizacion">Teléfono</label>
							<input class="form-control" name="telefonoOrganizacion" id="telefonoOrganizacion" type="number" />
                        </div>
						<div class="form-group">
							<label for="emailOrganizacion">Correo electrónico</label>
							<input class="form-control" name="emailOrganizacion" id="emailOrganizacion" type="email" />
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							<button type="submit" class="btn btn-primary" id="agregarOrganizacion">Agregar Organización</button>
						</div>
					</form>
					<script type='text/javascript'>
						//Para limpiar los controles del modal dialog al cerrarse:
						$('#modalAgregarOrganizacion').on('hidden.bs.modal', function () {
							$('.modal-body').find('input').val('');
						});		
					</script>
				</div>
			</div>
		</div>
    </div>
    
    <script>
        //Para evitar que se submitee al dar enter
        $(document).keypress(function (e) {
          if(e.which == 13 && e.target.nodeName != "TEXTAREA") return false;
        });
    </script>

</body>
</html>

      