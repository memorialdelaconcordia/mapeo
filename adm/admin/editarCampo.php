<?php
 //Verificar que sea admin
session_start();
include( '../../include/db.php');
if(!in_array(1, $_SESSION['rol'])){
	header("location:".$root_path."adm/login.php");
}

$info=null;
if(!isset($_GET['id'])){
	header("Location: gestionCampo.php");
}
if(isset($_GET['op'])){
	//editar informacion del campo
	if($_GET['op']=='1'){
		echo "<p>Nada para evaluar</p>";
	}
	//Cambio de estado (activar/inactivar):
	else if($_GET['op']=='2'){
		if(isset($_GET['idv'])){
			$result=null;
			//Cambiar de estado a valor de la tabla "profesion":
			if(trim($_GET['id'])=="profesion"){
				$result=cambiarEstadoProfesion($_GET['idv']);
				if($result){
					$alerta = 'cambioEstadoCorrecto';
				}
				else{
					$alerta = 'cambioEstadoError';
				}				
			}
			else if(trim($_GET['id'])=="sector"){
				$result=bajaSector($_GET['idv']);
			}
			else if(trim($_GET['id'])=="evento"){
				$result=bajaTipoEvento($_GET['idv']);
			}
			else if(trim($_GET['id'])=="tipo_monumento"){
				$result=bajaTipoMonumento($_GET['idv']);
			}
			else{
				$result=false;
			}
			if(!$result){
				echo "<p>Error al eliminar el valor</p>";
			}
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
	//Agregar un nuevo valor al campo:
	else if($_GET['op']=='3'){
		if(isset($_POST['estado'])&&isset($_POST['valor'])){
			$result=null;
			if(trim($_GET['id'])=="profesion"){
				$result=agregarProfesion($_POST['valor'],$_POST['estado']);
				if($result){
					$alerta = 'agregarValorCorrecto';
				}
				else{
					$alerta = 'agregarValorError';
				}					
			}
			else if(trim($_GET['id'])=="sector"){
				$result=agregarSector($_POST['valor'],$_POST['estado']);
			}
			else if(trim($_GET['id'])=="evento"){
				$result=agregarTipoEvento($_POST['valor'],$_POST['estado']);
			}
			else if(trim($_GET['id'])=="tipo_monumento"&&isset($_POST['descripcion'])){
				$result=agregarTipoMonumento($_POST['valor'],$_POST['estado'],$_POST['descripcion']);
			}
			else{
				$result=null;
			}
			if(!$result){
				echo "<p>Error al agregar el valor</p>";
			}
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
	//editar un valor del campo
	else if($_GET['op']==4){
		if(isset($_POST['estadov'])&&isset($_POST['valor'])){
			$result=editarValorCampo($_POST['id_valor'],$_POST['valor'],$_POST['estadov']);
			if(trim($_GET['id'])=="profesion"){
				$result=editarProfesion($_POST['id_valor'],$_POST['valor'],$_POST['estadov']);
			}
			else if(trim($_GET['id'])=="sector"){
				$result=editarSector($_POST['id_valor'],$_POST['valor'],$_POST['estadov']);
			}
			else if(trim($_GET['id'])=="evento"){
				$result=editarTipoEvento($_POST['id_valor'],$_POST['valor'],$_POST['estadov']);
			}
			else if(trim($_GET['id'])=="tipo_monumento"&&isset($_POST['descripcionv'])){
				$result=editarTipoMonumento($_POST['id_valor'],$_POST['valor'],$_POST['estadov'],$_POST['descripcionv']);
			}
			else{
				$result=null;
			}
			if(!$result){
				echo "<p>Error al editar el valor</p>";
			}
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
}
if(isset($_GET['id'])){
	$result=null;
	if(trim($_GET['id'])=="profesion"){
		$result=allProfesionCompleto();
	}
	else if(trim($_GET['id'])=="sector"){
		$result=allSectorCompleto();
	}
	else if(trim($_GET['id'])=="evento"){
		$result=allTipoEventoCompleto();
	}
	else if(trim($_GET['id'])=="tipo_monumento"){
		$result=allTiposMonumentoCompleto();
	}
	else{
		$result=false;
	}
	if(!$result){
		header("Location: gestionCampo.php");
	}
	else{
		$info=$result;
	}
}
else{
	header("Location: gestionCampo.php");
}
$tabla=trim($_GET['id']);
$idname="";
if($tabla=="profesion"){
	$idname="id_profesion";
}
else if($tabla=="sector"){
	$idname="id_sector";
}
else if($tabla=="evento"){
	$idname="id_tipo_evento";
}
else if($tabla=="tipo_monumento"){
	$idname="id_tipo_monumento";
}
?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <title>Editar campo</title>
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
		function cambiarEstado(id_valor,valor,tabla,estado_actual) {
			if(estado_actual == '0') {
				if (confirm("¿Está seguro que quiere activar el valor \n"+valor+"?") == true) {
					/* 	id = nombre de la tabla de donde se realizará la operación.
						op = operación a realizar (2 -> activar/desactivar).
						idv = valor sobre el cual se realizará la operación.
					*/
					document.location.href = window.location.href.split('?')[0]+"?id="+tabla+'&op=2&idv='+id_valor;
				}
			} else {
				if (confirm("¿Está seguro que quiere inactivar el valor \n"+valor+"?") == true) {
					document.location.href = window.location.href.split('?')[0]+"?id="+tabla+'&op=2&idv='+id_valor;
				}				
			}
		}
		$(function(){
		$('#modalEditar').on('shown.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id_valor =button.data('id');
			var valor= button.data('valor');
			var estado= button.data('estado');
			var descripcion="";
			if(button.attr('data-desc')!== undefined){
				descripcion=button.data('desc');
				$(".modal-body #descripcionv").val(descripcion);
			}
			$(".modal-body #valor").val(valor);
			$(".modal-body #id_valor").val(id_valor);
			if(estado=="1"){
				$(".modal-body #val1").prop('checked',true);	
			}
			else{
				$(".modal-body #val2").prop('checked',true);
			}
			});
		});
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
    							  <strong>¡Éxito!</strong> Estado modificado correctamente.
    							</div>				
    				<?php
    						} else if($alerta == 'cambioEstadoError') {
    				?>		
    							<div class="alert alert-danger alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> Ocurrió un error al modificar el estado.
    							</div>											
    				<?php
    						} else if($alerta == 'agregarValorCorrecto') {
    				?>		
    							<div class="alert alert-success alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>¡Éxito!</strong> Valor agregado correctamente.
    							</div>											
    				<?php
    						} else if($alerta == 'agregarValorError') {
    				?>		
    							<div class="alert alert-danger alert-dismissible" role="alert">
    							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							  <strong>Error.</strong> Ocurrió un error al agregar el nuevo valor.
    							</div>											
    				<?php
    						}
    					}
    				?>			
    			
            		<br><br>
            		<table class="table" border='1'>
            			<thead>
            			<tr> 
            		  		<th class="th">Valor</th> 
            		  		<th class="th">Disponible</th>
            		 	</tr>
            		 	</thead>
            			<?php
            				$result=$info;
            				while($row=mysqli_fetch_array($result)){
            					echo "<tr class=\"tr\">";
            			 		echo "<td>".$row[$tabla];
            			 		if($row['estado']==1){
            			 			echo "<td>si";
            			 		}
            			 		else{
            			 			echo "<td>no";
            			 		}
            			 		echo "<td>";
            			?>
            	
            			<!-- Botón "Editar" -->
            			<span data-toggle="tooltip" title="Editar" >
            				<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $row[$idname]?>" data-valor="<?php echo $row[$tabla]?>" data-estado="<?php echo $row['estado']?>" <?php if($tabla=="tipo_monumento"){ echo "data-desc=\"".$row['descripcion']."\"";} ?>>
            					<span class="glyphicon glyphicon-pencil" aria-hidden="true">
            				</button>
            			</span>
            			<td>
            	   
            			<!-- Botón "Activar/Inactivar" -->
            			<button class="btn btn-default" onclick="cambiarEstado(<?php echo $row[$idname].',\''.$row[$tabla].'\',\''.$tabla.'\',\''.$row['estado'].'\''?>)" data-toggle="tooltip" title="Activar / Inactivar">
            				<span class="glyphicon glyphicon-<?php if($row['estado']=='1'){ echo 'remove'; }else{ echo 'ok'; }?>" aria-hidden="true">
            			</button>
                	<?php
                	}
                	  echo "</td></tr>";
                	echo "</table>";
                	?>
                	<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAgregar">
                  		Agregar Valor
                	</button>
                	<br>
                	<br>
                	<form class="form-group" name="cancel" method="post" action="gestionCampo.php">
                		<button type="submit" class="btn btn-default">Regresar</button>
                	</form>
    			
    			</div>
	
            </div>
            
        </div>
        
    </div>

    <!-- Modal Agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Agregar Valor</h4>
          </div>
          <div class="modal-body">
           <form class="form-group" name="agregar" method="post" action="editarCampo.php?op=3&<?php echo 'id='.$_GET['id']?>">
    			<br><label for="valor">Valor</label><input class="form-control" name="valor" id="valor" type="text" required/>
    			<?php
    				if($tabla=="tipo_monumento"){
    					echo "<br><label for='descripcion'>Descripcion</label><input class='form-control' name='descripcion' id='descripcion' type='text' />";
    				}
    			?>
    			<br><label>Disponible</label>
    			<div class="radio">
    				<label><input type="radio" name="estado" value="1" CHECKED>Sí</label>
    				<label><input type="radio" name="estado" value="0">No</label>
    			</div>
    			<div class="modal-footer">
            		<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            		<button type="submit" class="btn btn-primary">Agregar Valor</button>
          		</div>
    		</form>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Editar Valor</h4>
          </div>
          <div class="modal-body">
           <form class="form-group" name="agregar" method="post" action="editarCampo.php?op=4&<?php echo 'id='.$_GET['id']?>">
           				 <br><label for="exampleInputEmail1">Id</label><input class="form-control" name="id_valor" id="id_valor" type="text" readonly="readonly"/>
    					 <br><label for="exampleInputEmail1">Valor</label><input class="form-control" name="valor" id="valor" type="text" />
    					 <?php
    						if($tabla=="tipo_monumento"){
    							echo "<br><label for='exampleInputEmail1'>Descripcion</label><input class='form-control' name='descripcionv' id='descripcionv' type='text' />";
    						}
    					 ?>
    					 <br><label for="exampleInputEmail1">Disponible</label>
    					<div class="radio" >
    				     	<label><input type="radio" id="val1" name="estadov" value="1">si</label>
    				     	<label><input type="radio" id="val2" name="estadov" value="0">no</label>
    					</div>
    			<div class="modal-footer">
            		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            		<button type="submit" class="btn btn-primary">Guardar</button>
          		</div>
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
