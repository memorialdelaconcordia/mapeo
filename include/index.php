<form action="welcome5.php" method="post">  

  <div><label for="usuario">First name:  

    <input type="text" name="usuario" id="usuario"/></label>  

  </div>  

  <div><label for="contraseña">Last name:  

    <input type="text" name="contraseña" id="contraseña"/></label></div>  

  <div><input type="submit" value="GO"/></div>  

</form>

<?php
#include
include("db.php");

$a = mysqli_query("SELECT * FROM genero");
$b = mysqli_fetch_array($a);
echo $b["genero"]."<br>";


?>