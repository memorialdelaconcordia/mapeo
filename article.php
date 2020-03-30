<?php
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'].'/include/article_model.php';  
	
	$result=getMonumento($_GET['id']);
	if(mysqli_num_rows($result)==0){
	    //TODO ¿Esto qué es?
		header('location: /');
	}


	$monumento=mysqli_fetch_assoc($result);
	$noticias=getNoticiasMonumento($_GET['id']);
	$tipo_monumento='pendiente';
	$tipo_evento='pendiente';
	$personas_monumento=getPersonasMonumento($_GET['id']);
	$camposAdicionales=getCampoValorAdicional($_GET['id']);
	if(isset($monumento['id_tipo_monumento'])){
		$result=getTipoMonumento($monumento['id_tipo_monumento']);
		$resultrow=mysqli_fetch_assoc($result);
		if((strcmp($monumento['estado_actual'],'Publicado')!=0)){
			if(((!in_array(2, $_SESSION['rol']))&&(!in_array(2, $_SESSION['rol'])))){
			    //TODO ¿Esto qué es?
				header('location:http://mapeo.memorialparalaconcordia.org?error=2');
			}
		}
		$tipo_monumento=$resultrow['tipo_monumento'];
	}
	if(isset($monumento['id_tipo_evento'])){
		$result=getTipoEvento($monumento['id_tipo_evento']);
		$resultrow=mysqli_fetch_assoc($result);
		$tipo_evento=$resultrow['evento'];
	}
	if(isset($monumento['foto_oficial'])&&($monumento['foto_oficial']!='')){
		$monumento['foto_oficial']=$monumento['foto_oficial'];
	}
	else{
		$monumento['foto_oficial']=mediapath().'default.JPG';	
	}
	if($monumento['titulo']==''){
		$monumento['titulo']='pendiente';
	}
	if($monumento['descripcion']==''){
		$monumento['descripcion']='Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente ipsam culpa amet consequuntur modi itaque voluptatum quaerat neque dicta rem expedita perferendis nostrum magni at eum dignissimos illum, ducimus id.';
	}
	if($monumento['identificador']==''){
		$monumento['identificador']='pendiente';
	}
