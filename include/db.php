<?php

//TODO ¿Eliminar esto?
require 'vendor/autoload.php';
use Mailgun\Mailgun;
include 'globals.php';
require 'PHPMailer/PHPMailerAutoload.php';
require_once 'user_functions.php';

/* Se comenta por estar obsoleto:
	$con=mysql_connect($host,$username,$password) or die (mysql_error());
	$db=mysql_select_db($db_name,$con) or die (mysql_error());
*/

function db_connect() {
	//Tomado de: https://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17

	// Define connection as a static variable, to avoid connecting more than once 
	static $connection;

	// Try and connect to the database, if a connection has not been established yet
	if(!isset($connection)) {
		$host="localhost"; 				// Host 
		$username="DB_USERNAME"; 		// Mysql username
		$password="DB_PASSWORD-"; 			// Mysql password
		$db_name="DB_NAME"; 				// Database			
		
		$connection = mysqli_connect($host, $username, $password, $db_name);
	}

	// If connection was not successful, handle the error
	if($connection === false) {
		die("Connection error: " . mysqli_connect_error());
	}
	
	mysqli_set_charset($connection,"utf8");

	return $connection;
}	

function db_query($query){
	
	// Connect to the database
	$connection = db_connect();
	
	$result = mysqli_query($connection, $query);
	
	if(!$result){
		echo "Error for ".$query."<br>".mysqli_error($connection)."<br>";
	}
	return $result;
}

function mediapath(){
    global $root_path;
	return "http://".$root_path."multimedia/";
}


//Obtiene todos los usuarios:
function allUsuario() {
    $query="SELECT * FROM usuario ORDER BY nombre_usuario;";
    $result=db_query($query);
    if(!$result){
        //que hacer en caso de fallo
        return array();
    }
    else{
        //que hacer en caso de exito
        return $result;
    }
}

//******************************************** Manejo de páginas estáticas ***************************************************
function getMenu($opcionesAdicionales){
	$result=allPagina();
	$menu="<ul>";
	while($row=mysqli_fetch_assoc($result)){
		if($row['estado']=='activo'){
			$menu=$menu.'<li><a href="/pageview.php?id='.$row['id_static_page'].'">'.$row['nombre'].'</a></li>';
		}
	}
	$menu=$menu.$opcionesAdicionales."</ul>";
	return $menu;
}


