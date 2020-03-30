<?php

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $token = $_POST['token'];
        $action = $_POST['action'];

        error_log("456456456456");
        error_log($token);
        error_log($action);


        $connection = db_connect();

        $query = "INSERT INTO contacto (nombre, 
                                              email,
                                              telefono,
                                              comentarios) 
                     VALUES ('" . $_POST["nombreApellido"] . "'," . 
                            "'" . $_POST["correoElectronico"] . "'," . 
                            "'" . $_POST["telefono"] . "'," . 
                            "'" . $_POST["comentario"] . "')";
        
        error_log(print_r($query, true));                          
                                  
        $result = mysqli_query($connection, $query);
        
        if ($result) {
            //COMMIT
            mysqli_commit($connection);
            
            $mensajeEnviado = true;
        } else {
            //ROLLBACK
            mysqli_rollback($connection);
        }
    }

?>

<html> 
<head>
    <title>Contacto - Mapeo de la Memoria</title>
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
    <!-- ReCaptcha -->
    <script src="https://www.google.com/recaptcha/api.js?render=LLAVE_GOOGLE_RECAPTCHA"></script>
</head>

<body>
	
	<?php include 'include/header.php'; ?>
	
   <!-- Page Content -->
    <div class="container">

      <div class="row">

        <div class="col-md-12" style="margin-top: 2rem;">

        <?php if (isset($mensajeEnviado) && $mensajeEnviado === true ) { ?>
            <div class="alert alert-success" role="alert">
              Mensaje enviado con éxito.
            </div>                                
        <?php } ?>

        <h3>Contacto</h3>

        <form method="post" name="contact-us" id="contact-us" action="contacto.php">
          <div class="form-group">
            <label for="nombreApellido">Nombre y Apellido</label>
            <input type="text" class="form-control" id="nombreApellido" name="nombreApellido" placeholder="Nombre y Apellido" required>
          </div>        
          <div class="form-group">
            <label for="correoElectronico">Correo Electrónico</label>
            <input type="email" class="form-control" id="correoElectronico" name="correoElectronico" aria-describedby="emailHelp" placeholder="Correo Electrónico" required>
            <small id="emailHelp" class="form-text text-muted">No compartiremos tu correo con nadie más.</small>
          </div>
          <div class="form-group">
            <label for="telefono">Teléfono / Celular</label>
            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono / Celular">
          </div>   
          <div class="form-group">
            <label for="comentario">Comentario</label>
            <textarea class="form-control" id="comentario" name="comentario" rows="6" required></textarea>
          </div>          
          <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

        <script>
          /* Attach a submit handler to the form */
          $("#contact-us").submit(function(event) {
            /* Stop form from submitting normally */
            event.preventDefault();

            grecaptcha.ready(function() {
              grecaptcha.execute('LLAVE_GOOGLE_RECAPTCHA', {action: 'form_submit'}).then(function(token) {
                $('#contact-us').prepend('<input type="hidden" name="token" value="' + token + '">');
                $('#contact-us').prepend('<input type="hidden" name="action" value="form_submit">');
                $('#contact-us').unbind('submit').submit();
              });
            });
          });
        </script>

        </div>

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <footer>
    <?php include 'include/footer.php'; ?>
    </footer>    

</body>