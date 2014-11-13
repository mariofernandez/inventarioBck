<?php
	require_once("../funciones/generales.php");
	require_once("../funciones/utilidades.php");
	require("../json/alUnidades.php");

	date_default_timezone_set('America/Mexico_City');

	$filePath = str_replace("procesos\\", "", str_replace("hold.php", "hold.txt", __FILE__));

	if (file_exists($filePath)) {
		$holdFile = fopen($filePath, "r");
		$listaVin = "";
		$listaClaves = "";
		while (!feof($holdFile)) {
			$linea = fgets($holdFile);
			$temp = explode("|", rtrim(ltrim($linea)));
			
			$listaVin .= $temp[0]."|";
			$listaClaves .= $temp[1]."|";
		}	

		$a = holdUnidades($listaVin, $listaClaves);
		echo "Correctos: ".$a['ok']."\r\n";
		echo "Fallos: ".$a['fail'];
	}	

?>