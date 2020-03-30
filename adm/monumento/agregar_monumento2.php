<?php 
    session_start();
    
    require_once 'agregar_monumento2_model.php';
    
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

        //VÍCTIMAS:
        $victimas = array();
        if (isset($_POST['victimas'])) {
            $victimas = $_POST['victimas'];
        }
        
        //KEYWORDS / PALABRAS CLAVE:
        $keywords=array();
        if (isset($_POST['keywords'])) {
            foreach($_POST['keywords'] as $i){
                $keywords[] = htmlentities($i, ENT_QUOTES);
            }
        }
        
        //CAMPOS ADICIONALES:
        foreach (array_keys($_POST) as $campo) {
            if(strncmp($campo, "campo_adicional", 15) === 0){
                $camposAdicionales[] = $_POST[$campo];
            }
        }

        $organizacionResponsable = empty($_POST["organizacion_responsable"]) ? 'NULL' : htmlentities($_POST['organizacion_responsable'], ENT_QUOTES);
        
        $result = crearSitio2(
            $keywords,
            htmlentities($_POST['tipo_evento'], ENT_QUOTES),
            htmlentities($_POST['tipo_monumento'], ENT_QUOTES),
            htmlentities($_POST['estado_sitio'], ENT_QUOTES),
            htmlentities($_POST['municipio'], ENT_QUOTES),
            htmlentities($_POST['periodo_estatal'], ENT_QUOTES),
            htmlentities($_POST['fecha_conmemoracion'], ENT_QUOTES),
            $victimas,
            htmlentities($_POST['direccion'], ENT_QUOTES),
            htmlentities($_POST['ubicacion'], ENT_QUOTES),
            htmlentities($_POST['como_llegar'], ENT_QUOTES),
            htmlentities($_POST['acceso'], ENT_QUOTES),
            $camposAdicionales,
            htmlentities($_POST['fecha_creacion'], ENT_QUOTES),
            htmlentities($_POST['construccion_monumento'], ENT_QUOTES),
            htmlentities($_POST['apoyo_monumento'], ENT_QUOTES),
            $organizacionResponsable,
            htmlentities($_POST['actividades'],ENT_QUOTES),
            htmlentities($_POST['autor_obra'],ENT_QUOTES),
            $id
        );
        
        if($result){
            header("location: /adm/monumento/agregar_monumento3.php?id=".$id."&msg=success");
        }
        

    } else { //GET
        
        if(empty($_GET['id'])) {
            //Se redirige a la 1ra página de creación de sitios:
            header("location: /adm/monumento/agregar_monumento.php");
        } else {
            
            $id = $_GET['id'];
            
            $datos = getSitio2($id);
            
            if($datos === false) {
                //TODO error
            } else {
                
                $tipoEvento = $datos->datosGenerales['id_tipo_evento'];
                $periodoEstatal = $datos->datosGenerales["periodo_estatal"];
                $fechaConmemoracion = $datos->datosGenerales["fecha_conmemoracion"];
                $tipoSitio = $datos->datosGenerales["id_tipo_monumento"];
                $estadoSitio = $datos->datosGenerales["estado_sitio"];
                $municipio = $datos->datosGenerales["id_municipio"];
                $direccion = $datos->datosGenerales["direccion"];
                $ubicacion = $datos->datosGenerales["ubicacion"];
                $comoLlegar = $datos->datosGenerales["como_llegar"];
                $acceso = $datos->datosGenerales["acceso"];
                $fechaCreacion = $datos->datosGenerales["fecha_creacion"];
                $construccionMonumento = $datos->datosGenerales["construccion_monumento"];
                $apoyoMonumento = $datos->datosGenerales["apoyo_monumento"];
                $organizacionResponsable = $datos->datosGenerales["id_organizacion_responsable"];
                $actividades = $datos->datosGenerales["actividades"];
                $autor = $datos->datosGenerales["autor_obra"];
                
                $keywords = $datos->keywords;
                $victimas = $datos->victimas;
                $camposAdicionalesSeleccionados = $datos->camposAdicionales;
                
            }
        }
        
    }

    //VIEW:
    require 'agregar_monumento2_view.php';

?>

