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
    <!-- TinyMCE -->
    <script src="/plugins/tinymce/tinymce.min.js"></script>    
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
						var $fuente = $("#fuente");
						$fuente.append($("<option></option>")
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
                                      Sitio actualizado. Puede continuar...
                                    </div>                                
                                <?php }
                            ?>             
                        
            				<form name="form_crear_sitio" id="form_crear_sitio" method="post" action="agregar_monumento3.php?op=3" enctype="multipart/form-data" >
                                
                                <input type="hidden" name="id" id="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ""; ?>">
                                
                                <h2>Creación del sitio virtual de memoria (3/3)</h2>                    
                                					
                                <h3>Reportaje y contenido multimedia</h3>
            					
                                <div class="form-group">	
            						<label for="fuente">Fuente que dio la información</label>
            						<select class="form-control" name="fuente" id="fuente">
            							<option value=""></option>
            							<?php
                                            $result = allOrganizacion();
                                            while($row=mysqli_fetch_array($result)){
                                                echo '<option value='.$row["id_organizacion"];
                                                echo (isset($fuenteInformacion) && ($fuenteInformacion==$row["id_organizacion"])) ? ' selected' : '';
                                                echo ">".$row["nombre_organizacion"]."</option>";
                                            }
            							?>
            						</select><br>
            						
            						<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_agregar_organizacion">
            							Agregar una organización
            						</button>
            					</div>

            					<div class="form-group">	
            						<label for="autor_reportaje">Autor/a del reportaje</label>
            						<input class="form-control" name="autor_reportaje" id="autor_reportaje" type="text" value="<?php echo isset($autorReportaje) ? $autorReportaje : ''; ?>"/>
            					</div>
            						
                                <div class="form-group">
            						<label for="fecha_investigacion">Fecha en la que se hizo la investigación</label>                  
            						<div class="hero-unit">
            							<input class="form-control" type="text" id="fecha_investigacion" name="fecha_investigacion" style="color:black"
                                            value="<?php echo isset($fechaInvestigacion) ? $fechaInvestigacion : ''; ?>" readonly>
            						</div>
            					</div>

            					<div class="form-group">
            						<label for="is_reportaje">Tiene reportaje</label>
            						<input type="checkbox" name="is_reportaje" id="is_reportaje" <?php echo (isset($esReportaje) && $esReportaje == 1) ? 'checked' : ''; ?>/>
            					</div>
            					<div class="form-group">
            						<label for="reportaje">Editor de texto (ingrese aquí el reportaje)</label>
            						<textarea class="form-control mceEditor" rows="8" name="reportaje" id="reportaje" >
                                        <?php echo isset($reportaje) ? $reportaje : ''; ?>
                                    </textarea>
            						<script>
            							tinyMCE.init({
                                            // mode : "specific_textareas",
                                            // editor_selector : "mceEditor",
                                            // selector: "textarea",
                                            // plugins: [
                                            //     "advlist autolink lists link image charmap print preview anchor",
                                            //     "searchreplace visualblocks code fullscreen",
                                            //     "insertdatetime media table contextmenu paste"
                                            // ],
                                            // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                                            mode : "specific_textareas",
                                            editor_selector : "mceEditor",
                                            selector: "textarea#reportaje",
                                            language_url: "/plugins/tinymce/langs/es_MX.js",
                                            language:"es_MX",
                                            plugins: [
                                                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                                                "searchreplace wordcount visualblocks visualchars code fullscreen",
                                                "media nonbreaking save table contextmenu directionality",
                                                "template paste textcolor colorpicker textpattern imagetools jbimages"
                                            ],
                                            toolbar1: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                                            toolbar2: "preview media | forecolor | code jbimages",
                                            templates: [
                                                {title: 'Test template 1', content: 'Test 1'},
                                                {title: 'Test template 2', content: 'Test 2'}
                                            ],
                                            relative_urls : false,
            							});
            						</script>
            					</div>
                            </form>            					
            
            				<hr class="thick-divider" />
            
                            <h3>Multimedia</h3>
                            
                            <div id="all_multimedia" class="panel panel-default">
                                <div class="panel-body">
                                    <div id="multimedia_actual">
                     
                                        <?php                  
                                            while($row_multimedia = mysqli_fetch_array($multimedia)) { 
                                        ?>
                                            <div id="multimedia" class="row">
                                                <div class="col-md-6">
                                        <?php 
                                            if($row_multimedia['tipo']=='imagen') { 
                                        ?>
                                                    <img src="/multimedia/<?php echo $row_multimedia['direccion_archivo']; ?>" class="img-thumbnail">
                                        <?php               
                                            } else {
                                        ?>
                                                    <div class="multimedia-container"><?php echo $row_multimedia['link']; ?></div>
                                        <?php 
                                            }
                                        ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="titulo_media">Título</label>
                                                        <input class="form-control" name="titulo_media[]" id="titulo_media_<?php echo $row_multimedia['id_multimedia']; ?>" type="text" value="<?php echo $row_multimedia['titulo']; ?>"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="autor_media">Autor</label>
                                                        <input class="form-control" name="autor_media[]" id="autor_media_<?php echo $row_multimedia['id_multimedia']; ?>" type="text" value="<?php echo $row_multimedia['autor']; ?>"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fuente_media">Fuente</label>
                                                        <input class="form-control" name="fuente_media[]" id="fuente_media_<?php echo $row_multimedia['id_multimedia']; ?>" type="text" value="<?php echo $row_multimedia['fuente']; ?>"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="licencia_media">Licencia</label>
                                                        <input class="form-control" name="licencia_media[]" id="licencia_media_<?php echo $row_multimedia['id_multimedia']; ?>" type="text" value="<?php echo $row_multimedia['licencia']; ?>"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="link_media">Link</label>
                                                        <input class="form-control" name="link_media[]" id="link_media_<?php echo $row_multimedia['id_multimedia']; ?>" type="text" value="<?php echo $row_multimedia['link']; ?>"/>
                                                    </div>
                                                    <button type="button" class="btn btn-primary" onclick="modifyMedia(<?php echo $row_multimedia['id_multimedia'] . ", " . $_GET['id']; ?>)">Modificar</button>
                                                    <button type="button" class="btn btn-primary" onclick="deleteMedia(<?php echo $row_multimedia['id_multimedia'] . ", " . $_GET['id']; ?>)">Eliminar</button>
                                                </div>                                              
                                            </div>
                                        <?php 
                                            }
                                        ?>
                            
                                	</div><!-- <div id="multimedia_actual"> -->

                                	<hr class="featurette-divider">  
        
                                	<form name="form_new_multimedia" id="form_new_multimedia" method="post" action="multimedia.php" enctype="multipart/form-data" >

                                            <h4>Nuevo multimedia:</h4>
                                            <div class="form-group">
                                                <label for="file_media"></label>
                                                <input type="file" name="file_media" id="file_media" class="form-control-file">
                                            </div>                                                                  
                                            <div class="form-group">
                                                <label for="titulo_media">Título</label>
                                                <input class="form-control" name="titulo_media" id="titulo_media" type="text"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="autor_media">Autor</label>
                                                <input class="form-control" name="autor_media" id="autor_media" type="text"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="fuente_media">Fuente</label>
                                                <input class="form-control" name="fuente_media" id="fuente_media" type="text"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="licencia_media">Licencia</label>
                                                <input class="form-control" name="licencia_media" id="licencia_media" type="text"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="link-media">Link</label>
                                                <input class="form-control" name="link_media" id="link_media" type="text"/>
                                            </div>
                                            <input name="op" id="op" type="hidden" value="2"/>
                                            <input name="id_monumento" id="id_monumento" type="hidden" value="<?php echo $_GET['id']; ?>"/>
                                            <button type="submit" class="btn btn-primary">Guardar multimedia</button>   
                                	</form>

                                </div><!-- <div class="panel-body"> -->
                            </div><!-- <div id="all_multimedia" ... > -->

							<hr class="thick-divider" />
        
                            <h3>Noticias</h3>
                            
                            <div id="all_noticias" class="panel panel-default">
                                <div class="panel-body">
                                	<div id="noticias_actuales">
                     
                                    <?php
                                        while($row_noticia = mysqli_fetch_array($noticias)) { 
                                    ?>
                                        <div id="noticia">
                                            <div class="form-group">
                                                <label for="titulo_noticia">Título</label>
                                                <input class="form-control" name="titulo_noticia[]" id="titulo_noticia-<?php echo $row_noticia['id_noticia']; ?>" type="text" value="<?php echo $row_noticia['titulo']; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="link_noticia">Link</label>
                                                <input class="form-control" name="link_noticia[]" id="link_noticia-<?php echo $row_noticia['id_noticia']; ?>" type="text" value="<?php echo $row_noticia['link']; ?>"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="fecha_noticia">Fecha</label>
                                                <input class="form-control" name="fecha_noticia[]" id="fecha_noticia-<?php echo $row_noticia['id_noticia']; ?>" type="text" value="<?php echo $row_noticia['fecha']; ?>" readonly/>
                                            </div>
                                            <div class="form-group">
                                                <label for="fuente_noticia">Fuente</label>
                                                <input class="form-control" name="fuente_noticia[]" id="fuente_noticia-<?php echo $row_noticia['id_noticia']; ?>" type="text" value="<?php echo $row_noticia['fuente']; ?>"/>
                                            </div>
                                            <button type="button" class="btn btn-primary" onclick="modifyNoticia(<?php echo $row_noticia['id_noticia'] . ", " . $_GET['id']; ?> )">Modificar</button>    
                                            <button type="button" class="btn btn-primary" onclick="deleteNoticia(<?php echo $row_noticia['id_noticia'] . ", " . $_GET['id']; ?> )">Eliminar</button>                                        
                                        </div>
                                    <?php 
                                        }
                                    ?>
                            
                            		</div>

                                    <hr class="featurette-divider">  
        
									<form name="form_nueva_noticia" id="form_nueva_noticia" method="post" action="multimedia.php">
                                        <h4>Nueva noticia:</h4>
                                        <div class="form-group">
                                            <label for="titulo_noticia">Título</label>
                                            <input class="form-control" name="titulo_noticia" id="titulo_noticia" type="text"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="link_noticia">Link</label>
                                            <input class="form-control" name="link_noticia" id="link_noticia" type="text"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="fecha_noticia">Fecha</label>
                                            <input class="form-control" name="fecha_noticia" id="fecha_noticia" type="text" readonly />
                                        </div>
                                        <div class="form-group">
                                            <label for="fuente_noticia">Fuente</label>
                                            <input class="form-control" name="fuente_noticia" id="fuente_noticia" type="text"/>
                                        </div>
                                        <input name="op" id="op" type="hidden" value="4"/>
                                        <input name="id_monumento" id="id_monumento" type="hidden" value="<?php echo $_GET['id']; ?>"/>
                                        <button type="submit" class="btn btn-primary">Guardar noticia</button>            
                                   </form>
                                </div>
                            </div>
            					
                            <br>
                            <button type="button" class="btn btn-default" onclick="window.location='/adm/monumento/agregar_monumento2.php?id=<?php echo $id?>';">Regresar</button> 
                            <button type="button" id="btn_submit" class="btn btn-primary">Finalizar</button>
                            <button type="button" class="btn btn-default" onclick="window.location='/adm';">Cancelar</button>   
                    </div>    
                </div>
            </div>
        </div>
    </div>

    <div class="loading-icon"><!-- Place at bottom of page --></div>