function existsPagina($pagina){
	$queryPagina=db_query("SELECT * FROM static_page WHERE nombre='".$pagina."'");
	if(mysqli_num_rows($queryPagina)>0){
		return true;
	}
	else{
		return false;
	}
}
function agregarPagina($nombre,$contenido,$estado){
    
    $query="INSERT INTO static_page (nombre, contenido, estado) VALUES('".$nombre."','".$contenido."','".$estado."');";
	//revisar la existencia del nombre de usuario
	if(!existsPagina($nombre)){
	
        $connection = db_connect();
	
        $result = mysqli_query($connection,$query);
		if(!$result){
			//que hacer en caso de fallo
			return false;
		}
		else{
			//registar en el log la accion
			$accion="Agrego la pagina con id ".mysqli_insert_id($connection);
			$insertid=mysqli_insert_id($connection);
			registrarAccion($_SESSION['uid'],$accion);
			return $insertid;
		}
	}
	else{
		return false;
	}
}
function editarPagina($id_static_page,$nombre,$contenido,$estado){
	$query="UPDATE static_page SET estado='".$estado."',nombre='".$nombre."',contenido='".$contenido."' WHERE id_static_page=".$id_static_page;
	$result=db_query($query);
	if(!$result){
		return false;
	}
	else{
		$accion="Modifico la pagina con id ".$id_static_page;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}
function bajaPagina($id_static_page){
	$resultadoP=getPagina($id_static_page);
	$infoPagina=mysqli_fetch_array($resultadoP);
	$estado='';
	if($infoPagina['estado']=='activo'){
		$estado='inactivo';
	}
	else{
		$estado='activo';
	}
	$query="UPDATE static_page SET estado='".$estado."' WHERE id_static_page=".$id_static_page;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		$accion="Se dio de baja la pagina con id ".$id_static_page;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}
function getPagina($id_static_page){
	$query="SELECT * FROM static_page WHERE id_static_page=".$id_static_page;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}
function allPagina(){
	$query="SELECT * FROM static_page ORDER BY nombre;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}
//********************************************seccion de gestion de usuarios************************************************
//resetear contrasenha
function resetPass($id_usuario){
	
	if(!in_array(1, $_SESSION['rol'])){
	    header("location:".$root_path."adm/login.php");
	}
    /*
	$resultUsuario = getUsuario($id_usuario);
	$info=mysql_fetch_array($resultUsuario);
	$newPassword = pwdGenerator();
	$newEncrypted = encriptarCadena($newPassword);
	$resultPassword = nuevaContrasena($id_usuario,$newEncrypted);
	*/
	/*
	$mgClient = new Mailgun('key-ff4bac648cdb3358e19bb51f4cc4c689');
	$domain = "sandbox3fe73f81f50049ba8b617ef2b6a2aced.mailgun.org";
	# Make the call to the client.
	try{
		$result = $mgClient->sendMessage($domain,
		                  array('from'    => 'Mailgun Sandbox <postmaster@sandbox3fe73f81f50049ba8b617ef2b6a2aced.mailgun.org>',
		                        'to'      => $info['nombre_usuario'].' <'.$info['correo'].'>',
		                        'subject' => 'Reinicio de contraseña',
	                        	'text'    => 'Hola '.$info['nombre_usuario'].': Su contraseña ha sido reiniciada temporalmente a: '.$newPassword.' y puede cambiarla desde su perfil. -- Atte. Memorial para la concordia.', 
	                        	'html'    => 'Hola '.$info['nombre_usuario'].': Su contraseña ha sido reiniciada temporalmente a: '.$newPassword.' y puede cambiarla desde su perfil. -- Atte. Memorial para la concordia.',
	                        	'o:require-tls'       => true,
    							'o:skip-verification' => false
	    ));
		$logItems = $result->http_response_body->items;
		foreach($logItems as $logItem){  
		    echo $logItem->message_id . "\n";
		}
	}
	catch(Exception $e){
		return 'error'.$e->getMessage();
	}
	*/
	
	try{
		$to = "juanalbertobv@gmail.com";
		$subject = "My subject";
		$txt = "Hello world!";
		$headers = "From: webmaster@example.com" . "\r\n" .
		"CC: somebodyelse@example.com";

		mail($to,$subject,$txt,$headers); 
	}
	catch(Exception $e){
		return 'error'.$e->getMessage();
	}
	
	return ('en mantenimiento');
}



function pwdGenerator($length = 7) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//revisa si el nombre de un usuario ya existe
function existsUsuario($nombre_usuario){
	$query="SELECT nombre_usuario FROM usuario WHERE nombre_usuario='".$nombre_usuario."'";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return true;
	}
	else{
		//que hacer en caso de eeeeexito
		//revisar todos los resultados
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			//nombre de usuario de la fila actual
			$nombre_usuario_actual=$row["nombre_usuario"];
			if($nombre_usuario==$nombre_usuario_actual){
				return true;
			}
		}
		return false;
	}
}

