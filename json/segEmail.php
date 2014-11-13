<?php
	require_once("../funciones/generales.php");

	$a = array();
	$a['success'] = true;

	$pwd = "";

	if ($_REQUEST['segLoginEmailTxt'] == '') {
		$a['success'] = false;
		$a['errorMessage'] = 'Ingrese su correo';
	}

	if ($a['success'] == true) {
		$for = $_REQUEST['segLoginEmailTxt'];
		$title = 'Recuperar Contrase&ntilde;a';
		$msg = 'Tu contraseña era: ';

		//$headers = 'From: Recordatorio <poncho.811@gmail.com>' . "\r\n";

		$sqlGetPasswordEmailStr = "SELECT password FROM segusuarios WHERE correoElectronico='".$_REQUEST['segLoginEmailTxt']."'";
		
		$rs = fn_ejecuta_query($sqlGetPasswordEmailStr);

		if (mysql_num_rows($rs) > 0) {
			$row = mysql_fetch_assoc($rs);
			$pwd = $row['email'];

			$msg .= $pwd;

			if(mail('siler_hack@hotmail.com', 'PRUEBA', 'HOLA')){
				$a['success'] = true;
				$a['successMessage'] = 'Se ha enviado un correo para recuperar tu contraseña';
			} else {
				$a['success'] = false;
				$a['errorMessage'] = 'Error al enviar el correo electronico';
			}
		} else {
			$a['success'] = false;
			$a['errorMessage'] = 'No existe este Email registrado';
		}
	}

	echo json_encode($a);
?>