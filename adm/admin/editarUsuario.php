<?php
session_start();
//Verificar que sea admin

include( '../../include/db.php');
if(!in_array(1, $_SESSION['rol'])){
	header("location:".$root_path."adm/login.php");
}
$info=null;
$rol1=null;
$rol2=null;
$rol3=null;
$rol4=null;
if(isset($_GET['id'])){
	$result=getUsuario(trim($_GET['id']));
	if(!$result){
		header("Location: gestionUsuario.php");
	}
	else{
		$info=mysqli_fetch_array($result);
		$tempres=permisoUsuario($info['id_usuario']);
		while($row=mysqli_fetch_array($tempres)){
			if($row['id_rol']==1){
				$rol1=true;
			}
			else if($row['id_rol']==2){
				$rol2=true;
			}
			else if($row['id_rol']==3){
				$rol3=true;
			}
			else if($row['id_rol']==4){
				$rol4=true;
			}
		}
	}
}
else{
	header("Location: gestionUsuario.php");
}

?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <title>Editar usuario</title>
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
    		var pass=document.forms["signup"]["pwd"].value;
    		var cpass=document.forms["signup"]["pwdcnfrm"].value;
    		
    		
    		var hola='';
    		var radios = document.getElementsByName('statusradio');
            for (var i = 0, length = radios.length; i < length; i++) {
                if (radios[i].checked) {
                    // do whatever you want with the checked radio
                    hola=radios[i].value;
    
                    // only one radio can be logically checked, don't check the rest
                    break;
                }
            }
            //Revisar que nombre no este vacio
            if(hola==''){
    			mensaje+='Es necesario elegir un estado\n';
    	    }
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
    		//Revisar que haya escrito un correo
    		//Revisar que las dos contrasenas sean iguales
    		
    		if(pass.localeCompare(cpass)){
    			mensaje+='Los campos de contrasenas no coinciden';
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

        <?php include '../include/sidebar.php'; ?>  
        
        <div class="main-panel">
        
			<?php include '../include/header.php'; ?>  

			<div class="panel-header panel-header-lg">
            </div>

        	<div class="container-fluid">	
        		
			    <!-- Sección principal: -->
    			<div class="col-sm-12" style="padding-left: 30px; padding-top: 20px;">      
    			
				<form name="signup" method="post" onsubmit="return validateForm()" action="gestionUsuario.php?op=2">
                    <div class="form-group">
                        <label for="id_usuario">Id</label>
                        <input class="form-control" name="id_usuario" id="id_usuario" type="text" readonly="readonly" value="<?php echo $info['id_usuario'] ?>"/>
                    </div>	
					<div class="form-group">    
                        <label for="nombre">Nombre</label>
                        <input class="form-control" name="nombre" id="nombre" type="text" value="<?php echo $info['nombre'] ?>"/>
                    </div>	
					<div class="form-group">        
                        <label for="apellido">Apellido</label>
                        <input class="form-control" name="apellido" id="apellido" type="text" value="<?php echo $info['apellido'] ?>"/>
                    </div>	
					<div class="form-group">    
                        <label for="email">Correo electrónico</label>
                        <input class="form-control" name="correo" id="email" type="email" readonly="readonly" value="<?php echo $info['correo'] ?>"/>
                    </div>	
					<div class="form-group">    
                        <label for="username">Nombre de usuario</label>
                        <input class="form-control" name="nombre_usuario" id="username" type="text" value="<?php echo $info['nombre_usuario'] ?>" readonly/>
                    </div>	
					<div class="form-group">    
                        <label for="pwd">Nueva contraseña</label>
                        <input class="form-control" name="pwd" id="pwd" type="password" value=""/>
                    </div>	
					<div class="form-group">    
                        <label for="cnfrmpwd">Repetir nueva contraseña</label>
                        <input class="form-control" name="cnfrmpwd" id="cnfrmpwd" type="password" value=""/>
                    </div>	
					<div class="form-group">    
                        <p><label>Tipo de usuario</label></p>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="check_list[]" value="1" <?php if($rol1){echo "CHECKED";}?>>Administrador
                        </label>
				     	<label class="checkbox-inline">
                            <input type="checkbox" name="check_list[]" value="2" <?php if($rol2){echo "CHECKED";}?>>Editor
                        </label>
				     	<label class="checkbox-inline">
                            <input type="checkbox" name="check_list[]" value="3" <?php if($rol3){echo "CHECKED";}?>>Redactor
                        </label>
					</div>
                    <div class="form-group">    
                        <p><label>Estado del usuario</label></p>
				     	<label class="radio-inline">
                            <input type="radio" name="statusradio" value="activo" <?php if($info['estado']=="activo"){echo "CHECKED";}?>>Activo
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="statusradio" value="inactivo" <?php if($info['estado']=="inactivo"){echo "CHECKED";}?>>Inactivo
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="statusradio" value="espera" <?php if($info['estado']=="espera"){echo "CHECKED";}?>>En espera
                        </label>
					</div>
					<button type="submit" class="btn btn-primary">Guardar</button>
					<button type="button" class="btn btn-default" onclick="window.location='gestionUsuario.php';">Cancelar</button>
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
