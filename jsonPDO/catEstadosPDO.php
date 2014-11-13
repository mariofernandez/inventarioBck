<?php
    //***********
    //FOR PDO USE
    //***********
    //CHECKED
    //***********
    session_start();
	$_SESSION['modulo'] = "catEstados";
    require("../funciones/generalesPDO.php");
    require("../funciones/construct.php");
	
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
	
    switch($_REQUEST['catEstadosActionHdn'])
	{
        case 'getEstados':
            getEstados();
            break;
        case 'getEstadosPorNombre':
            getEstadosPorNombre();
            break;
        case 'addEstado':
        	addEstado();
        	break;
        case 'updEstado':
        	updEstado();
        	break;
        case 'dltEstado':
        	dltEstado();
        	break;
    }

    function getEstados(){
    	$lsWhereStr = "WHERE p.idPais = e.idPais";

	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catEstadosIdEstadoHdn'], "idEstado", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
	    if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catEstadosIdPaisHdn'], "p.idPais", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }
		if ($gb_error_filtro == 0){
    		$ls_condicion = fn_construct($_REQUEST['catEstadosEstadoTxt'], "estado", 1);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $ls_condicion);
	    }

		$sqlGetEstadosStr = "SELECT e.idEstado, e.idPais, e.estado, p.pais ".
                            "FROM caestadostbl e, capaisestbl p " . $lsWhereStr;     
		
		$rs = fn_ejecuta_query($sqlGetEstadosStr);
		  
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }

    function getEstadosPorNombre(){

        $sqlGetEstadosStr = "SELECT e.estado FROM caestadostbl e, capaisestbl p ".
                            "WHERE e.idPais=p.idPais AND p.pais='".$_REQUEST['catDistribuidoresPaisHdn']."'";
        
        $rs = fn_ejecuta_query($sqlGetEstadosStr);
          
        $iInt = 0;
        $response->success = true;
        $response->records = $total;
        
        foreach($rs as $line){
            $response->root[$iInt] = $line;
            $iInt++;
        }
            
        echo json_encode($response);
    }

    function addEstado(){

    	$a = array();
        $e = array();
        $a['success'] = true;

    	if($_REQUEST['catEstadosIdPaisHdn'] == ""){
            $e[] = array('id'=>'catEstadosIdPaisHdn','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['catEstadosEstadoTxt'] == ""){
            $e[] = array('id'=>'catEstadosEstadoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        //Revisa que no exista un estado igual en el mismo Pais
        $sqlCheckEstadoStr = "SELECT * FROM caestadostbl ".
        					 "WHERE idPais=".$_REQUEST['catEstadosIdPaisHdn']." ".
        					 "AND estado= '".$_REQUEST['catEstadosEstadoTxt']."'";
        
        $rs = fn_ejecuta_query($sqlCheckEstadoStr);

        if(count($rs)){
        	$a['errorMessage'] = getEstadoDuplicateMsg();
        	$a['success'] = false;
        }

        if($a['success'] == true){

        	$sqlAddEstadoStr = "INSERT INTO caestadostbl (idPais, estado) ".
        					   "VALUES (".
        					   $_REQUEST['catEstadosIdPaisHdn'].", ".
        					   "'".$_REQUEST['catEstadosEstadoTxt']."')";
			
			$rs = fn_ejecuta_query($sqlAddEstadoStr);


        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlAddEstadoStr;
                $a['successMessage'] = getEstadoSuccessMsg();
                $a['id'] = $_REQUEST['catEstadosEstadoTxt'];;
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddEstadoStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updEstado(){
    	$a = array();
        $e = array();
        $a['success'] = true;

    	if($_REQUEST['catEstadosEstadoTxt'] == ""){
            $e[] = array('id'=>'catEstadosEstadoTxt','msg'=>getRequerido());
			$a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        //Revisa que no exista un estado de nombre igual en el mismo Pais
        $sqlCheckEstadoStr = "SELECT * FROM caestadostbl ".
        					 "WHERE idPais=".$_REQUEST['catEstadosIdPaisHdn']." ".
        					 "AND estado= '".$_REQUEST['catEstadosEstadoTxt']."' ".
                             "AND idEstado != '".$_REQUEST['catEstadosIdEstadoHdn']."'";
        
        $rs = fn_ejecuta_query($sqlCheckEstadoStr);

        if(count($rs)){
        	$a['errorMessage'] = getEstadoDuplicateMsg();
        	$a['success'] = false;
        }

        if($a['success'] == true){

        	$sqlUpdEstadoStr = "UPDATE caestadostbl ".
        					   "SET estado= '".$_REQUEST['catEstadosEstadoTxt']."' ".
        					   "WHERE idPais=".$_REQUEST['catEstadosIdPaisHdn']." ".
        					   "AND idEstado=".$_REQUEST['catEstadosIdEstadoHdn'];
        	
        	$rs = fn_ejecuta_query($sqlUpdEstadoStr);


        	if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
			    $a['sql'] = $sqlUpdEstadoStr;
                $a['successMessage'] = getEstadoUpdateMsg();
                $a['id'] = $_REQUEST['catEstadosIdEstadoHdn'];;
			} else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdEstadoStr;
			}
        }
        $a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function dltEstado(){
    	$a = array();
        $e = array();
        $a['success'] = true;

    	$sqlDeleteEstadoStr = "DELETE FROM caestadostbl WHERE idEstado=".$_REQUEST['catEstadosIdEstadoHdn'];
       
        $rs = fn_ejecuta_query($sqlDeleteEstadoStr);

        if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
			$a['sql'] = $sqlDeleteEstadoStr;
            $a['successMessage'] = getEstadoDeleteMsg();
            $a['id'] = $_REQUEST['catEstadosIdEstadoHdn'];
		} else {
	        $a['success'] = false;
            $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeleteEstadoStr;
		}
		
		$a['errors'] = $e;
		$a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>