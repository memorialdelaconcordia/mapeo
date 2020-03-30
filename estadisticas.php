<?php
	//error_reporting(0);
	include "include/db.php";

    $infoSource=$_POST;	
	
	//var_dump($infoSource['tipo_estadistica']);

	function rand_color() {
		return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
	}	
	
?>
<html> 
<head>
    <title>Estadísticas - Mapeo de la Memoria</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
	<link rel="stylesheet" href="css/estadisticas.css?">
    <!-- jQuery -->
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>        
	<!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>    
    <!-- Own -->
    <link rel="stylesheet" href="/css/homepage.css">
    <!-- Chart -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
</head>

<body>

	<header>
    <?php include 'include/header.php'; ?>
    </header>
	
    <div class="container">
        
		<div class="row">
		
			<!-- Sección de selección de tipos de estadísticas -->
            <div class="col-xs-10 col-sm-10" id="busqueda">
			
				<form method="POST" style="margin-top: 25px;">		
					<h3>Estadísticas</h3>

					<select class="filter-estadisticas" name="tipo_estadistica" style="width: 250px;"> 
						<option value="1">Sitios por Departamento</option>
						<option value="2">Sitios por Tipo de Delito</option>
						<option value="3">Sitios por Tipo</option>
						<option value="4">Sitios por Estado</option>
						<option value="5">Sitios por Período de Gobierno</option>
					</select>
					&nbsp
					<span class="tool-container">
						<i class="fa fa-question-circle fa-lg" id="tt-estadisticas"></i>
						<span class="tooltip" title="Elija el tipo de estadística a visualizar."></span>
					</span>		
					<button type="submit" class="btn" style="margin-left: 15px;">Mostrar</button><br><br>
					
				</form>
			
            </div>
			
		</div>
		
		<div class="row">

				<!-- Sección para mostrar las tablas de estadísticas: -->
				<div class="col-xs-12 col-sm-12 tabla-estadisticas" id="content">
					
					
					<!-- Título -->
					<div style="margin-left: 30px">
					<?php  
						if(isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '1') { //Sitios por Departamento
							echo "<h3>Sitios de memoria por departamento:</h3>";
						} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '2') { //Sitios por Tipo de Delito
							echo "<h3>Sitios de memoria por tipo de delito:</h3>";
						} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '3') { //Sitios por Tipo
							echo "<h3>Sitios de memoria por tipo:</h3>";
						} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '4') { //Sitios por Estado
							echo "<h3>Sitios de memoria por estado:</h3>";
						} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '5') { //Sitios por Período de Gobierno
							echo "<h3>Sitios de memoria por período de gobierno:</h3>";
						}
					?>
					</div>
					<br>
					
					<script>
						var randomColorGenerator = function () { 
							return '#' + (Math.random().toString(16) + '0000000').slice(2, 8); 
						};
					</script>
						
						
					<table class="table table-bordered">
						<thead class="thead-default">
						<tr>
						<!-- Encabezado de la tabla -->
						<?php  
							if(isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '1') { //Sitios por Departamento
								echo "<th>Departamento</th>";
								echo "<th>Cantidad</th>";
							} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '2') { //Sitios por Tipo de Delito
								echo "<th>Tipo de delito</th>";
								echo "<th>Cantidad</th>";
							} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '3') { //Sitios por Tipo
								echo "<th>Tipo de sitio de memoria</th>";
								echo "<th>Cantidad</th>";
							} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '4') { //Sitios por Estado
								echo "<th>Estado</th>";
								echo "<th>Cantidad</th>";
							} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '5') { //Sitios por Período de Gobierno
								echo "<th>Gobierno</th>";
								echo "<th>Cantidad</th>";
							}
							
						?>					

						</tr>
						</thead>
						<tbody>
						<?php  
							if(isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '1') { //Sitios por Departamento
								$result=estadisticas_monumento_departamento();
								$labels = '';
								$datos = '';
								$colors = '';
								while($row=mysqli_fetch_assoc($result)){
									echo "<tr><td>".$row["departamento"]."</td><td>".$row["count"]."</td></tr>";
									$labels.= "'".$row["departamento"]."',";
									$datos.= $row["count"].",";
									$colors.= "'".rand_color()."',";
								}
							} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '2') { //Sitios por Tipo de Delito
								$result=estadisticas_monumento_delito();
								$labels = '';
								$datos = '';
								$colors = '';
								while($row=mysqli_fetch_assoc($result)){
									echo "<tr><td>".$row["evento"]."</td><td>".$row["count"]."</td></tr>";
									$labels.= "'".$row["evento"]."',";
									$datos.= $row["count"].",";
									$colors.= "'".rand_color()."',";
								}
							} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '3') { //Sitios por Tipo
								$result=estadisticas_monumento_tipo();
								$labels = '';
								$datos = '';
								$colors = '';
								while($row=mysqli_fetch_assoc($result)){
									echo "<tr><td>".$row["tipo_monumento"]."</td><td>".$row["count"]."</td></tr>";
									$labels.= "'".$row["tipo_monumento"]."',";
									$datos.= $row["count"].",";
									$colors.= "'".rand_color()."',";
								}
							} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '4') { //Sitios por Estado
								$result=estadisticas_monumento_estado_sitio();
								$labels = '';
								$datos = '';
								$colors = '';
								while($row=mysqli_fetch_assoc($result)){
									echo "<tr><td>".$row["estado_sitio"]."</td><td>".$row["count"]."</td></tr>";
									$labels.= "'".$row["estado_sitio"]."',";
									$datos.= $row["count"].",";
									$colors.= "'".rand_color()."',";
								}
							} else if (isset($infoSource['tipo_estadistica']) && $infoSource['tipo_estadistica'] == '5') { //Sitios por Período de Gobierno
								$result=estadisticas_monumento_periodo_gobierno();
								$labels = '';
								$datos = '';
								$colors = '';
								$unwanted_array = array(    'Á'=>'A', 'É'=>'E',
															'Í'=>'I', 'Ó'=>'O', 
															'Ú'=>'U', 'á'=>'a', 
															'&eacute;'=>'é', '&iacute;'=>'í', '&oacute;'=>'ó',
															'ú'=>'u', );						
								while($row=mysqli_fetch_assoc($result)){
									echo "<tr><td>".$row["periodo_estatal"]."</td><td>".$row["count"]."</td></tr>";
									$labels.= "'". strtr($row["periodo_estatal"],$unwanted_array)."',";
									$datos.= $row["count"].",";
									$colors.= "'".rand_color()."',";
								}
							}																
						?>
						</tbody>
					</table>
		
					<br>
					<br>				
					
					<div style="width: 75%; margin: auto;">
						<canvas id="myChart" width="400" height="400"></canvas>
					</div>						
					
					<br>
					<br>	
					
					<script>
						
						var ctx = document.getElementById("myChart").getContext('2d');
						var myChart = new Chart(ctx, {
							type: 'horizontalBar',
							data: {
								labels: [<?php echo $labels; ?>],
								datasets: [{
									data: [<?php echo $datos; ?>],
									backgroundColor: [<?php echo $colors; ?>],
									borderColor: [],
									borderWidth: 1
								}]
							},
							options: {
								maintainAspectRatio: false,
								legend: {
									display: false
								},
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero:true
										}
									}],
									xAxes: [{
										ticks: {
											beginAtZero:true
										}
									}]									
								}
							}
						});
						
						function addData(chart, label, data, color) {
							chart.data.labels.push(label);
							chart.data.datasets.forEach((dataset) => {
								dataset.data.push(data);
								dataset.backgroundColor.push(color);
							});
							chart.update();
						}
						
					</script>										
					
				</div>

			
		</div>

    </div>

    <footer>
    <?php include 'include/footer.php'; ?>
    </footer>

	<script>
        $(document).ready(function() {
            // Select2
            $(".filter-estadisticas").select2();
        });
    </script>	
	
</body>
