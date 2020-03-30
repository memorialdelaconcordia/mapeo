<?php
    session_start();
    if(!isset($_SESSION['myusername'])) {
        header("location: login.php");
    }
    
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';
    
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if(isset($_POST["texto"]) && isset($_POST["frmname"])) {

            $texto = $_POST["texto"];
            $nombrePagina = $_POST["frmname"];
            
            //SE CREA EL SITIO EN LA BASE DE DATOS:
            //Retorna el id del sitio si se realizó todo bien. Retorna false si no.
            $result = guardarPagina(
                $nombrePagina,
                $texto
                );
            
            if($result === false) {
                
                $errores[] = "Ocurrió un error al guardar en base de datos.";
                
            } else {
                
                $msg = "Información guardada con éxito.";
            }
            
        }

    } 

    $query = "SELECT * FROM static_page WHERE id_static_page IN (1,3,4)";
    
    $result = db_query($query);
    if(!$result) {
        // TODO
    } else {
        /* fetch associative array */
        while ($row = $result->fetch_assoc()) {
            if($row["nombre"] === "Proyecto") {
                $textoProyecto = $row["contenido"];
            } else if ($row["nombre"] === "FAQ") {
                $textoFaq = $row["contenido"];
            } else if ($row["nombre"] === "Info") {
                $textoInfo = $row["contenido"];
            }
        }
    }
    
    
    function guardarPagina($nombrePagina,
        $texto
        ) {
            
        $connection = db_connect();
           
        $query = "UPDATE static_page SET contenido = '".mysqli_real_escape_string($connection, $texto)."' WHERE nombre = '".mysqli_real_escape_string($connection, $nombrePagina)."';";
        
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
    <title>Editar Información</title>
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
        			
                            if (isset($msg)) { ?>
                                <div class="alert alert-success" role="alert">
                                  <?php echo $msg; ?>
                                </div>                                
                            <?php } ?>                        
                    
        				<form name="proyecto" id="proyecto" method="post" action="informacion.php" enctype="multipart/form-data" >
                            <input type="hidden" name="frmname" value="Proyecto"/>
                            
                            <h2>Proyecto:</h2>
                            
        					<div class="form-group">
        						<textarea class="form-control mceEditor1" rows="20" name="texto" id="textoProyecto" style="height: 700px;">
        							<?php echo $textoProyecto; ?>
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
                                        editor_selector : "mceEditor1",
                                        selector: "#textoProyecto",
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
        
                        <hr class="featurette-divider">  
        
                        <form name="faq" id="faq" method="post" action="informacion.php" enctype="multipart/form-data" >
                            <input type="hidden" name="frmname" value="FAQ"/>
                            
                            <h2>FAQ:</h2>
                            
                            <div class="form-group">
                                <textarea class="form-control mceEditor2" rows="20" name="texto" id="textoFaq" style="height: 700px;">
                                    <?php echo $textoFaq; ?>
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
                                        editor_selector : "mceEditor2",
                                        selector: "#textoFaq",
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
                        
                        <hr class="featurette-divider">  
                        
                        <form name="info" id="info" method="post" action="informacion.php" enctype="multipart/form-data" >
                            <input type="hidden" name="frmname" value="Info"/>
                            
                            <h2>Información:</h2>
                            
                            <div class="form-group">
                                <textarea class="form-control mceEditor3" rows="20" name="texto" id="textoInfo" style="height: 400px;">
                                    <?php echo $textoInfo; ?>
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
                                        editor_selector : "mceEditor3",
                                        selector: "#textoInfo",
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
