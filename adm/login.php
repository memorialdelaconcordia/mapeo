<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/user_functions.php';

	function login_success(){
	    //session_start();
	    global $root_path;
	    // Si la sesion no esta iniciada, redirige al main_login.php
	    if(!isset($_SESSION['myusername'])){
	        header("location: http://".$_SERVER['SERVER_NAME']."/adm/login.php");
	    }
	    else{
	        header("location: http://".$_SERVER['SERVER_NAME']."/adm/index.php");
	    }
	}
	
	
	if(isset($_POST['myusername'])){
		session_start();
		
		$tbl_name="usuario"; // Tabla de usuarios

		// username and password sent from form
		$myusername=$_POST['myusername'];
		$mypassword=$_POST['mypassword'];

		// Para proteger de SQLinjection
		$myusername = stripslashes($myusername);
		$mypassword = stripslashes($mypassword);
		
		$connection = db_connect();
		
		$myusername = mysqli_real_escape_string($connection, $myusername);
		$mypassword = mysqli_real_escape_string($connection, $mypassword);
		$encrypted_mypassword=encriptarCadena($mypassword);

		$sql="SELECT * FROM $tbl_name WHERE nombre_usuario	='$myusername' and contrasena='$encrypted_mypassword'";
		$result=mysqli_query($connection, $sql);

		// Contar si encontro una coincidencia
		$count=mysqli_num_rows($result);

		// Login

		if($count==1){
			// Ir a login_success.php y regsistrar al usuario y su contrasena
			$_SESSION['myusername'] = $myusername;
			$_SESSION['mypassword'] = $encrypted_mypassword;
			$row=mysqli_fetch_array($result);
			$uid = $row['id_usuario'];
			$arrayRoles = [];
			$resultPermisos = mysqli_query($connection, "SELECT * FROM permiso_usuario WHERE id_usuario = '$uid'");
			while($row=mysqli_fetch_array($resultPermisos)){ 
				$arrayRoles[] = $row['id_rol'];
			}
			$_SESSION['rol'] = $arrayRoles;
			$_SESSION['uid'] = $uid;
			

			login_success();
		}
		else {
			echo '	<div class="alert alert-danger" role="alert">  
					<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  						<span class="sr-only">Error:</span> El usuario o contraseña son inválidos.
					</div>';
		}
	}


    //VIEW:
    require 'login_view.php';  
    
?>
