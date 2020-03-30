<?php
    session_start();
    if(!isset($_SESSION['myusername'])) {
        header("location: login.php");
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="/adm/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/adm/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Administración - Mapeo de la Memoria</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="/adm/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/adm/assets/css/now-ui-dashboard.css?v=1.0.1" rel="stylesheet" />
</head>

<body class="">
    <div class="wrapper ">

        <?php include 'include/sidebar.php'; ?>  
        
        <div class="main-panel">

            <?php include 'include/header.php'; ?>  
            
            <div class="panel-header panel-header-lg">
            </div>

        </div>
    </div>
</body>
<!--   Core JS Files   -->
<script src="/adm/assets/js/core/jquery.min.js"></script>
<script src="/adm/assets/js/core/popper.min.js"></script>
<script src="/adm/assets/js/core/bootstrap.min.js"></script>
<script src="/adm/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="/adm/assets/js/now-ui-dashboard.js?v=1.0.1"></script>


</html>