?>
<!DOCTYPE html>
<html>
	<head>
	    <title><?php echo $monumento['titulo']; ?></title>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <!-- CSS 
	    <link rel="stylesheet" href="css/reset.css">
	    <link rel="stylesheet" href="css/theme.css">  -->
        <link rel="stylesheet" href="css/article.css">
        <!-- jQuery -->
        <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>        
        <!-- Bxslider -->
        <link rel="stylesheet" href="plugins/bxslider-4-4.2.12/jquery.bxslider.css"/>
        <script src="plugins/bxslider-4-4.2.12/jquery.bxslider.min.js"></script>    

		<!-- TODO -->   
	    <script src="https://maps.googleapis.com/maps/api/js?key=LLAVE_GOOGLE_MAPS"></script>
	    <script src="/js/jssor.slider.mini.js"></script>
	    <script src="/plugins/galleria/galleria-1.5.7.min.js"></script>
        <!-- Own -->
    	<link rel="stylesheet" href="/css/homepage.css"> 
	</head>
	<body>
	
		<?php include 'include/header.php'; ?>
		
		<article class="articulo">
            <div class="buttons-right">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>', 'newwindow', 'width=300, height=250'); return false;" ><img src="/images/Logos/Facebook.png" alt=""></a>
            	<a href="https://twitter.com/home?status=<?php echo "http%3A//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" onclick="window.open('https://twitter.com/home?status=<?php echo "http%3A//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>', 'newwindow', 'width=300, height=250'); return false;"><img src="/images/Logos/Twitter.png" alt=""></a>
            </div>
			<div class="titulo-container">
				<div class="panel-titulo">
					<img class="foto-oficial" src="/multimedia/<?php echo $monumento['foto_oficial']; ?>"></img> 
					<span class="info">
						<h1><?php echo $monumento['titulo'] ?></h1>
					</span>
				</div>
			</div>
			<div class="panel-left">
				<div class="general-info">
						<?php 
						$existImage = False;
						$existVideo = False;
						$existAudio = False;
						$result=getMultimediaMonumento($monumento['id_monumento']);
						while($row=mysqli_fetch_assoc($result)){	
							if($row['tipo']=='imagen'){
								$existImage = True;
							}
							if($row['tipo']=='video'){
								$existVideo = True;
							}
							if($row['tipo']=='audio'){
								$existAudio = True;
							}

						}
						
						if ($existAudio OR $existVideo OR $existImage){
						echo '<div class="multimedia-section"><div class="multimedia-btn"> ';		
							if ($existAudio){
								echo '<a href="#audios" class="btn btn-anchor">Audios</a>';
							}
							if ($existVideo){
								echo '<a href="#video" class="btn btn-anchor">Videos</a>';
							}
							if ($existImage){
								echo '<a href="#fotos" class="btn btn-anchor">Fotos</a>';
							}
						echo '</div><span>Material multimedia</span></div>';
						}
					?>

					<div class="descripcion panel">
						<!-- <h2>Descripción: </h2> -->
						<?php echo htmlspecialchars_decode($monumento['descripcion']) ?><br><br>
					</div>
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
								echo '<h2 id="fotos">Fotos</h2><div class="fotos galleria" >';
								echo '<img class="img-gal" src="multimedia/'.$row['direccion_archivo'].'" data-big="multimedia/'.$row['direccion_archivo'].'"';
								if($titulo!=""){
									echo ' data-title="'.$titulo.'">'	;
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
			<div class="panel-right panel">
				<div class="panel-lugar panel">
					<div class="panel-mapa" id="mapa"></div>
				</div>
				<div class="panel-como-llegar panel">
					<h2>Dirección: </h2>
					<p class="direccion-text"><?php echo $monumento['direccion'] ?></p>
					<h2>Ubicación: </h2>
					<p class="ubicacion-text"><?php echo $monumento['ubicacion'] ?></p><br>
					<h2>Cómo llegar: </h2>
					<p class="como-llegar-text"><?php echo $monumento['como_llegar'] ?></p>
				</div>
				<div class="detalles-panel panel">
					<h2>Tipo de sitio de memoria: </h2>
					<p class="info-text"><?php echo $tipo_monumento ?></p><br>
					<h2>Estado sitio de memoria: </h2>
					<p class="info-text"><?php echo $monumento['estado_sitio'] ?></p><br>
					<h2>Tipo de delito: </h2>
					<p class="info-text"><?php echo $tipo_evento ?></p><br>
					
					<?php 
						while ($row=mysqli_fetch_assoc($camposAdicionales)) {
							echo '<h2>'.$row['campo'].': </h2><p class="info-text">'.$row['valor'].'</p><br>';
						}
					?>
				</div>
				<?php 
					$existence=False;
					while($row=mysqli_fetch_assoc($noticias)){
						if($existence==False){
							echo '<div class="panel-noticias panel"><h2>Más información</h2><ul class="noticias">';
							$existence=True;
						}
						echo '<li class="noticia panel"><h1>'.$row['titulo'].'</h1><h2>Fuente:'.$row['fuente'].'</h2><a href="'.$row['link'].'"><strong>Leer nota</strong></a></li>';
					}
					if($existence){
						echo '</ul></div>';
					}
					$existence=False;
					$renderButton=False;
					if(mysqli_num_rows($personas_monumento)>10){
						$renderButton=True;
					}
					$cant_personas=0;
					while($row=mysqli_fetch_assoc($personas_monumento)){
						if($existence==False){
							echo '<div class="panel-personas panel"><br><h2>Nombres de las víctimas fallecidas</h2><br><ul class="personas">';
							$existence=True;
						}
						echo '<li class="persona"><h1>'.$row['nombre'].'</h1></li>';
						$cant_personas++;
						if($cant_personas==10){
							echo '<li class="persona"><h1>...</h1></li>';
							break;
						}
					}
					if($existence){
						echo '</ul>';
						$renderButton=True;
						if($renderButton){
							echo '<a href="/victimas.php?id='.$_GET['id'].'"" class="btn">Ver todas</a>';
						}
						echo '</div>';
					}
				?>
				<?php // Monumentos relacionados por tipo de monumentos Agregada posteriormente por SGG 
					$monumentos_relacionados=getMonumentosRelacionados($_GET['id']);
					if(mysqli_num_rows($monumentos_relacionados)>0)
					{
						echo '<div class="panel-noticias panel"><h2>Sitios relacionados</h2>'; 
						while($row=mysqli_fetch_assoc($monumentos_relacionados))
						{ 
							$result_img=getMonumento($row['id_monumento']);
							$row_img=mysqli_fetch_assoc($result_img);
							echo '<div class="sitios-recomendados"><img class="foto-recomendada" src=/multimedia/'.$row_img['foto_oficial'].'></img>';
							echo '<div class="sitios-recomendados-text">'.$row['titulo'].'<a href="article.php?id='.$row_img['id_monumento'].'">Visitar</a></div></div>';
						}
						echo '</div>';
					}
				?>
			</div>
		</article>
		<script>
			var latBase = <?php echo $monumento['latitud'] ?>;
        	var lngBase = <?php echo $monumento['longitud'] ?>;
        	var myCenter=new google.maps.LatLng(latBase,lngBase);
			function initialize(){
				var mapProp = {
		          	center:myCenter,
		          	zoom:7,
		          	mapTypeId:google.maps.MapTypeId.ROADMAP
        		};
        		map = new google.maps.Map(document.getElementById("mapa"),mapProp);
        		var Latlng = new google.maps.LatLng(<?php echo $monumento['latitud'] ?>,<?php echo $monumento['longitud'] ?>);
				var marker = new google.maps.Marker({
					position: Latlng,
					animation: google.maps.Animation.DROP,
					map: map,               
				});
				map.setOptions({draggable: false, zoomControl: false, scrollwheel: false, disableDoubleClickZoom: true});
			}
			google.maps.event.addDomListener(window, 'load', initialize);

            
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
</html>
