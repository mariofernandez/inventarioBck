<?php
    session_start();
	$_SESSION['modulo'] = "segUsuarios";
    //SESION PARA PRUEBAS
    //$_SESSION['idUsuario'] = 1;
    //$_SESSION['nombreUsr'] = "Alfonso Martinez";
    ////////////////////
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
	
    switch($_REQUEST['segUsuariosActionHdn']){
        case 'getUsuarios':
            getUsuarios();
            break;
        case 'addUsuario':
            addUsuario();
            break;
        case 'updUsuario':
            updUsuario();
            break;
        case 'getCompaniasUsuario':
            getCompaniasUsuario();
            break;
        case 'getCompaniasDisponibles':
            getCompaniasDisponibles();
            break;
        case 'getIPUsuario':
            getIPUsuario();
            break;
        case 'getHorariosUsuario':
            getHorariosUsuario();
            break;
        case 'getUsuarioConfig':
            getUsuarioConfig();
            break;
        case 'generaPassword':
            generaPassword();
            break;
        case 'cambiarPassword':
            cambiarPassword();
            break;
    }

    function getUsuarios(){
        $lsWhereStr = "";

        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['segUsuariosIdUsuarioHdn'], "a.idUsuario", 0);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['segUsuariosUsuarioTxt'], "a.usuario", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['segUsuariosNombreTxt'], "a.nombre", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['segUsuariosCorreoElectronicoTxt'], "a.correoElectronico", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }
        if ($gb_error_filtro == 0){
            $lsCondicionStr = fn_construct($_REQUEST['segUsuariosEstatusHdn'], "a.estatus", 1);
            $lsWhereStr = fn_concatena_condicion($lsWhereStr, $lsCondicionStr);
        }

        $sqlGetUsuariosStr = "SELECT a.*, " . 
                             "(SELECT b.nombre " .
                                "FROM caGenerales b " . 
                                "WHERE b.tabla = 'segUsuariosTbl' " . 
                                "AND b.columna = 'Estatus' " . 
                                "AND b.valor = a.estatus) AS nombreEstatus " . 
                             "FROM segUsuariosTbl a " . $lsWhereStr;

        $rs = fn_ejecuta_query($sqlGetUsuariosStr);

        echo json_encode($rs);
    }

    function addUsuario(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['segUsuariosUsuarioTxt'] == ""){
            $e[] = array('id'=>'segUsuariosUsuarioTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosNombreTxt'] == ""){
            $e[] = array('id'=>'segUsuariosNombreTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosCorreoElectronicoTxt'] == ""){
            $e[] = array('id'=>'segUsuariosCorreoElectronicoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosEstatusHdn'] == ""){
            $e[] = array('id'=>'segUsuariosEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            //Checar next increment value
            $sqlCheckNextValStr = "SHOW TABLE STATUS WHERE name = 'segUsuariosTbl'";

            $rs = fn_ejecuta_query($sqlCheckNextValStr);

            $row = mysql_fetch_array($rs);
            $idUsuario = $row['Auto_increment'];
            $pwd = generaPassword();
            $a['idUsuario'] = $idUsuario;
            $a['password'] = $pwd;
            $ipRest = substr($_REQUEST['segUsuariosIpHdn'], 0, -1) == "" ? 0 : 1;
            $horarioRest = substr($_REQUEST['segUsuariosHorariosDiasHdn'], 0, -1) == "" ? 0 : 1;

            $sqlAddUsuarioStr = "INSERT INTO segUsuariosTbl (usuario, password, nombre, correoElectronico, restriccionPorIP, ".
                                "restriccionPorHorario, estatus, wallpaper, theme)".
                                "VALUES(".
                                "'".$_REQUEST['segUsuariosUsuarioTxt']."',".
                                "'".md5($pwd)."',".
                                "'".$_REQUEST['segUsuariosNombreTxt']."',".
                                "'".$_REQUEST['segUsuariosCorreoElectronicoTxt']."',".
                                $ipRest.",". 
                                $horarioRest.",".
                                "'".$_REQUEST['segUsuariosEstatusHdn']."',".
                                "'wallpapers/Carbook-Inicial.jpg',".
                                "'classic')";

            $rs = fn_ejecuta_query($sqlAddUsuarioStr);

            //IP
            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                if ($ipRest == 1) {
                    $ipArr = explode('|', substr($_REQUEST['segUsuariosIpHdn'], 0, -1));
                    $ipFechaArr = explode('|', substr($_REQUEST['segUsuariosFechaIpHdn'], 0, -1));
                    
                    $sqlAddIpRestStr = "INSERT INTO segUsuariosIpTbl (idUsuario, fecha, ip, idUsuarioAct, ipAct) VALUES";

                    for ($iInt=0; $iInt < sizeof($ipArr); $iInt++) { 
                        if ($ipArr[$iInt] != "") {
                            if ($iInt != 0) {
                                $sqlAddIpRestStr .= ",";
                            }

                            $sqlAddIpRestStr .= "(".$idUsuario.",".
                                                "'".$ipFechaArr[$iInt]."',".
                                                "'".$ipArr[$iInt]."',".
                                                $_SESSION['idUsuario'].",".
                                                "'".$_SERVER['REMOTE_ADDR']."')";
                        }
                    }
                    
                    $rs = fn_ejecuta_query($sqlAddIpRestStr);

                    $a['sql'] = $sqlAddIpRestStr;
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
            }
            
            //Horarios
            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                if ($horarioRest == 1) {
                    $diasArr = explode("|", substr($_REQUEST['segUsuariosHorariosDiasHdn'], 0, -1));
                    $entradasArr = explode("|", substr($_REQUEST['segUsuariosHorariosEntradasHdn'], 0, -1));
                    $salidasArr = explode("|", substr($_REQUEST['segUsuariosHorariosSalidasHdn'], 0, -1));
                    $diaCompArr = explode("|", substr($_REQUEST['segUsuariosHorariosDiaCompletoHdn'], 0, -1));

                    $sqlAddHorarioRestStr = "INSERT INTO segUsuariosHorariosTbl ".
                                            "(idUsuario, dia, entrada, salida, diaCompleto) VALUES";

                    for ($iInt=0; $iInt < sizeof($diasArr); $iInt++) { 
                        if ($diasArr[$iInt] != "") {
                            if ($iInt != 0) {
                                $sqlAddHorarioRestStr .= ",";
                            }

                            $sqlAddHorarioRestStr .= "(".$idUsuario.",".
                                                    $diasArr[$iInt].",".
                                                    "'".$entradasArr[$iInt]."',".
                                                    "'".$salidasArr[$iInt]."',".
                                                    $diaCompArr[$iInt].")";
                        }
                    }

                    $rs = fn_ejecuta_query($sqlAddHorarioRestStr);

                    $a['sql'] = $sqlAddHorarioRestStr;
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
            }

            //Centros Distribucion
            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                if ($_REQUEST['segUsuariosCentroDistHdn'] != "") {

                    $centroDistArr = explode("|", substr($_REQUEST['segUsuariosCentroDistHdn'], 0, -1));

                    $sqlAddCentroDistStr = "INSERT INTO segusuarioscompaniastbl (idUsuario, claveCompania, fecha) VALUES";

                    for ($iInt=0; $iInt < sizeof($centroDistArr); $iInt++) { 
                        if ($centroDistArr[$iInt] != "") {
                            if ($iInt != 0) {
                                $sqlAddCentroDistStr .= ",";
                            }

                            $sqlAddCentroDistStr .= "(".$idUsuario.",".
                                                    "'".$centroDistArr[$iInt]."',".
                                                    "NOW())";
                        }
                    }

                    $rs = fn_ejecuta_query($sqlAddCentroDistStr);

                    $a['sql'] = $sqlAddCentroDistStr;

                    if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                        $a['successMessage'] = getUsuariosSuccessMsg();
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddCentroDistStr;
                    }
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
            }  

            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['successMessage'] =  getUsuariosSuccessMsg();
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function updUsuario(){
        $a = array();
        $e = array();
        $a['success'] = true;

        if($_REQUEST['segUsuariosIdUsuarioHdn'] == ""){
            $e[] = array('id'=>'segUsuariosIdUsuarioHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosUsuarioTxt'] == ""){
            $e[] = array('id'=>'segUsuariosUsuarioTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosNombreTxt'] == ""){
            $e[] = array('id'=>'segUsuariosNombreTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosCorreoElectronicoTxt'] == ""){
            $e[] = array('id'=>'segUsuariosCorreoElectronicoTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosEstatusHdn'] == ""){
            $e[] = array('id'=>'segUsuariosEstatusHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $ipRest = (empty($_REQUEST['segUsuariosIpHdn'])) ? 0 : 1;
            $horarioRest = empty($_REQUEST['segUsuariosHorariosDiasHdn']) ? 0 : 1;

            $sqlUpdUsuarioStr = "UPDATE segUsuariosTbl ".
                                "SET usuario='".$_REQUEST['segUsuariosUsuarioTxt']."',".
                                "nombre='".$_REQUEST['segUsuariosNombreTxt']."',".
                                "estatus='".$_REQUEST['segUsuariosEstatusHdn']."', ".
                                "restriccionPorIP=".$ipRest.",".
                                "restriccionPorHorario=".$horarioRest.",".
                                "correoElectronico = '".$_REQUEST['segUsuariosCorreoElectronicoTxt']."' ".
                                "WHERE idUsuario=".$_REQUEST['segUsuariosIdUsuarioHdn'];

            $rs = fn_ejecuta_query($sqlUpdUsuarioStr);

            //IP
            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                if ($ipRest == 1) {
                    //Borra las restricciones
                    $sqlDeleteIpRestStr = "DELETE FROM segUsuariosIpTbl WHERE idUsuario=".$_REQUEST['segUsuariosIdUsuarioHdn']; 

                    $rs = fn_ejecuta_query($sqlDeleteIpRestStr);

                    //Inserta las nuevas
                    $ipArr = explode('|', substr($_REQUEST['segUsuariosIpHdn'], 0, -1));
                    $ipFechaArr = explode('|', substr($_REQUEST['segUsuariosFechaIpHdn'], 0, -1));
                    
                    $sqlAddIpRestStr = "INSERT INTO segUsuariosIpTbl (idUsuario, fecha, ip, idUsuarioAct, ipAct) VALUES";

                    for ($iInt=0; $iInt < sizeof($ipArr); $iInt++) { 
                        if ($ipArr[$iInt] != "") {
                            if ($iInt != 0) {
                                $sqlAddIpRestStr .= ",";
                            }

                            $sqlAddIpRestStr .= "(".$_REQUEST['segUsuariosIdUsuarioHdn'].",".
                                                "'".$ipFechaArr[$iInt]."',".
                                                "'".$ipArr[$iInt]."',".
                                                $_SESSION['idUsuario'].",".
                                                "'".$_SERVER['REMOTE_ADDR']."')";
                        }
                    }
                    
                    $rs = fn_ejecuta_query($sqlAddIpRestStr);

                    $a['sql'] = $sqlAddIpRestStr;
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
            }
            
            //Horarios
            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                if ($horarioRest == 1) {

                    //Borra las restricciones
                    $sqlDeleteIpRestStr = "DELETE FROM segUsuariosHorariosTbl WHERE idUsuario=".$_REQUEST['segUsuariosIdUsuarioHdn']; 

                    $rs = fn_ejecuta_query($sqlDeleteIpRestStr);

                    //Inserta las nuevas
                    $diasArr = explode("|", substr($_REQUEST['segUsuariosHorariosDiasHdn'], 0, -1));
                    $entradasArr = explode("|", substr($_REQUEST['segUsuariosHorariosEntradasHdn'], 0, -1));
                    $salidasArr = explode("|", substr($_REQUEST['segUsuariosHorariosSalidasHdn'], 0, -1));
                    $diaCompArr = explode("|", substr($_REQUEST['segUsuariosHorariosDiaCompletoHdn'], 0, -1));

                    $sqlAddHorarioRestStr = "INSERT INTO segUsuariosHorariosTbl ".
                                            "(idUsuario, dia, entrada, salida, diaCompleto) VALUES";

                    for ($iInt=0; $iInt < sizeof($diasArr); $iInt++) { 
                        if ($diasArr[$iInt] != "") {
                            if ($iInt != 0) {
                                $sqlAddHorarioRestStr .= ",";
                            }

                            $sqlAddHorarioRestStr .= "(".$_REQUEST['segUsuariosIdUsuarioHdn'].",".
                                                    $diasArr[$iInt].",".
                                                    "'".$entradasArr[$iInt]."',".
                                                    "'".$salidasArr[$iInt]."',".
                                                    $diaCompArr[$iInt].")";
                        }
                    }

                    $rs = fn_ejecuta_query($sqlAddHorarioRestStr);

                    $a['sql'] = $sqlAddHorarioRestStr;
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
            }

            //Centros Distribucion
            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                //Borra los Centros de Distribución
                $sqlDeleteIpRestStr = "DELETE FROM segusuarioscompaniastbl WHERE idUsuario=".$_REQUEST['segUsuariosIdUsuarioHdn']; 

                $rs = fn_ejecuta_query($sqlDeleteIpRestStr);

                 //Inserta las nuevas
                if ($_REQUEST['segUsuariosCentroDistHdn'] != "") {
                    $centroDistArr = explode("|", substr($_REQUEST['segUsuariosCentroDistHdn'], 0, -1));

                    $sqlAddCentroDistStr = "INSERT INTO segusuarioscompaniastbl (idUsuario, claveCompania, fecha) VALUES";

                    for ($iInt=0; $iInt < sizeof($centroDistArr); $iInt++) { 
                        if ($centroDistArr[$iInt] != "") {
                            if ($iInt != 0) {
                                $sqlAddCentroDistStr .= ",";
                            }

                            $sqlAddCentroDistStr .= "(".$_REQUEST['segUsuariosIdUsuarioHdn'].",".
                                                    "'".$centroDistArr[$iInt]."',".
                                                    "NOW())";
                        }
                    }

                    $rs = fn_ejecuta_query($sqlAddCentroDistStr);

                    $a['sql'] = $sqlAddCentroDistStr;

                    if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                        $a['successMessage'] = getUsuariosUpdMsg();
                    } else {
                        $a['success'] = false;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $sqlAddCentroDistStr;
                    }
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
            }

            if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql'] == "")) {
                $a['successMessage'] =  getUsuariosUpdMsg();
            }

        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }

    function getCompaniasUsuario(){
		if($_REQUEST['segUsuariosIdUsuarioHdn'])
		{
			$sqlGetCompaniasUsuarioStr = "SELECT cc.claveCompania, cc.descripcion ".
                                         "FROM cacompanias cc, segusuarioscompaniastbl sc where ".
                                         "cc.claveCompania = sc.compania ".
                                         "AND sc.idUsuario =".$_REQUEST['segUsuariosIdUsuarioHdn']." ".
                                         "AND cc.tipoCompania = 'SU'";
		}
		else
		{
			$sqlGetCompaniasUsuarioStr = "SELECT cc.claveCompania, cc.descripcion ".
                                         "FROM cacompanias cc, segusuarioscompaniastbl sc where ".
                                         "cc.claveCompania = sc.compania ".
                                         "AND sc.idUsuario =".$_SESSION['idUsuario']." ".
                                         "AND cc.tipoCompania = 'SU'";

		}

        $rs = fn_ejecuta_query($sqlGetCompaniasUsuarioStr);
            
        echo json_encode($rs);    
    }

    function getCompaniasDisponibles(){

        $sqlGetCompaniasDisponiblesStr = "SELECT dc.* ".
                                       "FROM cacompanias dc ";

        if ($_REQUEST['segUsuariosIdUsuarioHdn'] != "") {
            $sqlGetCompaniasDisponiblesStr .= "WHERE dc.claveCompania NOT IN ".
                                            "(SELECT su.claveCompania FROM segUsuariosCompaniasTbl su ".
                                            "WHERE su.idUsuario=".$_REQUEST['segUsuariosIdUsuarioHdn'].") ".
                                            "AND dc.tipoCompania = 'SU'";
        } else {
            $sqlGetCompaniasDisponiblesStr .= "WHERE dc.tipoCompania = 'SU'";
        }

        $rs = fn_ejecuta_query($sqlGetCompaniasDisponiblesStr);
            
        echo json_encode($rs);    
    }

    function getIPUsuario(){
        $sqlGetIPUsuarioStr = "SELECT * ".
                                   "FROM segUsuariosIpTbl ".
                                   "WHERE idUsuario = ".$_REQUEST['segUsuariosIdUsuarioHdn'];

        $rs = fn_ejecuta_query($sqlGetIPUsuarioStr);
            
        echo json_encode($rs);
    }

    function getHorariosUsuario(){
        $sqlGetHorariosUsuarioStr = "SELECT * ".
                                    "FROM segUsuariosHorariosTbl ".
                                    "WHERE idUsuario = ".$_REQUEST['segUsuariosIdUsuarioHdn'];

        $rs = fn_ejecuta_query($sqlGetHorariosUsuarioStr);
            
        echo json_encode($rs);
    }

    function getUsuarioConfig(){
        $sqlGetUsuarioConfigStr = "SELECT module, name, iconCls " .
                                  "FROM segUsuariosDesktopTbl " . 
                                  "WHERE idUsuario = " . $_SESSION['idUsuario'];

        $rs = fn_ejecuta_query($sqlGetUsuarioConfigStr);
            
        echo json_encode($rs);
    }

    function generaPassword(){
        $pwd = "";
        $characteres = "abcdefghijklmnopqrstuvwxyz";
        $min = 0;
        $may = 0;
        $num = 0;

        $i = 1;
        while ($i <= 8) {
            $choice = rand(1,3);
            if ($i == 6) {
                if ($min == 0) {
                    $pwd .= $characteres[rand(0,strlen($characteres))];
                    $min++;
                } elseif ($may == 0) {
                    $pwd .= strtoupper($characteres[rand(0,strlen($characteres))]);
                    $may++;
                } elseif ($num == 0) {
                    $pwd .= rand(0,9);
                    $num++;
                }
            }
            if ($choice == 1) {
                $pwd .= $characteres[rand(0,strlen($characteres))];
                $min++;
            } elseif ($choice == 2) {
                $pwd .= strtoupper($characteres[rand(0,strlen($characteres))]);
                $may++;
            } else {
                $pwd .= rand(0,9);
                $num++;
            }
            $i++;
        }
        return $pwd;
    }

    function cambiarPassword(){
        $a = array();
        $e = array();
        $a['success'] = true;

         if($_REQUEST['segUsuariosIdUsuarioHdn'] == ""){
            $e[] = array('id'=>'segUsuariosIdUsuarioHdn','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosPasswordTxt'] == ""){
            $e[] = array('id'=>'segUsuariosPasswordTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }
        if($_REQUEST['segUsuariosNewPasswordTxt'] == ""){
            $e[] = array('id'=>'segUsuariosNewPasswordTxt','msg'=>getRequerido());
            $a['errorMessage'] = getErrorRequeridos();
            $a['success'] = false;
        }

        if ($a['success'] == true) {
            $sqlCheckPasswordStr = "SELECT usuario ".
                                   "FROM segUsuariosTbl ".
                                   "WHERE idUsuario = ".$_REQUEST['segUsuariosIdUsuarioHdn']." ".
                                   "AND password = '".md5($_REQUEST['segUsuariosPasswordTxt'])."'";

            $rs = fn_ejecuta_query($sqlCheckPasswordStr);

            if (sizeof($rs['root']) > 0) {
                $sqlCheckHistoryPasswordStr = "SELECT COUNT(*) ".
                                              "FROM segUsuariosPasswordTbl ".
                                              "WHERE idUsuario = ".$_REQUEST['segUsuariosIdUsuarioHdn']." ".
                                              "ORDER BY fecha DESC ".
                                              "LIMIT 3";

                $rs = fn_ejecuta_query($sqlCheckHistoryPasswordStr);
                $newPwd = md5($_REQUEST['segUsuariosNewPasswordTxt']);
                
                for ($iInt=0; $iInt < sizeof($rs['root']); $iInt++) { 
                   if ($rs['root'][$iInt]['password'] == $newPwd) {
                        $a['success'] = false;
                        $a['errorMessage'] = getCambiarContraseniaRepeatMsg();
                   }
                }
            } else {
                $a['success'] = false;
                $a['errorMessage'] = getCambiarContraseniaErrorMsg();
            }

            if ($a['success'] == true) {
                $sqlAddHistoryPasswordStr = "INSERT INTO segUsuariosPasswordTbl ".
                                            "(idUsuario, fecha, password, observaciones, idUsuarioAct, ipAct) ".
                                            "VALUES(".$_REQUEST['segUsuariosIdUsuarioHdn'].",NOW(),".
                                            "'".md5($_REQUEST['segUsuariosNewPasswordTxt'])."','".$_REQUEST['segUsuariosObservacionesTxa']."',".
                                            $_SESSION['idUsuario'].",'".$_SERVER['REMOTE_ADDR']."')";
                    
                $rs = fn_ejecuta_query($sqlAddHistoryPasswordStr);

                if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                    $sqlUpdPasswordStr = "UPDATE segUsuariosTbl ".
                                         "SET password = '".md5($_REQUEST['segUsuariosNewPasswordTxt'])."' ".
                                         "WHERE idUsuario = ".$_SESSION['idUsuario'];

                    $rs = fn_ejecuta_query($sqlUpdPasswordStr);

                    if (!isset($_SESSION['error_sql']) || (isset($_SESSION['error_sql']) && $_SESSION['error_sql']) == "") {
                        $a['sql'] = $sqlUpdPasswordStr;
                        $a['successMessage'] = getCambiarContraseniaSuccessMsg();
                    } else {
                        $a['success'] = false;
                        $a['sql'] = $sqlUpdPasswordStr;
                        $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
                    }
                } else {
                    $a['success'] = false;
                    $a['sql'] = $sqlAddHistoryPasswordStr;
                    $a['errorMessage'] = $_SESSION['error_sql'] . "<br>" . $a['sql'];
                }
            }
        }
        $a['errors'] = $e;
        $a['successTitle'] = getMsgTitulo();
        echo json_encode($a);
    }
?>