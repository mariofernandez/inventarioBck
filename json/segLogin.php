<?php
    session_start();
    
	require_once("../funciones/generales.php");

	if (isset($_REQUEST['segLoginUsuarioTxt']) && $_REQUEST['segLoginUsuarioTxt'] != '') {
		if (isset($_REQUEST['segLoginPasswordTxt']) && $_REQUEST['segLoginPasswordTxt'] != '') {
			echo json_encode(checkUserPass($_REQUEST['segLoginUsuarioTxt'], $_REQUEST['segLoginPasswordTxt']));
		} else {
			echo json_encode(array('success'=> false, 'errorMessage'=>'Ingrese Contraseña'));
		}
	} else {
		echo json_encode(array('success'=> false, 'errorMessage'=>'Ingrese Usuario'));
	}

	function checkUserPass($user, $pwd){
		$a = array();

		$sqlCheckUserPassStr = "SELECT * FROM segUsuarios WHERE usuario = '" . $user . "'";
		$data = fn_ejecuta_query($sqlCheckUserPassStr);

		if (sizeof($data) > 0) {
			if ($data['root'][0]['estatus'] > 0) {
				//Check password
				if ($data['root'][0]['password'] == md5(utf8_encode($pwd))) {
					//Check IPs
					if ($data['root'][0]['restriccionPorIP'] > 0) {
						if (checkIP($data['root'][0]['idUsuario'])) {
							$a['success'] = true;
							$a['successMessage'] = "Accesso Correcto";
						} else {
							$a['success'] = false;
							$a['errorMessage'] = "IP Restringida";
							$_SESSION['idUsuario'] = "";
							$_SESSION['usuario']   = "";
							$_SESSION['nombreUsr'] = "";
							$_SESSION['correoUsr'] = "";
							return $a;
						}
					}
					//Checks Horario
					if ($data['root'][0]['restriccionPorHorario'] > 0) {
						if (checkHorario($data['root'][0]['idUsuario'])) {
							$a['success'] = true;
							$a['successMessage'] = "Accesso Correcto";
						} else {
							$a['success'] = false;
							$a['errorMessage'] = "Horario Restringido";
							$_SESSION['idUsuario'] = "";
							$_SESSION['usuario']   = "";
							$_SESSION['nombreUsr'] = "";
							$_SESSION['correoUsr'] = "";
							return $a;
						}
					}

					$a['success'] = true;
					$a['successMessage'] = "Accesso Correcto";
					
					$_SESSION['idUsuario'] = $data['root'][0]['idUsuario'];
					$_SESSION['usuario']   = $user;
					$_SESSION['nombreUsr'] = $data['root'][0]['nombre'];
					$_SESSION['correoUsr'] = $data['root'][0]['correoElectronico'];
					$_SESSION['wallpaper'] = $data['root'][0]['wallpaper'];
					$_SESSION['theme']     = $data['root'][0]['theme'];
					
				} else {
					$a['success'] = false;
					$a['errorMessage'] = "Usuario y/o Contrase&ntilde;a incorrecta";
					$_SESSION['idUsuario'] = "";
					$_SESSION['usuario']   = "";
					$_SESSION['nombreUsr'] = "";
					$_SESSION['correoUsr'] = "";
				}
			} else {
				$a['success'] = false;
				$a['errorMessage'] = "Usuario Inactivo";
				$_SESSION['idUsuario'] = "";
				$_SESSION['usuario']   = "";
				$_SESSION['nombreUsr'] = "";
				$_SESSION['correoUsr'] = "";
			}
		} else {
			$a['success'] = false;
			$a['errorMessage'] = "Usuario y/o Contrase&ntilde;a incorrecta";
			$_SESSION['idUsuario'] = "";
			$_SESSION['usuario']   = "";
			$_SESSION['nombreUsr'] = "";
			$_SESSION['correoUsr'] = "";
		}

		$a['idUsuario'] = $data['root'][0]['idUsuario'];
		return $a;
	}

	function checkIP($idUser){
		$sqlCheckIPStr = "SELECT ip FROM segUsuariosIpTbl WHERE idUsuario=".$idUser;
		
		$ipUsrArr = fn_ejecuta_query($sqlCheckIPStr);

		if (sizeof($ipUsrArr) > 0) {
			for ($iInt = 0; $iInt < sizeof($ipUsrArr); $iInt++) {
				if ($ipUsrArr['root'][$iInt]['ip'] == $_SERVER['REMOTE_ADDR']) {
					return true;
				}
			}

		} else {
			return true;
		}

		return false;
	}

	function checkHorario($idUser){
		return true;
	}

?>