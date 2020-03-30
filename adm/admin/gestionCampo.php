<?php 
session_start();
//Verificar que sea admin

include( '../../include/db.php');
if(!in_array(1, $_SESSION['rol'])){
	header("location:".$root_path."adm/login.php");
}
if(isset($_GET['op'])){
	//revisar agregar
	if($_GET['op']==1){
		if(isset($_POST['nombre'])&&isset($_POST['valor_default'])&&isset($_POST['filtrable'])&&isset($_POST['obligatorio'])){
			$result=agregarCampo($_POST['nombre'],$_POST['filtrable'],$_POST['obligatorio'],$_POST['valor_default'],$_POST['estado'],$_POST['tooltip']);
			if($result){
				//echo "<p>Campo".$_POST['nombre']." agregado correctamente</p>";
                $alerta = 'creacionCampoCorrecta';
			}
			else{
				//echo "<p>Error al agregar el campo";
                $alerta = 'creacionCampoError';
			}
		}
		else{
			echo "<p>Nada para agregar</p>";
		}
	}
	//revisar eliminacion
	else{
		if(isset($_GET['id'])){
			$result=bajaCampo(trim($_GET['id']));
			if($result){
				echo "<p>Campo con id ".$_GET['id']." eliminado correctamente</p>";
			}
			else{
				echo "<p>Error al eliminar el campo con id ".$_GET['id']."</p>";
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
    <title>Gestionar campos</title>
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
    	function eliminar(id_usuario,nombre_usuario) {
    	    var x;
    	    if (confirm("Esta seguro que quiere eliminar el campo \n"+nombre_usuario) == true) {
    	        x = "You pressed OK! "+id_usuario;
    	        document.location.href = window.location.href.split('?')[0]+"?id="+id_usuario+'&op=3';
    	    } else {
    	        x = "You pressed Cancel!";
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
    						if($alerta == 'creacionCampoCorrecta') {
    				?>		
    							<div class="alert alert-success alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>¡Éxito!</strong> Campo creado exitosamente.
    							</div>				
    				<?php
    						} else if($alerta == 'creacionCampoError') {
    				?>		
    							<div class="alert alert-danger alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> Ocurrió un error al crear el nuevo campo.
    							</div>																					
    				<?php
    						} 
    					}
    				?>				
    			
    				<h3>Gestión de campos:</h3>
    			
                	<table id="tablaValores" class="table" border='1'>
                		<thead>
                		<tr> 
                			<th class="th">Nombre</th> 
                	  		<th class="th">Cantidad</th> 
                	  		<th class="th">Disponible</th> 
                	  		<th class="th">Editar</th> 
                	  		<th class="th">Cambiar Estado</th> 
                	 	</tr>
                	 	</thead>
                	 	<tr class="tr">
                	 		<td>profesion</td>
                	 		<td><?php echo mysqli_num_rows(allProfesionCompleto());?></td>
                	 		<td>siempre</td>
                	 		<td>
                	   			<button type="button" class="btn btn-default" onclick="location.href = 'editarCampo.php?id=profesion'"><span class="far fa-edit" aria-hidden="true"></span></button>
                	   		</td>	
                	   		<td></td>
                	   	</tr>	
                	   	<tr class="tr">
                	 		<td>sector</td>
                	 		<td><?php echo mysqli_num_rows(allSectorCompleto());?></td>
                	 		<td>siempre</td>
                	 		<td>
                	   			<button type="button" class="btn btn-default" onclick="location.href = 'editarCampo.php?id=sector'"><span class="far fa-edit" aria-hidden="true"></span></button>
                	   		</td>
                	   		<td></td>
                	   	</tr>	
                		<tr class="tr">
                	 		<td>tipo de evento</td>
                	 		<td><?php echo mysqli_num_rows(allTipoEventoCompleto());?></td>
                	 		<td>siempre</td>
                	 		<td>
                	   			<button type="button" class="btn btn-default" onclick="location.href = 'editarCampo.php?id=evento'"><span class="far fa-edit" aria-hidden="true"></span></button>
                	   		</td>	
                	   		<td></td>
                	   	</tr>		
                		<tr class="tr">
                	 		<td>tipo de monumento</td>
                	 		<td><?php echo mysqli_num_rows(allTiposMonumentoCompleto());?></td>
                	 		<td>siempre</td>
                	 		<td>
                	   			<button type="button" class="btn btn-default" onclick="location.href = 'editarCampo.php?id=tipo_monumento'"><span class="far fa-edit" aria-hidden="true"></span></button>
                	   		</td>	
                	   		<td></td>
                	   	</tr>		
                	   	<tr class="tr">
                	 		<td>persona</td>
                	 		<td><?php echo mysqli_num_rows(allPersonasCompleto());?></td>
                	 		<td>siempre</td>
                	 		<td>
                	   			<button type="button" class="btn btn-default" onclick="location.href = 'editarPersona.php'"><span class="far fa-edit" aria-hidden="true"></span></button>
                	   		</td>	
                	   		<td></td>
                	   	</tr>		
                	   	<tr class="tr">
                	 		<td>organizacion</td>
                	 		<td><?php echo mysqli_num_rows(allOrganizacionCompleto());?></td>
                	 		<td>siempre</td>
                	 		<td>
								<button type="button" class="btn btn-default" onclick="location.href = 'editarOrganizacion.php'"><span class="far fa-edit" aria-hidden="true"></span></button>                	   			
                	   		</td>	
                	   		<td></td>
                	   	</tr>		
                	<?php
                		$result=allCampo();
                		while ($row=mysqli_fetch_array($result)) {  
                			echo "<tr class=\"tr\">";
                	 		echo "<td>".$row["nombre"]."</td>";
                	 		$resultVC=allValorCampo($row['id_campo_adicional']);
                	 		echo "<td>".mysqli_num_rows($resultVC)."</td>";
                	 		if($row['estado']=="1"){
                	 			echo "<td>si</td>";
                	 		}
                	 		else{
                	 			echo "<td>no</td>";
                	 		}
                	?>
                	   <td>
                    	   <button type="button" class="btn btn-default" onclick="location.href = 'editarCampoAdicional.php<?php echo '?id='.$row['id_campo_adicional']?>';"><span class="far fa-edit" aria-hidden="true"></span></button>
                	   </td>
                	   <td>
                	   	   <button class="btn btn-default" onclick="eliminar(<?php echo $row['id_campo_adicional'].',\''.$row['nombre'].'\'' ?>)"><span class="fas fa-<?php if($row['estado']=='1'){ echo 'ban'; }else{ echo 'check'; }?>" aria-hidden="true"></span></button>
                	<?php
                	       echo "</td></tr>";
                	   }
                	   echo "</table>";
                	?>
                	<form name="agregarCampo" method="POST" action="agregarCampo.php">
                		<button type="submit" class="btn btn-primary">Crear Campo</button>
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
