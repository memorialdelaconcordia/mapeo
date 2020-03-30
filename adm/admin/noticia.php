<?php
    session_start();
    if(!isset($_SESSION['myusername'])) {
        header("location: login.php");
    }
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';
    
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : '';
        $estado = isset($_POST["estado"]) ? $_POST["estado"] : '';
        $autor = isset($_POST["autor"]) ? $_POST["autor"] : '';
        $fecha = isset($_POST["fecha"]) ? $_POST["fecha"] : '';
        $texto = isset($_POST["texto"]) ? $_POST["texto"] : '';

        //SE CREA EL SITIO EN LA BASE DE DATOS:
        //Retorna el id del sitio si se realizó todo bien. Retorna false si no.
        $result = crearNoticia(
            htmlentities($titulo, ENT_QUOTES),
            htmlentities($estado, ENT_QUOTES),
            htmlentities($autor, ENT_QUOTES),
            htmlentities($fecha, ENT_QUOTES),
            $texto
            );
        
        if($result === false) {
            
            $errores[] = "Ocurrió un error al guardar en base de datos.";
            
        } else {
            
            header("location: /adm/index.php?msg=noticiacreada");
        }

    }
    
    
    function crearNoticia($titulo,
        $estado,
        $autor,
        $fecha,
        $texto
        ) {
            
        $connection = db_connect();
           
        $query = "INSERT INTO noticia_web (titulo,
                                         estado,
                                         autor,
                                         fecha,
                                         texto) VALUES (".
                                         "'".mysqli_real_escape_string($connection,$titulo)."',".
                                         mysqli_real_escape_string($connection,$estado).",".
                                         mysqli_real_escape_string($connection,$autor).",".
                                         "STR_TO_DATE('".mysqli_real_escape_string($connection,$fecha)."', '%d/%m/%Y'),".
                                         "'".mysqli_real_escape_string($connection,$texto)."');";
        
        $result = mysqli_query($connection, $query);
        
        return $result;
    }
    
?>

<html>
<head>

    <meta charset="utf-8">
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />    
    <title>Crear nueva noticia</title>
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
	<!-- TinyMCE -->
	<script src="/plugins/tinymce/tinymce.min.js"></script>
	<!-- Now UI -->
	<link href="/adm/assets/css/now-ui-dashboard.css?v=1.0.1" rel="stylesheet" />
	<!-- For the datepicker -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	<!-- Script de validación de los campos de las formas presentes en esta página: -->
	<script type="text/javascript">	
	
		$(document).ready(function () {
	
			//Validación de la forma de creación de nuevos sitios:
			$('#formaCrearSitio').validate({ //Initialize the plugin...
				rules: {
					titulo: {
						required: true,
						normalizer: function(value) {
							return $.trim(value);
						}						
					},
					latitud: {
						required: true,
						number: true					
					},
                    longitud: {
						required: true,
						number: true							
					},                    
					'descripcion-corta': {
						required: true,
						normalizer: function(value) {
							return $.trim(value);
						}						
					},
					'imagen-portada': {
						required: true				
					}
				},			
				submitHandler: function (form) { 
					if(validarCodigo()) {
						form.submit();
					} else {
						return false;
					}	
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
            			?>			
        			
        				<form name="crearnoticia" id="crearnoticia" method="post" action="noticia.php" enctype="multipart/form-data" >
                            
                            <h2>Nueva noticia:</h2>    
                            
        					<div class="form-group">
        						<label for="titulo">Título</label>
        						<input class="form-control" name="titulo" id="titulo" type="text" value="<?php echo $titulo; ?>"/>
        					</div>	
        
        					<div class="form-group">
        						<label for="estado">Estado</label>
        						<select class="form-control" name="estado" id="estado" >
        							<option value="0" <?php $estado==0 ? "selected" : ""; ?> >Borrador</option>
        							<option value="1" <?php $estado==1 ? "selected" : ""; ?>>Publicada</option>
        						</select>
        					</div>
        
        					<div class="form-group">
        						<label for="autor">Autor</label>
        						<input class="form-control" name="autor" id="autor" type="text" value="<?php echo $autor; ?>"/>
        					</div>	        
        
        					<div class="form-group">
        						<label for="fecha">Fecha de publicación</label>                  
        							<input class="form-control" type="text" name="fecha" id="fecha" style="color:black" 
        								value="<?php echo $fecha; ?>" readonly>
            						<!--Script para datepicker-->
            						<script type="text/javascript">
            							$(document).ready(function () {
            							    $( "#fecha" ).datepicker({
            							        showOn: "button",
            							        buttonImage: "/images/calendar.gif",
            							        buttonImageOnly: true,
            							        buttonText: "Seleccionar fecha",
                                                dateFormat: "dd/mm/yy"
            							      });
            							});
            						</script>
        					</div>	

        					<div class="form-group">
        						<label for="texto">Texto</label>
        						<textarea class="form-control mceEditor" rows="8" name="texto" id="texto" >
        							<?php echo $texto; ?>
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
                                        selector: "textarea#texto",
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

        					<br>
                            <button type="submit" class="btn btn-primary">Guardar</button>
        					<button type="button" class="btn btn-default" onclick="window.location='/adm';">Cancelar</button>		
                        </form>
        
        
                    </div>    
                </div>
            </div>
        </div>
    </div>


<!-- ---------------------------------------------------------------------- -->
	


</body>

<!--   Core JS Files  
<script src="assets/js/core/jquery.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>  -->
<script src="/adm/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/adm/assets/js/now-ui-dashboard.js?v=1.0.1"></script>

</html>
