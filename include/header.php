<nav class="navbar navbar-expand-lg fixed-top navbar-dark" style="background-color: #05253A;">

    <script>

        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        
        
        
        ga('create', 'UA-3047829-59', 'auto');
        
        ga('send', 'pageview');

    </script>

	<!-- Logo -->
	<a class="navbar-brand" href="/">
    	<img src="/images/main_logo.png" width="139" height="80" alt="">
  	</a>
  	
  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    	<span class="navbar-toggler-icon"></span>
  	</button>
  
  	<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
  	
    	<ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/">Inicio</a>
            </li>             
            <li class="nav-item dropdown">
            	<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                	Mapeo
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="/pageview.php?id=1">Proyecto</a>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalContacto">Sugerir un lugar</a>                  
                  <!-- <a class="dropdown-item" href="/estadisticas.php">Estadísticas</a>  -->
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/explorar.php">Explorar Lugares</a>
            </li>              
            <li class="nav-item">
                <a class="nav-link" href="/noticias.php">Noticias</a>
            </li>              
            <li class="nav-item">
                <a class="nav-link" href="/pageview.php?id=4">FAQ</a>
            </li>                
      		<li class="nav-item">
      			<a class="nav-link" href="/contacto.php">Contacto</a>
      		</li>
    	</ul>
    	

            <form class="form-inline my-2 my-lg-0" id="form-busqueda-sitios" style="display: none;" method="POST">
            
              <input class="form-control mr-sm-2" type="search" placeholder="Buscar lugares..." aria-label="Buscar" name="busqueda-simple"
              	value="<?php echo !empty($_POST['busqueda-simple']) ? $_POST['busqueda-simple'] : ''; ?>">
              <div>
                  <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">
                  	<i class="fa fa-search"></i>
                  </button>
              </div>
                <div style="text-align: center; margin-left: 15px;">
                          <a href="/adm/" target="_blank" style="color: #008e96; font-size: 1.5rem;">
                            <i class="fas fa-user"></i>
                          </a> 
                </div>                 
              <!-- <button class="btn btn-outline-secondary my-2 my-sm-0" type="button" data-toggle="collapse" data-target="#collapse1"
              	style="margin-left: 5px;">
              		Búsqueda<br>Avanzada
              </button>  --> 
            </form>	
    	
  	</div>

</nav>

<script>
   $(document).ready(function() {
        //Para marcar la página activa:
        // Get the page URL
    	//var url = window.location.href.split('/');
    	// Get the subdomain
    	//var loc = "/" + url[url.length-1];
    	// Apply active class to the link that matches the subdomain.
    	//$('a[href="' + loc + '"]').addClass('active');
   });
</script>



