<?php
	//***********
    //FOR PDO USE
    //***********
	//CHECKED
	//***********
	session_start();
	require("../funciones/generalesPDO.php");
    require("../funciones/construct.php");
    require("../funciones/utilidades.php");

    switch($_SESSION['idioma'])
	{
        case 'ES':
            include("../funciones/idiomas/mensajesES.php");
            break;
        case 'EN':
            include("../funciones/idiomas/mensajesEN.php");
            break;
        default:
            include("../funciones/idiomas/mensajesES.php");
    }
	
	switch($_REQUEST['catPlazasActionHdn']){
		case 'getPlazas':
			getPlazas();
			break;
		case 'getKilometrosPlaza':
			getKilometrosPlaza();
			break;
		case 'addPlaza':
			addPlaza();
			break;
		case 'updPlaza':
			updPlaza();
			break;
		default:
			echo '';	
	}
	
	function getPlazas(){
	
		$lsWhereStr = "WHERE g.tabla='caplazastbl' ".
					  "AND p.estatus = g.valor";
	
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catPlazasIdPlazaHdn'], "idPlaza", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catPlazasDescPlazaTxt'], "plaza", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catPlazasEstatusHdn'], "estatus", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		
		$sqlGetPlazasStr = "SELECT p.idPlaza, p.plaza, p.estatus, g.nombre ".
					 "FROM caplazastbl p, cageneralestbl g " . $lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetPlazasStr);
		
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
	}
	
	function getKilometrosPlaza(){
		$lsWhereStr = "WHERE k.idPlazaDestino = p.idPlaza";
		
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catKilometrosPlazaIdDestinoHdn'], "k.idPlazaDestino", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }

	    if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catKilometrosPlazaKilometrosTxt'], "k.kilometros", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catKilometrosTarifaCobradaTxt'], "k.tarifaCobradaPorKm", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catKilometrosPLazaTarifaIdaTxt'], "k.tarifaEspecialIda", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catKilometrosPlazaTarifaRegresoTxt'], "k.tarifaEspecialRegreso", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		
		if ($gb_error_filtro == 0)
	   	{
    		$ls_condicion = fn_construct($_REQUEST['catKilometrosPlazaRetencionTxt'], "k.retencion", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	
		$sqlGetKmPlazaStr = "SELECT k.idPlazaOrigen, k.idPlazaDestino, k.kilometros, k.tarifaCobradaPorKm, ".
							"k.tarifaEspecialIda, k.tarifaEspecialRegreso, k.retencion, p.plaza ".
							"FROM cakilometrosplazatbl k, caplazastbl p ".$lsWhereStr;

		echo $sqlGetKmPlazaStr;
		$rs = fn_ejecuta_query($sqlGetKmPlazaStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
	}
	
	function addPlaza(){

		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catPlazasDescPlazaTxt'] == "")
        {
            $e[] = array('id'=>'catPlazasDescPlazaTxt','msg'=>getRequerido());
			$a['errorMess'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catPlazasEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catPlazasEstatusHdn','msg'=>getRequerido());
			$a['errorMess'] = getErrorRequeridos();
            $a['success'] = false;
        }		
			
		if($a['success'] == true){
			$sqlAddPlazaStr = "INSERT INTO caplazastbl (plaza, estatus) VALUES("
				.'"'.$_REQUEST['catPlazasDescPlazaTxt'].'", '
				.'"'.$_REQUEST['catPlazasEstatusHdn'].'")';


			$rs = fn_ejecuta_query($sqlAddPlazaStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddPlazaStr;
            	$a['successMessage'] = getPlazaSuccessMsg();
			} else {
            	$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddPlazaStr;
        	}
        }


        //Revisa que ningun valor de estas variables en el grid sea vacia
		$idDestinoArr = explode('|', substr($_REQUEST['catKilometrosPlazaIdDestinoHdn'], 0, -1));
		if(in_array('', $idDestinoArr)){
        	$e[] = array('id'=>'catKilometrosPlazaIdDestinoHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $diasEntregaArr = explode('|', substr($_REQUEST['catKilometrosPlazaDiasEntregaTxt'], 0, -1));
		if(in_array('', $idDestinoArr)){
        	$e[] = array('id'=>'catKilometrosPlazaDiasEntregaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
		
		if($a['success'] == true){
			//Obtiene el iD de la plaza ya creada
			$sqlGetIdStr = "SELECT idPlaza ".
					 	 "FROM caplazastbl ".
					 	 "WHERE plaza=".'"'.$_REQUEST['catPlazasDescPlazaTxt'].'"';
					 
			$rs = fn_ejecuta_query($sqlGetIdStr);
			
			foreach ($rs as $line) {
			 	$idOrigenInt = $line['idPlaza'];
			 }

			//Se inserta el valor para kilometrosplaza por default
			$sqlInsertDefaultKmStr = "INSERT INTO cakilometrosplazatbl ".
									 "VALUES (".$idOrigenInt.",".$idOrigenInt.", 0, 0, 0, 0, 0, 0)";
			
			$rs = fn_ejecuta_query($sqlInsertDefaultKmStr);

			//Separa los valores pipeados que faltan que pueden ser vacios
			$kilometrosArr = explode('|', $_REQUEST['catKilometrosPlazaKilometrosHdn']);
			$tarifaKmArr = explode('|', $_REQUEST['catKilometrosTarifaCobradaHdn']);
			$tarifaEspIdaArr = explode('|', $_REQUEST['caKilometrosPlazaTarifaIdaHdn']);
			$tarifaEspRegresoArr = explode('|', $_REQUEST['catKilometrosPlazaTarifaRegresoHdn']);
			$retencionArr = explode('|', $_REQUEST['catKilometrosPlazaRetencionTxt']);

			//Crea el query INSERT y lo ejecuta por cada línea
			if($idDestinoArr[0] != ''){
				for($nInt=0;$nInt<count($idDestinoArr);$nInt++){
					$sqlAddKmPlazaStr = "INSERT INTO cakilometrosplazatbl ".
							 			"VALUES (".
							 			$idOrigenInt.", ".
							 			$idDestinoArr[$nInt].", ".
							 			replaceEmptyDec($kilometrosArr[$nInt]).", ".
							 			replaceEmptyDec($tarifaKmArr[$nInt]).", ".
							 			replaceEmptyDec($tarifaEspIdaArr[$nInt]).", ".
								 		replaceEmptyDec($tarifaEspRegresoArr[$nInt]).", ".
							 			replaceEmptyDec($retencionArr[$nInt]).", ".
							 			$diasEntregaArr[$nInt].
							 			")";
							 
				$rs = fn_ejecuta_query($sqlAddKmPlazaStr);
				}
			}

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$a['sql'] = $sqlAddKmPlazaStr;
            	$a['successMessage'] = getPlazaSuccessMsg();
			} else {
	            $a['success'] = false;
            	$a['errorMess'] = $_SESSION['error_sql'] . "<br>" . $sqlAddKmPlazaStr;
        	}
		}
		$a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}
	
	function updPlaza(){
		
		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catPlazasDescPlazaTxt'] == "")
        {
            $e[] = array('id'=>'catPlazasDescPlazaTxt','msg'=>getRequerido());
			$a['errorMess'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catPlazasEstatusHdn'] == "")
        {
            $e[] = array('id'=>'catPlazasEstatusHdn','msg'=>getRequerido());
			$a['errorMess'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catKilometrosPlazaIdDestinoHdn'] == "")
        {
            $e[] = array('id'=>'catKilometrosPlazaIdDestinoHdn','msg'=>getRequerido());
			$a['errorMess'] = getErrorRequeridos();
            $a['success'] = false;
        }

        //Revisa que ningun valor de estas variables en el grid sea vacia
		$idDestinoArr = explode('|', substr($_REQUEST['catKilometrosPlazaIdDestinoHdn'], 0, -1));
		if(in_array('', $idDestinoArr)){
        	$e[] = array('id'=>'catKilometrosPlazaIdDestinoHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $diasEntregaArr = explode('|', substr($_REQUEST['catKilometrosPlazaDiasEntregaTxt'], 0, -1));
		if(in_array('', $idDestinoArr)){
        	$e[] = array('id'=>'catKilometrosPlazaDiasEntregaTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

		//Select query
		if ($a['success'] == true) {

			$sqlGetDescStr = "SELECT * FROM caplazastbl ".
						     "WHERE idPlaza=".$_REQUEST['catPlazasIdPlazaHdn'];

			$rs = fn_ejecuta_query($sqlGetDescStr);
			
			if(count($rs) > 0){
				$sqlChangeDescStr = "UPDATE caplazastbl ".
							    	"SET plaza= '".$_REQUEST['catPlazasDescPlazaTxt']."' ".
							    	"WHERE idPlaza=".$_REQUEST['catPlazasIdPlazaHdn'];
				
				$rs = fn_ejecuta_query($sqlChangeDescStr);

				if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
					$a['sql'] = $sqlChangeDescStr;
	            	$a['successMessage'] = getPlazaUpdMsg();
				} else {	
            		$a['success'] = false;
	            	$a['errorMess'] = $_SESSION['error_sql'] . "<br>" . $sqlChangeDescStr;
       	 		}
			}

			//Obtiene los arrays de los datos del grid
			//Y se separan por pipes en un arreglo
			$kilometrosArr = explode('|', substr($_REQUEST['catKilometrosPlazaKilometrosHdn'], 0, -1));
			$tarifaKmArr = explode('|', substr($_REQUEST['catKilometrosTarifaCobradaHdn'], 0, -1));
			$tarifaEspIdaArr = explode('|', substr($_REQUEST['caKilometrosPlazaTarifaIdaHdn'], 0, -1));
			$tarifaEspRegresoArr = explode('|', substr($_REQUEST['catKilometrosPlazaTarifaRegresoHdn'], 0, -1));
			$retencionArr = explode('|', substr($_REQUEST['catKilometrosPlazaRetencionHdn'], 0, -1));
			
			$sqlAddKmPlazaStr = "INSERT INTO cakilometrosplazatbl VALUES";
			$newInt = 0;

			for($nInt=0;$nInt<count($idDestinoArr);$nInt++){
				$sqlGetLineStr = "SELECT * ".
						  		 "FROM cakilometrosplazatbl ".
						  		 "WHERE idPlazaOrigen=".$_REQUEST['catPlazasIdPlazaHdn']." ".
						  		 "AND idPlazaDestino=".$idDestinoArr[$nInt];
				
				$rs = fn_ejecuta_query($sqlGetLineStr);
			
				if(count($rs)){
					$sqlUpdKmPlazaStr = "UPDATE cakilometrosplazatbl ".
									"SET kilometros=".replaceEmptyDec($kilometrosArr[$nInt]).", ".
									"tarifaCobradaPorKm=".replaceEmptyDec($tarifaKmArr[$nInt]).", ".
									"tarifaEspecialIda=".replaceEmptyDec($tarifaEspIdaArr[$nInt]).", ".
									"tarifaEspecialRegreso=".replaceEmptyDec($tarifaEspRegresoArr[$nInt]).", ".
									"retencion=".replaceEmptyDec($retencionArr[$nInt]).", ".
									"diasEntrega=".$diasEntregaArr[$nInt]." ".
									"WHERE idPlazaDestino=".$idDestinoArr[$nInt]." ".
									"AND idPlazaOrigen=".$_REQUEST['catPlazasIdPlazaHdn'];
						
						$rs = fn_ejecuta_query($sqlUpdKmPlazaStr);

						if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
							$a['sql'] = $sqlUpdKmPlazaStr;
            				$a['successMessage'] = getPlazaUpdMsg();
						} else {
            				$a['success'] = false;
            				$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdKmPlazaStr;
        				}

				} else {   //Si no hay lineas lo inserta como nuevo
 					$sqlAddKmPlazaStr = $sqlAddKmPlazaStr.
							  	"(".$_REQUEST['catPlazasIdPlazaHdn'].", ".
							  	$idDestinoArr[$nInt].", ".
							  	replaceEmptyDec($kilometrosArr[$nInt]).", ".
								replaceEmptyDec($tarifaKmArr[$nInt]).", ".
							  	replaceEmptyDec($tarifaEspIdaArr[$nInt]).", ".
							  	replaceEmptyDec($tarifaEspRegresoArr[$nInt]).", ".
							  	replaceEmptyDec($retencionArr[$nInt]).", ".
							  	$diasEntregaArr[$nInt].
							  	")";
					
					if($nInt+1<count($idDestinoArr)){
            			$sqlAddKmPlazaStr = $sqlAddKmPlazaStr.",";
            		}

            		$newInt++;	
				}	
			}
			if($newInt > 0){
				$rs = fn_ejecuta_query($sqlAddKmPlazaStr);

				if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    	$a['sql'] = $sqlAddKmPlazaStr;
                	$a['successMessage'] = getPlazaSuccessMsg();
                	$a['id'] = $_REQUEST['catPlazasIdPlazaHdn'];
				} else {
               		$a['success'] = false;
                	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddKmPlazaStr;
				}
			}
		}
		$a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
	    echo json_encode($a);
	}
?>
