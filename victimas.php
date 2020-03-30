<?php
    include "include/db.php";
    $result=getMonumento($_GET['id']);
    $monumento=mysqli_fetch_assoc($result);
    $personas_monumento=getPersonasMonumento($_GET['id']);
    if(isset($monumento['foto_oficial'])&&($monumento['foto_oficial']!='')){
        $monumento['foto_oficial']=$monumento['foto_oficial'];
    }
    else{
        $monumento['foto_oficial']=mediapath().'default.jpg';   
    }
    if($monumento['titulo']==''){
        $monumento['titulo']='pendiente';
    }

    //Revisar el proceso de guardado de esto...
    //
    
?>



<!DOCTYPE html>
<html>
    <head>
        <title>Víctimas fallecidas-<?php echo $monumento['titulo'] ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSS -->
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/article.css">
        <!-- Scripts -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="http://maps.googleapis.com/maps/api/js?key=LLAVE_GOOGLE_MAPS"></script>
        <script src="/plugins/jquery.bxslider/jquery.bxslider.min.js"></script>
    </head>
    <body>
        <?php include 'include/header.php'; ?>
        <?php echo $_GET['id'] ?>
        <article class="articulo">
            <div class="buttons-left">
                <button onclick="location.href='http://mapeo.memorialparalaconcordia.org'">Mapa</button>
            </div>
            <div class="titulo-container">
                <div class="panel-titulo">
                    <img class="foto-oficial" src=<?php echo $monumento['foto_oficial']?> ></img>
                    <span class="info">
                        <h1><?php echo $monumento['titulo'] ?></h1>
                        <h3>Código: <?php echo $monumento['identificador'] ?></h3>
                    </span>
                </div>
            </div>
            <div class="panel-victimas">
                <table class="table">
                    <thead>
                      <tr>
                        <th><strong>Nombre</strong></th>
                        <th><strong>Sector</strong></th>
                        <th><strong>Profesión</strong></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        while($row=mysqli_fetch_assoc($personas_monumento)){
                            echo '<tr>';
                            //nombre
                            echo'<td>'.$row['nombre'].'</td>';
                            $result=getSector($row['id_sector']);
                            $element=mysqli_fetch_assoc($result);
                            //sector
                            echo'<td>'.$element['sector'].'</td>';
                            $result=getProfesion($row['id_profesion']);
                            $element=mysqli_fetch_assoc($result);
                            //profesion
                            echo'<td>'.$element['profesion'].'</td>';
                            echo '</tr>';
                        }
                      ?>
                    </tbody>
                </table>
            </div>
        </article>

    </body>
</html>