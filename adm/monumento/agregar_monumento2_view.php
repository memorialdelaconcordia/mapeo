<html>
<head>
    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <title>Crear Sitio de Memoria</title>
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
	<!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=LLAVE_GOOGLE_MAPS"></script>
	<!-- Now UI -->
	<link href="/adm/assets/css/now-ui-dashboard.css?v=1.0.1" rel="stylesheet" />
	<!-- For the datepicker -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<!-- Own css -->
	<link href="/adm/assets/css/admin.css" rel="stylesheet" />	
	
	<!-- Script de validación de los campos de las formas presentes en esta página: -->
	<script type="text/javascript">	
	
		$(document).ready(function () {
				
			//Validación de la forma de adición de nuevas víctimas:
			$('#form_agregar_persona').validate({ //Initialize the plugin...
				rules: {
					nombrePersona: {
						required: true,
						normalizer: function(value) {
							return $.trim(value);
						}						
					}
				},			
				submitHandler: function (form) {
					var $form = $( form )					
					url = $form.attr( 'action' );
					var posting = $.post( url, { 
													nombre_persona: $('#nombre_persona').val(), 
													menordeedad: $('#menordeedad').val(), 
													genero: $('#genero').val(), 
													sector: $('#sector').val(), 
													profesion: $('#profesion').val(), 
													pais: $('#pais').val() 
												}
					);
					/* Alerts the results: */
					posting.done(function( data ) {
						alert('¡Persona agregada exitosamente!');
						var obj = jQuery.parseJSON(data);
						var $victimas = $('#victimas');
						$victimas.append($("<option></option>")
											.attr("value", obj.idVictima)
											.text(obj.nombreVictima));
						$victimasSel2 = $("#victimas").select2();
						$victimasSel2Values = $victimasSel2.val() || [];
						$victimasSel2Values.push(obj.idVictima);
						$victimasSel2.val($victimasSel2Values).trigger("change");
						$('#modal_agregar_persona').modal('hide');
					});				
				},
				//https://stackoverflow.com/questions/18754020/bootstrap-3-with-jquery-validation-plugin
				highlight: function(element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight: function(element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement: 'span',
				errorClass: 'help-block',
				errorPlacement: function(error, element) {
					if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
						element.parent().parent().append(error);
					} else {
						error.insertAfter(element);
					}
				}				
			});		

			//Validación de la forma de adición de nuevas organizaciones:
			$('#form_agregar_organizacion').validate({ //Initialize the plugin...
				rules: {
					'nombre_organizacion': {
						required: true,
						normalizer: function(value) {
							return $.trim(value);
						}						
					},
					'email_organizacion': {
						email: true				
					}					
				},			
				submitHandler: function (form) {
					var $form = $( form ),
					url = $form.attr( 'action' );
					var posting = $.post( url, { 
					                                nombre_organizacion: $('#nombre_organizacion').val(), 
					                                persona_organizacion: $('#persona_organizacion').val(), 
					                                telefono_organizacion: $('#telefono_organizacion').val(), 
					                                email_organizacion: $('#email_organizacion').val() 
												});

					/* Alerts the results:  */
					posting.done(function( data ) {
						alert('¡Organización agregada exitosamente!');
						var obj = jQuery.parseJSON(data);
						var $orgResponsable = $("#organizacion_responsable");
						$orgResponsable.append($("<option></option>")
							.attr("value", obj.idOrganizacion).text(obj.nombreOrganizacion));
						$('#modal_agregar_organizacion').modal('hide');
					});
				},
				//https://stackoverflow.com/questions/18754020/bootstrap-3-with-jquery-validation-plugin
				highlight: function(element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight: function(element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement: 'span',
				errorClass: 'help-block',
				errorPlacement: function(error, element) {
					if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
						element.parent().parent().append(error);
					} else {
						error.insertAfter(element);
					}
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
        		<div class="row">
        			
        			<!-- Sección principal: -->
        			<div class="col-sm-12" style="padding-left: 30px; padding-top: 20px;">                
        			
                        <!-- Errores: -->
                        <?php
                            foreach ($errores as $error) {
                                echo $error . "<br>";
                            }
                            
                            //TODO
                            if (isset($_GET['msg']) && $_GET['msg'] == "success" ) { ?>
                                <div class="alert alert-success" role="alert">
                                  Sitio creado. Puede continuar...
                                </div>                                
                            <?php } else if(isset($_GET['msg']) && $_GET['msg'] == "updateSuccess" ) { ?>
                                <div class="alert alert-success" role="alert">
                                  Sitio actualizado. Puede continuar...
                                </div>  
                            <?php } ?>                  
                    
        				<form name="form_crear_sitio" id="form_crear_sitio" method="post" action="agregar_monumento2.php">
                            
                            <input type="hidden" name="id" id="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ""; ?>">
                            
                            <h2>Creación del sitio virtual de memoria (2/3)</h2>
                            
                            <h3>Información general sobre las víctimas:</h3>
        	
        					<div class="form-group">
        						<label for="keywords">Palabras clave</label>
        						<select class="form-control" name="keywords[]" id="keywords" multiple="multiple">
                                <?php 
                                    while($keyword = mysqli_fetch_array($keywords)){
                                        echo '<option value="'.$keyword["keyword"].'" selected="selected">'.$keyword["keyword"]."</option>";
                                    }
                                ?>                                
                                </select><br>
        					</div>
        
        					<div class="form-group">			
        						<label for="tipo_evento">Tipo de delito</label>
        						<select class="form-control" name="tipo_evento" id="tipo_evento">
        							<?php
        								$result=allTipoEvento();
        								while($row=mysqli_fetch_array($result)){
        									if($row['estado'] == 1){
        										echo '<option value="'.$row["id_tipo_evento"].'" ';
        										echo (isset($tipoEvento) && ($tipoEvento==$row["id_tipo_evento"])) ? 'selected' : '';
        										echo ">".$row["evento"]."</option>";    
        									}
        								}
        							?>
        						</select>
        					</div>
        						
                            <div class="form-group">
        						<label for="periodo_estatal">Período de gobierno en el que sucedió el hecho</label>
                                <select class="form-control" name="periodo_estatal" id="periodo_estatal">
                                    <?php
                                        $result=allPeriodoEstatal();
                                        while($row=mysqli_fetch_array($result)){
                                            echo '<option value="'.$row["id_periodo"].'" ';
                                            echo (isset($periodoEstatal) && ($periodoEstatal==$row["id_periodo"])) ? 'selected' : '';
                                            echo ">".$row["nombre"]." (".$row["periodo"].")</option>";
                                        }
                                    ?>
                                </select>                        
                            </div>        						
        						
        					<div class="form-group">
            					<label for="fecha_conmemoracion">Fecha de conmemoración del hecho</label>                  
            					<input class="form-control" type="text" name="fecha_conmemoracion" id="fecha_conmemoracion" style="color:black" 
                                    value="<?php echo isset($fechaConmemoracion) ? $fechaConmemoracion : ''; ?>" readonly>
        					</div>	        						
        						
        					<div class="form-group">					
        						<label for="victimas">Víctimas nombradas en el sitio de memoria</label>
        						<div>
        							<select class="form-control" name="victimas[]" id="victimas" multiple="multiple">
        								<?php
        									$result = allPersonas();
        									while($row = mysqli_fetch_array($result)){
        										echo '<option value="'.$row["id_persona"].'" ';
        										mysqli_data_seek($victimas, 0);
        										while($victima = mysqli_fetch_array($victimas)){
        										    if($victima['id_persona'] == $row["id_persona"]) { 
        										        echo 'selected="selected"';
        										    }
        										}
        										echo '>'.$row["nombre"]."</option>";
        									}
        								?>
        							</select>
        							<br>
        							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_agregar_persona">
        								Agregar nueva víctima
        							</button>
        						</div>
        					</div>        						
        						
                            <!-- Campos adicionales -->
                            <?php
                                // Se obtiene el listado de campos adicionales.
        						$result = allCampoActivo(); 
        						while($row = mysqli_fetch_array($result)) {
        					?>	    
        						<div class="form-group">
        							<label for="campo_adicional_<?php echo $row["id_campo_adicional"]; ?>"><?php echo $row["nombre"]; ?></label>
        							<select class="form-control" name="campo_adicional_<?php echo $row["id_campo_adicional"]; ?>" id="campo_adicional_<?php echo $row["id_campo_adicional"]; ?>">
        					<?php 		
        					        // Se obtienen los posibles valores para este campo adicional:
        					        $resultValores = allValorCampoActivo($row["id_campo_adicional"]);
        							while($rowVal = mysqli_fetch_array($resultValores)) {
        								echo '<option value="'.$rowVal["id_valor"].'" ';
        								mysqli_data_seek($camposAdicionalesSeleccionados, 0);
        								while($campoAdicionalSelec = mysqli_fetch_array($camposAdicionalesSeleccionados)) {
        								    if($campoAdicionalSelec['id_valor'] == $rowVal['id_valor']) {
        								        echo 'selected="selected"';
        								    }
        								}
        								echo '>'.$rowVal["valor"].'</option>';
        							}
        					?>		
        							</select>
        						</div>
        					<?php 	
        						}
        					?>
  
  
        					<hr class="featurette-divider">	 
        					
                            <!-- ############################################################################################################# -->	 
                            <h3>Información general del sitio de memoria:</h3>
        					
                            <div class="form-group">	
        						<label for="tipo_monumento">Tipo del sitio de memoria</label>
        						<select class="form-control" name="tipo_monumento" id="tipo_monumento">
                                    <?php
                                        $result=allTiposMonumento();
                                        while($row=mysqli_fetch_array($result)) {
                                            echo '<option value="'.$row["id_tipo_monumento"].'" ';
                                            echo (isset($tipoSitio) && ($tipoSitio==$row["id_tipo_monumento"])) ? 'selected' : '';
                                            echo ">".$row["tipo_monumento"]."</option>";
                                        }
                                    ?>
        						</select>
        					</div>        					
        					
                            <div class="form-group">
        						<label for="estado_sitio">Estado del sitio</label>
        						<select class="form-control" name="estado_sitio" id="estado_sitio">
                                    <option value="Buen estado" <?php echo (isset($estadoSitio) && ($estadoSitio=="Buen estado")) ? 'selected' : ''; ?> >Buen estado</option>
                                    <option value="Mal estado" <?php echo (isset($estadoSitio) && ($estadoSitio=="Mal estado")) ? 'selected' : ''; ?> >Mal estado</option>
                                    <option value="Destruído" <?php echo (isset($estadoSitio) && ($estadoSitio=="Destruído")) ? 'selected' : ''; ?> >Destruído</option>
        						</select>
        					</div>        					
        					
                            <div class="form-group">
        						<label for="municipio">Municipio donde se encuentre el sitio</label>
        						<select class="form-control" name="municipio" id="municipio">
                                      <?php
                                        $result=municipiosDepto();
                                        while($row=mysqli_fetch_array($result)) {
                                            echo '<option value='.$row["id_municipio"];
                                            echo (isset($municipio) && ($municipio==$row["id_municipio"])) ? ' selected' : '';
                                            echo ">".$row["municipio"]." - ".$row["departamento"]."</option>";
                                        } 
                                      ?>
        						</select>
        					</div>	        					
        					
        					<div class="form-group">
        						<label for="direccion">Dirección del sitio</label>
        						<input class="form-control" name="direccion" id="direccion" type="text" value="<?php echo isset($direccion) ? $direccion : ''; ?>"/>
        					</div>	
        						
        					<div class="form-group">	
        						<label for="ubicacion">Ubicación exacta (Ejemplo: en el centro del parque)</label>
        						<input class="form-control" name="ubicacion" id="ubicacion" type="text" value="<?php echo isset($ubicacion) ? $ubicacion : ''; ?>"/>
        					</div>	
        					
        					<div class="form-group">	
        						<label for="como_llegar">Indicaciones para llegar</label>
        						<input class="form-control" name="como_llegar" id="como_llegar" type="text" value="<?php echo isset($comoLlegar) ? $comoLlegar : ''; ?>"/>
        					</div>	
        					
        					<div class="form-group">						
        						<label for="acceso">Requisitos para acceder al sitio</label>
        						<input class="form-control" name="acceso" id="acceso" type="text" value="<?php echo isset($acceso) ? $acceso : ''; ?>"/>
        					</div>		
                            
                            <div class="form-group">
        						<label for="fecha_creacion">Fecha de creación</label> 
        						<input class="form-control" type="text" name="fecha_creacion" id="fecha_creacion" style="color:black"
                                    value="<?php echo isset($fechaCreacion) ? $fechaCreacion : ''; ?>" readonly>
                            </div>
                            
                            <div class="form-group">
        						<label for="construccion_monumento">Persona/organización que construyó el sitio</label>
        						<input class="form-control" name="construccion_monumento" id="construccion_monumento" type="text" value="<?php echo isset($construccionMonumento) ? $construccionMonumento : ''; ?>"/>
                            </div>
                            
        					<div class="form-group">
        						<label for="apoyo_monumento">Organización/institución que financió el sitio</label>
        						<input class="form-control" name="apoyo_monumento" id="apoyo_monumento" type="text" value="<?php echo isset($apoyoMonumento) ? $apoyoMonumento : ''; ?>"/>
        					</div>	
                                
                            <div class="form-group">    
                                <label for="organizacion_responsable">Organización responsable del sitio</label>
                                <select class="form-control" name="organizacion_responsable" id="organizacion_responsable">                
                                    <option value=""></option>
                                    <?php
                                        $result = allOrganizacion();
                                        while($row=mysqli_fetch_array($result)){
                                            echo '<option value='.$row["id_organizacion"];
                                            echo (isset($organizacionResponsable) && ($organizacionResponsable==$row["id_organizacion"])) ? ' selected' : '';
                                            echo ">".$row["nombre_organizacion"]."</option>";
                                        }
                                    ?>
                                </select><br>
                            </div>                                
                                
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_agregar_organizacion">
                                Agregar una organización
                            </button>
                            
                            <div class="form-group">
                                <label for="actividades">Actividades</label>
                                <input class="form-control" name="actividades" id="actividades" type="text" value="<?php echo isset($actividades) ? $actividades : ''; ?>"/>
                            </div>
                            <div class="form-group">
                                <label for="autor_obra">Artista que realizó el sitio de memoria</label>
                                <input class="form-control" name="autor_obra" id="autor_obra" type="text" value="<?php echo isset($autor) ? $autor : ''; ?>"/>
                            </div>                                                          
                                
        					<br>
                            <button type="button" class="btn btn-default" onclick="window.location='/adm/monumento/agregar_monumento.php?id=<?php echo $id?>';">Regresar</button> 
                            <button type="submit" class="btn btn-primary">Guardar y continuar</button>
        					<button type="button" class="btn btn-default" onclick="window.location='/adm';">Cancelar</button>		
                        </form>        

                    </div>    
                </div>
            </div>
        </div>
    </div>


<!-- ---------------------------------------------------------------------- -->

<?php 	
    require 'agregar_victima.php';
    
    require 'agregar_organizacion.php';
?>

</body>

<script>
	$(document).ready(function() {
		$("#keywords").select2({
			tags: true,
			tokenSeparators: [',', ' ']
		});

		$("#victimas").select2();

		$("#municipios").select2();

		$("#organizacion_responsable").select2();
		
	    $( "#fecha_conmemoracion" ).datepicker({
	        showOn: "button",
	        buttonImage: "/images/calendar.gif",
	        buttonImageOnly: true,
	        buttonText: "Seleccionar fecha",
            dateFormat: "dd/mm/yy"
	      });

	    $( "#fecha_creacion" ).datepicker({
	        showOn: "button",
	        buttonImage: "/images/calendar.gif",
	        buttonImageOnly: true,
	        buttonText: "Seleccionar fecha",
            dateFormat: "dd/mm/yy"
	      });
      
	});
</script>

<!--   Core JS Files -->
<script src="/adm/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/adm/assets/js/now-ui-dashboard.js?v=1.0.1"></script>

</html>
