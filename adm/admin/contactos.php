<?php
    session_start();
    
    //Verificar que sea admin
    if(!in_array(1, $_SESSION['rol'])){
        header("location: http://".$root_path."adm/login.php");
    }

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';

    function getContactos() {
        $query = "SELECT id,
                         nombre,
                         email,
                         telefono,
                         comentarios
                  FROM contacto";

        $result = db_query($query);
        
        if(!$result){
            //que hacer en caso de fallo
            return array();
        }
        else{
            //que hacer en caso de exito
            return $result;
        }
    }
    
    function eliminarContacto($id) {
        $query = "DELETE FROM contacto
                  WHERE id = ".$id;
        
        $result = db_query($query);
        
        if(!$result){
            return false;
        }
        else{
            return true;
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        if(isset($_GET['op']) && $_GET['op']=="1") {
            
            //TODO sql injection
            $resultado = eliminarContacto($_GET['id']);
            
            if($resultado) {
                $alerta = 'eliminacionContacto';
            } else {
                $alerta = 'eliminacionContactoError';
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
    <title>Contactos</title>
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
    	function eliminar(id) {
    	    if (confirm("¿Está seguro que quiere eliminar la información?") == true) {
    	        location.href = "/adm/admin/contactos.php?id="+id+"&op=1";
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
        					if($alerta == 'eliminacionContacto') {
        			?>		
        						<div class="alert alert-success alert-dismissible" role="alert">
        						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        						  <strong>¡Éxito!</strong> Información eliminada exitosamente.
        						</div>				
        			<?php
        					} else if($alerta == 'eliminacionContactoError') {
        			?>		
        						<div class="alert alert-danger alert-dismissible" role="alert">
        						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        						  <strong>Error.</strong> Ocurrió un error al eliminarla información.
        						</div>																					
        			<?php
        					} 
        				}
        			?>		    		
    		
                	<br><br>
                	
                	<h3>Mensajes recibidos:</h3>
                	
                	<table class="table" border='1'>
                		<thead>
                		<tr> 
                			<th class="th">Nombre</th> 
                	  		<th class="th">Email</th> 
                	  		<th class="th">Teléfono</th> 
                	  		<th class="th">Comentarios</th>
                            <th class="th">Eliminar</th>
                	 	</tr>
                	 	</thead>
                	<?php
                	    $result = getContactos();
                		while($row = mysqli_fetch_array($result)){
                			echo "<tr class=\"tr\">";
                			echo "<td>".$row["nombre"]."</td>";
                			echo "<td>".$row["email"]."</td>";
                			echo "<td>".$row["telefono"]."</td>";
                			echo "<td>".$row["comentarios"]."</td>";
                	?>
            			<!-- Botón "Eliminar" -->
            			<td>
            			<button class="btn btn-default" onclick="eliminar(<?php echo $row['id']; ?>)" data-toggle="tooltip" title="Eliminar">
            				<span class="far fa-trash-alt" aria-hidden="true"></span>
            			</button>
            			</td>
                	<?php
                	}
                	  echo "</tr>";
                	echo "</table>";
                	?>
    			
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
