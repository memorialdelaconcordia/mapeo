<?php 
    include( 'include/db.php');
    $result=getPagina($_GET['id']);
    $info=mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $info['nombre'] ?></title>
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
        <!-- Own -->
        <link rel="stylesheet" href="/css/homepage.css">
    </head>
    <body>
        <?php include 'include/header.php'; ?>
        <article class="articulo">
            <div class="panel-content" style="margin-left: 30px; margin-right: 30px; margin-bottom: 40px;">
                <?php 
                    if($info['estado']!='activo'){
                        echo '<p>Pagina no disponible</p>';
                    }
                    else{
                        echo $info['contenido'];
                    }
                ?>
            </div>
        </article>
        <br />
        <br />
        <br />
        <footer>
        <?php include 'include/footer.php'; ?>
        </footer>
    </body>
</html>