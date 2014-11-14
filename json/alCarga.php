<?php
	require("../funciones/generales.php");
	require("../funciones/utilidades.php");
	global $dirPath;
	global $filePath;
	global $fileName;
	date_default_timezone_set('America/Mexico_City');
	$dirPath = "C:/carbook";
	$fileName= "chr660.txt";
	$filePath = $dirPath.$fileName;
	echo "Inicio: ".date("Y-m-d H:i", strtotime("now"))."\r\n";
	if(file_exists($filePath)){ 
	   carga660();
	} else { 
	   	echo "El archivo no existe\r\n";
	   	echo "$dirPath"; 
	}
	sdffk
?>
