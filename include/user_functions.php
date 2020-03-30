<?php

    //metodo para encriptar
    function encriptarCadena($cadena){
        return hash('sha512',$cadena);
    }

    function resetPwd($id_usuario, $pwd){
        $resultUsuario = getUsuario($id_usuario);
        $info=mysqli_fetch_array($resultUsuario);
        $newEncrypted = encriptarCadena($pwd);
        $resultPassword = nuevaContrasena($id_usuario,$newEncrypted);
        if($resultPassword){
            return true;
        }
        return false;
    }

?>