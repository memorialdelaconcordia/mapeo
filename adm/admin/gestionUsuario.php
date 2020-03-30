<?php 
session_start();
include( '../../include/db.php');

//Verificar que sea admin
if(!in_array(1, $_SESSION['rol'])){
	header("location: http://".$root_path."adm/login.php");
}

//revisar que tipo de operacion se ha realizado eliminar, agregar o editar
if(isset($_GET['op'])){
	//Crear nuevo usuario:
	if($_GET['op']==1){
		if(isset($_POST['username']) && //Verificación de campos obligatorios...
			isset($_POST['nombre']) &&
			isset($_POST['apellido']) &&
			isset($_POST['correo']) &&
			isset($_POST['pass']) &&
			isset($_POST['statusradio'])) {
				
			$result=agregarUsuario(	$_POST['username'],
									$_POST['nombre'],
									$_POST['apellido'],
									$_POST['correo'],
									$_POST['pass'],
									$_POST['statusradio']);
									
			if($result){
				//echo "<p>Usuario ".$_POST['nombre_usuario']." agregado correctamente</p>";
				$alerta = 'usuarioCreado';
				if(!empty($_POST['chk_tipo_usuario'])) {
					foreach($_POST['chk_tipo_usuario'] as $check) {
						$asignacion=asignarRolUsuario($result,$check);
						if($asignacion==false){
							$alerta.= 'ErrorAsigRol';
						}
					}
				}
			} else {
				$alerta = 'errorCreacionUsuario';
			}	
		} else {
			$alerta = 'errorCreacionUsuario';
		}
	}
	//revisar edicion
	else if ($_GET['op']=='2'){
		if(isset($_POST['pwd']) && isset($_POST['cnfrmpwd'])){
			if($_POST['pwd']!=""){
				$result = resetPwd($_POST['id_usuario'], $_POST['pwd']);
				if($result){
					echo "<p>Contraseña reiniciada exitosamente</p>";
				}
				else{
					echo "<p>Error al reiniciar la contraseña</p>";
				}
			}

		}
		if(isset($_POST['id_usuario'])&&isset($_POST['nombre'])&&isset($_POST['apellido'])&&isset($_POST['statusradio'])){
			$result=editarUsuario($_POST['id_usuario'],$_POST['nombre'],$_POST['apellido'],$_POST['statusradio']);
			if($result){
				echo "<p>Usuario con id ".$_POST['id_usuario']." editado correctamente</p>";
				borrarPermisosUsuario($_POST['id_usuario']);
				if(!empty($_POST['check_list'])) {
					foreach($_POST['check_list'] as $check) {
						$asignacion=asignarRolUsuario($_POST['id_usuario'],$check);
						if($asignacion==false){
							echo "<p>Error al asignar el rol ".$check;
						}
					}
				}
			}
			else{
				echo "<p>Error al eliminar el usuario con id ".$_POST['id_usuario']."</p>";
			}
		}
		else{
			echo "<p>Nada para editar</p>";
		}
	}
	//reestablecer contrasenha
	else if ($_GET['op']=='4'){
		echo '<script language="javascript">';
		echo 'alert("'.resetPass($_GET['id']).'")';
		echo '</script>';
	}
	//Activación / Inactivación de usuario:
	else if ($_GET['op']=='3'){
		if(isset($_GET['id'])){
			$result=cambiarEstadoUsuario(trim($_GET['id']));
			if($result){
				echo "<p>Usuario con id ".$_GET['id']." eliminado correctamente</p>";
					$alerta = 'cambioEstadoCorrecto';
			} else {
				echo "<p>Error al eliminar el usuario con id ".$_GET['id']."</p>";
				$alerta = 'cambioEstadoError';
			}
		}
	}
}
?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <title>Gestionar de usuarios</title>
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
		function cambiarEstado(id_usuario,nombre_usuario,estado_actual) {
			if(estado_actual == 'inactivo') {
				if (confirm("¿Está seguro que quiere habilitar el usuario\n"+nombre_usuario+"?") == true) {
					/* 	id_usuario = ID del usuario sobre el cual se realizará la operación.
						op = operación a realizar (3 -> activar/desactivar).
					*/
					 document.location.href = window.location.href.split('?')[0]+"?id="+id_usuario+'&op=3';
				}
			} else {
				if (confirm("¿Está seguro que quiere inactivar el usuario \n"+nombre_usuario+"?") == true) {
					 document.location.href = window.location.href.split('?')[0]+"?id="+id_usuario+'&op=3';
				}				
			}
		}	
	</script>
