<?php 
	session_start();

	require_once 'agregar_monumento_model.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/util/multimedia.php';
	
	error_reporting(E_ALL);
	/* This determines whether errors should be printed to the screen as part of the output or if they should be hidden from the user. */
	//ini_set('display_errors', 1);
	
	
	//VERIFICACIÓN DE ROLES:
    //ROL 3: campo.
	if(!in_array(3, $_SESSION['rol'])) {
		//Se redirige a la página de logueo:
	    header("location: /adm/login.php");
	}

	//Lista de mensajes de los errores ocurridos al intentar crear el sitio:
	$errores = array();
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    
	    $idSitio = isset($_POST["id"]) ? $_POST["id"] : '';
	    $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : '';
	    $estadoPublicacion = isset($_POST["estado_publicacion"]) ? $_POST["estado_publicacion"] : '';
	    $usuarioEdicion = isset($_POST["usuario_edicion"]) ? $_POST["usuario_edicion"] : $_SESSION['uid'];
	    $longitud = isset($_POST["longitud"]) ? $_POST["longitud"] : '14.634016';
	    $latitud = isset($_POST["latitud"]) ? $_POST["latitud"] : '-90.515467';
	    $descripcionCorta = isset($_POST["descripcion_corta"]) ? $_POST["descripcion_corta"] : '';
	    $tituloImagen = isset($_POST["titulo_imagen"]) ? $_POST["titulo_imagen"] : '';
	    $autorImagen = isset($_POST["autor_imagen"]) ? $_POST["autor_imagen"] : '';
	    $fuenteImagen = isset($_POST["fuente_imagen"]) ? $_POST["fuente_imagen"] : '';
	    $licenciaImagen = isset($_POST["licencia_imagen"]) ? $_POST["licencia_imagen"] : '';
	    $linkImagen = isset($_POST["link_imagen"]) ? $_POST["link_imagen"] : '';
	    $idImagenPortada = isset($_POST["id_imagen_portada"]) ? $_POST["id_imagen_portada"] : '';
	    
	    
	    /****** VALIDACIÓN DE CAMPOS ******/

	    $errorCamposObligatorios = false;
	    
	    //Se verifican los campos obligatorios:
	    if(empty(trim($titulo))) {
	        $errorCamposObligatorios = true;
	        $errores[] = "Debe ingresar el título del sitio.";
	    }
	    if(empty(trim($estadoPublicacion))) {
	        $errorCamposObligatorios = true;
	        $errores[] = "Debe especificar el estado de la publicación.";
	    }
	    if(empty(trim($longitud))) {
	        $errorCamposObligatorios = true;
	        $errores[] = "Debe especificar la latitud del sitio.";
	    }
	    if(empty(trim($latitud))) {
	        $errorCamposObligatorios = true;
	        $errores[] = "Debe especificar la longitud del sitio.";
	    }
	    if(empty(trim($descripcionCorta))) {
	        $errorCamposObligatorios = true;
	        $errores[] = "Debe ingresar una descripción corta para el sitio.";
	    }
	    
	    
	    if(!$errorCamposObligatorios) {
	        
	        //Es una creación de sitio:
	        if(empty($idSitio)) {
	        
	            $idImagenPortada = subirImagenPortada();
	            
	            //Si ocurrió ningún error en la subida de todos los archivos:
	            if ($idImagenPortada === false) {
	                
	                $errores[] = "Ocurrió un error al subir el archivo de imagen de portada.";
	                
	            } else {
	                
	                //SE CREA EL SITIO EN LA BASE DE DATOS:
	                //Retorna el id del sitio si se realizó todo bien. Retorna false si no.
	                $result = crearSitio1(
	                    htmlentities($_POST['titulo'], ENT_QUOTES),
	                    htmlentities($_POST['estado_publicacion'], ENT_QUOTES),
	                    htmlentities($_POST['usuario_edicion'], ENT_QUOTES),
	                    htmlentities($_POST['longitud'], ENT_QUOTES),
	                    htmlentities($_POST['latitud'], ENT_QUOTES),
	                    htmlentities($_POST['descripcion_corta'], ENT_QUOTES),
	                    $idImagenPortada
	                    );
	                
	                if($result === false) {
	                    
	                    $errores[] = "Ocurrió un error al guardar en base de datos.";
	                    
	                    echo "{\"id\":\"0\"}";
	                    
	                } else {
	                    
	                    echo "{\"id\":".$result."}";
	                    
	                    //header("location: /adm/monumento/agregar_monumento2.php?id=".$result."&msg=success");
	                }
	                
	            }
	            
	        } else { //Es una actualización de sitio:
	            
	            //Si hay que actualizar la imagen de portada:
	            if(isset($_FILES['imagen_portada'])) {
	                
	                $idImagenPortada = subirImagenPortada(); //REVISAR
	                
	            } else {
	                actualizarDatosMultimedia($tituloImagen,
	                    $autorImagen,
	                    $fuenteImagen,
	                    $licenciaImagen,
	                    $linkImagen,
	                    $idImagenPortada);
	            }
	            
	            //SE ACTUALIZA EL SITIO EN LA BASE DE DATOS:
	            //Retorna el id del sitio si se realizó todo bien. Retorna false si no.
	            $result = actualizarSitio1(
	                $idSitio,
	                htmlentities($_POST['titulo'], ENT_QUOTES),
	                htmlentities($_POST['estado_publicacion'], ENT_QUOTES),
	                htmlentities($_POST['usuario_edicion'], ENT_QUOTES),
	                htmlentities($_POST['longitud'], ENT_QUOTES),
	                htmlentities($_POST['latitud'], ENT_QUOTES),
	                htmlentities($_POST['descripcion_corta'], ENT_QUOTES),
	                $idImagenPortada
	                );
	            
	            if($result === false) {
	                
	                $errores[] = "Ocurrió un error al guardar en base de datos.";
	                
	                echo "{\"id\":\"0\"}";
	                
	            } else {
	                
	                echo "{\"id\":".$result."}";
	                
	                //header("location: /adm/monumento/agregar_monumento2.php?id=".$result."&msg=success");
	            }
	            
	        }
	        
	    } //if(!$errorCamposObligatorios)
	    
	    
	} else { //GET
	    
	    if(!empty($_GET['id'])) {
	        
	        $datos = getSitio1($_GET['id']);
	        
	        if($datos === false) {
	            //TODO error
	        } else {
	            
	            $titulo = $datos['titulo'];
	            $estadoPublicacion = $datos["estado_actual"];
	            $usuarioEdicion = $datos["usuario_edicion"];
	            $longitud = $datos["longitud"];
	            $latitud = $datos["latitud"];
	            $descripcionCorta = $datos["descripcion_corta"];
	            $imagenPortada = $datos["direccion_archivo"];
	            $tituloImagen = $datos["titulo_media"];
	            $autorImagen = $datos["autor_media"];
	            $fuenteImagen = $datos["fuente_media"];
	            $licenciaImagen = $datos["licencia_media"];
	            $linkImagen = $datos["link_media"];
	            $idImagenPortada = $datos["id_multimedia"];
	            
	        }
	    } else {
	        //todo ???
	        $usuarioEdicion = $_SESSION['uid'];
	    }
	    
	    //VIEW:
	    require 'agregar_monumento_view.php';
	}

?>

