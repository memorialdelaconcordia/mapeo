<!-- Forma para agregar nuevas víctimas -->
<div class="modal fade" id="modal_agregar_persona" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Agregar Persona</h4>
			</div>
			<div class="modal-body">
				<form name="form_agregar_persona" id="form_agregar_persona" method="post" action="/include/agregarPersona.php">
                    <div class="form-group">
						<label for="nombre_persona">Nombre</label>
						<input class="form-control" name="nombre_persona" id="nombre_persona" type="text" />
                    </div>
					<div class="form-group">
						<p><label class="control-label">Menor de edad</label></p>
						<label class="radio-inline">
							<input type="radio" name="menordeedad" id="menordeedad" value="1">Sí
						</label>
						<label class="radio-inline">
							<input type="radio" name="menordeedad" id="menordeedad" value="0" CHECKED>No
						</label>
					</div>
					<div class="form-group">						
						<label for="genero">Género</label>
						<select class="form-control" name="genero" id="genero">
							<?php
								$result=allGenero();
								while($row=mysqli_fetch_array($result)){
									echo '<option value='.$row["id_genero"].">".$row["genero"]."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">	
						<label for="sector">Sector</label>
						<select class="form-control" name="sector" id="sector">
                        <?php
                            $result=allSector();
                            while($row=mysqli_fetch_array($result)){
                                echo '<option value='.$row["id_sector"].">".$row["sector"]."</option>";
                            }
                        ?>
						</select>
					</div>
					<div class="form-group">	
						<label for="profesion">Profesion</label>
						<select class="form-control" name="profesion" id="profesion">
							<?php
								$result=allProfesion();
								while($row=mysqli_fetch_array($result)){
									echo '<option value='.$row["id_profesion"].">".$row["profesion"]."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">	
						<label for="pais">País de nacionalidad</label>
						<select class="form-control" name="pais" id="pais">
							<?php
								$result=allPais();
								while($row=mysqli_fetch_array($result)){
									echo '<option value='.$row["id_pais"].">".$row["pais"]."</option>";
								}
							?>
						</select>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary">Agregar Persona</button>
					</div>
				</form>
				
				<script type='text/javascript'>
					//Para limpiar los controles del modal dialog al cerrarse:
					$('#modal_agregar_persona').on('hidden.bs.modal', function () {
						$('.modal-body').find('input').val('');
					});
				</script>
			</div>
		</div>
	</div>
</div>
