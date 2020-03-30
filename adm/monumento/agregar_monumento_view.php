<html>
<head>
    <meta charset="utf-8">
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
    <script src="/js/jquery.validate.js"></script>  <!-- TODO CDN? -->
    <script src="/js/messages_es.js"></script>  
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
	<!-- Now UI -->
	<link href="/adm/assets/css/now-ui-dashboard.css?v=1.0.1" rel="stylesheet" />
    <!-- jquery-cropper -->
    <script src="/lib/cropperjs/cropper.js"></script><!-- Cropper.js is required -->
    <link  href="/lib/cropperjs/cropper.css" rel="stylesheet">
    <script src="/lib/jquery-cropper/jquery-cropper.js"></script>
	<!-- Own css -->
	<link href="/adm/assets/css/admin.css" rel="stylesheet" />

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
            			?>			
        			
        				<form name="form_crear_sitio" id="form_crear_sitio" method="post" action="agregar_monumento.php" enctype="multipart/form-data" >
                            
                            <input type="hidden" name="id" id="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ""; ?>" maxlength="250">
                            
                            <h2>Creación del sitio virtual de memoria (1/3)</h2>
                            
                            <h3>Información General del Sitio:</h3>
        					
        					<div class="form-group">
        						<label for="titulo">Título</label>
        						<input class="form-control" name="titulo" id="titulo" type="text" value="<?php echo isset($titulo) ? $titulo : ''; ?>"/>
        					</div>
        
        					<div class="form-group">	
        						<label for="estado_publicacion">Estado de la publicación</label>
        						<select class="form-control" name="estado_publicacion" id="estado_publicacion" >
        							<option value="Pendiente" <?php echo (isset($estadoPublicacion) && ($estadoPublicacion=="Pendiente")) ? 'selected' : ''; ?> >Pendiente de aprobación</option>
        							<option value="Publicado" <?php echo (isset($estadoPublicacion) && ($estadoPublicacion=="Publicado")) ? 'selected' : ''; ?>>Publicado</option>
        							<option value="Inactivo" <?php echo (isset($estadoPublicacion) && ($estadoPublicacion=="Inactivo")) ? 'selected' : ''; ?>>Inactivo</option>
        						</select>
        					</div>
        
        					<div class="form-group">
        						<label for="usuario_edicion">Usuario con permisos de edición
            						<select class="form-control" name="usuario_edicion" id="usuario_edicion">
            							<?php
            								  $result = allUsuario();
            								  while($row=mysqli_fetch_array($result)) { ?>
            									<option value="<?php echo $row["id_usuario"]; ?>" <?php echo $usuarioEdicion==$row["id_usuario"] ? 'selected' : ''; ?>>
            										<?php echo $row["nombre_usuario"]; ?>
            									</option>
            							<?php } ?>
            						</select>
        						</label>
        					</div>	        
        
        					<div class="form-group">
        						<label>Coordenadas (Clic en el mapa para fijar la posición)</label>
        						<div class="form-group row">
        							<div class="col-md-2">
        								<input class="form-control" name="latitud" id="latitud" type="double" placeholder="latitud" value="<?php echo isset($latitud) ? $latitud : '14.634016'; ?>"/>
        								<small id="" class="form-text text-muted">Latitud</small>
        							</div>
        							<div class="col-md-2">
        								<input class="form-control" name="longitud" id="longitud" type="double" placeholder="longitud" value="<?php echo isset($longitud) ? $longitud : '-90.515467'; ?>"/>
        								<small id="" class="form-text text-muted">Longitud</small>
        							</div>
        						</div>
        						<button type="button" onclick="updateLocation(); return false;" class="btn btn-default">Actualizar coordenadas en el mapa</button>
        					</div>
                            
                            <!-- MAPA -->
        					<div id="mapa" style="width:100%; height:50%; margin-bottom: 15px;"></div>
                            				
                            <div class="form-group">
        						<label for="descripcion_corta">Descripción corta del sitio (Máximo 200 caracteres)</label>
        						<textarea class="form-control" rows="4" name="descripcion_corta" id="descripcion_corta" maxlength="200"><?php echo isset($descripcionCorta) ? $descripcionCorta : ''; ?></textarea>
                            </div>
        
                            <br />
                            <hr class="featurette-divider">  
                            
                            <!-- ############################################################################################################# -->           
        
                            <h3>Imagen de portada</h3>
                            <div id="divMultimedia">
                            
                                    <?php if(isset($imagenPortada)) {?>
                                        <img src="/multimedia/<?php echo $imagenPortada; ?>" width="100%"/>
                                        <br />
                                    <?php }?>
                                    
                                    <div class="form-group">
        								<label for="imagen_portada">(Máximo 2 MB)</label>
        								<input type="file" name="imagen_portada" id="imagen_portada" class="form-control-file" onchange="readURL(this);"/>
        							</div>

        							<!-- <button type="button" id="subir_imagen" class="btn btn-primary">Subir imagen</button>   -->

                                    <div class="form-group">
        								<label for="titulo_imagen">Título</label>
        								<input class="form-control" name="titulo_imagen" id="titulo_imagen" type="text" value="<?php echo isset($tituloImagen) ? $tituloImagen : ''; ?>"/>
                                    </div>
        							<div class="form-group">
        								<label for="autor_imagen">Autor</label>
        								<input class="form-control" name="autor_imagen" id="autor_imagen" type="text" value="<?php echo isset($autorImagen) ? $autorImagen : ''; ?>"/>
                                    </div>
        							<div class="form-group">
        								<label for="fuente_imagen">Fuente</label>
        								<input class="form-control" name="fuente_imagen" id="fuente_imagen" type="text" value="<?php echo isset($fuenteImagen) ? $fuenteImagen : ''; ?>"/>
                                    </div>
        							<div class="form-group">
        								<label for="licencia_imagen">Licencia</label>
        								<input class="form-control" name="licencia_imagen" id="licencia_imagen" type="text" value="<?php echo isset($licenciaImagen) ? $licenciaImagen : ''; ?>"/>
                                    </div>
        							<div class="form-group">
        								<label for="link_imagen">Link (u objeto incrustado)</label>
        								<textarea class="form-control" name="link_imagen" id="link_imagen" rows="4" ><?php echo isset($linkImagen) ? $linkImagen : ''; ?></textarea>
                                    </div>
                                    <input type="hidden" name="id_imagen_portada" id="id_imagen_portada" value="<?php echo isset($idImagenPortada) ? $idImagenPortada : ''; ?>">
                            </div>
        
        					<!-- 
                            <script>
                                $('#subir_imagen').on('click', function(){
                                        
                            	    var imagenPortada = $("#imagen_portada").prop("files")[0]; 
                                    var formData = new FormData();
                                    formData.append('imagen_portada', imagenPortada);
                                    formData.append('op', '5');
                                                        	
                                    $.ajax({
                                        type: 'post',
                                        url: 'agregar_monumento_aux.php',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: formData,
                                        dataType: 'json'
                                    }).done(function(data) {
										console.log("doneeeeeeeeeee");
										console.log(data);
                                        var $image = $('#image');
                                        $image.attr('src', data.resultado);
                                  
                                        $image.cropper({
                                           aspectRatio: 16 / 9,
                                           crop: function(event) {
                                              console.log(event.detail.x);
                                              console.log(event.detail.y);
                                              console.log(event.detail.width);
                                              console.log(event.detail.height);
                                              console.log(event.detail.rotate);
                                              console.log(event.detail.scaleX);
                                              console.log(event.detail.scaleY);
                                           }
                                         });
                                  
                                         // Get the Cropper.js instance after initialized
                                         var cropper = $image.data('cropper');

                                         $('#image_cropper').show();
                                    });
                                });

                            </script>     
                            -->
            

        					<!-- Wrap the image or canvas element with a block element (container) -->
                            <div id="image_cropper" class="image-cropper">
                              <h4>Edición de foto de portada:</h4>	
                              <p>Recorte la foto de portada. Se recomienda utilizar una foto con resolución mínima de YYYY x ZZZ pixeles.</p>
                              <img id="image" src="" style="width: 100%; max-width: 100%;">
                            </div>
        
        					<br>
                            <button type="submit" class="btn btn-primary">Guardar y continuar</button>
        					<button type="button" class="btn btn-default" onclick="window.location='/adm';">Cancelar</button>
	
                        </form>
        
                    </div>    
                </div>
            </div>
        </div>
    </div>

	<div class="loading-icon"><!-- Place at bottom of page --></div>

</body>

<!--   Core JS Files -->
<script src="/adm/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script> 
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/adm/assets/js/now-ui-dashboard.js?v=1.0.2"></script>
<!-- Own js -->
<script src="/adm/monumento/agregar_monumento_view.js?v=323"></script>

<!-- Google Maps -->
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=LLAVE_GOOGLE_MAPS&callback=initMap">
</script>	

</html>
