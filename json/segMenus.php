<?php
	session_start();
	$_SESSION['modulo'] = "segMenusDetalle";
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
	
    switch($_REQUEST['segMenusDetalleActionHdn']){
        case 'getArbolMenu':
        	getArbolMenu();
        	break;
        default:
            echo '';
    }

    function getArbolMenu(){
    	$lsWhereStr = "WHERE m.idModulo = md.idModulo ";

    	if ($gb_error_filtro == 0){
    		$lsCondicionStr = fn_construct($_REQUEST[''], "", 0);
		    $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
	    }

	    $lsWhereStr .= "ORDER BY md.secuencial ";

	    $sqlGetMenuDetalleStr = "SELECT md.*, m.tipoModulo, m.nombreMenuArbol, m.modulo ".
	    						"FROM segMenusDetalleTbl md, sisModulosTbl m ".$lsWhereStr;  
		
		$rs = fn_ejecuta_query($sqlGetMenuDetalleStr);
		$menu = $rs['root'];

		for ($iInt=0; $iInt < sizeof($menu); $iInt++) { 
			if ($menu[$iInt]['tipoModulo'] == 0) {
				$tree['text'] = $menu[$iInt]['nombreMenuArbol'];
                $tree['claveMenu'] = $menu[$iInt]['modulo'];
				$tree['children'] = array();
			} elseif ($menu[$iInt]['tipoModulo'] == 1) {
				$tempChildOne = array("text"=>$menu[$iInt]['nombreMenuArbol'],"claveMenu"=>$menu[$iInt]['modulo']);
				for ($jInt=0; $jInt < sizeof($menu); $jInt++) {
					if ($menu[$jInt]['idModuloPadre'] != 1 && ($menu[$jInt]['idModuloPadre'] == $menu[$iInt]['idModulo'])) {
						if (!isset($tempChildOne['children'])) {
							$tempChildOne['children'] = array(); 							
						}
						if ($menu[$jInt]['tipoModulo'] == 2) {
							array_push($tempChildOne['children'],array("text"=>$menu[$jInt]['nombreMenuArbol'],"claveMenu"=>$menu[$jInt]['modulo'],"children"=>checkLevelTwo($menu, $jInt)));
						} else {
							array_push($tempChildOne['children'],array("text"=>$menu[$jInt]['nombreMenuArbol'],"claveMenu"=>$menu[$jInt]['modulo']));
						}
					}	
				}
				//echo "-".json_encode($tempChildOne)."<br>";
				array_push($tree['children'], $tempChildOne);
			} elseif ($menu[$iInt]['tipoModulo'] == 3 && $menu[$iInt]['idModuloPadre'] == 1) {
				array_push($tree['children'], array("text"=>$menu[$iInt]['nombreMenuArbol'],"claveMenu"=>$menu[$jInt]['modulo']));
			}
		}
			
		echo json_encode(array($tree));
    }

    function checkLevelTwo($menu, $parent){
    	$temp = array();
    	for ($iInt=0; $iInt < sizeof($menu); $iInt++) { 
    		if ($menu[$iInt]['idModuloPadre'] == $menu[$parent]['idModulo']) {
    			//echo $menu[$iInt]['nombreMenuArbol']."<br>";
    			if ($menu[$iInt]['tipoModulo'] == 2) {
    				array_push($temp, array("text"=>$menu[$iInt]['nombreMenuArbol'],"claveMenu"=>$menu[$iInt]['modulo'],"children"=>checkLevelTwo($menu, $iInt)));
    			} else {
    				array_push($temp, array("text"=>$menu[$iInt]['nombreMenuArbol'],"claveMenu"=>$menu[$iInt]['modulo']));
    			}
    		}
    	}
    	//echo "2: ".json_encode($temp)."<br>";
    	return $temp;
    }
?>