function getUsuario($id_usuario){
	$query="SELECT * FROM usuario WHERE id_usuario = ".$id_usuario;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

//agrega un usuario
//falta encriptar contrasenha
//estado puede ser activo o inactivo por ahora
function agregarUsuario($nombre_usuario,$nombre,$apellido,$correo,$contrasena,$estado){
	$query="INSERT INTO usuario (nombre_usuario, nombre, apellido, correo, contrasena, estado) VALUES('".$nombre_usuario."', '".$nombre."', '".$apellido."', '".$correo."', '".encriptarCadena($contrasena)."','".$estado."');";
	//revisar la existencia del nombre de usuario
	if(!existsUsuario($nombre_usuario)){
		
		// Connect to the database
		$connection = db_connect();
		$result = mysqli_query($connection, $query);
		
		if(!$result){
			return false;
		}
		else{
			//Registar en el log la acción:
			$accion="Se agregó el usuario con id ".mysqli_insert_id($connection);
			$insertid=mysqli_insert_id($connection);
			registrarAccion($_SESSION['uid'],$accion);
			return $insertid;
		}
	}
	else{
		return false;
	}
}
//edita la informacion de un usuario, menos la contrasena, correo y usuario
function editarUsuario($id_usuario,$nombre,$apellido,$estado){
	$query="UPDATE usuario SET nombre='".$nombre."',apellido='".$apellido."',estado='".$estado."' WHERE id_usuario=".$id_usuario;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		$accion="Edito el usuario con id ".$id_usuario;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}
//edita la informacion de un usuario, menos la contrasena
function editarUsuarioP($id_usuario,$nombre,$apellido,$estado,$correo,$nombre_usuario){
	$query="UPDATE usuario SET nombre='".$nombre."',apellido='".$apellido."',estado='".$estado."',nombre_usuario='".$nombre_usuario."',correo='".$correo."' WHERE id_usuario=".$id_usuario;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		$accion="Usuario edito su perfil";
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}
function nuevaContrasena($id_usuario,$contrasena){
	$query="UPDATE usuario SET contrasena='".$contrasena."' WHERE id_usuario=".$id_usuario;
	$result=db_query($query);
	if(!$result){
		return false;
	}
	else{
		$accion="El usuario cambio su contrasena";
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

function cambiarEstadoUsuario($id_usuario){
	
	//Se obtiene la información del usuario a modificar:
	$resultadoU=getUsuario($id_usuario);
	$infoUsuario=mysqli_fetch_array($resultadoU);
	
	$estado='';
	if($infoUsuario['estado']=='activo'){
		$estado='inactivo';
	}
	else{
		$estado='activo';
	}
	
	$query="UPDATE usuario SET estado='".$estado."' WHERE id_usuario=".$id_usuario;
	$result=db_query($query);
	if(!$result){
		//Si hay error al ejecutar el UPDATE:
		return false;
	} else {
		if($estado = 'inactivo') {
			$accion="Se inactivó el usuario con id ".$id_usuario;
		} else {
			$accion="Se activó el usuario con id ".$id_usuario;
		}		
		//Registro en el log:
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}


//obtener los permisos de un usuario
function permisoUsuario($id_usuario){
	$query="SELECT id_rol FROM permiso_usuario WHERE id_usuario=".$id_usuario;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function borrarPermisosUsuario($id_usuario){
	$query="DELETE FROM permiso_usuario WHERE id_usuario=".$id_usuario;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}
//metodo para asignar roles a usuarios
//asociacion de roles a usuarios
//rol=1 root,rol=2 monumento, rol=3 campo
function asignarRolUsuario($id_usuario,$id_rol){
	$query="INSERT INTO permiso_usuario(id_usuario,id_rol) VALUES (".$id_usuario.",".$id_rol.");";
	$permisos=permisoUsuario($id_usuario);
	//revisar si el usuario ya tiene asignado el rol
	while($row=mysqli_fetch_array($permisos)){
		if($id_rol==$row["id_rol"]){
			return false;
		}
	}
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		$accion="Se han asignado roles al usuario con id ".$id_usuario;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}
//no usado
function resultsetToArray($resultSet){
	for($i = 0; $array[$i] = mysqli_fetch_assoc($resultSet); $i++) ;
	// Delete last empty one
	array_pop($array);
	return $array;
}
//************************************************************fin gestion de usuarios*****************************************
//************************************************************seccion de gestion de campos************************************
//revisar si existe un sector
function existsSector($sector){
	$querySector=db_query("SELECT * FROM sector WHERE sector='".$sector."'");
	if(mysqli_num_rows($querySector)>0){
		return true;
	}
	else{
		return false;
	}
}
//agregar un sector
function agregarSector($sector,$estado){
	$query="INSERT INTO sector (sector,estado) VALUES ('".mysqli_real_escape_string($sector)."',".$estado.")";
	if(existsSector($sector)){
		return false;
	}
	else{
		$result=db_query($query);
		if(!$result){
			//que hacer en caso de fallo
			return false;
		}
		else{
			//que hacer en caso de exito
			$accion="Agrego el sector con id ".mysqli_insert_id();
			registrarAccion($_SESSION['uid'],$accion);
			return true;
		}
	}
}
//revisar si existe una profesion
function existsProfesion($profesion){
	$query="SELECT * FROM profesion WHERE profesion='".$profesion."'";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		if(mysqli_num_rows($result)>0){
			return true;
		}
		else{
			return false;
		}
	}
}
//agregar una profesion
function agregarProfesion($profesion,$estado){
	
	// Connect to the database
	$connection = db_connect();
	
	$query="INSERT INTO profesion (profesion, estado) VALUES ('".mysqli_real_escape_string($connection,$profesion)."',".$estado.")";
	
	if(existsProfesion($profesion)){
		return false;
	} else {
		$result=db_query($query);
		if(!$result){
			return false;
		}
		else{
			$accion="Se agregí la profesión con id ".mysqli_insert_id($connection);
			registrarAccion($_SESSION['uid'],$accion);
			return true;
		}
	}
}
//revisar si existe un evento
function existsTipoEvento($evento){
	$query="SELECT * FROM tipo_evento WHERE evento='".$evento."'";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		if(mysqli_num_rows($result)>0){
			return true;
		}
		else{
			return false;
		}
	}
}
//agrega un tipo de evento si no existe ya
function agregarTipoEvento($evento,$estado){
	$query="INSERT INTO tipo_evento (evento,estado) VALUES ('".mysqli_real_escape_string($evento)."',".$estado.")";
	echo $query;
	if(existsTipoEvento($evento)){
		return false;
	}
	else{
		$result=db_query($query);
		if(!$result){
			//que hacer en caso de fallo
			return false;
		}
		else{
			//que hacer en caso de exito
			$accion="Agrego el tipo de evento con id ".mysqli_insert_id();
			registrarAccion($_SESSION['uid'],$accion);
			return true;
		}
	}
}
function existsTipoMonumento($tipo_monumento){
	$query="SELECT * FROM tipo_monumento WHERE tipo_monumento='".$tipo_monumento."'";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		if(mysqli_num_rows($result)>0){
			return true;
		}
		else{
			return false;
		}
	}
}
//agrega un tipo de evento si no existe ya
function agregarTipoMonumento($tipo_monumento,$estado,$descripcion){
	$query="INSERT INTO tipo_monumento (tipo_monumento,estado,descripcion) VALUES ('".mysqli_real_escape_string($tipo_monumento)."',".$estado.",'".mysqli_real_escape_string($descripcion)."')";
	if(existsTipoMonumento($tipo_monumento)){
		return false;
	}
	else{
		$result=db_query($query);
		if(!$result){
			//que hacer en caso de fallo
			return false;
		}
		else{
			//que hacer en caso de exito
			$accion="Agrego el tipo de monumento con id ".mysqli_insert_id();
			registrarAccion($_SESSION['uid'],$accion);
			return true;
		}
	}
}
//revisar si existe un campo
function existsCampo($campo){
	$query="SELECT * FROM campo_adicional WHERE nombre='".$campo."'";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		if(mysqli_num_rows($result)>0){
			return true;
		}
		else{
			return false;
		}
	}
}
//agregar campo
function agregarCampo($nombre,$filtrable,$obligatorio,$valor_default,$estado,$tooltip){
    
	$query="INSERT INTO campo_adicional (nombre,filtrable,obligatorio,valor_default,estado,tooltip) VALUES ('".
                $nombre."',".
                $filtrable.",".
                $obligatorio.",'".
                $valor_default."',".
                $estado.",'".
                $tooltip."')";
                
	if(existsCampo($nombre)){
		return false;
	}
	else{
    
        $connection = db_connect();
    
		$result=mysqli_query($connection, $query);

        if(!$result){
			//que hacer en caso de fallo
			return false;
		}
		else{
			//que hacer en caso de exito
			$idCampo=mysqli_insert_id($connection);
			$accion="Agrego el campo con id ".$idCampo;
			registrarAccion($_SESSION['uid'],$accion);
			//crear valor default
			$queryAddD="INSERT INTO valor (id_campo_adicional,valor,estado) VALUES (".$idCampo.",'".mysqli_real_escape_string($connection,$valor_default)."',true)";
			$resultAdd=mysqli_query($connection, $queryAddD);
			$idDefault=mysqli_insert_id($connection);
			//agregar valor default a todos los campos
			$queryGet="SELECT * FROM monumento";
			$monumentos=mysqli_query($connection, $queryGet);
			if(!$monumentos){
				//error al agregar todos los valores
			}
			else{
				while($row=mysqli_fetch_array($monumentos)){
					$queryAdd="INSERT INTO valor_monumento (id_valor,id_monumento) VALUES(".$idDefault.",".$row['id_monumento'].");";
					mysqli_query($connection, $queryAdd);
				}
			}
			return true;
		}
	}
}

//existe el valor en el campo
function existeValorCampo($id_campo_adicional,$valor){
	$query="SELECT * FROM valor WHERE id_campo_adicional=".$id_campo_adicional." AND valor='".$valor."'";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		if(mysqli_num_rows($result)>0){
			return true;
		}
		else{
			return false;
		}
	}
}

