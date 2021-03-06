<?php //Verificar que sea admin
include "../../include/globals.php";
session_start();
if(!in_array(1, $_SESSION['rol'])){
	header("location: http://".$root_path."adm/login.php");
}
?>
<html>
<head>
	<title>Crear Pagina</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../../css/bootstrap.css">
	<link rel="stylesheet" href="../../css/dashboard.css">	
	<script src="../../js/bootstrap.js" type="text/javascript"></script>	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
	
</head>
<body>

    <?php
        include '../include/header.php';
    ?>
	
	<div class="container-fluid">

		<div class="row">
		
			<!-- Sidebar: -->
			<?php
				include '../include/sidebar.php';
			?>
		
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            
				<form name="signup" method="post"  action="gestionPaginas.php?op=1">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input class="form-control" name="nombre" id="nombre" type="text" />
                    </div>    
                    <div class="form-group">
                        <label for="contenido">Contenido</label>
                        <textarea class="form-control mceEditor" rows="8" name="contenido" id="contenido" ></textarea>
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
                                    selector: "textarea#contenido",
                                    language:"es_MX",
                                    language_url: "/js/tinymce/langs/es_MX.js",
                                    plugins: [
                                        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                                        "media nonbreaking save table contextmenu directionality",
                                        "template paste textcolor colorpicker textpattern imagetools"
                                    ],
                                    toolbar1: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                                    toolbar2: "preview media | forecolor | code",
                                    templates: [
                                        {title: 'Test template 1', content: 'Test 1'},
                                        {title: 'Test template 2', content: 'Test 2'}
                                    ]

                            });
                        </script>
					</div>
                    <div class="form-group">
                        <p><label>Estado de la página</label></p>
                        <label class="radio-inline">
                            <input type="radio" name="estado" value="activo" CHECKED>Activo
                        </label>
				     	<label class="radio-inline">
                            <input type="radio" name="estado" value="inactivo">Inactivo
                        </label>
				     	<label class="radio-inline">
                            <input type="radio" name="estado" value="espera">En espera
                        </label>
					</div>
					<button type="submit" class="btn btn-primary">Crear Pagina</button>
					<button type="submit" class="btn btn-default" onclick="window.location='gestionPaginas.php';">Cancelar</button>
				</form>
				
				</div>
				
				
		</div>
		
	</div>
</body>
</html>
