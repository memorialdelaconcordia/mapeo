<?php
//Verificar que sea admin
session_start();

include( '../../include/db.php');
if(!in_array(1, $_SESSION['rol'])){
	header("location:".$root_path."adm/login.php");
}
if(isset($_GET['op'])){
	//editar organizacion
	if($_GET['op']==1){
		if(isset($_POST['id_organizacion'])&&isset($_POST['nombreOrganizacion'])){
			$result=updateOrganizacion($_POST['id_organizacion'],$_POST['nombreOrganizacion'], $_POST['personaOrganizacion'], $_POST['telefonoOrganizacion'], $_POST['emailOrganizacion']);
			if(!$result){
				echo "<p>Error al editar la Organizacion</p>";
			}
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
	//crear organizacion
	else if($_GET['op']=='2'){
		if(isset($_POST['nombreOrganizacion'])){
			$result=crearOrganizacion($_POST['nombreOrganizacion'], $_POST['personaOrganizacion'], $_POST['telefonoOrganizacion'], $_POST['emailOrganizacion']);
			if(!$result){
				echo "<p>Error al agregar la Organizacion</p>";
			}
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
}
?>
<html>
<head>
	<title>Editar Organizaciones</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../../css/theme.css">
	<script src="../../js/jquery.js" type="text/javascript"></script> 
	<script src="../../js/bootstrap.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(function(){
		$('#modalEditar').on('shown.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id_organizacion =button.data('id');
			var nombreOrganizacion= button.data('nombre-organizacion');
			var responsable= button.data('responsable');
			var telefono=button.data('telefono');
			var correo=button.data('correo');
			$(".modal-body #nombreOrganizacion").val(nombreOrganizacion);
			$(".modal-body #id_organizacion").val(id_organizacion);
			$(".modal-body #personaOrganizacion").val(responsable);
			$(".modal-body #emailOrganizacion").val(correo);
			$(".modal-body #telefonoOrganizacion").val(telefono);
		});
	});
	</script>
</head>
<body>
	<div class="container">
		<?php
		include '../../include/header.php';
		?>
	</div>
	<div class="row clearfix">
		<?php
			include '../include/sidebar.php';
		?>
		<div class="col-md-6 column">
			<br>
			<div class="panel panel-default">
				<div class="panel-heading">Gestion de Organizaciones</div>
				<div class="panel-body">
					<table class="table" border='1'>
						<thead>
							<tr> 
		  						<th class="th">Valor</th> 
		  						<th class="th">Disponible</th>
		 					</tr>
		 				</thead>
						<?php
							$result=allOrganizacion();
							while($row=mysqli_fetch_array($result)){
								echo "<tr class=\"tr\">";
						 		echo "<td>".$row["nombre_organizacion"];
						 		echo "<td>";
						?>
	
	   					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $row['id_organizacion']?>" data-nombre-organizacion="<?php echo $row['nombre_organizacion']?>" data-responsable="<?php echo $row['responsable']?>" data-telefono="<?php echo $row['telefono']?>" data-correo="<?php echo $row['correo']?>">
  							<span class="glyphicon glyphicon-pencil" aria-hidden="true">
						</button>
						<?php
						}
						?>
						</td></tr>
					</table>
					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAgregar">
  						Agregar Organizacion
					</button>
				</div>
			</div>
			<br>
			<form class="form-group" name="cancel" method="post" action="gestionCampo.php?">
				<button type="submit" class="btn btn-default">Terminar</button>
			</form>
    	</div>
    </div>
</div>
<!-- Modal Agregar -->
<div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      	<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        	<h4 class="modal-title" id="myModalLabel">Agregar Organizacion</h4>
      	</div>
      	<div class="modal-body">
	       	<form class="form-group" name="agregar" method="post" action="editarOrganizacion.php?op=2">
				<br><label for="inputOrganizacion">Nombre de la organización</label><input class="form-control" name="nombreOrganizacion" id="nombreOrganizacion" type="text" />
                <br><label for="inputOrganizacion">Persona responsable de la organización</label><input class="form-control" name="personaOrganizacion" id="personaOrganizacion" type="text" />
                <br><label for="inputOrganizacion">Teléfono</label><input class="form-control" name="telefonoOrganizacion" id="telefonoOrganizacion" type="number" />
                <br><label for="inputOrganizacion">Correo electrónico</label><input class="form-control" name="emailOrganizacion" id="emailOrganizacion" type="email" />
	            <div class="modal-footer">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        		<button type="submit" class="btn btn-primary">Agregar</button>
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
	       	<form class="form-group" name="agregar" method="post" action="editarOrganizacion.php?op=1">
	       		<br><label for="exampleInputEmail1">Id</label><input class="form-control" name="id_organizacion" id="id_organizacion" type="text" readonly="readonly"/>
	       		<br><label for="inputOrganizacion">Nombre de la organización</label><input class="form-control" name="nombreOrganizacion" id="nombreOrganizacion" type="text" />
                <br><label for="inputOrganizacion">Persona responsable de la organización</label><input class="form-control" name="personaOrganizacion" id="personaOrganizacion" type="text" />
                <br><label for="inputOrganizacion">Teléfono</label><input class="form-control" name="telefonoOrganizacion" id="telefonoOrganizacion" type="number" />
                <br><label for="inputOrganizacion">Correo electrónico</label><input class="form-control" name="emailOrganizacion" id="emailOrganizacion" type="email" />
				<div class="modal-footer">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        		<button type="submit" class="btn btn-primary">Guardar</button>
	      		</div>
			</form>
      	</div>
    </div>
  </div>
</div>
</body>
</html>
