<?php 
	session_start();
	$basedir = realpath(__DIR__);
	include "../../include/globals.php";
	include( '../../include/db.php');

	//Verificar que se tenga el rol de administrador:
	if(!in_array(1, $_SESSION['rol'])){
		header("location: http://".$root_path."adm/login.php");
	}
	
	if(isset($_GET['cambio_estado_id'])){
		$result=cambioEstadoMonumento(trim($_GET['cambio_estado_id']));
		if($result){
			$alerta = 'cambioEstadoCorrecto';
		}
		else{
			$alerta = 'cambioEstadoError';
		}
	}
	
	if(isset($_GET['delete_id'])){
		$result=bajaMonumento(trim($_GET['delete_id']));
		if($result){
			$alerta = 'eliminacionCorrecta';
		}
		else{
			$alerta = 'eliminacionError';
		}
	}	
      
?>


<html>
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <title>Gestionar sitios de memoria</title>
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
	
	<!-- Datatables -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.css"/> 
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/datatables.min.js"></script>
    
</head>

<body>

    <div class="wrapper ">

        <?php include '../include/sidebar.php'; ?>  
        
        <div class="main-panel">
        
			<?php include '../include/header.php'; ?>  

			<div class="panel-header panel-header-lg">
            </div>

        	<div class="container-fluid">	
        		
        		<div class="row">
        			
        			<!-- Sección principal: -->
        			<div class="col-sm-12" style="padding-left: 30px; padding-top: 20px;">                
        			
            			<?php
            				if(isset($alerta)) {
            					if($alerta == 'cambioEstadoCorrecto') {
            			?>		
            						<div class="alert alert-success alert-dismissible" role="alert">
            						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            						  <strong>¡Éxito!</strong> Estado del sitio modificado correctamente.
            						</div>				
            			<?php
            					} else if($alerta == 'cambioEstadoError') {
            			?>		
            						<div class="alert alert-danger alert-dismissible" role="alert">
            						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            						  <strong>Error.</strong> Ocurrió un error al modificar el estado del sitio.
            						</div>			
            			<?php
            					} else if($alerta == 'eliminacionCorrecta') {
            			?>		
            						<div class="alert alert-success alert-dismissible" role="alert">
            						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            						  <strong>¡Éxito!</strong> Sitio eliminado correctamente.
            						</div>		
            			<?php
            					} else if($alerta == 'eliminacionError') {
            			?>		
            						<div class="alert alert-danger alert-dismissible" role="alert">
            						  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            						  <strong>Error.</strong> Ocurrió un error al eliminar el sitio.
            						</div>										
            			<?php
            					} 
            				}
            			?>	

						<h3>Gestión de sitios de memoria:</h3>

						<div class="row">
						
        			         <!-- Búsqueda: -->
                			<input id="filter" type="text" class="form-control" placeholder="Escriba su búsqueda aquí" style="width:300px; float:left;">
                			<p id="sresult" style="float:right;font-size:2em;"></p>
						
						</div>

						<div class="row">
						
                			<!-- Tabla: -->
                			<table class="table table-bordered" id="example" style="margin-top:70px;">
                				
                				<!-- Encabezado: -->
                				<thead>
                				<tr> 
                					<th class="th">Título</th> 
                			  		<th class="th col-md-2">Estado</th> 
                			  		<th class="th col-md-2">Creado por:</th> 
                                    <th class="th col-md-2">Eliminar</th> 
                			 	</tr>
                			 	</thead>
                			 	
                				<?php
                					$result=allMonumentosConSolicitudDeEliminacion();
                					echo "<tbody class='searchable'>";
                					while($row=mysqli_fetch_array($result)){
                						echo "<tr class=\"tr\">";
                						echo "<td>".$row["titulo"];
                						echo "<td col-md-2>".$row["estado_actual"];
                						echo "<td col-md-2>".$row["nombre_usuario"];
                						echo "<td>";
                				?>
                			
                                    <!-- Botón "Eliminar" -->
                                    <button class="btn btn-default" onclick="eliminarMonumento(<?php echo $row['id_monumento'].',\''.$row['titulo'].'\'' ?>)" data-toggle="tooltip" title="Eliminar">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                    </button>
                				</td></tr>
                                <?php
                                    }
                                ?>
                				</tbody>
                			</table>						
						
						</div>

                    </div>    
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
    
    	function cambiarEstadoMonumento(id,titulo,estado_actual) {
    		if(estado_actual == 'Inactivo') {
    			if (confirm("¿Está seguro que quiere activar el sitio \n"+titulo+"?") == true) {
    				document.location.href = window.location.href.split('?')[0]+"?cambio_estado_id="+id;
    			} 
    		} else {
    			if (confirm("¿Está seguro que quiere inactivar el sitio \n"+titulo+"?") == true) {
    				document.location.href = window.location.href.split('?')[0]+"?cambio_estado_id="+id;
    			}
    		}
    	}
    
    	function eliminarMonumento(id, titulo) {
    		if (confirm("¿Está seguro que quiere eliminar el sitio \n"+titulo+"?") == true) {
    			document.location.href = window.location.href.split('?')[0]+"?delete_id="+id;
    		}
    	}
    
        
    	$(document).ready(function () {
    
    		(function ($) {
    
    			$('#filter').keyup(function () {
    				var rex = new RegExp($(this).val(), 'i');
    				var $artifacts= $('.searchable tr');
    				$artifacts.hide();
    				var $result = $artifacts.filter(function () {
    					return rex.test($(this).text());
    				});
    				$result.show();
    				$('#sresult').text('Resultados: '+$result.length);
    			})
    
    		}(jQuery));
    
    	});
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable( {
                "columns": [
                    null,
                    null,
                    null,
                    { "width": "20px", "sortable": false }
                ],
                "lengthChange": false, //used to hide the property  
                "searching": false     ,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                }
            });
        });
    </script>    


</body>

<!--   Core JS Files  
<script src="assets/js/core/jquery.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>  -->
<script src="/adm/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/adm/assets/js/now-ui-dashboard.js?v=1.0.1"></script>

</html>

