//agregar el valor para el campo dado
function agregarValorCampo($id_campo_adicional,$valor,$estado){
    
    $connection = db_connect();
    
	$query="INSERT INTO valor (id_campo_adicional,valor,estado) VALUES (".$id_campo_adicional.",'".mysqli_real_escape_string($connection,$valor)."',".$estado.")";
	if(existsSector($id_campo_adicional,$valor)){
		return false;
	}
	else{
		$result=mysqli_query($connection,$query);
		if(!$result){
			//que hacer en caso de fallo
			return false;
		}
		else{
			//que hacer en caso de exito
			$accion="Agrego el valor con id ".mysqli_insert_id($connection)." al campo de id ".$id_campo_adicional;
			registrarAccion($_SESSION['uid'],$accion);
			return true;
		}
	}
}





function allSectorCompleto(){
	$query="SELECT * FROM sector ORDER BY sector;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}



function allProfesionCompleto(){
	$query="SELECT * FROM profesion ORDER BY profesion;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}



function allTipoEventoCompleto(){
	$query="SELECT * FROM tipo_evento ORDER BY evento;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}



function allOrganizacionCompleto(){
	$query="SELECT * FROM organizacion ORDER BY nombre_organizacion;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function allCampo(){
	$query="SELECT * FROM campo_adicional ORDER BY nombre;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}




function allValorCampo($id_campo_valor){
	$query="SELECT * FROM valor WHERE id_campo_adicional=".$id_campo_valor." ORDER BY valor;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}





function bajaCampo($id_campo_adicional){
	$resultadoC=getCampo($id_campo_adicional);
	$campoInfo=mysqli_fetch_array($resultadoC);
	$estado='';
	if($campoInfo['estado']=='1'){
		$estado='false';
	}
	else{
		$estado='true';
	}
	$query="UPDATE campo_adicional SET estado=".$estado." WHERE id_campo_adicional=".$id_campo_adicional;
	$result=db_query($query);
	if(!$result){
		return false;
	}
	else{
		$accion="Se modifico el estado a ".$estado." al campo con id ".$id_campo_adicional;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

function getCampo($id_campo_adicional){
	$query="SELECT * FROM campo_adicional WHERE id_campo_adicional=".$id_campo_adicional;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function editarCampo($id_campo_adicional,$nombre,$tooltip,$filtrable,$obligatorio,$estado){
	
    $connection = db_connect();
    
    $query="UPDATE campo_adicional SET nombre='".mysqli_real_escape_string($connection,$nombre)
                ."',tooltip='".mysqli_real_escape_string($connection,$tooltip)
                ."',filtrable=".$filtrable
                .",obligatorio=".$obligatorio
                .",estado=".$estado
                ." WHERE id_campo_adicional=".$id_campo_adicional;
                
	$result=mysqli_query($connection, $query);
	if(!$result){
		return false;
	}
	else{
		$accion="Edito el campo con id ".$id_campo_adicional;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

function editarValorCampo($id_valor,$valor,$estado){
    
    $connection = db_connect();
    
	$query="UPDATE valor SET estado=".$estado.",valor='".mysqli_real_escape_string($connection, $valor)."' WHERE id_valor=".$id_valor;
	$result=mysqli_query($connection,$query);
	if(!$result){
		return false;
	}
	else{
		$accion="Modifico el valor con id ".$id_valor;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

function getValorCampo($id_valor_campo){
	$query="SELECT * FROM valor WHERE id_valor=".$id_valor_campo;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}

}

function bajaValorCampo($id_valor){
	$resultadoVC=getValorCampo($id_valor);
	$infoValor=mysqli_fetch_array($resultadoVC);
	$estado='';
	if($infoValor['estado']=='1'){
		$estado='false';
	}
	else{
		$estado='true';
	}
	$query="UPDATE valor SET estado=".$estado." WHERE id_valor=".$id_valor;
	$result=db_query($query);
	if(!$result){
		return false;
	}
	else{
		$accion="Dio de baja el valor con id ".$id_valor;
		registrarAccion($_SESSION['uid'],$accion);
		
		return true;
	}
}
function getProfesion($id_profesion){
	$query="SELECT * FROM profesion WHERE id_profesion=".$id_profesion;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function cambiarEstadoProfesion($id_profesion){
	
	//Se obtiene la información de la profesión a modificar:
	$resultadoP=getProfesion($id_profesion);
	$infoProfesion=mysqli_fetch_array($resultadoP);
	
	$estado='';
	//Si la profesión tiene estado 'Activo':
	if($infoProfesion['estado']=='1'){
		$estado='false';
	
	//Si la profesión tiene estado 'Inactivo':	
	} else { 
		$estado='true';
	}
	
	$query="UPDATE profesion SET estado=".$estado." WHERE id_profesion=".$id_profesion;
	$result=db_query($query);
	if(!$result){
		//Si hay error al ejecutar el UPDATE:
		return false;
	} else {
		if($estado = 'false') {
			$accion="Se inactivó la profesion con id ".$id_profesion;
		} else {
			$accion="Se activó la profesion con id ".$id_profesion;
		}
		//Registro en el log:
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

function getTipoEvento($id_tipo_evento){
	$query="SELECT * FROM tipo_evento WHERE id_tipo_evento=".$id_tipo_evento;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function bajaTipoEvento($id_tipo_evento){
	$resultadoP=getTipoEvento($id_tipo_evento);
	$infoTipoEvento=mysqli_fetch_array($resultadoP);
	$estado='';
	if($infoTipoEvento['estado']=='1'){
		$estado='false';
	}
	else{
		$estado='true';
	}
	$query="UPDATE tipo_evento SET estado=".$estado." WHERE id_tipo_evento=".$id_tipo_evento;
	$result=db_query($query);
	if(!$result){
		return false;
	}
	else{
		$accion="Dio de baja el tipo de evento con id ".$id_tipo_evento;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}

}

function getTipoMonumento($id_tipo_monumento){
	$query="SELECT * FROM tipo_monumento WHERE id_tipo_monumento=".$id_tipo_monumento;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function bajaTipoMonumento($id_tipo_monumento){
	$resultadoP=getTipoMonumento($id_tipo_monumento);
	$infoTipoMonumento=mysqli_fetch_array($resultadoP);
	$estado='';
	if($infoTipoMonumento['estado']=='1'){
		$estado='false';
	}
	else{
		$estado='true';
	}
	$query="UPDATE tipo_monumento SET estado=".$estado." WHERE id_tipo_monumento=".$id_tipo_monumento;
	$result=db_query($query);
	if(!$result){
		return false;
	}
	else{
		$accion="Dio de baja el tipo de monumento con id ".$id_tipo_monumento;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}

}

function getSector($id_sector){
	$query="SELECT * FROM sector WHERE id_sector=".$id_sector;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}
function bajaSector($id_sector){
	$resultadoS=getSector($id_sector);
	$infoSector=mysqli_fetch_array($resultadoS);
	$estado='';
	if($infoSector['estado']=='1'){
		$estado='false';
	}
	else{
		$estado='true';
	}
	$query="UPDATE sector SET estado=".$estado." WHERE id_sector=".$id_sector;
	$result=db_query($query);
	if(!$result){
		return false;
	}
	else{
		$accion="Dio de baja el sector con id ".$id_sector;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

function editarProfesion($id_profesion,$valor,$estado){
    
    $connection = db_connect();
    
	$query="UPDATE profesion SET estado=".$estado.",profesion='".mysqli_real_escape_string($connection,$valor)."' WHERE id_profesion=".$id_profesion;
	$result=mysqli_query($connection,$query);
	if(!$result){
		return false;
	}
	else{
		$accion="Modifico la profesion con id ".$id_profesion;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

function editarSector($id_sector,$valor,$estado){
    
    $connection = db_connect();
    
	$query="UPDATE sector SET estado=".$estado.",sector='".mysqli_real_escape_string($connection,$valor)."' WHERE id_sector=".$id_sector;
	$result=mysqli_query($connection,$query);
	if(!$result){
		return false;
	}
	else{
		$accion="Modifico el sector con id ".$id_sector;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

function editarTipoMonumento($id_tipo_monumento,$tipo_monumento,$estado,$descripcion){
    
    $connection = db_connect();
    
	$query="UPDATE tipo_monumento SET estado=".$estado.",tipo_monumento='".mysqli_real_escape_string($connection,$tipo_monumento)."',descripcion='".$descripcion."' WHERE id_tipo_monumento=".$id_tipo_monumento;
	$result=mysqli_query($connection,$query);
	if(!$result){
		return false;
	}
	else{
		$accion="Modifico el tipo de monumento con id ".$id_tipo_monumento;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

function editarTipoEvento($id_tipo_evento,$evento,$estado){
    
    $connection = db_connect();
    
	$query="UPDATE tipo_evento SET estado=".$estado.",evento='".mysqli_real_escape_string($connection,$evento)."' WHERE id_tipo_evento=".$id_tipo_evento;
	$result=mysqli_query($connection,$query);
	if(!$result){
		return false;
	}
	else{
		$accion="Modifico el tipo de evento con id ".$id_tipo_evento;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}
}

//************************************************************fin gestion de campos*******************************************
//******************************************Obtener campos********************************************
function allDepartamentos(){
	$query="SELECT * FROM departamento ORDER BY departamento;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}





function allTiposMonumentoCompleto(){
	$query="SELECT * FROM tipo_monumento ORDER BY tipo_monumento;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}




function allPersonasCompleto(){
	$query="SELECT * FROM persona ORDER BY nombre;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}



//Sirve en las páginas de gestión de monumentos/sitios:
function allMonumentos($usuario,$rol){
	if ($rol==2){
		$query = "SELECT titulo, 
                         estado_actual, 
                         nombre_usuario,
                         id_monumento
                  FROM monumento, 
                       usuario 
                  WHERE monumento.id_usuario_owner = usuario.id_usuario 
                  ORDER BY titulo;";
	} else {
		$query = "SELECT titulo, 
                         estado_actual, 
                         nombre_usuario, 
                         id_monumento
                  FROM monumento, 
                       usuario 
                  WHERE usuario.id_usuario = '".$usuario."' 
                   AND (monumento.id_usuario_owner = '".$usuario."' OR monumento.id_usuario_campo = '".$usuario."') 
                  ORDER BY titulo;";
	}
	
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function allMonumentosConSolicitudDeEliminacion(){

	$query="SELECT titulo, estado_actual, nombre_usuario, id_monumento, identificador FROM monumento, usuario WHERE monumento.id_usuario_owner=usuario.id_usuario AND estado_actual = 'Eliminación Solicitada' ORDER BY titulo;";

	
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function getMonumento($id){
	$query="SELECT DISTINCT m.id_monumento as id_monumento, m.titulo as titulo, m.identificador as identificador, m.estado_actual as estado_actual, mul.direccion_archivo as foto_oficial, m.id_tipo_monumento as id_tipo_monumento, m.id_tipo_evento as id_tipo_evento, m.latitud as latitud, m.longitud as longitud, m.estado_sitio as estado_sitio, m.direccion as direccion, m.ubicacion as ubicacion, m.como_llegar as como_llegar, m.descripcion as descripcion, m.descripcion_corta as descripcion_corta FROM monumento m LEFT JOIN multimedia mul on(m.foto_oficial=mul.id_multimedia) WHERE id_monumento = ".$id.";";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}	
}



function getPersonasMonumento($id){
	$query="SELECT persona.id_persona AS id_persona, persona.nombre , persona.nombre as nombre, persona.id_sector as id_sector, persona.id_profesion as id_profesion FROM persona_monumento, persona WHERE persona_monumento.id_persona = persona.id_persona AND persona_monumento.id_monumento = '".$id."';";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}	
}

function getKeywordsMonumento($id){
	$query="SELECT id_keyword, keyword FROM keywords WHERE id_monumento = ".$id.";";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}	
}

function getNoticiasMonumento($id){
	$query="SELECT * FROM noticia WHERE id_monumento = '".$id."';";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}







function getCampoValorAdicional($id){
	$query="SELECT DISTINCT ca.nombre as campo, v.valor as valor FROM valor_monumento vm LEFT JOIN valor v ON(vm.id_valor=v.id_valor) LEFT JOIN campo_adicional ca ON (v.id_campo_adicional=ca.id_campo_adicional) WHERE vm.id_monumento=".$id." AND ca.estado=1;";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}	
}

function cambioEstadoMonumento($id){
	$query="UPDATE monumento 
			SET estado_actual = CASE WHEN estado_actual = 'Inactivo' THEN 'Publicado' ELSE 'Inactivo' END 
			WHERE id_monumento = '".$id."';";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		return true;
	}	
}

function solicitudBajaMonumento($id){
	$query="UPDATE monumento 
			SET estado_actual = 'Eliminación Solicitada'
			WHERE id_monumento = '".$id."';";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		return true;
	}	
}

function bajaMonumento($id){
	$query="CALL EliminarMonumento(".$id.")";
	echo $query;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return false;
	}
	else{
		//que hacer en caso de exito
		return true;
	}	
}




function updatePersona($id_persona,$nombrePersona, $menordeedad, $genero, $id_sector, $id_profesion, $id_pais,$estado){
	$query = "UPDATE persona SET nombre='".mysqli_real_escape_string($nombrePersona)."',id_genero=".$genero.",id_sector=".$id_sector.",id_profesion=".$id_profesion.",id_pais=".$id_pais.",estado=".$estado.",ninez=".$menordeedad." WHERE id_persona=".$id_persona.";";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function getPersona($id_persona){
	$query="SELECT * FROM persona WHERE id_persona=".$id_persona;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function bajaPersona($id_persona){
	$resultadoP=getPersona($id_persona);
	$infoPersona=mysqli_fetch_array($resultadoP);
	$estado='';
	if($infoPersona['estado']=='1'){
		$estado='false';
	}
	else{
		$estado='true';
	}
	$query="UPDATE persona SET estado=".$estado." WHERE id_persona=".$id_persona;
	$result=db_query($query);
	if(!$result){
		return false;
	}
	else{
		$accion="Dio de baja la persona con id ".$id_persona;
		registrarAccion($_SESSION['uid'],$accion);
		return true;
	}

}

function crearOrganizacion($nombreOrganizacion, $personaOrganizacion, $telefonoOrganizacion, $emailOrganizacion){
	//TODO ¿Esto para qué es?
	if(isset($telefonoOrganizacion)||(strcmp($telefonoOrganizacion, '')==0)){
		$telefonoOrganizacion='NULL';
	}
	$query = "INSERT INTO organizacion (responsable,telefono,correo,nombre_organizacion) VALUES ('".
				$personaOrganizacion."',".
				$telefonoOrganizacion.",'".
				$emailOrganizacion."','".
				$nombreOrganizacion."');";
	
	// Connect to the database
	$connection = db_connect();	
	$result = mysqli_query($connection, $query);
	
	if(!$result){
		return 0;
	} else {
		return mysqli_insert_id($connection);
	}
}

function updateOrganizacion($id_organizacion,$nombreOrganizacion, $personaOrganizacion, $telefonoOrganizacion, $emailOrganizacion){
	if(isset($telefonoOrganizacion)||(strcmp($telefonoOrganizacion, '')==0)){
		$telefonoOrganizacion='NULL';
	}
	$query = "UPDATE organizacion SET responsable='".$personaOrganizacion."',telefono=".$telefonoOrganizacion.",correo='".$emailOrganizacion."',nombre_organizacion='".$nombreOrganizacion."' WHERE id_organizacion=".$id_organizacion.";";
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function getOrganizacion($id_organizacion){
	$query="SELECT * FROM organizacion WHERE id_organizacion=".$id_organizacion;
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return $result;
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}
// Monumentos relacionados por tipo de monumentos Agregada posteriormente por SGG
function getMonumentosRelacionados($id_monumento){ 
	$query="SELECT * FROM monumento WHERE estado_actual ='Publicado' AND id_monumento !=".$id_monumento." ORDER BY rand() LIMIT 5";   
	$result=db_query($query);
	if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}



//**************************Update*************************






//****************************************************************************************
//Area de estadisticas


function total_monumentos(){
	$query = "SELECT count(id_monumento) AS count FROM monumento;";
	$result=db_query($query);
	$row=mysqli_fetch_assoc($result);
	return $row['count'];
}

function estadisticas_monumento_estado(){
	$query = "SELECT estado_actual, count(estado_actual) AS count FROM monumento GROUP BY estado_actual;";
	$result=db_query($query);
		if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function estadisticas_monumento_departamento(){
	$query = "SELECT departamento.id_departamento, departamento.departamento, count(municipio.id_departamento) AS count FROM monumento JOIN municipio ON (monumento.id_municipio = municipio.id_municipio) JOIN departamento ON (municipio.id_departamento = departamento.id_departamento) WHERE monumento.estado_actual<>'Inactivo' GROUP BY departamento.id_departamento ORDER BY departamento.departamento;";
	$result=db_query($query);
		if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function estadisticas_monumento_tipo(){
	$query = "SELECT tipo_monumento.tipo_monumento, count(tipo_monumento.tipo_monumento) AS count FROM monumento JOIN tipo_monumento ON monumento.id_tipo_monumento = tipo_monumento.id_tipo_monumento WHERE monumento.estado_actual<>'Inactivo' GROUP BY tipo_monumento.tipo_monumento ORDER BY tipo_monumento.tipo_monumento;";
	$result=db_query($query);
		if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function estadisticas_monumento_estado_sitio(){
	$query = "SELECT estado_sitio, 
			  count(1) AS count 
			  FROM monumento
			  WHERE monumento.estado_actual <> 'Inactivo' 
			  GROUP BY estado_sitio;";
	$result=db_query($query);
		if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function estadisticas_monumento_periodo_gobierno(){
	$query = "SELECT periodo_estatal, 
			  count(1) AS count 
			  FROM monumento
			  WHERE monumento.estado_actual <> 'Inactivo' 
			  GROUP BY periodo_estatal;";
	$result=db_query($query);
		if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}

function estadisticas_monumento_delito(){
	$query = "SELECT tipo_evento.evento, count(tipo_evento.id_tipo_evento) AS count FROM monumento JOIN tipo_evento ON monumento.id_tipo_evento = tipo_evento.id_tipo_evento WHERE monumento.estado_actual<>'Inactivo' GROUP BY tipo_evento.evento ORDER BY tipo_evento.evento;";
	$result=db_query($query);
		if(!$result){
		//que hacer en caso de fallo
		return array();
	}
	else{
		//que hacer en caso de exito
		return $result;
	}
}




//****************************************************************************************




function to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}


//********************************************************funciones de log***********************************************
function registrarAccion($id_usuario,$accion){
	$fecha="'".date("Y-m-d")."'";
	$hora="'".date("h:i:s")."'";
	$query="INSERT INTO log (id_usuario,fecha,hora,descripcion) VALUES (".$id_usuario.",".$fecha.",".$hora.",'".$accion."')";
	$result=db_query($query);
	if(!$result){
		return false;
	}
	else{
		return true;
	}
}
//********************************************************funciones de sesion********************************************


function logout(){
	session_start();
	session_destroy();	
}

if(isset($_GET['logout'])){
	if($_GET['logout']==true){
		logout();
		header("location: http://".$root_path."adm/login.php");
	}
	
}
//********************************************************busqueda de documentos******************************************
//recibe dos arrays con valores de la forma [nombrecampo->[valor1,valor2]]
//el array de campos fijos es camposf
//el array de campos adicionales camposa

?>
