<?php
    session_start();
	$_SESSION['modulo'] = "catMarcasCd";
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
	
    switch($_REQUEST['catMarcasCdActionHdn']){
        case 'getDisponibles':
            getDisponibles();
            break;
        case 'getAsignadas':
            getAsignadas();
            break;
        case 'asignarMarca':
            asignarMarca();
            break;
    }

    function getDisponibles(){
        $sqlGetDisponiblesStr = "SELECT mu.* FROM caMarcasUnidadesTbl mu ".
                                "WHERE mu.marca NOT IN ".
                                "(SELECT md.marca FROM caMarcasDistribuidoresCentrosTbl md ".
                                "WHERE distribuidor = '".$_REQUEST['catMarcasCdDistribuidorHdn']."')";

        $rs = fn_ejecuta_query($sqlGetDisponiblesStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['concatMarca'] = $rs['root'][$iInt]['marca']." - ".$rs['root'][$iInt]['descripcion'];
        }
            
        echo json_encode($rs);    
    }

    function getAsignadas(){
        $lsWhereStr =  "WHERE mu.marca = md.marca ".
                       "AND distribuidor = '".$_REQUEST['catMarcasCdDistribuidorHdn']."'";


        $sqlMarcasCentroDistStr = "SELECT md.*, mu.descripcion ".
                                  "FROM caMarcasDistribuidoresCentrosTbl md, caMarcasUnidadesTbl mu ".$lsWhereStr;
        
        $rs = fn_ejecuta_query($sqlMarcasCentroDistStr);

        for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
            $rs['root'][$iInt]['concatMarca'] = $rs['root'][$iInt]['marca']." - ".$rs['root'][$iInt]['descripcion'];
        }
            
        echo json_encode($rs);    
    }

    function asignarMarca(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if ($_REQUEST['catMarcasCdDistribuidorHdn'] == "") {
            $e[] = array('id'=>'catMarcasCdDistribuidorHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlBorrasAsignacionesStr = "DELETE FROM caMarcasDistribuidoresCentrosTbl ".
                                        "WHERE distribuidor = '".$_REQUEST['catMarcasCdDistribuidorHdn']."'";

            $rs = fn_ejecuta_query($sqlBorrasAsignacionesStr);
        }

        if ($a['success'] == true) {
            if ($_REQUEST['catMarcasCdAsignadasHdn'] != "|") {
                $marcasAsignadas = explode('|', substr($_REQUEST['catMarcasCdAsignadasHdn'], 0, -1));

                $sqlAsignarMarcaStr = "INSERT INTO caMarcasDistribuidoresCentrosTbl VALUES";

                for ($i = 0; $i<sizeof($marcasAsignadas);$i++) {
                    if($i != 0){
                        $sqlAsignarMarcaStr .= ",";
                    }
                    $sqlAsignarMarcaStr .= "('".$_REQUEST['catMarcasCdDistribuidorHdn']."','".$marcasAsignadas[$i]."')";
                }

                $rs = fn_ejecuta_query($sqlAsignarMarcaStr);

                if((!isset($_SESSION['error_sql'])) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")){
                    $a['sql'] = $sqlAsignarMarcaStr;
                    $a['successMessage'] = getAsignarMarcaSuccess();
                    $a['id'] = $_REQUEST['catMarcasCdDistribuidorHdn'];;
                } else {
                    $a['success'] = false;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAsignarMarcaStr;
                }
            } else {
                $a['sql'] = $sqlAsignarMarcaStr;
                $a['successMessage'] = getAsignarMarcaSuccess();
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>