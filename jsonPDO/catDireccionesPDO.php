<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
    session_start();
	$_SESSION['modulo'] = "catDirecciones";
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

        switch($_REQUEST['catDireccionesActionHdn'])
	{
        case 'getDireccionesCombo':
            getDireccionesCombo();
            break;
        case 'getDirecciones':
        	getDirecciones();
        	break;   
        case 'addDirecciones':
        	addDirecciones();
        	break;
        case 'updDirecciones':
            updDirecciones();
            break;                                  
        default:
        	echo '';
            
    }

   	function getDireccionesCombo(){
		$ls_where = "WHERE d.idColonia = c.idColonia ". 
		            "AND c.idMunicipio = m.idMunicipio ".
		            "AND m.idEstado = e.idEstado ".
		            "AND e.idPais = p.idPais"; 


		$sqlGetDireccionesComboStr = "SELECT CONCAT(d.calleNumero,', ', c.colonia,', ',m.municipio,', ".
									 "',e.estado,', ', p.pais,', ', c.cp) AS DIRECCION " .
		       						 "FROM cadireccionestbl d, cacoloniastbl c, camunicipiostbl m, capaisestbl p, ".
		       						 "caestadostbl e  " . $ls_where;       
		
		$rs = fn_ejecuta_query($sqlGetDireccionesComboStr);
        
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
	}

	function getDirecciones(){
		$lsWhereStr = "WHERE d.idColonia = c.idColonia ". 
		              "AND c.idMunicipio = m.idMunicipio ".
		              "AND m.idEstado = e.idEstado ".
		              "AND e.idPais = p.idPais ";

        if($_REQUEST['catDistribuidoresDistribuidorTxt'] != ''){
            $lsWhereStr .= "AND d.distribuidor = '".$_REQUEST['catDistribuidoresDistribuidorTxt']."'";
        }

		$sqlGetDireccionesStr = "SELECT d.direccion, d.calleNumero, c.idColonia, c.colonia, d.distribuidor, ".
								"m.idMunicipio, m.municipio, e.idEstado, e.estado, p.idPais, p.pais ".
								"FROM cadireccionestbl d, cacoloniastbl c, camunicipiostbl m, caestadostbl e, capaisestbl p ".$lsWhereStr;
		//echo $sqlGetDireccionesStr;
		$rs = fn_ejecuta_query($sqlGetDireccionesStr);
		  
        $i = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$i] = $line;
            $i++;
        }
            
        echo json_encode($response);
	}

	function addDirecciones(){
		$a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catDireccionesCalleNumeroHdn'] == "") {
            $e[] = array('id'=>'catDireccionesCalleNumeroHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDireccionesIdColoniaHdn'] == "") {
            $e[] = array('id'=>'catDireccionesIdColoniaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        //Revisar que ningun dato del grid esté vacio
        $calleNumeroArr = explode('|', substr($_REQUEST['catDireccionesCalleNumeroHdn'], 0, -1));
        if(in_array('', $calleNumeroArr)){
        	$e[] = array('id'=>'catDireccionesCalleNumeroHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $coloniaArr = explode('|', substr($_REQUEST['catDireccionesIdColoniaHdn'], 0, -1));
        if(in_array('', $coloniaArr)){
        	$e[] = array('id'=>'catDireccionesIdColoniaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	//Explode al campo que puede ser vacio
        	$distribuidorArr = explode('|', substr($_REQUEST['catDireccionesDistribuidorTxt'], 0, -1));

        	$sqlAddDireccionesStr = "INSERT INTO cadireccionestbl (calleNumero, idColonia, distribuidor) VALUES";

        	for($nInt=0; $nInt<count($calleNumeroArr); $nInt++){
        		$sqlAddDireccionesStr .= "('".$calleNumeroArr[$nInt]."', ".
        								 $coloniaArr[$nInt].", ".
        								 replaceEmptyNull("'".$distribuidorArr[$nInt]."'").")";

				if($nInt+1<count($calleNumeroArr)){
            		$sqlAddDireccionesStr .= ",";
            	}
        	}

        	$rs = fn_ejecuta_query($sqlAddDireccionesStr);
        	
        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
			{
			    $a['sql'] = $sqlAddDireccionesStr;
                $a['successMessage'] = getDireccionesSuccessMsg();
                $a['id'] = $_REQUEST['catDireccionesCalleNumeroHdn'];
			}
		    else
			{
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddDireccionesStr;
			}
		}
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
	}

	function updDirecciones(){
		$a = array();
        $e = array();
        $a['success'] = true;
		
		if($_REQUEST['catDireccionesCalleNumeroHdn'] == "") {
            $e[] = array('id'=>'catDireccionesCalleNumeroHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catDireccionesIdColoniaHdn'] == "") {
            $e[] = array('id'=>'catDireccionesIdColoniaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        //Revisar que ningun dato del grid esté vacio
        $calleNumeroArr = explode('|', substr($_REQUEST['catDireccionesCalleNumeroHdn'], 0, -1));
        if(in_array('', $calleNumeroArr)){
        	$e[] = array('id'=>'catDireccionesCalleNumeroHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $coloniaArr = explode('|', substr($_REQUEST['catDireccionesIdColoniaHdn'], 0, -1));
        if(in_array('', $coloniaArr)){
        	$e[] = array('id'=>'catDireccionesIdColoniaHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
        	//Explode al campo que puede ser vacio
        	$distribuidorArr = explode('|', substr($_REQUEST['catDireccionesDistribuidorTxt'], 0, -1));

        	$sqlAddDireccionesStr = "INSERT INTO cadireccionestbl (calleNumero, idColonia, distribuidor) VALUES";

        	for($nInt=0; $nInt<count($calleNumeroArr); $nInt++){
        		//Si no tiene distribuidor inserta como nueva la direccion con distribuidor NULL
        		if($distribuidorArr[$nInt] == ''){
        			$sqlAddDireccionesStr .= "('".$calleNumeroArr[$nInt]."', ".
        								 	$coloniaArr[$nInt].", ".
        								 	replaceEmptyNull("'".$distribuidorArr[$nInt]."'").")";

					if($nInt+1<count($calleNumeroArr)){
            			$sqlAddDireccionesStr .= ",";
            		}
            	//Si ya tiene le da UPDATE a sus campos
            	} else {
            		$sqlUpdDireccionesStr = "UPDATE cadireccionestbl ".
        									"SET calleNumero='".$calleNumeroArr[$nInt]."', ".
        									"colonia='".$coloniaArr[$nInt]."', ".
        									"WHERE distribuidor='".$distribuidorArr[$nInt]."'";

        			$rs = fn_ejecuta_query($sqlUpdDireccionesStr);
            	}
        	}

        	$rs = fn_ejecuta_query($sqlAddDireccionesStr);
        	
        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == ""))
			{
			    $a['sql'] = $sqlAddDireccionesStr;
                $a['successMessage'] = getDireccionesUpdateMsg();
                $a['id'] = $_REQUEST['catDireccionesCalleNumeroHdn'];
			}
		    else
			{
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddDireccionesStr;
			}
		}
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);	
	}
?>