<html>
<head>
	<title>Login Form</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../css/theme.css">
</head>
<body>
    <br>
    <div class="container">
		<br><br><br><br><br><br><br><br>
		
		<div class="col-md-4 column">
        </div>
		<div class="col-md-4 column">
          <p>Sección de administración del mapeo de la memoria:</p>
		  <form class="form-signin" name="login" method="post" action="login.php">
				<input name="myusername" id="myusername" class="form-control" placeholder="Usuario" required="" autofocus="" type="text">
				<input name="mypassword" id="mypassword" class="form-control" placeholder="Contraseña" required="" type="password">
				<input type="submit" class="btn btn-primary btn-block center-block" name="Submit" value="Iniciar sesión">
		  </form>
		  <br />
		  <br />
		  <div style="text-align: center">
          <p>Esta sección es de acceso únicamente para los usuarios encargados del mantenimiento del mapeo.</p>
          </div>
		</div>
		<div class="col-md-4 column">
		</div>
	</div>

</body>

</html>