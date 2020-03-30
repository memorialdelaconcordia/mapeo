<?php

    session_start();

    require_once 'agregar_monumento3_model.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/util/multimedia.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        error_log(print_r($_POST,true));
        error_log(print_r($_FILES,true));
        
        if(!empty($_POST['op'])) {
        
            $operacion = $_POST['op'];

            if($operacion == "1") { //Eliminar multimedia
                
                if(empty($_POST['id_multimedia']) || empty ($_POST['id_monumento'])) {
                    //TODO error
                    
                    
                } else {
                
                    eliminarMultimedia($_POST['id_multimedia'], $_POST['id_monumento']);
                
                    $multimedia = getMultimediaMonumento($_POST['id_monumento']);
                    
                    $json = array();
                    while ($row = mysqli_fetch_assoc($multimedia)){
                        $json[] = $row;
                    }
                    
                    echo json_encode($json);
                    
                }
                
            } else if($operacion == "2") { //Añadir multimedia
                
                //Verificar campos obligatorios
                error_log("fasdfasdfasdf");
                error_log(print_r($_FILES,true));

                $idImagen = subirImagen();
                
                //Si ocurrió ningún error en la subida de todos los archivos:
                if ($idImagen === false) {
                    
                    //TODO $errores[] = "Ocurrió un error al subir el archivo de imagen de portada.";
                    
                } else {
                    
                    //TODO verificar si existe id-monumento.
                    registrarMultmediaMonumento($idImagen, $_POST['id_monumento']);
                }
                
                //Se busca toda la multimedia relacionada al sitio/monumento (que ahora ya incluye la recién agregada):
                $multimedia = getMultimediaMonumento($_POST['id_monumento']);
                
                $json = array();
                while ($row = mysqli_fetch_assoc($multimedia)){
                    $json[] = $row;
                }
                
                echo json_encode($json);
                
            } else if($operacion == "3") { //Eliminar noticia
                
                if(empty($_POST['id_noticia']) || empty ($_POST['id_monumento'])) {
                    //TODO error
                    
                } else {
                    
                    eliminarNoticia($_POST['id_noticia']);
                    
                    $noticias = getNoticiasMonumento($_POST['id_monumento']);
                    
                    $json = array();
                    while ($row = mysqli_fetch_assoc($noticias)){
                        $json[] = $row;
                    }
                    
                    echo json_encode($json);
                    
                }
                
            } else if($operacion == "4") { //Añadir noticia
                
                crearNuevaNoticia(
                    $_POST['titulo_noticia'],
                    $_POST['link_noticia'],
                    $_POST['fecha_noticia'],
                    $_POST['fuente_noticia'],
                    $_POST['id_monumento']
                    );
                
                //TODO verificar si la noticia fue creada correctamente.
                
                //Se busca toda la multimedia relacionada al sitio/monumento (que ahora ya incluye la recién agregada):
                $noticias = getNoticiasMonumento($_POST['id_monumento']);
                
                $json = array();
                while ($row = mysqli_fetch_assoc($noticias)){
                    $json[] = $row;
                }
                
                echo json_encode($json);
                
            } else if($operacion == "5") { //Subir foto de portada
                
                $archivo = subirImagenPortadaTemp();
                
                echo "{\"resultado\":\"".$archivo."\"}";
                
            } else if($operacion == "6") { //Modificar datos de un archivo multimedia
                
                if(empty($_POST['id_multimedia'])) {
                    //TODO error
                    
                    
                } else {
                    
                    actualizarDatosMultimedia(
                        $_POST['titulo'], 
                        $_POST['autor'], 
                        $_POST['fuente'], 
                        $_POST['licencia'], 
                        $_POST['link'],
                        $_POST['id_multimedia']
                        );
                    
                    $multimedia = getMultimediaMonumento($_POST['id_monumento']);
                    
                    $json = array();
                    while ($row = mysqli_fetch_assoc($multimedia)){
                        $json[] = $row;
                    }
                    
                    echo json_encode($json);
                    
                }
                
            } else if($operacion == "7") { //Modificar noticia
                
                if(empty($_POST['id_noticia']) || empty ($_POST['id_monumento'])) {
                    //TODO error
                    
                } else {
                    
                    actualizarNoticia(
                        $_POST['titulo'],
                        $_POST['link'],
                        $_POST['fecha'],
                        $_POST['fuente'],
                        $_POST['id_noticia']
                        );
                    
                    $noticias = getNoticiasMonumento($_POST['id_monumento']);
                    
                    $json = array();
                    while ($row = mysqli_fetch_assoc($noticias)){
                        $json[] = $row;
                    }
                    
                    echo json_encode($json);
                    
                }
                
            }
            
        } else { //Si no viene el parámetro "op":
            
            //TODO
            
        }
        
    }
    
    
?>