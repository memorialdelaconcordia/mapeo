<?php 
    session_start();
 
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';
    
    function crearPersona($nombrePersona, 
                            $menordeedad, 
                            $genero, 
                            $id_sector, 
                            $id_profesion, 
                            $id_pais) {
        
        // Connect to the database
        $connection = db_connect();
        
        $query = "INSERT INTO persona (nombre, ninez, id_genero, id_sector, id_profesion, id_pais, estado) VALUES ('".
            mysqli_escape_string($connection,$nombrePersona)."',".
            $menordeedad.",".
            $genero.",".
            $id_sector.",".
            $id_profesion.",".
            $id_pais.
            ",1);";
            
        $result = mysqli_query($connection, $query);
            
        if(!$result){
            return 0;
        } else {
            return mysqli_insert_id($connection);
        }
    }
       
    
    if(isset($_POST['nombre_persona']) &&
		isset($_POST['menordeedad']) &&
		isset($_POST['genero']) &&
		isset($_POST['profesion']) &&
		isset($_POST['pais'])) {
		    
			$result=crearPersona($_POST['nombre_persona'],
			                     $_POST['menordeedad'],
			                     $_POST['genero'],
			                     $_POST['sector'],
			                     $_POST['profesion'],
			                     $_POST['pais']);
			
			if(!$result){
				echo "<p>Error al generar la persona</p>";
			} else {
				$arr = array(
							'idVictima'=>$result,
							'nombreVictima'=>$_POST["nombre_persona"]
							);
				echo json_encode($arr);
			}
			
    } else {
        echo json_encode(array());
    }
   


?>
