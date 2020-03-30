<?php 
session_start();
include( '../../include/db.php');

//Verificar que sea admin
if(!in_array(1, $_SESSION['rol'])){
	header("location: http://".$root_path."adm/login.php");
}


//revisar que tipo de operacion se ha realizado eliminar, agregar o editar
if(isset($_GET['op'])){
	//revisar agregar
	if($_GET['op']==1){
		if(isset($_POST['nombre'])&&isset($_POST['contenido'])&&isset($_POST['estado'])){
			$result=agregarPagina($_POST['nombre'],$_POST['contenido'],$_POST['estado']);
			if($result){
				echo "<p>Pagina ".$_POST['nombre']." agregado correctamente</p>";
                }
			else{
				echo "<p>Error al agregar la pagina ".$_POST['nombre']."</p>";
			}	
		}
		else{
			echo "<p>Nada para agregar</p>";
		}
	}
	//revisar edicion
	else if ($_GET['op']=='2'){
		if(isset($_POST['id_static_page'])&&isset($_POST['nombre'])&&isset($_POST['contenido'])&&isset($_POST['estado'])){
			$result=editarPagina($_POST['id_static_page'],$_POST['nombre'],$_POST['contenido'],$_POST['estado']);
			if($result){
				echo "<p>Pagina con id ".$_POST['id_static_page']." editada correctamente</p>";
			}
			else{
				echo "<p>Error al editar pagina con id ".$_POST['id_static_page']."</p>";
			}
		}
		else{
			echo "<p>Nada para editar</p>";
		}
	}
	//revisar eliminacion
	else{
		if(isset($_GET['id'])){
			$result=bajaPagina(trim($_GET['id']));
			if($result){
				echo "<p>Pagina con id ".$_GET['id']." eliminada correctamente</p>";
			}
			else{
				echo "<p>Error al eliminar la pagina con id ".$_GET['id']."</p>";
			}
		}
	}
}
?>
<html>
<head>
	<title>Gestion Paginas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../../css/bootstrap.css">
	<link rel="stylesheet" href="../../css/dashboard.css">	
	<script src="../../js/jquery.js" type="text/javascript"></script> 
	<script src="../../js/bootstrap.js" type="text/javascript"></script>	
	<script type="text/javascript">
	function eliminar(id_pagina,nombre_pagina) {
	    var x;
	    if (confirm("Esta seguro que quiere eliminar la pagina \n"+nombre_pagina) == true) {
	        document.location.href = window.location.href.split('?')[0]+"?id="+id_pagina+'&op=3';
	    } else {
	    }
	}
	</script>
</head>
<body>

    <?php
        include '../include/header.php';
    ?>
	
	<div class="container-fluid">

		<div class="row">
		
			<!-- Sidebar: -->
			<?php
				include '../include/sidebar.php';
			?>
		
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            
            
	<br><br>
	<table class="table" border='1'>
		<thead>
		<tr> 
			<th class="th">ID</th> 
	  		<th class="th">nombre</th> 
	  		<th class="th">estado</th>
	 	</tr>
	 	</thead>
	<?php
		$result=allPagina();
		while($row=mysqli_fetch_array($result)){
			echo "<tr class=\"tr\">";
	 		echo "<td>".$row["id_static_page"];
	 		echo "<td>".$row["nombre"];
	 		echo "<td>".$row["estado"];
	 		echo "<td>";
	?>
	
	   <form name="form" method="POST" action="editarPagina.php<?php echo '?id='.$row['id_static_page']?>">
	     <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></button>
	   </form>
	<td>
	   <button class="btn btn-default" onclick="eliminar(<?php echo $row['id_static_page'].',\''.$row['nombre'].'\'' ?>)"><span class="glyphicon glyphicon-<?php if($row['estado']=='activo'){ echo 'remove'; }else{ echo 'ok'; }?>" aria-hidden="true"></button>
	<?php
	}
	  echo "</td></tr>";
	echo "</table>";
	?>
	<form name="agregarPagina" method="POST" action="agregarPagina.php">
		<button type="submit" class="btn btn-primary">Crear Pagina</button>
	</form>
</div>
</div>
</div>
</body>
</html>