<?php
    session_start();
	$_SESSION['modulo'] = "sisModulosTbl";
	//SESION PARA PRUEBAS
    //$_SESSION['idUsuario'] = 1;
    //$_SESSION['nombreUsr'] = "Alfonso Martinez";
    require("../funciones/generales.php");
    require("../funciones/construct.php");

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

    switch($_REQUEST['sisModulosActionHdn']){
        case 'getModulos':
            getModulos();
            break;
        case 'getModulosNoDesktop':
            getModulosNoDesktop();
            break;
    }

    function getModulos(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['sisPanelControlUsuarioHdn'], "idUsuario", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetModulosStr = "SELECT * ".
                            "FROM sisModulosTbl ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetModulosStr);

        echo json_encode($rs);
    }

    function getModulosNoDesktop(){
    	$lsWhereStr = "WHERE sm.modulo NOT IN (SELECT module ".
    				  "FROM segUsuariosDesktopTbl WHERE idUsuario=".$_SESSION['idUsuario'].") ";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['sisPanelControlUsuarioHdn'], "idUsuario", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetModulosStr = "SELECT sm.* ".
                            "FROM sisModulosTbl sm ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetModulosStr);

        echo json_encode($rs);
    }


?>