<!-- ---------------------------------------------------------------------- -->

<?php   
    require 'agregar_organizacion.php';
?>	

</body>

<script>
	$(document).ready(function() {
        
		$( "#fuente" ).select2();

	    $( "#fecha_investigacion" ).datepicker({
	        showOn: "button",
	        buttonImage: "/images/calendar.gif",
	        buttonImageOnly: true,
	        buttonText: "Seleccionar fecha",
            dateFormat: "dd/mm/yy"
	      });

	    $( "input[id|='fecha_noticia']" ).datepicker({
	        showOn: "button",
	        buttonImage: "/images/calendar.gif",
	        buttonImageOnly: true,
	        buttonText: "Seleccionar fecha",
            dateFormat: "dd/mm/yy"
	      });

        //Para mostrar el ícono de loading:
        $body = $("body");
        
	    $(document).on({
	        ajaxStart: function() { $body.addClass("loading"); },
	         ajaxStop: function() { $body.removeClass("loading"); }
	    });

        //Validación de la forma (jQuery Validate):
        //TODO marcar en rojo los campos con errores.
        $('#form_new_multimedia').validate({ //Initialize the plugin...
            rules: {
                titulo_media: {
                    required: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    }                       
                },
                autor_media: {
                    required: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    }                    
                }
            },
            submitHandler: function(form) {
                envioFormaMultimedia(form);
                //form.submit();
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

<!--   Core JS Files -->
<script src="/adm/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/adm/assets/js/now-ui-dashboard.js?v=1.0.1"></script>
<!-- Own js -->
<script src="/adm/monumento/agregar_monumento3_view.js?v=2"></script>
</html>

