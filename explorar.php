<?php
	
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/explorar_model.php';  
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/pagineo.php';  
    
        
    $departamentos = isset($_GET['dp']) ? $_GET["dp"] : [];
    $titulo = isset($_GET['nm']) ? $_GET["nm"] : '';
    $tiposDelitos = isset($_GET['dl']) ? $_GET["dl"] : [];
    $tiposSitios = isset($_GET['ts']) ? $_GET["ts"] : [];
    $victimasSeleccionadas = isset($_GET['vic']) ? $_GET["vic"] : [];
    $estadoSitio = isset($_GET['est']) ? $_GET["est"] : '';
    $ordenar = isset($_GET['or']) ? $_GET["or"] : '';
    $periodosSeleccionados = isset($_GET['gob']) ? $_GET["gob"] : [];
    
    // Campos adicionales:
    $camposAdicionales = array();
    foreach($_GET as $key => $value) {
        if(strpos($key, 'adicional') !== false) {
            $camposAdicionales[$key] = $value;
        }
    }
    
    
    // Pagineo:
    
    $sql = getSitiosExplorar(-1, -1);
    $total = mysqli_num_rows($sql);
    
    $adjacents = 4;
    $limit = 20; //how many items to show per page
    $page = $_GET['page'];
    
    if($page) {
        $start = ($page - 1) * $limit; //first item to display on this page
    } else {
        $start = 0;
    }
    
    /* Setup page vars for display. */
    if ($page == 0) $page = 1; //if no page var is given, default to 1.
    $prev = $page - 1; //previous page is current page - 1
    $next = $page + 1; //next page is current page + 1
    $lastpage = ceil($total/$limit); //lastpage.
    $lpm1 = $lastpage - 1; //last page minus 1
    
    $sitios = getSitiosExplorar($start, $limit);
    
    unset($_GET['page']);
    $getQuery = http_build_query($_GET);
    $targetpage = "explorar.php?".$getQuery; //your file name
    
    $pagineo = pagineo($targetpage,
        $page,
        $lastpage,
        $lpm1,
        $prev,
        $next,
        $adjacents);
    
?>
<html> 
<head>
    <title>Explorar - Mapeo de la Memoria</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- jQuery -->
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>        
    <!-- Icons  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>    
    <!-- Own -->
    <link rel="stylesheet" href="/css/homepage.css">
    <link rel="stylesheet" href="/css/explorar.css">
</head>

