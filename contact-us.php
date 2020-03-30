<?php
	/*require 'include/vendor/autoload.php';
	use Mailgun\Mailgun;

	# Instantiate the client.
	$mgClient = new Mailgun('key-ff4bac648cdb3358e19bb51f4cc4c689');
	$domain = "sandbox3fe73f81f50049ba8b617ef2b6a2aced.mailgun.org";

	# Make the call to the client.
	$result = $mgClient->sendMessage("$domain",
	                  array('from'    => 'Mailgun Sandbox <postmaster@sandbox3fe73f81f50049ba8b617ef2b6a2aced.mailgun.org>',
	                        'to'      => 'Memorial <mapeo.memorialparalaconcordia@gmail.com>',
	                        'subject' => 'Sugerencia monumento',
                        	'text'    => 'Título del sitio:'.$_POST["titulositio"].'--tipo de sitio: '.$_POST["id_tipo_monumento"].'--tipo de evento: '.$_POST["id_tipo_evento"].'Municipio: '.$_POST["id_municipio"].'Nombre de contacto: '.$_POST["nombre_contacto"].'Teléfono de contacto: '.$_POST["tel_contacto"].'Correo de contacto: '.$_POST["email_contacto"], 
                        	'html'    => '<br>Título del sitio:'.$_POST["titulositio"].'<br>tipo de sitio: '.$_POST["id_tipo_monumento"].'<br>tipo de evento: '.$_POST["id_tipo_evento"].'<br>Municipio: '.$_POST["id_municipio"].'<br>Nombre de contacto: '.$_POST["nombre_contacto"].'<br>Teléfono de contacto: '.$_POST["tel_contacto"].'<br>Correo de contacto: '.$_POST["email_contacto"]
    ));*/

    require_once $_SERVER['DOCUMENT_ROOT'].'/include/db_common.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $connection = db_connect();

        $query = "INSERT INTO sitio_sugerido (nombre, 
                                              id_tipo_monumento, 
                                              id_tipo_evento, 
                                              id_municipio,
                                              nombre_contacto,
                                              tel_contacto,
                                              email_contacto) 
                     VALUES ('" . $_POST["titulositio"] . "'," . 
                            "'" . $_POST["id_tipo_monumento"] . "'," . 
                            "'" . $_POST["id_tipo_evento"] . "'," . 
                            "'" . $_POST["id_municipio"] . "'," . 
                            "'" . $_POST["nombre_contacto"] . "'," .
                            "'" . $_POST["tel_contacto"] . "'," .
                            "'" . $_POST["email_contacto"] . "')";
        
        error_log(print_r($query, true));                          
                                  
        $result = mysqli_query($connection, $query);
        
        if ($result) {
            //COMMIT
            mysqli_commit($connection);
        } else {
            //ROLLBACK
            error_log("Error1");
            mysqli_rollback($connection);
        }
        
    }

?>