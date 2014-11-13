<?php
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

    function leerXLS($inputFileName)
	{
    	/** Error reporting */
	    error_reporting(E_ALL);
		
		date_default_timezone_set ("America/Mexico_City");
		
		//  Include PHPExcel_IOFactory
		include '../funciones/Classes/PHPExcel/IOFactory.php';
		
		//$inputFileName = str_replace('json', 'Temp', str_replace('alUnidadesLeeArchivo.php', 'avanzadas.xls', __FILE__));
		//echo $inputFileName;
		//  Read your Excel workbook
		try {
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader =
			 PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}
		
		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		$iInt = 0;
		$root = array();
		//  Loop through each row of the worksheet in turn
		for ($row = 1; $row <= $highestRow; $row++){ 
			//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
											NULL,
											TRUE,
											FALSE);

			$root[$iInt] = array($rowData[$iInt][0], $rowData[$iInt][1], $rowData[$iInt][2],$rowData[$iInt][3],$rowData[$iInt][4]);
			//  Insert row data array into your database of choice here
			//Validar si las unidades estan bien o no
			$vVin = strlen($rowData[$iInt][0]);
			$vSimbolo = strlen($rowData[$iInt][1]);
			$vDistribuidor = strlen($rowData[$iInt][2]);
			$vColor = strlen($rowData[$iInt][3]);
			$vAvanzada = substr($rowData[$iInt][0],9,17);

			$lsWhereStr_01 = "WHERE distribuidorCentro ='".$rowData[$iInt][2]."'";
			$sqlDistribuidoresStr = "SELECT distribuidorCentro FROM caDistribuidoresCentrosTbl ".$lsWhereStr_01;
			$rsDistribuidor = fn_ejecuta_query($sqlDistribuidoresStr);
			$cDistribuidor = $rsDistribuidor['root'][0]['distribuidorCentro'];

			$lsWhereStr_02 = "WHERE simboloUnidad ='".$rowData[$iInt][1]."'";
			$sqlSomboloStr = "SELECT simboloUnidad FROM caSimbolosUnidadesTbl ".$lsWhereStr_02;
			$rsSimbolo = fn_ejecuta_query($sqlSomboloStr);
			$cSimbolo = $rsSimbolo['root'][0]['simboloUnidad'];

			$lsWhereStr_03 = "WHERTE color ='".$rowData[$iInt][3]."'";
			$sqlColorStr = "SELECT color FROM cacolorunidadestbl ".$lsWhereStr_03;
			$rsColor = fn_ejecuta_query($sqlColorStr);
			$cColor = $rsColor['root'][0]['color'];

			$lsWhereStr_04 = "WHERE vin ='".$rowData[$iInt][0]."'";
			$sqlVintr = "SELECT vin FROM alUnidadesTbl ".$lsWhereStr_04;
			$rsVin = fn_ejecuta_query($sqlVintr);
			$cVin = $rsVin['root'][0]['vin'];

			$lsWhereStr_05 = "WHERE A.clasificacion = B.clasificacion ".
							 "AND B.idTarifa = C.idTarifa ".
							 "AND C.tipoTarifa = 'N' ".
							 "AND A.simboloUnidad = '".$rowData[$iInt][1]."'";

			$sqlidTrarifatr = "SELECT B.idTarifa FROM casimbolosunidadestbl A, caclasificaciontarifastbl B, catarifastbl C ".$lsWhereStr_05;
			$rsTarifa = fn_ejecuta_query($sqlidTrarifatr);

			$vIdTarifa = $rsTarifa['root'][0]['idTarifa'];


        
			if($rsColor > 0 and  $vColor > 0 )
			{
				if($cDistribuidor != '' and $vDistribuidor == '5')
				{
					if($cSimbolo != '' and $vSimbolo > 0)
					{
						if($cVin == '' and $vVin == '17')
						{							
							$root[$iInt] = array('VIN'=>$rowData[$iInt][0],'simbolo'=>$rowData[$iInt][1],'distribuidor'=>$rowData[$iInt][2],'color'=>$rowData[$iInt][3],'nose'=>'Unidad Cargada...');
							
							$sqlAddUnidadStr = "INSERT INTO alUnidadesTbl ".
                               "VALUES(".
                               "'".$rowData[$iInt][0]."',".
                               "'".substr($rowData[$iInt][0], -8)."',".
                               "'".$rowData[$iInt][2]."',".
                               "'".$rowData[$iInt][1]."',".
                               "'".$rowData[$iInt][3]."',".
                               replaceEmptyNull("'".$repuve."'").")";
							$rs_01 = fn_ejecuta_query($sqlAddUnidadStr);
            				
            				$sqlAddHistoricoUnidadStr = "INSERT INTO alHistoricoUnidadesTbl ".
                                "(centroDistribucion, vin, fechaEvento, claveMovimiento, distribuidor, idTarifa, ".
                                "localizacionUnidad, claveChofer, observaciones, usuario, ip) ".
                                "VALUES(".
                                "'CDVER',".
                                "'".$rowData[$iInt][0]."',".
                                "(SELECT CURRENT_TIMESTAMP),".
                                "'PR',".
                                "'".$rowData[$iInt][2]."',".
                                "'".$vIdTarifa."',".
                                "'PVER1',".
                                replaceEmptyNull("'".$chofer."'").",".
                                "'ARCHIVO DE CARGA MASIVA',".
                                "'".$_SESSION['usuario']."',".
                                "'".$_SERVER['REMOTE_ADDR']."')";
            				$rs_02 = fn_ejecuta_query($sqlAddHistoricoUnidadStr);

						}
						else
						{
							$root[$iInt] = array('VIN'=>$rowData[$iInt][0],'simbolo'=>$rowData[$iInt][1],'distribuidor'=>$rowData[$iInt][2],'color'=>$rowData[$iInt][3],'nose'=>'El VIN ya existe o no tiene los 17 caracteres');

						}
					}
					else
					{
						$root[$iInt] = array('VIN'=>$rowData[$iInt][0],'simbolo'=>$rowData[$iInt][1],'distribuidor'=>$rowData[$iInt][2],'color'=>$rowData[$iInt][3],'nose'=>'El Simbolo No existe en el Sistema');
					}
				}
				else
				{
					$root[$iInt] = array('VIN'=>$rowData[$iInt][0],'simbolo'=>$rowData[$iInt][1],'distribuidor'=>$rowData[$iInt][2],'color'=>$rowData[$iInt][3],'nose'=>'El Distribuidor No existe en el Sistema');	
				}		
			}
			else
			{				
				$root[$iInt] = array('VIN'=>$rowData[$iInt][0],'simbolo'=>$rowData[$iInt][1],'distribuidor'=>$rowData[$iInt][2],'color'=>$rowData[$iInt][3],'nose'=>'El Color No existe');	
			}

			$iInt++;
		}
		return $root;
	}
?>