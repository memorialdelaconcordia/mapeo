<?php
	//error_reporting(0);
    include "include/globals.php";
    include "include/mapa.php";
    $infoSource=null;
    if(isset($_COOKIE['busqueda'])){
        $infoSource=unserialize($_COOKIE['busqueda']);
    }
    if(isset($_POST['busqueda'])){
        $infoSource = $_POST;
        setcookie('busqueda', serialize($_POST), time()+3600);
    }
    
    if(!empty($_POST['busqueda-simple'])){
        $infoSource['busqueda-simple'] = $_POST['busqueda-simple'];
    }
?>
<html> 
<head>
    <title>Mapeo de la memoria</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- jQuery -->
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <script
      src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
      integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
      crossorigin="anonymous"></script>        
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>        
    <!-- Icons  -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- Bxslider -->
    <link rel="stylesheet" href="plugins/bxslider-4-4.2.12/jquery.bxslider.css"/>
    <script src="plugins/bxslider-4-4.2.12/jquery.bxslider.min.js"></script>    
    <!-- Markerclusterer -->
	<script src="js/markerclusterer.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="/css/homepage.css?v=1">
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>

	<header>
    <?php include 'include/header.php'; ?>
    </header>
    
    <div class="container-fluid">
    
    	<!-- row 1 -->
    	<!-- Búsqueda avanzada -->
        <div class="row row-busqueda-avanzada collapse" id="collapse1">
        	
            <div class="col-xs-12 col-sm-12" id="busqueda">
				<?php include 'include/busqueda.php'; ?>
            </div>

        </div>
        
        <!-- row 2 -->
        <!-- Mapa -->
        <div class="row">

            <?php
                
                if(isset($infoSource)) {
                    $campoa=array();
                    foreach($infoSource as $key => $value){
                        if(strpos($key,'adicional') !== false){
                            $campoa[$key]=$value;
                        }
                    }
                    $result_puntos=busqueda($infoSource,$campoa);
    
                } else {
                    $result_puntos=busqueda(null,null);
                }
                
                $json_puntos = array();
                while ($row = mysqli_fetch_assoc($result_puntos)){
                    $json_puntos[] = $row;
                }
    			
                $json_tiposMonumento = array();
                $tipoMonumentos=allTipoMonumentos();
                mysqli_data_seek($tipoMonumentos, 0);
                while ($row = mysqli_fetch_assoc($tipoMonumentos)) {
                    $json_tiposMonumento[]=$row;
                }
            ?>
        
        	<?php include 'include/mapa2.php'; ?>

			<!-- Una sola columna -->
            <div class="col-xs-12 col-sm-12" id="content">
                
                <!-- Mapa -->
                <div id="mapa">
            	</div>
				
				<!-- Tipos de sitios 
    			<span data-toggle="tooltip" title="Mostrar los tipos de sitio visibles en el mapa.">
    				<button type="button" class="btn btn-mostrar" data-toggle="collapse" data-target="#tipos-sitio">
    					<i class="fa fa-info fa-lg"></i>
    				</button>
    			</span>
    			
    			<div id="tipos-sitio" class="collapse leyenda-marcadores">
                </div> -->

			</div> 
		
		</div>  
		  

        <!-- row 3 -->
        <!-- Lugares: -->
        <div class="row" style="margin-bottom: 15px;">  

			<!-- Lugares -->
			<div class="col">
			
				<div id="titulo-ubicaciones-cercanas"></div>
				<div class="row" id="ubicaciones-cercanas"></div>
			
			</div>
            <div class="col-md-auto d-flex align-items-center">            
                
                <a href="/explorar.php" data-toggle="tooltip" title="Ver más lugares..."><img class="" src="/images/mas_lugares.png" alt="" width="100" height="100"></a>
                
            </div>

        </div>
       
        <!-- Pestaña de información -->
        <div class="info-window">
            <div class="btn btn-info" id="btn_info">
                <i class="fa fa-info fa-2x" aria-hidden="true"></i>
            </div>
        </div>    
        
        <div class="card info-window-main" id="info">
          <div class="card-body" style="text-align: justify; ">
            <?php echo getInfo(); ?>
          </div>
        </div>
        <!-- Fin - Pestaña de información -->

       <!-- Forma - Sugerir sitios de memoria  -->
       <div class="modal fade" id="modalContacto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
         <div class="modal-dialog">
           <div class="modal-content">
             <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <!-- <h4 class="modal-title" id="myModalLabel">Sugiere un sitio de memoria</h4> -->
             </div>
             <div class="modal-body">
               <form class="form-group" name="contact-us" id="contact-us" method="post" action="contact-us.php">
                            <p>Si usted conoce algún sitio de memoria no reportado en este sitio, y desea que se publique, favor de llenar la siguiente información:</p>
                            <br><h3>Sobre el sitio</h3>
                            <br><label for="sitio-titulo">Título</label><input class="form-control" name="titulositio" id="titulositio" type="text" />
                            <br><label for="inputSitio">Tipo de Delito</label>
                            <select class="form-control" name="id_tipo_evento" id="id_tipo_evento" style="width:90%; border-radius:4px;">
                                <?php
                                    $result=allEventos();
                                    while($row=mysqli_fetch_array($result)){
                                        if($row['estado']==1){
                                            echo '<option value='.$row["evento"].">".$row["evento"]."</option>";    
                                        }
                                    }
                                ?>
                            </select>
                            <br><label for="inputSitio">Tipo de Sitio de Memoria</label>
                            <select class="form-control" name="id_tipo_monumento" id="id_tipo_monumento">
                                <?php
                                    $result=allTipoMonumentos();
                                    while($row=mysqli_fetch_array($result)){
                                        if($row['estado']==1){
                                            echo '<option value='.$row["tipo_monumento"].">".$row["tipo_monumento"]."</option>";
                                        }
                                    }
                                ?>
                            </select>
                           <br><label for="inputSitio">Municipio</label>
                           
                            <br>
                           <select class="municipiosContact form-control" name="id_municipio" id="id_municipio">
                                 <?php
                                   $result=allMunicipios();
                                   while($row=mysqli_fetch_array($result)){
                                       echo '<option value="'.$row["departamento"]." - ".$row["municipio"].'">'.$row["departamento"]." - ".$row["municipio"]."</option>";
                                   }
                               ?>
                           </select>
                           <br>
                           <br><h3>Información de contacto</h3>
                           <br><label for="inputPersona">Nombre: </label>
                           <br><textarea name="nombre_contacto" id="nombre_contacto" cols="30" rows="1"> </textarea>
                           <br><label for="inputPersona">Número de teléfono: </label>
                           <br><textarea name="tel_contacto" id="tel_contacto" cols="30" rows="1"> </textarea>
                           <br><label for="inputPersona">Correo electrónico: </label>
                           <br><textarea name="email_contacto" id="email_contacto" cols="30" rows="1"> </textarea>



                   <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                       <button type="submit" class="btn btn-primary">Sugerir sitio</button>
                   </div>
               </form>
               
               <script type='text/javascript'>
                   /* attach a submit handler to the form */
                   $("#contact-us").submit(function(event) {

                   /* stop form from submitting normally */
                       event.preventDefault();

                   /* get some values from elements on the page: */
                       var $form = $( this ),
                       url = $form.attr( 'action' );

                       /* Send the data using post */
                       var posting = $.post( url, { titulositio: $('#titulositio').val(), id_tipo_monumento: $('#id_tipo_monumento').val(), id_tipo_evento: $('#id_tipo_evento').val(), id_municipio: $('#id_municipio').val(), nombre_contacto: $('#nombre_contacto').val(), tel_contacto: $('#tel_contacto').val(), email_contacto: $('#email_contacto').val() } );

                       /* Alerts the results */
                       posting.done(function( data ) {
                           alert('Se ha enviado la información y lo contactaremos lo más pronto posible');
                           });
                       });
               </script>
                      
             </div>
           </div>
         </div>
       </div>
       <!-- Fin - Forma sugerir sitios de memoria -->                

    <script>
        $(document).ready(function() {
        // Select2
            $(".filter-delito").select2();
            $(".filter-monumento").select2();
            $(".filter-departamento").select2();
            $(".filter-municipio").select2();
            $(".filter-victimas").select2();
            $(".filter-estado").select2();
            $(".filter-periodo").select2();
            $(<?php
                // Agregando select2 a los campos adicionales
                echo '"';
                $stringAdicionales="";
                foreach ($arrayAdicionales as $k => $v) {
                  $stringAdicionales.= '.'.$v.',';
                }
                $stringAdicionales = trim($stringAdicionales, ",");
                echo $stringAdicionales;
                echo '"';
              ?>
              ).select2();



            //Se muestran los controles de búsqueda en el header:
            var formBusquedaSitios = $('#form-busqueda-sitios');
            formBusquedaSitios.show();          

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
            
        });    

        //Bootstrap tooltips:
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        var infoMostrada = true;
        $(function() {
             $("#btn_info").click(function() {
                 $("#info").toggle("slide");
                 if(!infoMostrada) {
                     $(".info-window").animate({
                         left: '250px'
                     });
                     infoMostrada = true;
                 } else {
                     $(".info-window").animate({
                         left: '0px'
                     });
                     infoMostrada = false;
                 }
                 return false;
             }); 
        });
    </script>

	</div>
    
    <footer>
        <?php include 'include/footer.php'; ?>
    </footer>    

	<!-- Google Maps -->
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=LLAVE_GOOGLE_MAPS&callback=initMap">
    </script>	
</body>
