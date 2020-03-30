<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/article_model.php';  
    
    $result = getMonumento($_GET['id']);
    if(mysqli_num_rows($result)==0){
        header('location: /');
    }
    
    $monumento = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html>
	<head>
	    <title>Sitio</title>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- CSS 
	    <link rel="stylesheet" href="css/reset.css">
	    <link rel="stylesheet" href="css/theme.css">  -->
	    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
        <!-- jQuery -->
        <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>        
        <!-- Para el multimedia TODO -->
            <!-- Bxslider -->
            <link rel="stylesheet" href="plugins/bxslider-4-4.2.12/jquery.bxslider.css"/>
            <script src="plugins/bxslider-4-4.2.12/jquery.bxslider.min.js"></script>    
            <!-- otros -->
    	    <script src="/js/jssor.slider.mini.js"></script>
    	    <script src="/plugins/galleria/galleria-1.5.7.min.js"></script>
        <!-- Own -->
        <link rel="stylesheet" href="css/article.css">
    	<link rel="stylesheet" href="/css/hero2.css?v=4"> 
        <!-- Own -->
        <link rel="stylesheet" href="/css/homepage.css">       
        
        <meta property="og:url"                content="http://mapeo.memorialparalaconcordia.org/presentacion2.php?id=<?php echo $_GET['id'];?>" />
        <meta property="og:type"               content="article" />
        <meta property="og:title"              content="<?php echo $monumento['titulo']; ?>" />
        <meta property="og:description"        content="<?php echo $monumento['descripcion_corta']; ?>" />
        <meta property="og:image"              content="http://mapeo.memorialparalaconcordia.org/multimedia/<?php echo $monumento['foto_oficial']; ?>" />         
	</head>
	<body>
	
        <?php include 'include/header.php'; ?>
        
        <main>
            
            <div class="container-fluid encabezado">
                <div class="row align-items-center"> 
                    <div class="col-md-6" style="padding-left: 3rem;">
                        <h1 class="titulo"><span>&nbsp;<?php echo $monumento['titulo']; ?>&nbsp;</span></h1>
                        <p class="descripcion-corta"><span>&nbsp;<?php echo $monumento['descripcion_corta']; ?>&nbsp;</span></p>                       
                    </div>                
                    <div class="col-md-6">
                        <img class="main-photo" src="/multimedia/<?php echo $monumento['foto_oficial']; ?>" />
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">     
                  <div class="col-lg-8">
                        <div class="general-info descripcion panel" style="margin-bottom: 2rem;">
                        	<?php echo htmlspecialchars_decode($monumento['descripcion']) ?>    
                        </div>
                    
                        <div class="multimedia panel">
                            <h1>Multimedia</h1>
                            <?php
                                //creacion de slider imagenes
                                $existence=False; 
                                $contimg=0;
                                $result=getMultimediaMonumento($monumento['id_monumento']);
                                while($row=mysqli_fetch_assoc($result)){    
                                    $titulo="";
                                    if($row['titulo']!=""){
                                        $titulo.='Titulo: '.$row['titulo'].'<br>';
                                    }
                                    if($row['autor']!=""){
                                        $titulo.='Autor: '.$row['autor'].'<br>';
                                    }
                                    if($row['fuente']!=""){
                                        $titulo.='Fuente: '.$row['fuente'].'<br>';
                                    }
                                    if(($existence==False)&&($row['tipo']=='imagen')){
                                        echo '<h2 id="fotos">Fotografías</h2><div class="fotos galleria" >';
                                        echo '<img class="img-gal" src="multimedia/'.$row['direccion_archivo'].'" data-big="multimedia/'.$row['direccion_archivo'].'"';
                                        if($titulo!=""){
                                            echo ' data-title="'.$titulo.'">'   ;
                                        }
                                        echo '</img>';
                                        $existence=True;
                                    }
                                    else if(($existence==True)&&($row['tipo']=='imagen')){
                                        echo '<img class="img-gal" src="multimedia/'.$row['direccion_archivo'].'" data-big="multimedia/'.$row['direccion_archivo'].'"';
                                        if($titulo!=""){
                                            echo ' data-title="'.$titulo.'">';
                                        }
                                        echo '</img>';
                                    }
                                    ++$cont;
                                    ++$contimg;
                                }
                                if($existence){
                                    echo '</div>';
                                }
                                //creacion de slider audio
                                $existence=False; 
                                $result=getMultimediaMonumento($monumento['id_monumento']);
                                while($row=mysqli_fetch_assoc($result)){    
                                    if(($existence==False)&&($row['tipo']=='audio')){
                                        echo '<h2 id="audios">Audios</h2><div class="audios panel"><ul class="bxslider">';
                                        echo '<li>'.$row['link'].'<span>autor: '.$row['autor'].'<br>fuente: '.$row['fuente'].'</span></li>';
                                        $existence=True;
                                    }
                                    else if(($existence==True)&&($row['tipo']=='audio')){
                                        echo '<li>'.$row['link'].'<span>autor: '.$row['autor'].'<br>fuente: '.$row['fuente'].'</span></li>';
                                    }
                                    ++$cont;
                                }
                                if($existence){
                                    echo '</ul></div>';
                                }
                                //creacion de slider video
                                $existence=False; 
                                $result=getMultimediaMonumento($monumento['id_monumento']);
                                while($row=mysqli_fetch_assoc($result)){    
                                    if(($existence==False)&&($row['tipo']=='video')){
                                        echo '<h2 id="video">Videos</h2><div class="videos panel"><ul class="bxslider">';
                                        echo '<li>'.$row['link'].'<span>autor: '.$row['autor'].'<br>fuente: '.$row['fuente'].'</span></li>';
                                        $existence=True;
                                    }
                                    else if(($existence==True)&&($row['tipo']=='video')){
                                        echo '<li>'.$row['link'].'<span>autor: '.$row['autor'].'<br>fuente: '.$row['fuente'].'</span></li>';
                                    }
                                    ++$cont;
                                }
                                if($existence){
                                    echo '</ul></div>';
                                }
                            ?>
                        </div>                  
                  
                  </div>                               
                  <div class="col-lg-4">
                    <div class="datos" style="background-color: #dff1fc; padding: 15px;">
                    <h4>Ubicación</h4>
                    <input id="latitud" name="latitud" type="hidden" value="<?php echo $monumento['latitud']; ?>">
                    <input id="longitud" name="longitud" type="hidden" value="<?php echo $monumento['longitud']; ?>">
                  	<!-- MAPA -->
        			<div id="mapa" style="width:100%; height: 400px;"></div>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo $monumento['latitud']; ?>,<?php echo $monumento['longitud']; ?>" target="_blank">¿Cómo llegar?</a>
                    <br />
                    <br />
                    <?php if(!empty($monumento['municipio'])) { ?>
        			<div class="form-group">
        				<label for="municipio">Municipio</label>
        				<input type="text" readonly class="form-control-plaintext" id="municipio" value="<?php echo $monumento['municipio']; ?>">
        			</div>
        			<?php } ?>
        			<?php if(!empty($monumento['departamento'])) { ?>
        			<div class="form-group">
        				<label for="departamento">Departamento</label>
        				<input type="text" readonly class="form-control-plaintext" id="departamento" value="<?php echo $monumento['departamento']; ?>">
        			</div>
        			<?php } ?>
        			<?php if(!empty($monumento['direccion'])) { ?>
        			<div class="form-group">
        				<label for="direccion">Dirección</label>
        				<input type="text" readonly class="form-control-plaintext" id="direccion" value="<?php echo $monumento['direccion']; ?>">
        			</div>
        			<?php } ?>
        			<?php if(!empty($monumento['ubicacion'])) { ?>
        			<div class="form-group">
        				<label for="ubicacion">Ubicación exacta</label>
        				<input type="text" readonly class="form-control-plaintext" id="ubicacion" value="<?php echo $monumento['ubicacion']; ?>">
        			</div>
        			<?php } ?>
        			<?php if(!empty($monumento['como_llegar'])) { ?>
        			<div class="form-group">
        				<label for="como_llegar">Indicaciones para llegar</label>
        				<input type="text" readonly class="form-control-plaintext" id="como_llegar" value="<?php echo $monumento['como_llegar']; ?>">
        			</div>
        			<?php } ?>
        			<?php if(!empty($monumento['requisitos'])) { ?>
        			<div class="form-group">
        				<label for="requisitos">Requisitos para acceder al sitio</label>
        				<input type="text" readonly class="form-control-plaintext" id="requisitos" value="<?php echo $monumento['requisitos']; ?>">
        			</div>
        			<?php } ?>
                    <hr/>
                    <h4>Información general</h4>
                    <?php if(!empty($monumento['tipo_monumento'])) { ?>
                    <div class="form-group">
                        <label for="tipo_sitio">Tipo de sitio de memoria</label>
                        <input type="text" readonly class="form-control-plaintext" id="tipo_sitio" value="<?php echo $monumento['tipo_monumento']; ?>">
                    </div>
                    <?php } ?>
                    <?php if(!empty($monumento['estado_sitio'])) { ?>                    
                    <div class="form-group">
                        <label for="estado_sitio">Estado del sitio</label>
                        <input type="text" readonly class="form-control-plaintext" id="estado_sitio" value="<?php echo $monumento['estado_sitio']; ?>">
                    </div> 
                    <?php } ?>
                    <?php if(!empty($monumento['fecha_creacion'])) { ?>    
                    <div class="form-group">
                        <label for="fecha_creacion">Fecha de creación</label>
                        <input type="text" readonly class="form-control-plaintext" id="fecha_creacion" value="<?php echo $monumento['fecha_creacion']; ?>">
                    </div> 
                    <?php } ?>
                    <?php if(!empty($monumento['construccion_monumento'])) { ?>    
                    <div class="form-group">
                        <label for="constructor">Persona/organización que construyó el sitio</label>
                        <input type="text" readonly class="form-control-plaintext" id="constructor" value="<?php echo $monumento['construccion_monumento']; ?>">
                    </div> 
                    <?php } ?>
                    <?php if(!empty($monumento['apoyo_monumento'])) { ?>    
                    <div class="form-group">
                        <label for="apoyo">Organización/institución que financió el sitio</label>
                        <input type="text" readonly class="form-control-plaintext" id="apoyo" value="<?php echo $monumento['apoyo_monumento']; ?>">
                    </div> 
                    <?php } ?>
                    <?php if(!empty($monumento['nombre_organizacion'])) { ?>    
                    <div class="form-group">
                        <label for="responsable">Organización responsable del sitio</label>
                        <input type="text" readonly class="form-control-plaintext" id="responsable" value="<?php echo $monumento['nombre_organizacion']; ?>">
                    </div> 
                    <?php } ?>
                    <?php if(!empty($monumento['actividades'])) { ?>    
                    <div class="form-group">
                        <label for="actividades">Actividades</label>
                        <input type="text" readonly class="form-control-plaintext" id="actividades" value="<?php echo $monumento['actividades']; ?>">
                    </div> 
                    <?php } ?>
                    <?php if(!empty($monumento['artista'])) { ?>    
                    <div class="form-group">
                        <label for="artista">Artista que realizó el sitio de memoria</label>
                        <input type="text" readonly class="form-control-plaintext" id="artista" value="<?php echo $monumento['artista']; ?>">
                    </div> 
                    <?php } ?>
                    <hr/>
                    <h4>Información de las víctimas</h4>
                    <?php if(!empty($monumento['evento'])) { ?>    
                    <div class="form-group">
                        <label for="municipio">Tipo de delito</label>
                        <input type="text" readonly class="form-control-plaintext" id="municipio" value="<?php echo $monumento['evento']; ?>">
                    </div> 
                    <?php } ?>
                    <?php if(!empty($monumento['periodo_estatal'])) { ?>    
                    <div class="form-group">
                        <label for="municipio">Período de gobierno</label>
                        <input type="text" readonly class="form-control-plaintext" id="municipio" value="<?php echo $monumento['periodo_estatal']; ?>">
                    </div>    
                    <?php } ?>
                    <?php if(!empty($monumento['fecha_conmemoracion'])) { ?>                     
                    <div class="form-group">
                        <label for="municipio">Fecha de conmemoración del hecho</label>
                        <input type="text" readonly class="form-control-plaintext" id="municipio" value="<?php echo $monumento['fecha_conmemoracion']; ?>">
                    </div> 
                    <?php } ?>
                    <?php 
                        $victimas = getPersonasMonumento($_GET['id']); 
                        
                        $numVictimas = mysqli_num_rows($victimas);
                        
                        if($numVictimas > 0) { ?>

                            <label>Nombres</label>
                            <ul class="list-group">

                            <?php while($victima = mysqli_fetch_array($victimas)) { ?>
                                
                                <li class="list-group-item"><?php echo $victima['nombre']; ?></li>
                                
                            <?php } ?>
                            </ul>
                    <?php } ?>
                    </div>
                  </div><!-- /.col-lg-4 -->
                </div><!-- /.row -->  

                
            </div>
        
        </main>

        <div class="boton-fb">
            <div>
                <a href="https://www.facebook.com/sharer/sharer.php?u=http://mapeo.memorialparalaconcordia.org/presentacion2.php?id=<?php echo $_GET['id'];?>" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=http://mapeo.memorialparalaconcordia.org/presentacion2.php?id=<?php echo $_GET['id'];?>', 'newwindow', 'width=300, height=250'); return false;">
                    <img src="/images/f-ogo_RGB_HEX-58.png" aria-hidden="true" />
                </a>
            </div>
            <div style="margin-top: 0.2rem">
                <a href="https://twitter.com/home?status=http%3A//mapeo.memorialparalaconcordia.org/presentacion2.php?id=<?php echo $_GET['id'];?>" onclick="window.open('https://twitter.com/home?status=http%3A//mapeo.memorialparalaconcordia.org/presentacion2.php?id=<?php echo $_GET['id'];?>', 'newwindow', 'width=300, height=250'); return false;">
                    <img src="/images/Twitter_Logo_WhiteOnBlue58.png" aria-hidden="true" />
                </a>
            </div>
        </div>  

        <br />
        <br />
        <br />

        <footer>
        <?php include 'include/footer.php'; ?>
        </footer>  

        <script>
            $(document).ready(function(){
                $('.bxslider').bxSlider();
            });

            // Funcion para anchors
            function offsetAnchor() {
                if(location.hash.length !== 0) {
                    window.scrollTo(window.scrollX, window.scrollY - 80);
                }
            }
            $(window).on("hashchange", function () {
                offsetAnchor();
            });

            window.setTimeout(function() {
                offsetAnchor();
            }, 1);

            Galleria.loadTheme('plugins/galleria/themes/classic/galleria.classic.min.js');
            Galleria.configure({
                transition: 'fade',
                fullscreenDoubleTap: true               
            });
            Galleria.run('.galleria', {

                extend: function(options) {

                    Galleria.log(this) // the gallery instance
                    Galleria.log(options) // the gallery options

                    // listen to when an image is shown
                    this.bind('image', function(e) {

                        Galleria.log(e) // the event object may contain custom objects, in this case the main image
                        Galleria.log(e.imageTarget) // the current image

                        // lets make galleria open a lightbox when clicking the main image:
                        $(e.imageTarget).click(this.proxy(function() {
                           this.openLightbox();
                        }));
                    });
                }
            });
        </script>

	</body>
	

	<script src="/presentacion2.js?v=323"></script>	
    <!-- Google Maps -->
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=LLAVE_GOOGLE_MAPS&callback=initMap">
    </script>		
	
</html>
