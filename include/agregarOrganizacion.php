<?php 
    session_start();
    $basedir = realpath(__DIR__);
    include "globals.php";
    include( "db.php");

    if(isset($_POST['nombre_organizacion']) &&
		isset($_POST['persona_organizacion']) &&
		isset($_POST['telefono_organizacion']) &&
		isset($_POST['email_organizacion'])){
		
		//TODO ¿Esto para qué es?
        $telOrganizacion="NULL";
        if(!($_POST['telefono_organizacion']=="")){
            $telOrganizacion=$_POST['telefono_organizacion'];
        }
        $result=crearOrganizacion($_POST['nombre_organizacion'],$_POST['persona_organizacion'],$telOrganizacion,$_POST['email_organizacion']);
        if(!$result){
            echo json_encode(array());
        } else {
            $arr = array(
              'idOrganizacion'=>$result,
              'nombreOrganizacion'=>$_POST["nombre_organizacion"]
            );
            echo json_encode($arr);
        }
    } else {
        echo json_encode(array());
    }
    

?>
