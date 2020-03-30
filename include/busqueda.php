<button type="button" class="btn" id='close' data-toggle="collapse" data-target="#collapse1" style="background-color: transparent;">X</button>

<form method="POST">
		<!-- row 1 -->
		<div class="row">
			<div class="col align-self-start">
				<h3>Búsqueda avanzada</h3>
			</div>
			<input type="hidden" name="busqueda">
		</div>
		<!-- row 2 -->
		<div class="row">
			<div class="form-group col-sm-3">
				<label> 
					<input type="checkbox" name="is-reportaje" value="Yes" <?php echo isset($infoSource['is-reportaje']) ? 'CHECKED' : ''; ?> />
						&nbsp;Sitios con reportajes
				</label>
        		<span class="">
        			<i class="fa fa-question-circle fa-lg" data-toggle="tooltip" title="Elija el tipo de delito cometido." data-placement="right"></i>
        		</span><br>				
			</div>
		</div>
		<!-- row 3 -->
		<div class="row">
			<div class="form-group col-sm-4">
				<label>Tipo de delito</label><br> 
				<select class="filter-delito" name="filter-delito[]" multiple="multiple" id="filter-delito" style="width: 75%"> 
                    <?php $result = allEventos();
                          while ($row = mysqli_fetch_array($result)): ?>
                              <option value="<?php echo $row["id_tipo_evento"]; ?>"
                              	<?php echo (isset($infoSource['filter-delito']) && in_array($row["id_tipo_evento"], $infoSource['filter-delito'])) ? 'SELECTED' : ''; ?>>
                              		<?php echo $row["evento"]; ?>
                              </option>
                    <?php endwhile; ?>
        		</select> 
        		<span class="">
        			<i class="fa fa-question-circle fa-lg" data-toggle="tooltip" title="Elija el tipo de delito cometido." data-placement="right"></i>
        		</span><br>
			</div>
			
			<div class="form-group col-sm-4">
				<label>Tipo de sitio de memoria</label><br> 
				<select class="filter-monumento" name="filter-monumento[]" multiple="multiple" style="width: 75%"> 
                    <?php $tipoMonumentos = allTipoMonumentos();
                    while ($row = mysqli_fetch_array($tipoMonumentos)): ?>
                              <option value="<?php echo $row["id_tipo_monumento"]; ?>"
                              	<?php echo (isset($infoSource['filter-monumento']) && in_array($row["id_tipo_monumento"], $infoSource['filter-monumento'])) ? 'SELECTED' : ''; ?>>
                              		<?php echo $row["tipo_monumento"]; ?>
                              </option>
                    <?php endwhile; ?>
    			</select>
        		<span class="">
        			<i class="fa fa-question-circle fa-lg" data-toggle="tooltip" title="Elija el tipo de sitio de memoria." data-placement="right"></i>
        		</span><br>    			
			</div>
			
			<div class="form-group col-sm-4">
				<label>Departamento</label><br> 
				<select class="filter-departamento" name="filter-departamento[]" multiple="multiple" style="width: 75%"> 
                    <?php $result = allDepartamentos();
                    while ($row = mysqli_fetch_array($result)): ?>
                              <option value="<?php echo $row["id_departamento"]; ?>"
                              	<?php echo (isset($infoSource['filter-departamento']) && in_array($row["id_departamento"], $infoSource['filter-departamento'])) ? 'SELECTED' : ''; ?>>
                              		<?php echo $row["departamento"]; ?>
                              </option>
                    <?php endwhile; ?>
        		</select>
        		<span class="">
        			<i class="fa fa-question-circle fa-lg" data-toggle="tooltip" title="Elija el departamento donde se encuentra el sitio." data-placement="right"></i>
        		</span><br>    		    			
			</div>
		</div>
		
		<!-- row 4 -->
		<div class="row">
			<div class="form-group col-sm-4">
				<label>Nombres de Víctimas fallecidas</label><br>
				<select class="filter-victimas" name="filter-victimas[]" multiple="multiple" style="width: 75%">
                    <?php $result = allPersonas();
                    while ($row = mysqli_fetch_array($result)): ?>
                              <option value="<?php echo $row["id_persona"]; ?>"
                              	<?php echo (isset($infoSource['filter-victimas']) && in_array($row["id_persona"], $infoSource['filter-victimas'])) ? 'SELECTED' : ''; ?>>
                              		<?php echo $row["nombre"]; ?>
                              </option>
                    <?php endwhile; ?>
           		</select>
        		<span class="">
        			<i class="fa fa-question-circle fa-lg" data-toggle="tooltip" title="Especifique los nombres de las víctimas referidas en el sitio. Ej: Luis de Lión" data-placement="right"></i>
        		</span><br>           			
			</div>
			
			<div class="form-group col-sm-4">
				<label>Estado del sitio</label><br> 
				<select class="filter-estado" name="filter-estado[]" multiple="multiple" style="width: 75%"> 
                    <?php $result = allEstadoSitios();
                    while ($row = mysqli_fetch_array($result)): ?>
                              <option value="<?php echo $row["estado_sitio"]; ?>"
                              	<?php echo (isset($infoSource['filter-estado']) && in_array($row["estado_sitio"], $infoSource['filter-estado'])) ? 'SELECTED' : ''; ?>>
                              		<?php echo $row["estado_sitio"]; ?>
                              </option>
                    <?php endwhile; ?>
            	</select>
        		<span class="">
        			<i class="fa fa-question-circle fa-lg" data-toggle="tooltip" title="Especifique el estado del sitio ej: Buen Estado" data-placement="right"></i>
        		</span><br>  					
			</div>
			
			<div class="form-group col-sm-4">
				<label>Periodo de gobierno</label><br> 
				<select class="filter-periodo" name="filter-periodo[]" multiple="multiple" style="width: 75%"> 
                    <?php $result = allPeriodos();
                    while ($row = mysqli_fetch_array($result)): ?>
                              <option value="<?php echo $row["periodo_estatal"]; ?>"
                              	<?php echo (isset($infoSource['filter-estado']) && in_array($row["periodo_estatal"], $infoSource['filter-estado'])) ? 'SELECTED' : ''; ?>>
                              		<?php echo $row["periodo_estatal"]; ?>
                              </option>
                    <?php endwhile; ?>
    			</select>
        		<span class="">
        			<i class="fa fa-question-circle fa-lg" data-toggle="tooltip" title="Indique el período estatal en que se cometió el delito." data-placement="right"></i>
        		</span><br>  	
			</div>
		</div>
		
		<!-- row 5 -->
		<div class="row">           
            <?php
                $resultCampos = getCampos();
                $arrayAdicionales = array();
                $contador = 0;
                while ($row = mysqli_fetch_array($resultCampos)) {
                    $arrayAdicionales[] = "filter-adicional-" . $row['id_campo_adicional'];
                    echo '<div class="form-group col-sm-4">';
                    echo '<label>' . $row['nombre'] . '</label><br>';
                    echo '<select class="filter-adicional-' . $row['id_campo_adicional'] . '" name="adicional' . $row['id_campo_adicional'] . '[]" multiple="multiple" style="width: 75%">';
                    $resultValores = getValorCampo($row['id_campo_adicional']);
                    while ($valorAdicional = mysqli_fetch_array($resultValores)) {
                        echo '<option value="' . $valorAdicional["id_valor"] . '"';
                        if (isset($infoSource['adicional' . $row['id_campo_adicional']]) && in_array($valorAdicional["id_valor"], $infoSource['adicional' . $row['id_campo_adicional']])) {
                            echo 'SELECTED';
                        }
                        echo '>' . $valorAdicional["valor"] . "</option>";
                    }
                    echo '</select>'; ?> 
            		<span class="">
            			<i class="fa fa-question-circle fa-lg" data-toggle="tooltip" title="<?php echo $row['tooltip']; ?>" data-placement="right"></i>
            		</span><br>  	
                    <?php echo '</div>';
                    $contador = $contador + 1;
                    if ($contador % 3 == 0) {
                        echo '</div> ';
                        echo '<div class="row">';
                    }
                }
            ?>
 		</div>

		<button type="submit" class="btn btn-primary">Buscar</button>
		<button type="reset" class="btn btn-secondary" onclick="delete_cookie('busqueda')">Reestablecer campos</button>
		
		<script type="text/javascript">
    	    function delete_cookie(name) {
    	        document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    	    }
		</script>
		
		<br>
		<br>
</form>
