<?php
	session_start();
	require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

    $_REQUEST = trasformUppercase($_REQUEST);

    switch($_SESSION['idioma']){
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
		case 'getKilometrosPlazaOrigenes':
			getKilometrosPlazaOrigenes();
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
		$lsWhereStr = "";
	
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catPlazasIdPlazaHdn'], "p.idPlaza", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catPlazasPlazaTxt'], "p.plaza", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catPlazasEstatusHdn'], "p.estatus", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		if (isset($_REQUEST['catPlazasNoIdPlazaHdn']) && $_REQUEST['catPlazasNoIdPlazaHdn'] != "") {
    			$lsCondicionStr = fn_construct("!=".$_REQUEST['catPlazasNoIdPlazaHdn'], "p.idPlaza", 0);
		    	$lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
    		}
	    }
		
		$sqlGetStr = "SELECT p.idPlaza, p.plaza, p.estatus, ".
					 "(SELECT g.nombre FROM caGeneralesTbl g WHERE g.tabla='caPlazasTbl' AND g.columna='estatus' AND g.valor=p.estatus) AS 'nombreEstatus' ".
					 "FROM caPlazasTbl p ".$lsWhereStr;
	
		$rs = fn_ejecuta_query($sqlGetStr);
		
		echo json_encode($rs);
	}
	
	function getKilometrosPlaza(){
		$lsWhereStr = "";
		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catPlazasIdPlazaHdn'], "k.idPlazaOrigen", 0);
    		$lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaIdDestinoHdn'], "k.idPlazaDestino", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaKilometrosTxt'], "k.kilometros", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosTarifaCobradaTxt'], "k.tarifaCobradaPorKm", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPLazaTarifaIdaTxt'], "k.tarifaEspecialIda", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaTarifaRegresoTxt'], "k.tarifaEspecialRegreso", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaRetencionHdn'], "k.retencion", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaDiasEntregaHdn'], "k.diasEntrega", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	
		$sqlGetKmPlazaStr = "SELECT k.idPlazaOrigen, k.idPlazaDestino, k.kilometros, k.tarifaCobradaPorKm, ".
							"k.tarifaEspecialIda, k.tarifaEspecialRegreso, k.retencion,  k.diasEntrega, ".
							"(SELECT p1.plaza FROM caPlazasTbl p1 where p1.idPlaza = k.idPlazaOrigen) as nombreOrigen, ".
							"(SELECT p2.plaza FROM caPlazasTbl p2 where p2.idPlaza = k.idPlazaDestino) as nombreDestino ".
							"FROM caKilometrosPlazaTbl k ".$lsWhereStr;
		
		$rs = fn_ejecuta_query($sqlGetKmPlazaStr);
		
		echo json_encode($rs);
	}

	function getKilometrosPlazaOrigenes(){
		$lsWhereStr = "";
		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catPlazasIdPlazaHdn'], "k.idPlazaOrigen", 0);
    		$lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaIdDestinoHdn'], "k.idPlazaDestino", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaKilometrosTxt'], "k.kilometros", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosTarifaCobradaTxt'], "k.tarifaCobradaPorKm", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPLazaTarifaIdaTxt'], "k.tarifaEspecialIda", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaTarifaRegresoTxt'], "k.tarifaEspecialRegreso", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }		
		if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaRetencionHdn'], "k.retencion", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	    if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST['catKilometrosPlazaDiasEntregaHdn'], "k.diasEntrega", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }
	
		$sqlGetKmPlazaStr = "SELECT k.idPlazaOrigen, ".
							"(SELECT p1.plaza FROM caPlazasTbl p1 where p1.idPlaza = k.idPlazaOrigen) as nombreOrigen ".
							"FROM caKilometrosPlazaTbl k ".$lsWhereStr." GROUP BY idPlazaOrigen";
		
		$rs = fn_ejecuta_query($sqlGetKmPlazaStr);
		
		echo json_encode($rs);
	}
	
	function addPlaza(){

		$a = array();
        $e = array();
        $a['success'] = true;

		if($_REQUEST['catPlazasPlazaTxt'] == ""){
            $e[] = array('id'=>'catPlazasPlazaTxt','msg'=>getRequerido());
			$a['errorMess'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catPlazasEstatusHdn'] == ""){
            $e[] = array('id'=>'catPlazasEstatusHdn','msg'=>getRequerido());
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
    	$diasEntregaArr = explode('|', substr($_REQUEST['catKilometrosPlazaDiasEntregaHdn'], 0, -1));
		if(in_array('', $diasEntregaArr)){
        	$e[] = array('id'=>'catKilometrosPlazaDiasEntregaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
        	$a['success'] = false;
    	}
			
		if($a['success'] == true){
			$sqlAddPlazaStr = "INSERT INTO caPlazasTbl (plaza, estatus) VALUES(".
							  "'".$_REQUEST['catPlazasPlazaTxt']."',".
							  "'".$_REQUEST['catPlazasEstatusHdn']."')";


			$rs = fn_ejecuta_query($sqlAddPlazaStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				$sqlGetIdPlazaStr = "SELECT LAST_INSERT_ID() AS idPlaza";

				$rs = fn_ejecuta_query($sqlGetIdPlazaStr);
				$idPlazaOrigen = $rs['root'][0]['idPlaza'];

				//Obtiene los arrays de los datos del grid
				//Y se separan por pipes en un arreglo
				$kilometrosArr = explode('|', substr($_REQUEST['catKilometrosPlazaKilometrosHdn'], 0, -1));
				$tarifaKmArr = explode('|', substr($_REQUEST['catKilometrosPlazaTarifaCobradaHdn'], 0, -1));
				$tarifaEspIdaArr = explode('|', substr($_REQUEST['catKilometrosPlazaTarifaIdaHdn'], 0, -1));
				$tarifaEspRegresoArr = explode('|', substr($_REQUEST['catKilometrosPlazaTarifaRegresoHdn'], 0, -1));
				$retencionArr = explode('|', substr($_REQUEST['catKilometrosPlazaRetencionHdn'], 0, -1));

				//Funcion separada para guardar en caKilometrosPlazaTbl
				$temp = addKmPlaza($idPlazaOrigen,$idDestinoArr,$diasEntregaArr,$kilometrosArr,$tarifaKmArr,$tarifaEspIdaArr,$tarifaEspRegresoArr,$retencionArr);
				$a['success'] = $temp['success'];
		        $a['success'] = false;
				$e[] = $temp['errors'];
				$a['successMessage'] = $temp['successMessage']; 
				$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddKmPlazaStr;
			} else {
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddPlazaStr;
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

        if($_REQUEST['catPlazasIdPlazaHdn'] == ""){
            $e[] = array('id'=>'catPlazasIdPlazaHdn','msg'=>getRequerido());
			$a['errorMess'] = getErrorRequeridos();
            $a['success'] = false;
        }
		if($_REQUEST['catPlazasPlazaTxt'] == ""){
            $e[] = array('id'=>'catPlazasPlazaTxt','msg'=>getRequerido());
			$a['errorMess'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catPlazasEstatusHdn'] == ""){
            $e[] = array('id'=>'catPlazasEstatusHdn','msg'=>getRequerido());
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
    	$diasEntregaArr = explode('|', substr($_REQUEST['catKilometrosPlazaDiasEntregaHdn'], 0, -1));
		if(in_array('', $diasEntregaArr)){
        	$e[] = array('id'=>'catKilometrosPlazaDiasEntregaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
        	$a['success'] = false;
    	}

		//Select query
		if ($a['success'] == true) {
			$sqlChangeDescStr = "UPDATE caPlazasTbl ".
						    	"SET plaza= '".$_REQUEST['catPlazasPlazaTxt']."', ".
						    	"estatus ='".$_REQUEST['catPlazasEstatusHdn']."' ".
						    	"WHERE idPlaza=".$_REQUEST['catPlazasIdPlazaHdn'];
			
			$rs = fn_ejecuta_query($sqlChangeDescStr);

			if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
				//Obtiene los arrays de los datos del grid
				//Y se separan por pipes en un arreglo
				$kilometrosArr = explode('|', substr($_REQUEST['catKilometrosPlazaKilometrosHdn'], 0, -1));
				$tarifaKmArr = explode('|', substr($_REQUEST['catKilometrosPlazaTarifaCobradaHdn'], 0, -1));
				$tarifaEspIdaArr = explode('|', substr($_REQUEST['catKilometrosPlazaTarifaIdaHdn'], 0, -1));
				$tarifaEspRegresoArr = explode('|', substr($_REQUEST['catKilometrosPlazaTarifaRegresoHdn'], 0, -1));
				$retencionArr = explode('|', substr($_REQUEST['catKilometrosPlazaRetencionHdn'], 0, -1));
				//Funcion separada para guardar en caKilometrosPlazaTbl
				$temp = addKmPlaza($_REQUEST['catPlazasIdPlazaHdn'],$idDestinoArr,$diasEntregaArr,$kilometrosArr,$tarifaKmArr,$tarifaEspIdaArr,$tarifaEspRegresoArr,$retencionArr);

				$a = $temp;
			} else {	
        		$a['success'] = false;
            	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlChangeDescStr;
   	 		}
		}

		$a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
	    echo json_encode($a);
	}
	//array insertarKmPlaza(int,array,array,array,array,array,array,array)
	function addKmPlaza($idPlazaOrigen,$idDestinoArr,$diasEntregaArr,$kilometrosArr,$tarifaKmArr,$tarifaEspIdaArr,$tarifaEspRegresoArr,$retencionArr){
		$sqlCleanKmPlazaPorOrigenStr = "DELETE * FROM caKilometrosPlazaTbl ".
									   "WHERE idPlazaOrigen = ".$idPlazaOrigen;
			
		$rs = fn_ejecuta_query($sqlCleanKmPlazaPorOrigenStr);

		$sqlAddKmPlazaStr = "INSERT INTO caKilometrosPlazaTbl VALUES";
		
		for($nInt=0;$nInt<sizeof($idDestinoArr);$nInt++){
			if($nInt != 0){
    			$sqlAddKmPlazaStr = $sqlAddKmPlazaStr.",";
    		}

			$sqlAddKmPlazaStr = $sqlAddKmPlazaStr.
							  	"(".$idPlazaOrigen.", ".
							  	$idDestinoArr[$nInt].", ".
							  	replaceEmptyDec($kilometrosArr[$nInt]).", ".
								replaceEmptyDec($tarifaKmArr[$nInt]).", ".
							  	replaceEmptyDec($tarifaEspIdaArr[$nInt]).", ".
							  	replaceEmptyDec($tarifaEspRegresoArr[$nInt]).", ".
							  	replaceEmptyDec($retencionArr[$nInt]).", ".
							  	$diasEntregaArr[$nInt].
							  	")";
		}
		
		$rs = fn_ejecuta_query($sqlAddKmPlazaStr);

		if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
	    	$a['sql'] = $sqlAddKmPlazaStr;
        	$a['successMessage'] = getPlazaSuccessMsg();
		} else {
       		$a['success'] = false;
        	$a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddKmPlazaStr;
		}

		$a['errors'] = $e;
		return $a;
	}
?>
