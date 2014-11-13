<?php
	session_start();
	$_SESSION['modulo'] = "catGrupos";
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
    switch($_REQUEST['catGruposActionHdn']){
        case 'getGrupos':
            getGrupos();
            break;
        case 'addGrupo':
        	addGrupo();
        	break;
        case 'updGrupo':
        	updGrupo();
        	break;
    }
        function addGrupo(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['catGrupoGrupoTxt'] == ""){
            $e[] = array('id'=>'catGrupoGrupoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
         if($a['success'] == true){

            $sqlAddGrupoStr = "INSERT INTO algrupospatiotbl ".
                                "(grupo, clasificacion, distribuidor) VALUES(".
                                $_REQUEST['catGrupoGrupoTxt'].", ".
                                "'".$_REQUEST['catGrupoClasificacionHdn']."', ".
                                "'".$_REQUEST['catGrupoDistribuidorHdn']."')";
            
            $rs = fn_ejecuta_query($sqlAddGrupoStr);

            if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                $a['sql'] = $sqlAddGrupoStr;
                $a['successMessage'] = getGrupoSuccessMsg();
                $a['id'] = $_REQUEST['catGrupoGrupoTxt'];;
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddGrupoStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

?>