<body>
	
	<?php include 'include/header.php'; ?>
	
    <div class="container-fluid">

		<!-- Títulos -->
		<div class="row" style="margin-top: 20px;">
			<div class="col-9">
				
			</div>
			<div class="col-3">
				<h4>Búsqueda</h4>
			</div>			
		</div>

		<div class="row">
			<!-- Sitios -->
			<div class="col-9">
				
				<?php $contador = 0;
					  while ($row = mysqli_fetch_assoc($sitios)):
					      if($contador % 4 == 0): error_log("1 - " . $contador); ?>
					   	       <div class="row">
				<?php     endif; ?>
				           	       <div class="col-md-3">
                                        <!-- <div class="card mb-4 box-shadow" style="margin: 10px; background-color: #dfeefc; borde: 0;">  -->
                                        <div class="card mb-3 box-shadow" style="border: 0;">
                                          <img class="card-img-top" src="<?php echo "multimedia/thumbnails/".$row['direccion_archivo']; ?>" alt=""
                                          	onerror="this.src='/images/Imagen_no_disponible.png'">
                                          <!-- <div class="card-body">
                                            <h5 class="card-title"><?php echo $row['titulo']; ?></h5>
                                            <p class="card-text"><?php echo $row['descripcion_corta']; ?></p>
                                            <a href="/presentacion2.php?id=<?php echo $row['id_monumento']; ?>" class="btn btn-outline-light" style="background-color: #05253A">
                                            	Sitio completo
                                            </a>
                                          </div> -->
                                          <a class="" data-toggle="tooltip" data-html="true" title="<?php echo $row['titulo'].'<br>'.$row['departamento']; ?>" data-placement="bottom" href="presentacion2.php?id=<?php echo $row['id_monumento']; ?>"> 
                                            <span class="link-spanner"></span>
                                          </a>                                          
                                        </div>
        					       </div>
    			<?php     if($contador % 4 == 3): error_log("2 - " . $contador);?>
    					       </div>
				<?php     endif; 
				          $contador++;
				      endwhile; 
				      
				      if($contador % 4 != 0):
				          echo "</div>";
				          error_log("3 - " . $contador);
				      endif; ?>
		
				<div class="row" style="margin-top: 20px;">
					<div class="col-12">
						<?php echo $pagineo; ?>
					</div>
				</div>	
		
			</div>
			
			<!-- Controles -->
			<div class="col-3">
				
                <form method="GET" action="explorar.php">
                    <!-- 1 
                    <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" name="is-reportaje" id="is-reportaje">
                          <label class="form-check-label" for="is-reportaje">
                            Sitios con reportajes
                          </label>
                        </div>                     
                    </div> -->

                    <!-- 2 -->
                    <div class="form-group">
                        <label for="nm">Nombre del sitio</label><br>
                        <input class="form-control" name="nm" id="nm" style="width: 75%" value="<?php echo $titulo; ?>"></input>
                    </div>

                    <!-- 3 -->
                    <div class="form-group">
                        <label for="dp">Departamento</label>
                        <select class="form-control" name="dp[]" id="dp" multiple="multiple" style="width: 75%"> 
                            <?php $result = allDepartamentos();
                                  while($row = mysqli_fetch_array($result)): ?>
                                      <option value="<?php echo $row['id_departamento']; ?>" <?php echo (in_array($row['id_departamento'], $departamentos)) ? 'selected' : ''; ?>>
                                        <?php echo $row['departamento']; ?>
                                      </option>
                            <?php endwhile;?>
                        </select>
                    </div>

                    <!-- 4 -->
                    <div class="form-group">
                        <label for="dl">Tipo de delito</label><br>
                        <select class="form-control" name="dl[]" multiple="multiple" id="dl" style="width: 75%"> 
                            <?php $result = allEventos();
                                  while($row = mysqli_fetch_array($result)): ?>
                                      <option value="<?php echo $row['id_tipo_evento'];?>" <?php echo (in_array($row['id_tipo_evento'], $tiposDelitos)) ? 'selected' : ''; ?>>
                                      	<?php echo $row['evento']; ?>
                                      </option>
                            <?php endwhile; ?>
                        </select>         
                    </div>    
                    
                    <!-- 4 -->
                    <div class="form-group">
                        <label for="ts">Tipo de sitio de memoria</label><br>
                        <select class="form-control" name="ts[]" id="ts" multiple="multiple" style="width: 75%"> 
                            <?php $tipoMonumentos = allTipoMonumentos();
                                  while($row = mysqli_fetch_array($tipoMonumentos)): ?>
                                      <option value="<?php echo $row['id_tipo_monumento']; ?>" <?php echo (in_array($row['id_tipo_monumento'], $tiposSitios)) ? 'selected' : ''; ?>>
                                      		<?php echo $row["tipo_monumento"]; ?>
                                      </option>
                            <?php endwhile; ?>    
                        </select>
                    </div>

                    <!-- 5 -->
                    <div class="form-group">
                        <label for="vic">Víctimas</label><br>
                        <select class="form-control" name="vic[]" id="vic" multiple="multiple" style="width: 75%"> 
                            <?php $victimas = allPersonas();
                                  while($row = mysqli_fetch_array($victimas)): ?>
                                      <option value="<?php echo $row['id_persona']; ?>" <?php echo (in_array($row['id_persona'], $victimasSeleccionadas)) ? 'selected' : ''; ?>>
                                      		<?php echo $row["nombre"]; ?>
                                      </option>
                            <?php endwhile; ?>    
                        </select>
                    </div>

                    <!-- 6 -->
                    <div class="form-group">
                        <label for="est">Estado del sitio</label><br>
                        <select class="form-control" name="est" id="est" style="width: 75%"> 
                            <option value="" <?php echo (empty($estadoSitio)) ? 'selected' : ''; ?>></option> 
                            <option value="Buen estado" <?php echo ($estadoSitio == "Buen estado") ? 'selected' : ''; ?>>Buen estado</option> 
                            <option value="Mal estado" <?php echo ($estadoSitio == "Mal estado") ? 'selected' : ''; ?>>Mal estado</option> 
                        </select>
                    </div>

                    <!-- 7 -->
                    <div class="form-group">
                        <label for="gob">Perido de gobierno</label><br>
                        <select class="form-control" name="gob[]" id="gob" multiple="multiple" style="width: 75%"> 
                            <?php $periodos = allPeriodoEstatal();
                                  while($row = mysqli_fetch_array($periodos)): ?>
                                      <option value="<?php echo $row['id_periodo']; ?>" <?php echo (in_array($row['id_periodo'], $periodosSeleccionados)) ? 'selected' : ''; ?>>
                                      		<?php echo $row["nombre"]." - ".$row["periodo"]; ?>
                                      </option>
                            <?php endwhile; ?>    
                        </select>
                    </div>

            		<!-- 8 -->    
                        <?php
                            //Se obtiene el catálogo de campos adicionales:
                            $campos = getCampos();
                            while ($campo = mysqli_fetch_array($campos)) {
                                echo '<div class="form-group">';
                                echo '<label>' . $campo['nombre'] . '</label>';
                                echo '<select class="form-control" name="adicional_' . $campo['id_campo_adicional'] . '[]" id="adicional_' . $campo['id_campo_adicional'] . '" multiple="multiple" style="width: 75%">';
                                //Se obtienen los posibles valores a seleccionar para el campo:
                                $valores = getValorCampo($campo['id_campo_adicional']);
                                while ($valor = mysqli_fetch_array($valores)) {
                                    echo '<option value="' . $valor["id_valor"] . '"';
                                    if (isset($camposAdicionales['adicional_' . $campo['id_campo_adicional']]) && in_array($valor["id_valor"], $camposAdicionales['adicional_' . $campo['id_campo_adicional']])) {
                                        echo 'SELECTED';
                                    }
                                    echo '>' . $valor["valor"] . "</option>";
                                }
                                echo '</select>'; ?> 
                                <?php echo '</div>';
                            }
                        ?>

                    <!-- 9 -->
                    <div class="form-group">
                        <label for="or">Ordenar por</label><br>
                        <select class="form-control" name="or" id="or" style="width: 75%"> 
                        	<option value="" <?php echo ($ordenar == "") ? 'selected' : ''; ?>>Ninguno</option>
                            <option value="departamento" <?php echo ($ordenar == "departamento") ? 'selected' : ''; ?>>Departamento</option>
                            <option value="titulo" <?php echo ($ordenar == "titulo") ? 'selected' : ''; ?>>Título</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary" style="background-color: #3a1a05; border-color: #3a1a05;">Aplicar</button>
						<button type="reset" class="btn btn-secondary">Reestablecer campos</button>                        
                    </div>
                </form>
                
			</div>
		</div>

	</div>

    <footer>
    <?php include 'include/footer.php'; ?>
    </footer>

    <script>
        $(document).ready(function() {
            $("#vic").select2();

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        });
	</script>        

</body>