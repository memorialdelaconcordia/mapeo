<?php
//Verificar que sea admin
session_start();

include( '../../include/db.php');
if(!in_array(1, $_SESSION['rol'])){
	header("location:".$root_path."adm/login.php");
}
$info=null;
if(isset($_GET['op'])){
	//editar informacion del campo
	if($_GET['op']=='1'){
		if(isset($_POST['nombre'])&&isset($_POST['tooltip'])&&isset($_POST['filtrable'])&&isset($_POST['obligatorio'])){
			$result=editarCampo($_GET['id'],$_POST['nombre'],$_POST['tooltip'],$_POST['filtrable'],$_POST['obligatorio'],$_POST['estado']);
			if(!$result){
				//echo "<p>Error al editar el campo</p>";
                $alerta = 'editarCampoError';
			} else {
                $alerta = 'editarCampoExito';
            }
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
	//eliminar un valor del campo
	else if($_GET['op']=='2'){
		if(isset($_GET['idv'])){
			$result=bajaValorCampo($_GET['idv']);
			if(!$result){
				echo "<p>Error al eliminar el valor</p>";
			}
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
	//agregar un valor del campo
	else if($_GET['op']=='3'){
		if(isset($_POST['estado'])&&isset($_POST['valor'])){
			$result=agregarValorCampo($_GET['id'],$_POST['valor'],$_POST['estado']);
			if(!$result){
				echo "<p>Error al agregar el valor</p>";
			}
		}
		else{
			echo "<p>Nada para evaluar</p>";
		}
	}
	else if($_GET['op']==4){
		if(isset($_POST['estadov'])&&isset($_POST['valor'])){
			$result=editarValorCampo($_POST['id_valor'],$_POST['valor'],$_POST['estadov']);
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
	$result=getCampo(trim($_GET['id']));
	if(!$result){
		header("Location: gestionCampo.php");
	}
	else{
		$info=mysqli_fetch_array($result);
	}
}
else{
	header("Location: gestionCampo.php");
}
?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <title>Editar Campo Adicional</title>
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
    	function eliminar(id_valor,valor,id_campo) {
    	    var x;
    	    if (confirm("Esta seguro que quiere eliminar el valor \n"+valor) == true) {
    	        document.location.href = window.location.href.split('?')[0]+"?id="+id_campo+'&op=2&idv='+id_valor;
    	    } else {
    			
    	    }
    	}
    	$(function(){
    	$('#modalEditar').on('shown.bs.modal', function (event) {
    		var button = $(event.relatedTarget);
    		var id_valor =button.data('id');
    		var valor= button.data('valor');
    		var estado= button.data('estado');
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
        					if($alerta == 'editarCampoExito') {
        			?>		
        						<div class="alert alert-success alert-dismissible" role="alert">
        						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        						  <strong>¡Éxito!</strong> Campo editado correctamente.
        						</div>				
        			<?php
        					} else if($alerta == 'editarCampoError') {
        			?>		
        						<div class="alert alert-danger alert-dismissible" role="alert">
        						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        						  <strong>Error.</strong> Ocurrió un error al editar el campo.
        						</div>																					
        			<?php
        					} 
        				}
        			?>					            

        			<div class="panel panel-default">
        				<div class="panel-heading">
        					<h2>Información del campo</h2>
        				</div>
        				<div class="panel-body">
        					<form name="signup" method="post" action="editarCampoAdicional.php?op=1&id=<?php echo $_GET['id']?>">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input class="form-control" name="nombre" id="nombre" type="text" value="<?php echo $info['nombre'] ?>"/>
        						 </div>
                                 <div class="form-group">
                                    <label for="tooltip">Tooltip</label>
                                    <input class="form-control" name="tooltip" id="tooltip" type="text" value="<?php echo $info['tooltip'] ?>"/>
                                 </div>   
        						 <div class="form-group">
                                    <p><label>Filtrable</label></p>
                                    <label class="radio-inline">
                                        <input type="radio" name="filtrable" value="1" <?php if($info['filtrable']=='1'){ echo 'CHECKED';}?>>Sí
                                    </label>
        					     	<label class="radio-inline">
                                        <input type="radio" name="filtrable" value="0" <?php if($info['filtrable']=='0'){ echo 'CHECKED';}?>>No
                                    </label>
        						</div>
                                <div class="form-group">
                                    <p><label>Obligatorio</label></p>
        					     	<label class="radio-inline">
                                        <input type="radio" name="obligatorio" value="1" <?php if($info['obligatorio']=='1'){ echo 'CHECKED';}?>>Sí
                                    </label>
        					     	<label class="radio-inline">
                                        <input type="radio" name="obligatorio" value="0" <?php if($info['obligatorio']=='0'){ echo 'CHECKED';}?>>No
                                    </label>
        						</div>
                                <div class="form-group">
                                    <p><label>Disponible</label></p>
                                    <label class="radio-inline">
                                        <input type="radio" name="estado" value="1" <?php if($info['estado']=='1'){ echo 'CHECKED';}?>>Sí
                                    </label>
        					     	<label class="radio-inline">
                                        <input type="radio" name="estado" value="0" <?php if($info['estado']=='0'){ echo 'CHECKED';}?>>No
                                    </label>
        						</div>
        						<button type="submit" class="btn btn-primary">Guardar</button>
        					</form>
        				</div>
        			</div>
                    			
                    			
                    <br>
                    <div class="panel panel-default">
        				<div class="panel-heading">
        					<h2>Gestión de valores</h2>
        				</div>
                    	<div class="panel-body">
                    	<table class="table" border='1'>
                    	<thead>
                    	<tr> 
                      		<th class="th">Valor</th> 
                      		<th class="th">Disponible</th>
                      		<th class="th">Editar</th>
                      		<th class="th">Activar/Desactivar</th>                      		                      		
                     	</tr>
                     	</thead>
                    <?php
                    	$result=allValorCampo($_GET['id']);
                    	while($row=mysqli_fetch_array($result)) {
                    		echo "<tr class=\"tr\">";
                     		echo "<td>".$row["valor"]."</td>";
                     		if($row['estado']==1){
                     			echo "<td>si</td>";
                     		}
                     		else{
                     			echo "<td>no</td>";
                     		}
                    ?>
                    <td>
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $row['id_valor']?>" data-valor="<?php echo $row['valor']?>" data-estado="<?php echo $row['estado']?>">
                        	<span class="far fa-edit" aria-hidden="true"></span>
                        </button>
                    </td>	
                    <td>
                       <button class="btn btn-default" onclick="eliminar(<?php echo $row['id_valor'].',\''.$row['valor'].'\','.$_GET['id'] ?>)">
                       		<span class="fas fa-<?php if($row['estado']=='1'){ echo 'ban'; }else{ echo 'check'; }?>" aria-hidden="true"></span>
                       </button>
                    </td>   
                    <?php
                    }
                      echo "</tr>";
                    echo "</table>";
                    ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregar">
                    	Agregar Valor
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
           <form class="form-group" name="agregar" method="post" action="editarCampoAdicional.php?op=3&<?php echo 'id='.$_GET['id']?>">
    					 <br><label for="exampleInputEmail1">Valor</label><input class="form-control" name="valor" id="valor" type="text" />
    					 <br><label for="exampleInputEmail1">Disponible</label>
    					<div class="radio">
    				     	<label><input type="radio" name="estado" value="1">si</label>
    				     	<label><input type="radio" name="estado" value="0">no</label>
    					</div>
    			<div class="modal-footer">
            		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
           <form class="form-group" name="editar" method="post" action="editarCampoAdicional.php?op=4&<?php echo 'id='.$_GET['id']?>">
           				 <br><label for="exampleInputEmail1">Id</label><input class="form-control" name="id_valor" id="id_valor" type="text" readonly="readonly"/>
    					 <br><label for="exampleInputEmail1">Valor</label><input class="form-control" name="valor" id="valor" type="text" />
    					 <br><label for="exampleInputEmail1">Disponible</label>
    					<div class="radio" >
    				     	<label><input type="radio" id="val1" name="estadov" value="1">si</label>
    				     	<label><input type="radio" id="val2" name="estadov" value="0">no</label>
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

<!--   Core JS Files  
<script src="assets/js/core/jquery.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>  -->
<script src="/adm/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/adm/assets/js/now-ui-dashboard.js?v=1.0.1"></script>

</html>
