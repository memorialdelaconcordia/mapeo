<?php //Verificar que sea admin
include "../include/db.php";
session_start();
if(!in_array(1, $_SESSION['rol']) && !in_array(2, $_SESSION['rol']) && !in_array(3, $_SESSION['rol'])){
	header("location: http://".$root_path."adm/login.php");
}
if(isset($_GET['op'])){
	//edicion de informacion
	if($_GET['op']=='1'){
		if(isset($_POST['nombre'])&&isset($_POST['apellido'])&&isset($_POST['correo'])&&isset($_POST['nombre_usuario'])){
			$result=editarUsuarioP($_SESSION['uid'],$_POST['nombre'],$_POST['apellido'],'activo',$_POST['correo'],$_POST['nombre_usuario']);
			if($result){
				//echo "<p>Perfil editado exitosamente</p>";
                $alerta = 'usuarioModificadoExito';
				$_SESSION['myusername']=$_POST['nombre_usuario'];
			}
			else{
				//echo "<p>Error al editar pefil</p>";
                $alerta = 'usuarioModificadoError';
			}
		}
		else{
			echo "<p>Nada para editar</p>";
		}
	}
	//cambio de contrasenha
	else if($_GET['op']=='2'){
		
		if(isset($_POST['contrasenaa'])&&isset($_POST['contrasena'])&&isset($_POST['ccontrasena'])){
			$contrasenaEncriptada=encriptarCadena($_POST['contrasenaa']);
			if($_SESSION['mypassword']==$contrasenaEncriptada){
				$ncontrasena=encriptarCadena($_POST['contrasena']);
				$result=nuevaContrasena($_SESSION['uid'],$ncontrasena);
				if($result){
					//echo "<p>Contrasena modificada exitosamente</p>";
                    $alerta = 'passwordModExito';
					$_SESSION['mypassword']=$ncontrasena;
				}
				else{
					//echo "<p>Error al modificar contrasena</p>";
                    $alerta = 'passwordModError';
				}
			}
			else{
				//echo "<p>La contrasena proporcionada no es la correcta</p>";
                $alerta = 'passwordIncorrecto';
			}
		}
		else{
			//echo "<p>No hay informacion para cambiar contrasena</p>";
            $alerta = 'passwordNoInfo';
		}
	}
}
$uresult=getUsuario($_SESSION['uid']);
$uinfo=mysqli_fetch_array($uresult);

?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <title>Perfil</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">    
    <!-- jQuery -->
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script src="/js/jquery.validate.js"></script>  
    <script src="/js/messages_es.js"></script>  
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<!-- Now UI -->
	<link href="/adm/assets/css/now-ui-dashboard.css?v=1.0.1" rel="stylesheet" />
    
	<script type="text/javascript">
		function validateForm() { 
			var mensaje='';
			var nombre = document.forms["signup"]["nombre"].value;
			var	apellido = document.forms["signup"]["apellido"].value;
			var nombre_usuario=document.forms["signup"]["nombre_usuario"].value;
			var correo=document.forms["signup"]["correo"].value;
	        if(nombre==''){
				mensaje+='Es necesario llenar el campo de nombre\n';
			}
			//Revisar que apellido no este vacio
			if(apellido==''){
				mensaje+='Es necesario llenar el campo de apellido\n';
			}
			//Revisar que nombre_usuario no este vacio
			if(nombre_usuario==''){
				mensaje+='Es necesario llenar el campo de usuario\n';
			}
			if(correo==''){
				mensaje+='Es necesario llenar el campo de correo\n';
			}
			if(mensaje==''){
			}
			else{
				alert(mensaje);
				return false;	
			}
	    }
	    function validatePass(){
	    	var mensaje='';
	    	var passa=document.forms["edit"]["contrasenaA"].value;
	    	var pass=document.forms["edit"]["contrasena"].value;
			var cpass=document.forms["edit"]["ccontrasena"].value;
			if(pass!=cpass){
				mensaje+='Los campos de contrasenas no coinciden\n';
			}
			if(passa==pass){
				mensaje+='La contrasena nueva debe ser distinta a la anterior\n';
			}
			if(pass==''){
				mensaje+='Es necesario ingresar una contrasena';
			}
			if(mensaje==''){
			}
			else{
				alert(mensaje);
				return false;	
			}
	    }
	</script>
