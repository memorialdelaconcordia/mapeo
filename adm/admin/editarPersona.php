<?php
//Verificar que sea admin
session_start();

include( '../../include/db.php');
if(!in_array(1, $_SESSION['rol'])){
	header("location:".$root_path."adm/login.php");
}
if(isset($_GET['op'])){
	//editar persona
	if($_GET['op']==1){
		if(isset($_POST['id_persona'])&&isset($_POST['nombrePersona'])){
			$result=updatePersona($_POST['id_persona'],$_POST['nombrePersona'], $_POST['menordeedad'], $_POST['genero'], $_POST['id_sector'], $_POST['id_profesion'], $_POST['id_pais'],$_POST['estado']);
			if(!$result){
				echo "<p>Error al editar la persona</p>";
			}
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
	//eliminar una persona
	else if($_GET['op']=='2'){
		if(isset($_GET['idp'])){
			$result=bajaPersona($_GET['idp']);
			if(!$result){
				echo "<p>Error al eliminar la persona</p>";
			}
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
	//agregar un valor del campo
	else if($_GET['op']=='3'){
		if(isset($_POST['nombrePersona'])){
			$result=crearPersona($_POST['nombrePersona'], $_POST['menordeedad'], $_POST['genero'], $_POST['id_sector'], $_POST['id_profesion'], $_POST['id_pais']);
			if(!$result){
				echo "<p>Error al agregar el valor</p>";
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
	<title>Editar Personas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../../css/theme.css">
	<script src="../../js/jquery.js" type="text/javascript"></script> 
	<script src="../../js/bootstrap.js" type="text/javascript"></script>
	<script type="text/javascript">
	function eliminar(id_persona,nombre) {
	    var x;
	    if (confirm("Esta seguro que quiere eliminar el valor \n"+nombre) == true) {
	        document.location.href = window.location.href.split('.php')[0]+'.php?op=2&idp='+id_persona;
	    } else {
			
	    }
	}
	$(function(){
		$('#modalEditar').on('shown.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id_persona =button.data('id');
			var nombre= button.data('nombre');
			var estado= button.data('estado');
			var ninez= button.data('ninez');
			var id_sector=button.data('id-sector');
			var id_profesion=button.data('id-profesion');
			var id_pais=button.data('id-pais');
			var id_genero=button.data('id-genero');
			$(".modal-body #nombrePersona").val(nombre);
			$(".modal-body #id_persona").val(id_persona);
			if(estado=="1"){
				$(".modal-body #val1").prop('checked',true);	
			}
			else{
				$(".modal-body #val2").prop('checked',true);
			}
			if(ninez=="1"){
				$(".modal-body #menordeedad1").prop('checked',true);
			}
			else{
				$(".modal-body #menordeedad2").prop('checked',true);
			}
			$(".modal-body #genero option[value="+id_genero+"]").prop('selected',true);
			$(".modal-body #id_sector option[value="+id_sector+"]").prop('selected',true);
			$(".modal-body #id_profesion option[value="+id_profesion+"]").prop('selected',true);
			$(".modal-body #id_pais option[value="+id_pais+"]").prop('selected',true);
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
				<div class="panel-heading">Gestion de Personas</div>
				<div class="panel-body">
					<table class="table" border='1'>
						<thead>
							<tr> 
		  						<th class="th">Valor</th> 
		  						<th class="th">Disponible</th>
		 					</tr>
		 				</thead>
						<?php
							$result=allPersonasCompleto();
							while($row=mysqli_fetch_array($result)){
								echo "<tr class=\"tr\">";
						 		echo "<td>".$row["nombre"];
						 		if($row['estado']==1){
						 			echo "<td>si";
						 		}
						 		else{
						 			echo "<td>no";
						 		}
						 		echo "<td>";
						?>
	
	   					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $row['id_persona']?>" data-nombre="<?php echo $row['nombre']?>" data-estado="<?php echo $row['estado']?>" data-ninez="<?php echo $row['ninez']?>" data-id-genero="<?php echo $row['id_genero']?>" data-id-sector="<?php echo $row['id_sector']?>" data-id-profesion="<?php echo $row['id_profesion']?>" data-id-genero="<?php echo $row['id_pais']?>">
  							<span class="glyphicon glyphicon-pencil" aria-hidden="true">
						</button>
		
						<td>
	   					<button class="btn btn-default" onclick="eliminar(<?php echo $row['id_persona'].',\''.$row['nombre'].'\'' ?>)"><span class="glyphicon glyphicon-<?php if($row['estado']=='1'){ echo 'remove'; }else{ echo 'ok'; }?>" aria-hidden="true"></button>
						<?php
						}
						?>
						</td></tr>
					</table>
					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAgregar">
  						Agregar Persona
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
        	<h4 class="modal-title" id="myModalLabel">Agregar Persona</h4>
      	</div>
      	<div class="modal-body">
	       	<form class="form-group" name="agregar" method="post" action="editarPersona.php?op=3">
				<br><label for="exampleInputEmail1">Nombre</label><input class="form-control" name="nombrePersona" id="nombrePersona" type="text" />
	            <br><label for="exampleInputEmail1">Menor de edad</label>
	            <div class="radio">
	                <label><input type="radio" name="menordeedad" id="menordeedad" value="1">si</label>
	                <label><input type="radio" name="menordeedad" id="menordeedad" value="0">no</label>
	            </div>
	            <br><label for="exampleInputEmail1">Género</label>
	            <select class="form-control" name="genero" id="genero">
	            <?php
	                $result=allGenero();
	                while($row=mysqli_fetch_array($result)){
	                    echo '<option value='.$row["id_genero"].">".$row["genero"]."</option>";
	                }
	            ?>
	            </select>
				
	            <br><label for="exampleInputEmail1">Sector</label>
	            <select class="form-control" name="id_sector" id="id_sector">
	            <?php
	                $result=allSector();
	                while($row=mysqli_fetch_array($result)){
	            	    echo '<option value='.$row["id_sector"].">".$row["sector"]."</option>";
	                }
	            ?>
	            </select>

	            <br><label for="exampleInputEmail1">Profesion</label>
	            <select class="form-control" name="id_profesion" id="id_profesion">
	            <?php
	                $result=allProfesion();
	                while($row=mysqli_fetch_array($result)){
	                    echo '<option value='.$row["id_profesion"].">".$row["profesion"]."</option>";
	                }
	            ?>
	            </select>

	            <br><label for="exampleInputEmail1">País de nacionalidad</label>
	            <select class="form-control" name="id_pais" id="id_pais">
	            <?php
	                $result=allPais();
	                while($row=mysqli_fetch_array($result)){
	            	    echo '<option value='.$row["id_pais"].">".$row["pais"]."</option>";
	                }
	            ?>
	            </select>
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
        	<h4 class="modal-title" id="myModalLabel">Editar Persona</h4>
      	</div>
      	<div class="modal-body">
	       	<form class="form-group" name="agregar" method="post" action="editarPersona.php?op=1">
	       		<br><label for="exampleInputEmail1">Id</label><input class="form-control" name="id_persona" id="id_persona" type="text" readonly="readonly"/>
	       		<br><label for="exampleInputEmail1">Nombre</label><input class="form-control" name="nombrePersona" id="nombrePersona" type="text" />
	            <br><label for="exampleInputEmail1">Menor de edad</label>
	            <div class="radio">
	                <label><input type="radio" name="menordeedad" id="menordeedad1" value="1">si</label>
	                <label><input type="radio" name="menordeedad" id="menordeedad2" value="0">no</label>
	            </div>
	            <br><label for="exampleInputEmail1">Género</label>
	            <select class="form-control" name="genero" id="genero">
	            <?php
	                $result=allGenero();
	                while($row=mysqli_fetch_array($result)){
	                    echo '<option value='.$row["id_genero"].">".$row["genero"]."</option>";
	                }
	            ?>
	            </select>
				
	            <br><label for="exampleInputEmail1">Sector</label>
	            <select class="form-control" name="id_sector" id="id_sector">
	            <?php
	                $result=allSector();
	                while($row=mysqli_fetch_array($result)){
	            	    echo '<option value='.$row["id_sector"].">".$row["sector"]."</option>";
	                }
	            ?>
	            </select>

	            <br><label for="exampleInputEmail1">Profesion</label>
	            <select class="form-control" name="id_profesion" id="id_profesion">
	            <?php
	                $result=allProfesion();
	                while($row=mysqli_fetch_array($result)){
	                    echo '<option value='.$row["id_profesion"].">".$row["profesion"]."</option>";
	                }
	            ?>
	            </select>

	            <br><label for="exampleInputEmail1">País de nacionalidad</label>
	            <select class="form-control" name="id_pais" id="id_pais">
	            <?php
	                $result=allPais();
	                while($row=mysqli_fetch_array($result)){
	            	    echo '<option value='.$row["id_pais"].">".$row["pais"]."</option>";
	                }
	            ?>
	            </select>
	            <br><label for="exampleInputEmail1">Disponible</label>
	            <div class="radio" >
				     	<label><input type="radio" id="val1" name="estado" value="1">si</label>
				     	<label><input type="radio" id="val2" name="estado" value="0">no</label>
					</div>
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
