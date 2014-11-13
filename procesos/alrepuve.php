<?php
	require("../funciones/generales.php");
	require("../funciones/utilidades.php");

	global $dirPath;
	global $filePath;
	global $fileName;
	date_default_timezone_set('America/Mexico_City');

	$dirPath = "/home/poncho/www/carbookBck/";
	$fileName= "RECIBIDO.txt";
	$filePath = $dirPath.$fileName;

	echo "Inicio: ".date("Y-m-d H:i", strtotime("now"))."\r\n";
	if(file_exists($filePath)){ 
	   cargaRepuve();
	} else { 
	   	echo "El archivo no existe\r\n"; 
	}
	echo "Terminó: ".date("Y-m-d H:i", strtotime("now"))."\r\n";

	function cargaRepuve(){
		global $dirPath;
		global $filePath;
		global $fileName;
		
		$repuveFile = fopen($filePath, "r");//abres el archivo para lectura 
		
		$fecha = date("d-m-Y_H-i-s");
		$nInt = 1;
		if(file($filePath)) {
			while (!feof($repuveFile)) {
   				$linea = fgets($repuveFile);
   				insertarRepuve(rtrim(ltrim($linea)), $nInt);
   				$nInt++;
			}	
        	
        	copy($filePath,$dirPath.'RECIBIDO_'.$fecha.'.txt');
	        unlink($filePath);
         
        } else {
        	echo "No se pudo abrir el archivo\r\n";
      	}
      	fclose($repuveFile);
	}

	function insertarRepuve($linea, $numLineaInt){
		if(substr($linea, -2) == 'OK'){			
			$vin = substr($linea, 0,17);
 	     	$folio = substr($linea, 17,8);
	     	$tag_id = substr($linea, 25,28);
	     	$anio = substr($linea, 53,4);
	     	$mes = substr($linea, 57,2);
	     	$dia = substr($linea, 59,2);
	     	$hora = substr($linea, 61,2);
	     	$mins = substr($linea, 63,2);
	     	$seg = substr($linea, 65,2);
	     	$status = substr($linea, 67,2);
	     	$observaciones = substr($linea, 69,80);

	     	$sqlInsertRepuve = "INSERT INTO alrepuvetbl ".
	     					   "VALUES('".$folio."','DC','0','".$tag_id."', ";

	     	if ($anio == '' || $mes == '' || $dia == ''){
	     		$sqlInsertRepuve .= "NULL";
	     	} else {
	     		$sqlInsertRepuve .= "'".$anio."-".$mes."-".$dia." ".$hora.":".$mins.":".$seg."', ";
	     	}
	     					   	
	     	$sqlInsertRepuve .= "'".$status."','".$observaciones."')";
			
			$sqlUpdateFolioUnidades = "UPDATE alunidadestbl ".
									  "SET folioRepuve ='".$folio."' ".
									  "WHERE vin='".$vin."';";

			$sqlCheckVin = "SELECT vin FROM alunidadestbl WHERE vin='".$vin."'";
			$rs = fn_ejecuta_query($sqlCheckVin);

			$rsIn = fn_ejecuta_query($sqlInsertRepuve);

			if(mysql_num_rows($rs) == 1){
				$rsUp = fn_ejecuta_query($sqlUpdateFolioUnidades);

				if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    	echo $numLineaInt.": OK\r\n";
				} else {
                	echo $numLineaInt.": ".$_SESSION['error_sql']."\r\n";
				}

			} else {
				echo $numLineaInt.": No existe el vin.\r\n";
			}

		}
	}
?>