<?php 
    session_start();
    include( '../../include/db.php');

    //Verificar que sea admin
    if(!in_array(1, $_SESSION['rol'])){
        header("location: http://".$root_path."adm/login.php");
    }
?>

<html>
    <head>
        <title>Estadísticas sobre el mapeo</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../../css/theme.css">
    </head>
    <body>
        <div class="container">
            <?php
				include '../../include/header.php';
            ?>
            <div class="row clearfix">
                <?php
                    include '../include/sidebar.php';
                ?>
                <div class="col-md-9 column">

                    <h3>Total de sitios de memoria: <?php echo total_monumentos();?></h3>
                    <br>
                    <h3>Cantidad de sitios de memoria según su estado:</h3>
                    <table class="table">
                        <thead>
                          <tr>
                            <th>Estado</th>
                            <th>Cantidad</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php  
                            $result=estadisticas_monumento_estado();
                            while($row=mysqli_fetch_assoc($result)){
                                echo "<tr><td>".$row["estado_actual"]."</td><td>".$row["count"]."</td></tr>";
                            }

                        ?>
                        </tbody>
                      </table>
                      <br>

                    <h3>Sitios de memoria según departamento (Publicados y pendientes de publicación):</h3>
                    <table class="table">
                        <thead>
                          <tr>
                            <th>Departamento</th>
                            <th>Cantidad</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php  
                            $result=estadisticas_monumento_departamento();
                            while($row=mysqli_fetch_assoc($result)){
                                echo "<tr><td>".$row["departamento"]."</td><td>".$row["count"]."</td></tr>";
                            }

                        ?>
                        </tbody>
                      </table>
                      <br>

                    <h3>Sitios de memoria según tipo (Publicados y pendientes de publicación):</h3>
                    <table class="table">
                        <thead>
                          <tr>
                            <th>Tipo de sitio de memoria</th>
                            <th>Cantidad</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php  
                            $result=estadisticas_monumento_tipo();
                            while($row=mysqli_fetch_assoc($result)){
                                echo "<tr><td>".$row["tipo_monumento"]."</td><td>".$row["count"]."</td></tr>";
                            }

                        ?>
                        </tbody>
                      </table>
                      <br>

                    <h3>Sitios de memoria según tipo de delito (Publicados y pendientes de publicación):</h3>
                    <table class="table">
                        <thead>
                          <tr>
                            <th>Tipo de delito</th>
                            <th>Cantidad</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php  
                            $result=estadisticas_monumento_delito();
                            while($row=mysqli_fetch_assoc($result)){
                                echo "<tr><td>".$row["evento"]."</td><td>".$row["count"]."</td></tr>";
                            }

                        ?>
                        </tbody>
                      </table>
                      <br>
                    

                    
                </div>
            </div>
        </div>
    </body>
</html>