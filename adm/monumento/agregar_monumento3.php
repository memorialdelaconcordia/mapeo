<?php 
    session_start();
    
    require_once 'agregar_monumento3_model.php';
    
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
        
        //El id del sitio al cual se le agregará información:
        if(!empty($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            //Se redirige la 1ra página de creación de sitios:
            header("location: /adm/monumento/agregar_monumento.php");
        }
        
        $result = crearSitio3(
            htmlentities($_POST['fuente'], ENT_QUOTES),
            htmlentities($_POST['autor_reportaje'], ENT_QUOTES),
            htmlentities($_POST['fecha_investigacion'], ENT_QUOTES),
            isset($_POST['is_reportaje']) ? 1 : 0,
            $_POST['reportaje'],
            $id
            );
        
        if($result){
            header("location: /adm/campo/gestion_monumento.php");
        }
        
    } else {
        
        if(empty($_GET['id'])) {
            //Se redirige la 1ra página de creación de sitios:
            header("location: /adm/monumento/agregar_monumento.php");
        } else {
            //TODO validar el id.
            
            $id = $_GET['id'];
            
            $datos = getSitio3($id);
            
            if($datos === false) {
                //TODO error
            } else {
                
                $fuenteInformacion = $datos['id_organizacion'];
                $autorReportaje = $datos['persona_informacion'];
                $fechaInvestigacion = $datos['fecha_investigacion'];
                $esReportaje = $datos['is_reportaje'];
                $reportaje = $datos['descripcion'];
                
            }
            
            $multimedia = getMultimediaMonumento($_GET['id']);
            $noticias = getNoticiasMonumento($_GET['id']);
            
        }
        
    }

    //VIEW:
    require 'agregar_monumento3_view.php';

?>

