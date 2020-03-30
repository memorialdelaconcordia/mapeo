<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/noticias_model.php';  
    require_once $_SERVER['DOCUMENT_ROOT'].'/include/pagineo.php';  
    
    //Pagineo:
    
    $sql = getNoticias(-1, -1);
    $total = mysqli_num_rows($sql);
    
    $adjacents = 3;
    $targetpage = "noticias.php"; //your file name
    $limit = 4; //how many items to show per page
    $page = $_GET['page'];
    
    if($page) {
        $start = ($page - 1) * $limit; //first item to display on this page
    } else {
        $start = 0;
    }
    
    /* Setup page vars for display. */
    if ($page == 0) $page = 1; //if no page var is given, default to 1.
    $prev = $page - 1; //previous page is current page - 1
    $next = $page + 1; //next page is current page + 1
    $lastpage = ceil($total/$limit); //lastpage.
    $lpm1 = $lastpage - 1; //last page minus 1
    
    $noticias = getNoticias($start, $limit);
    
    $pagineo = pagineo($targetpage,
        $page,
        $lastpage,
        $lpm1,
        $prev,
        $next,
        $adjacents);
    
?>

<html> 
<head>
    <title>Noticias - Mapeo de la Memoria</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- jQuery -->
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>        
    <!-- CSS -->
    <link href="css/blog-post.css" rel="stylesheet">
    <!-- Own -->
    <link rel="stylesheet" href="/css/homepage.css">
</head>

<body>
	
	<?php include 'include/header.php'; ?>
	
   <!-- Page Content -->
    <div class="container">

      <div class="row">

        <!-- Post Content Column -->
        <div class="col-lg-8">

        <?php while ($noticia = mysqli_fetch_assoc($noticias)): ?>
            <!-- Title -->
            <h1 class="mt-4"><?php echo $noticia['titulo']; ?></h1>

            <!-- Author -->
            <p class="lead">
                por
                <a href="#"><?php echo $noticia['autor']; ?></a>
            </p>

            <hr>

            <!-- Date/Time -->
            <p>Publicado el <?php echo $noticia['fecha']; ?></p>

            <hr>

            <!-- Preview Image 
            <img class="img-fluid rounded" src="http://placehold.it/900x300" alt=""> -->

            <hr>

            <!-- Post Content -->
            <?php echo $noticia['texto']; ?>  
    
            <hr>

        <?php endwhile; ?>

        </div>

        <!-- Sidebar Widgets Column -->
        <div class="col-md-4">

          <!-- Sitios Recientes Widget -->
          <div class="card my-4">
            <h5 class="card-header">Sitios recientes</h5>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <ul class="mb-0">
                  	<?php $sitios = getSitiosRecientes(); 
                  	      while($sitio = mysqli_fetch_assoc($sitios)) { ?>
                  	      	<li>
                      			<a href="/presentacion2.php?id=<?php echo $sitio['id_monumento']; ?>"><?php echo $sitio['titulo']; ?></a>
                    		</li>
                  	<?php } ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <footer>
    <?php include 'include/footer.php'; ?>
    </footer>    

</body>