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
        <!-- Own -->
    	<link rel="stylesheet" href="/css/hero.css?v=3"> 
	</head>
	<body>
	
        <?php include 'include/header.php'; ?>
        
        <div id="carouselSitioMemoria" class="carousel slide" data-ride="carousel" data-interval="false">
          <ol class="carousel-indicators">
            <li data-target="#carouselSitioMemoria" data-slide-to="0" class="active"></li>
            <li data-target="#carouselSitioMemoria" data-slide-to="1"></li>
            <li data-target="#carouselSitioMemoria" data-slide-to="2"></li>
          </ol>        
          <div class="carousel-inner">
          	<?php 
          	     $active = false;
          	     //TODO Cambiar a solo imágenes:
          	     $multimedia = getMultimediaMonumento2($monumento['id_monumento']);
          	     
          	     for($i = 0; $i < 3; $i++) {
          	         
          	         $row = mysqli_fetch_assoc($multimedia);
          	     
          	         if($row == null) { //No hay más multimedia,
          	             mysqli_data_seek($multimedia, 0); //Iniciar otra vez...
          	             $row = mysqli_fetch_assoc($multimedia);
          	         }

          	         if($row['tipo']=='imagen') { ?>
          	         
          		     	<div class="carousel-item <?php echo $active == false ? 'active' : ''; ?>">
             				<div class="d-block w-100 segmento" 
             				     style="background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url('/multimedia/<?php echo $row['direccion_archivo']; ?>'); height: 100%;">
             				</div>
             				<?php if($i == 0) {?>
                 				<div class="carousel-caption d-none d-md-block">
                                     <h1><?php echo $monumento['titulo']; ?></h1>
                                     <p><?php echo $monumento['descripcion_corta']; ?></p>
                                     <p><a class="enlace-articulo" href="/article.php?id=<?php echo $_GET['id']; ?>">Información completa del sitio</a></p>
                                </div>
                            <?php } elseif ($i == 1) {?>
                            	<div class="carousel-caption d-none d-md-block articulo">
                					<?php echo htmlspecialchars_decode($monumento['descripcion']); ?>
                            	</div>
                            <?php } elseif ($i == 2) {?>
                            
                            <?php }?>
            			</div>
          		
          	<?php       $active = true;
          	         }     
          	     } ?>
          </div>
          <a class="carousel-control-prev" href="#carouselSitioMemoria" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselSitioMemoria" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>          
        </div>

	</body>
</html>