</head>
<body>

    <div class="wrapper ">

        <?php include '../include/sidebar.php'; ?>  
        
        <div class="main-panel">
        
			<?php include '../include/header.php'; ?>  

			<div class="panel-header panel-header-lg">
            </div>

        	<div class="container-fluid">	
        		
			    <!-- Sección principal: -->
    			<div class="col-sm-12" style="padding-left: 30px; padding-top: 20px;">      
    			
    				<?php
    					if(isset($alerta)) {
    						if($alerta == 'cambioEstadoCorrecto') {
    				?>		
    							<div class="alert alert-success alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>¡Éxito!</strong> Estado del usuario modificado correctamente.
    							</div>				
    				<?php
    						} else if($alerta == 'cambioEstadoError') {
    				?>		
    							<div class="alert alert-danger alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> Ocurrió un error al modificar el estado del usuario.
    							</div>																					
    				<?php
    						} else if($alerta == 'usuarioCreado') {
    				?>		
    							<div class="alert alert-success alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>¡Éxito!</strong> Usuario creado con éxito.
    							</div>
    				<?php	
    						} else if($alerta == 'errorCreacionUsuario') {
    				?>		
    							<div class="alert alert-danger alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> Ocurrió un error al crear el nuevo usuario.
    							</div>
    				<?php	
    						} else if(substr($alerta, 0, 25) === "usuarioCreadoErrorAsigRol") {
    				?>		
    							<div class="alert alert-warning alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> Usuario se creó exitosamente pero ocurrió un error al asignarle los roles seleccionados.
    							</div>
    				<?php	
    						}
    					}
    				?>					

                	<br><br>
                	<table class="table" border='1'>
                		<thead>
                		<tr> 
                			<th class="th">ID</th> 
                	  		<th class="th">Usuario</th> 
                	  		<th class="th">Nombre</th> 
                	  		<th class="th">Estado</th>
                	  		<th class="th">Editar</th>
                	  		<th class="th">Cambiar Estado</th>
                	  		<th class="th">Reiniciar Contraseña</th>
                	 	</tr>
                	 	</thead>
                	<?php
                		$result=allUsuario();
                		while($row=mysqli_fetch_array($result)) {
                			echo "<tr class=\"tr\">";
                	 		echo "<td>".$row["id_usuario"]."</td>";
                	 		echo "<td>".$row["nombre_usuario"]."</td>";
                	 		echo "<td>".$row["nombre"]."</td>";
                	 		echo "<td>".$row["estado"]."</td>";
                	?>
                	<td>
                		<!-- Botón "Editar" -->
                		<button type="submit" class="btn btn-default" onclick="location.href = 'editarUsuario.php?id=<?php echo $row['id_usuario']?>'">
                			<span class="far fa-edit" aria-hidden="true" data-toggle="tooltip" title="Editar"></span>
                		</button>
                	</td>
                	<td>
                		<!-- Botón "Activar/Inactivar" -->
                		<button class="btn btn-default" onclick="cambiarEstado(<?php echo $row['id_usuario'].',\''.$row['nombre_usuario'].'\',\''.$row['estado'].'\''?>)" data-toggle="tooltip" title="Activar / Inactivar">
                			<span class="fas fa-<?php if($row['estado']=='activo'){ echo 'ban'; }else{ echo 'check'; }?>" aria-hidden="true">
                		</button>
                	</td>
                	<td>
                		<!-- Botón "Reiniciar Contraseña" -->
						<button type="submit" class="btn btn-default" onclick="location.href = 'gestionUsuario.php?id=<?php echo $row['id_usuario']?>&op=4'">
							<span class="fas fa-redo-alt" aria-hidden="true" data-toggle="tooltip" title="Reiniciar Contraseña"></span>
						</button>
                	</td>
                	</tr>
                	<?php
                	} ?>

                	</table>
                	
                	<form name="agregarUsuario" method="POST" action="agregarUsuario.php">
                		<button type="submit" class="btn btn-default">Crear Usuario</button>
                	</form>
    			
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