</head>
<body>

    <div class="wrapper ">

        <?php include 'include/sidebar.php'; ?>  
        
        <div class="main-panel">
        
			<?php include 'include/header.php'; ?>  

			<div class="panel-header panel-header-lg">
            </div>

        	<div class="container-fluid">	
        		
			    <!-- Sección principal: -->
    			<div class="col-sm-12" style="padding-left: 30px; padding-top: 20px;">      

                    <?php
                        if(isset($alerta)) {
    						if($alerta == 'usuarioModificadoExito') {
    				?>		
    							<div class="alert alert-success alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>¡Éxito!</strong> Usuario modificado correctamente.
    							</div>				
    				<?php
    						} else if($alerta == 'usuarioModificadoError') {
    				?>		
    							<div class="alert alert-danger alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> Ocurrió un error al modificar los datos del usuario.
    							</div>																					
    				<?php
    						} else if($alerta == 'passwordModExito') {
    				?>		
    							<div class="alert alert-success alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>¡Éxito!</strong> Contraseña modificada exitosamente.
    							</div>																					
    				<?php
    						} else if($alerta == 'passwordModError') {
    				?>		
    							<div class="alert alert-danger alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> Ocurrió un error al modificar la contraseña.
    							</div>																					
    				<?php
    						} else if($alerta == 'passwordIncorrecto') {
    				?>		
    							<div class="alert alert-danger alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> La constraseña proporcionada no es correcta.
    							</div>																					
    				<?php
    						} else if($alerta == 'passwordNoInfo') {
    				?>		
    							<div class="alert alert-danger alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> No hay información para cambiar la contraseña.
    							</div>																					
    				<?php
    						} 
    					}
    				?>					            
                
        			<div class="panel panel-default">
        				<div class="panel-heading"><b>Información</b></div>
        				<div class="panel-body">
        					<form class="form-group" name="signup" method="post" onsubmit="return validateForm()" action="perfil.php?op=1">
        						 <br><label for="exampleInputEmail1">Nombre</label><input class="form-control" name="nombre" id="nombre" type="text" value="<?php echo $uinfo['nombre']?>"/>
        						 <br><label for="exampleInputEmail1">Apellido</label><input class="form-control" name="apellido" id="apellido" type="text" value="<?php echo $uinfo['apellido']?>"/>
        						 <br><label for="exampleInputEmail1">Correo electrónico</label><input class="form-control" name="correo" id="email" type="email" value="<?php echo $uinfo['correo']?>"/>
        						 <br><label for="exampleInputEmail1">Nombre de usuario</label><input class="form-control" name="nombre_usuario" id="username" type="text" value="<?php echo $uinfo['nombre_usuario']?>" readonly/>
        						 <br><button type="submit" class="btn btn-default">Guardar</button>
        					</form>
        				</div>
        			</div>
        			<div class="panel panel-default">
        				<div class="panel-heading"><b>Cambio de contraseña</b></div>
        				<div class="panel-body">
        					<form class="form-group" name="edit" method="post" onsubmit="return validatePass()" action="perfil.php?op=2">
        						<br><label for="exampleInputEmail1">Contraseña Actual</label><input class="form-control" name="contrasenaa" id="pass" type="password" />
        						<br><label for="exampleInputEmail1">Nueva Contraseña</label><input class="form-control" name="contrasena" id="pass" type="password" />
        					 	<br><label for="exampleInputEmail1">Confirmar nueva contraseña</label><input class="form-control" name="ccontrasena" id="confirmpass" type="password" />
        					 	<br><button type="submit" class="btn btn-default">Cambiar contraseña</button>
        					</form>
        				</div>
        			</div>
			
    			
    			</div>
	
            </div>
            
        </div>
        
    </div> 

</body>

<!--   Core JS Files  
<script src="assets/js/core/jquery.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>  -->
<script src="/adm/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/adm/assets/js/now-ui-dashboard.js?v=1.0.1"></script>

</html>
