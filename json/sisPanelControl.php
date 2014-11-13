<?php
    session_start();
	$_SESSION['modulo'] = "sisPanelControl";
    //SESION PARA PRUEBAS
   // $_SESSION['idUsuario'] = 1;
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
	
    switch($_REQUEST['sisPanelControlActionHdn']){
        case 'cambiarFondoPantalla':
            cambiarFondoPantalla();
            break;
        case 'cambiarTema':
            cambiarTema();
            break;
        case 'getDesktop':
            getDesktop();
            break;
        case 'cambiarDesktop':
            cambiarDesktop();
            break;
    }

    function cambiarFondoPantalla(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['sisPanelControlFondoPantallaHdn'] == "") {
            $e[] = array('id'=>'sisPanelControlFondoPantallaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdWallpaperStr = "UPDATE segUsuariosTbl ".
                                  "SET wallpaper='".$_REQUEST['sisPanelControlFondoPantallaHdn']."' ".
                                  "WHERE idUsuario=".$_SESSION['idUsuario'];

            $rs = fn_ejecuta_query($sqlUpdWallpaperStr);

            if (!isset($_SESSION['error_sql'])||(isset($_SESSION['error_sql'])&&$_SESSION['error_sql'])==""){
                $a['sql'] = $sqlUpdWallpaperStr;
                $a['successMessage'] = getSisWallpaperSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdWallpaperStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function cambiarTema(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['sisPanelControlTemaHdn'] == "") {
            $e[] = array('id'=>'sisPanelControlTemaHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlUpdThemeStr = "UPDATE segUsuariosTbl ".
                              "SET theme='".$_REQUEST['sisPanelControlTemaHdn']."' ".
                              "WHERE idUsuario=".$_SESSION['idUsuario'];

            $rs = fn_ejecuta_query($sqlUpdThemeStr);

            if (!isset($_SESSION['error_sql'])||(isset($_SESSION['error_sql'])&&$_SESSION['error_sql'])==""){
                $a['sql'] = $sqlUpdThemeStr;
                $a['successMessage'] = getSisThemeSuccessMsg();
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlUpdThemeStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function getDesktop(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['sisPanelControlUsuarioHdn'], "idUsuario", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetUsuariosDesktop = "SELECT * ".
                                 "FROM segUsuariosDesktopTbl ".$lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetUsuariosDesktop);

        echo json_encode($rs);
    }

    function cambiarDesktop(){
        $a = array();
        $e = array();
        $a['success'] = true;

        $moduloArr = explode("|", substr($_REQUEST['sisPanelControlModuloHdn'], 0, -1));
        if (in_array('', $moduloArr)) {
            $e[] = array('id'=>'sisPanelControlModuloHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $nombreArr = explode("|", substr($_REQUEST['sisPanelControlNombreHdn'], 0, -1));
        if (in_array('', $nombreArr)) {
            $e[] = array('id'=>'sisPanelControlNombreHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        $iconoArr = explode("|", substr($_REQUEST['sisPanelControlIconoHdn'], 0, -1));
        if (in_array('', $iconoArr)) {
            $e[] = array('id'=>'sisPanelControlIconoHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            //Borra los registros del usuario primero
            $sqlCleanUsuarioStr = "DELETE FROM segUsuariosDesktopTbl ".
                                  "WHERE idUsuario =".$_SESSION['idUsuario'];

            $rs = fn_ejecuta_query($sqlCleanUsuarioStr);

            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                $sqlCambiarDesktopStr = "INSERT INTO segUsuariosDesktopTbl (idUsuario, module, name, iconCls) VALUES";

                for ($iInt=0; $iInt < sizeof($moduloArr); $iInt++) { 
                    if ($iInt != 0) {
                        $sqlCambiarDesktopStr .= ",";
                    }

                    $sqlCambiarDesktopStr .= "(".$_SESSION['idUsuario'].",".
                                             "'".$moduloArr[$iInt]."',".
                                             "'".$nombreArr[$iInt]."',".
                                             "'".$iconoArr[$iInt]."')";
                }

                $rs = fn_ejecuta_query($sqlCambiarDesktopStr);

                if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                    $a['sql'] = $sqlCambiarDesktopStr;
                    $a['successMessage'] = getSisDesktopSuccessMsg();
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlCambiarDesktopStr;
                }            
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql']."<br>".$sqlCleanUsuarioStr;
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>