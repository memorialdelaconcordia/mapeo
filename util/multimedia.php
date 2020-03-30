<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/classes/Multimedia.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/thumbnails.php';
    
    function crearMultimedia($multimedia, $filename) {
        
        $connection = db_connect();
        
        $query = "INSERT INTO multimedia (titulo,
    										autor,
    										fuente,
    										licencia,
    										link,
    										direccion_archivo,
    										tipo) VALUES ('".
    										mysqli_escape_string($connection, $multimedia->titulo)."','".
    										mysqli_escape_string($connection, $multimedia->autor)."','".
    										mysqli_escape_string($connection, $multimedia->fuente)."','".
    										mysqli_escape_string($connection, $multimedia->licencia)."','".
    										mysqli_escape_string($connection, $multimedia->link)."','".
    										mysqli_escape_string($connection, $multimedia->direccion_archivo)."','".
    										mysqli_escape_string($connection, $multimedia->tipo)."');";
    										
		$result = mysqli_query($connection, $query);
		
		if (!$result) {
		    return 0;
		} else {
		    
		    //TODO ¿Está bien que esto se haga antes del insert a la multimedia_monumento?
		    createThumbsFromArray("../../multimedia/", "../../multimedia/thumbnails/", 200, $filename);
		    createThumbsFromArray("../../multimedia/", "../../multimedia/comprimidos/", 2048, $filename);
		    
		    return mysqli_insert_id($connection);
		}
    }
    
    function actualizarDatosMultimedia($titulo,
        $autor,
        $fuente,
        $licencia,
        $link,
        $idImagenPortada) {
        
        $connection = db_connect();
        
        $query = "UPDATE multimedia
                  set titulo = '".mysqli_escape_string($connection, $titulo)."',
                  autor = '".mysqli_escape_string($connection, $autor)."',
    			  fuente = '".mysqli_escape_string($connection, $fuente)."',
    			  licencia = '".mysqli_escape_string($connection, $licencia)."',
                  link = '".mysqli_escape_string($connection, $link)."'
    			  where id_multimedia = ".$idImagenPortada;
    										
		$result = mysqli_query($connection, $query);
		
		if (!$result) {
		    return 0;
		} else {
		    
		    return mysqli_insert_id($connection);
		}
    }
    
    //Valida y guarda en el servidor la imagen de portada luego de haberse editado.
    function subirImagenPortada() {
        
        //NO ocurrió ningún error al subir el archivo...
        if ($_FILES["imagen_portada"]["error"] == 0) {
            
            //TODO ¿es esto necesario?
            if (!empty($_FILES["imagen_portada"]["name"])) {
                
                //Verificación del tipo de archivo:
                //$imageFileType = strtolower(pathinfo($_FILES["imagen_portada"]["name"], PATHINFO_EXTENSION));
                
                //Extensiones soportadas:
                //$extAllowed = array("jpg", "jpeg", "gif", "png");
                
                //if (in_array($imageFileType, $extAllowed)) {
                    
                    //Tipos de archivo soportados:
                    $typesAllowed = array("image/jpg", "image/jpeg", "image/gif","image/png");
                    
                    if (in_array(strtolower($_FILES["imagen_portada"]["type"]), $typesAllowed)) {
                        
                        //Verificación del tamaño del archivo (50MB máx.):
                        if ($_FILES["imagen_portada"]["size"] <= 50/*MB*/ * 1024/*KB*/ * 1024/*B*/) {
                            
                            //Se crea el nuevo nombre del archivo:
                            $file = tempnam($_SERVER['DOCUMENT_ROOT'].'/multimedia/', '');
                            unlink($file);
                            //TODO ¿Yesto?
                            //move_uploaded_file($_FILES['imagen-portada']['tmp_name'], $filename);
                            
                            if (move_uploaded_file($_FILES["imagen_portada"]["tmp_name"], $file.".png"/*.$imageFileType*/)) {
                                
                                $filename = basename($file).".png"/*.$imageFileType*/;
                                
                                $multimedia = new Multimedia();
                                
                                $multimedia->titulo = htmlentities($_POST['titulo_imagen'], ENT_QUOTES);
                                $multimedia->autor = htmlentities($_POST['autor_imagen'], ENT_QUOTES);
                                $multimedia->fuente = htmlentities($_POST['fuente_imagen'], ENT_QUOTES);
                                $multimedia->licencia = htmlentities($_POST['licencia_imagen'], ENT_QUOTES);
                                $multimedia->link = htmlentities($_POST['link_imagen'], ENT_QUOTES);
                                $multimedia->direccion_archivo = $filename;
                                $multimedia->tipo = "imagen";
                                
                                //Se crea el registro en la base de datos:
                                $id = crearMultimedia($multimedia, $filename);
                                
                                if ($id === 0) {
                                    error_log("Error al guardar en base de datos.");
                                    return false;
                                } else {
                                    return $id;
                                }
                                
                            }
                            
                        } else {
                            error_log("Tamaño de la imagen.");
                            return false;
                        }
                    }
                    
                /*} else {
                    error_log("Extensión no soportada.");
                    return false;
                }*/
            }
            
        } else {
            error_log("Error al subir el archivo.");
            return false;
        }
        
    }
    
    
    //Valida y guarda en el servidor una imagen (que no es imagen de portada).
    function subirImagen() {

        //NO ocurrió ningún error al subir el archivo...
        if ($_FILES["file_media"]["error"] == 0) {
            
            //TODO ¿es esto necesario?
            if (!empty($_FILES["file_media"]["name"])) {
                
                //Verificación del tipo de archivo:
                $imageFileType = strtolower(pathinfo($_FILES["file_media"]["name"], PATHINFO_EXTENSION));
                
                //Extensiones soportadas:
                $extAllowed = array("jpg", "jpeg", "gif", "png");
                
                if (in_array($imageFileType, $extAllowed)) {
                    
                    //Tipos de archivo soportados:
                    $typesAllowed = array("image/jpg", "image/jpeg", "image/gif","image/png");
                    
                    if (in_array(strtolower($_FILES["file_media"]["type"]), $typesAllowed)) {
                        
                        //Verificación del tamaño del archivo (50MB máx.):
                        if ($_FILES["file_media"]["size"] <= 50/*MB*/ * 1024/*KB*/ * 1024/*B*/) {
                            
                            //Se crea el nuevo nombre del archivo:
                            $file = tempnam($_SERVER['DOCUMENT_ROOT'].'/multimedia/', '');
                            unlink($file);
                            //TODO ¿Yesto?
                            //move_uploaded_file($_FILES['imagen-portada']['tmp_name'], $filename);
                            
                            if (move_uploaded_file($_FILES["file_media"]["tmp_name"], $file.".".$imageFileType)) {
                                
                                $filename = basename($file).".".$imageFileType;

                                $multimedia = new Multimedia();
                                
                                $multimedia->titulo = htmlentities($_POST['titulo_media'], ENT_QUOTES);
                                $multimedia->autor = htmlentities($_POST['autor_media'], ENT_QUOTES);
                                $multimedia->fuente = htmlentities($_POST['fuente_media'], ENT_QUOTES);
                                $multimedia->licencia = htmlentities($_POST['licencia_media'], ENT_QUOTES);
                                $multimedia->link = htmlentities($_POST['link_media'], ENT_QUOTES);
                                //TODO
                                $multimedia->direccion_archivo = $filename;
                                $multimedia->tipo = "imagen";
                                
                                //Se crea el registro en la base de datos:
                                $id = crearMultimedia($multimedia, $filename);
                                
                                if ($id === 0) {
                                    error_log("Error al guardar en base de datos.");
                                    return false;
                                } else {
                                    return $id;
                                }
                                
                            }
                            
                        } else {
                            error_log("Tamaño de la imagen.");
                            return false;
                        }
                    }
                    
                } else {
                    error_log("Extensión no soportada.");
                    return false;
                }
            }
            
        } else {
            error_log("Error al subir el archivo.");
            return false;
        }
        
    }

    function subirImagenPortadaTemp() {
        
        //NO ocurrió ningún error al subir el archivo...
        if ($_FILES["imagen_portada"]["error"] == 0) {
            
            //TODO ¿es esto necesario?
            if (!empty($_FILES["imagen_portada"]["name"])) {
                
                //Verificación del tipo de archivo:
                $imageFileType = strtolower(pathinfo($_FILES["imagen_portada"]["name"], PATHINFO_EXTENSION));
                
                //Extensiones soportadas:
                $extAllowed = array("jpg", "jpeg", "gif", "png");
                
                if (in_array($imageFileType, $extAllowed)) {
                    
                    //Tipos de archivo soportados:
                    $typesAllowed = array("image/jpg", "image/jpeg", "image/gif","image/png");
                    
                    if (in_array(strtolower($_FILES["imagen_portada"]["type"]), $typesAllowed)) {
                        
                        //Verificación del tamaño del archivo (50MB máx.):
                        if ($_FILES["imagen_portada"]["size"] <= 50/*MB*/ * 1024/*KB*/ * 1024/*B*/) {
                            
                            //Se crea el nuevo nombre del archivo:
                            $file = tempnam($_SERVER['DOCUMENT_ROOT'].'/temp/', '');
                            unlink($file);
                            //TODO ¿Yesto?
                            //move_uploaded_file($_FILES['imagen-portada']['tmp_name'], $filename);
                            
                            if (move_uploaded_file($_FILES["imagen_portada"]["tmp_name"], $file.".".$imageFileType)) {
                                
                                $direccion_archivo = "/temp/".basename($file).".".$imageFileType;

                                return $direccion_archivo;                      
                            }
                            
                        } else {
                            error_log("Tamaño de la imagen.");
                            return false;
                        }
                    }
                    
                } else {
                    error_log("Extensión no soportada.");
                    return false;
                }
            }
            
        } else {
            error_log("Error al subir el archivo.");
            return false;
        }
        
    }
    
    
    function subirAudioVideo($index) {
        
        $multimedia = new Multimedia();
        
        $multimedia->autor = htmlentities($_POST['autorMedia'][$i], ENT_QUOTES);
        $multimedia->fuente = htmlentities($_POST['fuenteMedia'][$i], ENT_QUOTES);
        $multimedia->licencia = htmlentities($_POST['licenciaMedia'][$i], ENT_QUOTES);
        $multimedia->link = htmlentities($_POST['linkMedia'][$i], ENT_QUOTES);
        $multimedia->direccion_archivo = $filename;
        $multimedia->tipo = $_POST['fileType'][$i];
        $multimedia->titulo = htmlentities($_POST['tituloMedia'][$i], ENT_QUOTES);
        
        //Se crea el registro en la base de datos:
        $idArchivo = crearMultimedia($multimedia);
    }

    
?>