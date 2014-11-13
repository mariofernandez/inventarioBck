<?php
    session_start();
    $_SESSION['modulo'] = "caPaises";
    require_once("../funciones/generales.php");
    require_once("../funciones/construct.php");
    require_once("../funciones/utilidades.php");

    //$_REQUEST = trasformUppercase($_REQUEST);
    
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
    
    switch($_REQUEST['caPaisesActionHdn']){
        case 'getPaises':
            getPaises();
            break;
        case 'addPais':
            addPais();
            break;
        case 'updPais':
            updPais();
            break;
        case 'dltPais':
            dltPais();
            break;
    }

    function getPaises(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caPaisesIdPaisHdn'], "idPais", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['caPaisesDescripcionTxt'], "descripcion", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetPaisStr = "SELECT * FROM caPaises ".$lsWhereStr." order by descripcion;";
            
        $rs = fn_ejecuta_query($sqlGetPaisStr);
        
        echo json_encode($rs);
    }

    function addPais(){

        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caPaisesDescripcionTxt'] == ""){
            $e[] = array('id'=>'caPaisesDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($a['success'] == true){
            $sqlAddPaisStr = "INSERT INTO caPaises (descripcion) ".
                             "VALUES (".
                                "'".$_REQUEST['caPaisesDescripcionTxt']."')";

            $rs = fn_ejecuta_query($sqlAddPaisStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['successMessage'] = getPaisSuccessMsg();                
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddPaisStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updPais(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caPaisesIdPaisHdn'] == ""){
            $e[] = array('id'=>'caPaisesIdPaisHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
                if($_REQUEST['caPaisesDescripcionTxt'] == ""){
            $e[] = array('id'=>'caPaisesDescripcionTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }


        if ($a['success'] == true){
            $sqlUpdatePaisStr = "UPDATE caPaises ".
                                "SET descripcion= '".$_REQUEST['caPaisesDescripcionTxt']."' ".
                                "WHERE idPais=".$_REQUEST['caPaisesIdPaisHdn'];

            $rs = fn_ejecuta_query($sqlUpdatePaisStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['successMessage'] = getPaisUpdateMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdatePaisStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);

    }

    function dltPais(){

        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['caPaisesIdPaisHdn'] == ""){
            $e[] = array('id'=>'caPaisesIdPaisHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if ($a['success'] == true){
            $sqlDeletePaisStr = "DELETE FROM caPaises WHERE idPais=".$_REQUEST['caPaisesIdPaisHdn'];
            
            $rs = fn_ejecuta_query($sqlDeletePaisStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['sql'] = $sqlDeletePaisStr;
                $a['successMessage'] = getPaisDeleteMsg();
                $a['id'] = $_REQUEST['caPaisesIdPaisHdn'];
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlDeletePaisStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);       
}
?>