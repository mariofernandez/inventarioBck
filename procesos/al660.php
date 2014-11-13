<?php
	require("../funciones/generales.php");
	require("../funciones/utilidades.php");

	global $dirPath;
	global $filePath;
	global $fileName;
	date_default_timezone_set('America/Mexico_City');

	$dirPath = "/Users/poncho/www/carbookBck/";
	$fileName= "chr660.txt";
	$filePath = $dirPath.$fileName;

	echo "Inicio: ".date("Y-m-d H:i", strtotime("now"))."\r\n";
	if(file_exists($filePath)){ 
	   carga660();
	} else { 
	   	echo "El archivo no existe\r\n"; 
	}

	function carga660(){
		global $dirPath;
		global $filePath;
		global $fileName;
		global $lineaStr;
		global $cont;

		$_660File = fopen($filePath, "r");//abres el archivo para lectura 
		$fecha = date("d-m-Y_H-i-s");

		if(file($filePath)) {
			//fn_ejecuta_query("LOAD DATA LOCAL INFILE '$filePath' INTO TABLE alcarga660tmptbl ");
			$cont = 1;
			while (!feof($_660File)) {
				$lineaStr = fgets($_660File);

				if($lineaStr!=''){
					$cabeceroStr = substr($lineaStr,0,2);

					//echo $lineaStr."<br>";
					if ($cabeceroStr == 'HD'){
						//echo $cont."- ";
						//echo $lineaStr."<br>";
						datosHD($lineaStr);
					}else{
						insertarDatos($lineaStr);
					}
				}
				$cont = $cont + 1;	
			}
		}else {
        	echo "No se pudo abrir el archivo\r\n";
      	}
      	copy($filePath, $dirPath.'respaldo/chr660_'.date("Y-m-d H:i").'.txt');
     	unlink($filePath);
      	echo "Terminó: ".date("Y-m-d H:i", strtotime("now"))."\r\n";
	}

	function datosHD($lineaStr){
		global $scacCodeStr;
	    global $estatusBol;
        global $oriSplcStr;
        global $desSplcStr;
        global $marcaStr;
        global $transStr;
        global $cobradoStr;
        global $cont;

        $scacCodeStr = substr($lineaStr,3,4);
	    $estatusBol = substr($lineaStr,7,2);
        $oriSplcStr = substr($lineaStr,10,9);
        $desSplcStr= substr($lineaStr,19,9);
        $marcaStr = 'DC';
        $transStr = '100';
        $cobradoStr = 'N';
	}

	function insertarDatos($lineaStr){
		global $scacCodeStr;
	    global $estatusBol;
        global $oriSplcStr;
        global $desSplcStr;
        global $marcaStr;
        global $transStr;
        global $cobradoStr;
        global $filePath;
        global $cont;

        $avanzadaStr= substr($lineaStr,12,8);
	    $vinStr = substr($lineaStr,3,17);
	    $rutaDestinoStr = substr($lineaStr,33,13);
	    $distribuidorStr = substr($lineaStr,62,5);
	    $simboloStr = substr($lineaStr,109,6);           
	    $updateStr = substr($lineaStr,84,6);
	    $colorStr = substr($lineaStr,136,3); 

		validarDistribuidor($distribuidorStr);
		validarColor($colorStr);
		validarSimbolo($simboloStr);
		
		$sqlAdd = "INSERT INTO al660tbl ".
								"VALUES (".
								"'".$vinStr."', ".
								"'".$avanzadaStr."', ".
								"'".$scacCodeStr."', ".
								"'".$estatusBol."', ".
								"'".$oriSplcStr."', ".
								"'".$desSplcStr."', ".
								"'', ".//routeori 
								"'".$rutaDestinoStr."', ".
								"'', ".//von
								"'".$distribuidorStr."', ".
								"'', ".//startdate
								"'', ".//enddate
								"'', ".//iddealer2
								"'".$updateStr."', ".
								"'', ".//vuptime
								"'', ".//estimdate
								"'', ".//expedite 
								"'', ".//merlcode
								"'".$simboloStr."', ".
								"'', ".//city
								"'', ".//state
								"'".$colorStr."', ".
								"'', ".//ladingdes
								"'', ".//authoriza
								"'', ".//weight
								"'', ".//height
								"'', ".//lenght
								"'', ".//width
								"'', ".//volume
								"'')";//tipoOrigen
//echo $sqlAdd."<br>";
		fn_ejecuta_query($sqlAdd);
		
     		
	}

	function validarDistribuidor($dist){
		$rs = fn_ejecuta_query("SELECT distribuidorCentro FROM cadistribuidorescentrostbl WHERE distribuidorCentro =='".$dist."'");
		if (sizeof($rs['root']) > 0){
			$sqlAdd = "INSERT INTO cadistribuidorescentrostbl ".
						"(distribuidorCentro, descripcionCentro, tipoDistribuidor, idPlaza, rutaDestino, direccionFiscal,".
							"direccionEntrega, sueldoGarantizado, idRegion, estatus, tieneRepuve, detieneUnidades) ".	
						  "VALUES (".
						  "'".$dist."', ".
						  "'DISTRIBUIDOR GENERICO CARGA 660', ".
						  "'DI', ".
						  "1, ".
						  "'M1',".
						  "1,".
						  "1,".
						  "1,".
						  "1,".
						  "0,".
						  "0)";

			fn_ejecuta_query($sqlAdd);
		}
	}

	function validarColor($col){
		$rs = fn_ejecuta_query("SELECT color FROM cacolorunidadestbl WHERE color =='".$col."'");
		if (sizeof($rs['root']) > 0){
			$sqlAdd = "INSERT INTO cacolorunidadestbl ".
        					  "VALUES(".
        					  "'DC', ".
        					  "'".$col."', ".
        					  "'COLOR GENERICO 660')";
			fn_ejecuta_query($sqlAdd);
		}
	}

	function validarSimbolo($simb){
		$rs = fn_ejecuta_query("SELECT simboloUnidad FROM caSimbolosUnidadesTbl WHERE simboloUnidad =='".$simb."'");
		if (sizeof($rs['root']) > 0){
			$sqlAdd = "INSERT INTO caSimbolosUnidadesTbl (simboloUnidad, descripcion, tipoOrigenUnidad, clasificacion, importeBonificacion, tieneRepuve, marca, tipoUnidad) " . 
                   "VALUES (".
                   "'" . $simb . "',".
                   "'SIMBOLO GENERICO 660', " . 
                   "'N',".
                   "'99', " .
                   "0.00,".
                   "0," .
                   "'DC',".
                   "'A')";
			fn_ejecuta_query($sqlAdd);
		}
	}
?>