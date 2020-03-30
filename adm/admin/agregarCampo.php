<?php 
//Verificar que sea admin
session_start();
include '../../include/globals.php';
if(!in_array(1, $_SESSION['rol'])){
	header("location:".$root_path."adm/login.php");
}
?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <title>Crear campo</title>
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
    			
    				<form name="signup" method="post" action="gestionCampo.php?op=1">
                         
                         <h3>Crear nuevo campo:</h3>
                         <br>
                         
    					 <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input class="form-control" name="nombre" id="nombre" type="text" />
    					 </div>
                         <div class="form-group">
                            <label for="apellido">Valor por defecto</label> 
                            <input class="form-control" name="valor_default" id="valor_default" type="text" />
                         </div> 
    					 <div class="form-group">
                            <label for="tooltip">Tooltip</label>
                            <input class="form-control" name="tooltip" id="tooltip" type="text" />
    					 </div>
                         <div class="form-group">
                            <p><label>Filtrable</label></p>
                            <label class="radio-inline">
                                <input type="radio" name="filtrable" value="1" CHECKED>Sí
                            </label>
    				     	<label class="radio-inline">
                                <input type="radio" name="filtrable" value="0">No
                            </label>
    					</div>
                        <div class="form-group">
                            <p><label>Obligatorio</label></p>
                            <label class="radio-inline">
                                <input type="radio" name="obligatorio" value="1" CHECKED>Sí
                            </label>
    				     	<label class="radio-inline">
                                <input type="radio" name="obligatorio" value="0">No
                            </label>
    					</div>
                        <div class="form-group">
                            <p><label>Disponible</p>
    				     	<label class="radio-inline">
                                <input type="radio" name="estado" value="1" CHECKED>Sí
                            </label>
    				     	<label class="radio-inline">
                                <input type="radio" name="estado" value="0">No
                            </label>
    					</div>
    					<button type="submit" class="btn btn-primary">Crear campo</button>
                        <button type="button" class="btn btn-default" onclick="window.location='gestionCampo.php';">Cancelar</button>
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
