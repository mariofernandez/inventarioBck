<?php

	if($_REQUEST['action'] == 'print_org'){
		print_org();
	}

	function fn_ejecuta_query($ps_sql){
		set_time_limit(0);
        $_SESSION['error_sql'] = "";
        $_SESSION['sql_debug'] = $ps_sql;
		
		$usuario = $_SESSION['usuario'];
        $ip = $_SERVER['REMOTE_ADDR'];
        //$hoy = getdate();
        //$fecha = date('Y-m-d H:i:s', $hoy[0]);
        $modulo = $_SESSION['modulo'];

        $link = mysql_connect ("localhost", "root", "toor") or die ('I cannot connect to the database because: ' . mysql_error());
        mysql_select_db ("test");
        $result = mysql_query($ps_sql, $link);
      
        if (mysql_errno($link) == 0)
            $error_msg = "";
        else
            $error_msg = mysql_errno($link) . ": " . mysql_error($link);
        
		//$sqlMonitor = mysql_query("INSERT INTO lxs_monitor VALUES ('" . $_SESSION['usuario'] . "', '" . $_SESSION['ingreso'] . "', '" . $modulo . "', '" . $ps_sql . "', '" . $error_msg . "', NOW())", $link);
		 
        $_SESSION['error_sql'] = $error_msg;
        $_SESSION['error_sql_debug'] = $error_msg;
		      
        return $result;
    }

	function print_org(){
		$sql = "SELECT * FROM org ORDER BY nivel";
		$rs = fn_ejecuta_query($sql);
		
		$row = mysql_fetch_assoc($rs);
		$total = mysql_num_rows($rs);

		$i = 0;
		do
		{
			$Arr[$i] = $row;
			$i++;
		}while($row = mysql_fetch_assoc($rs));
		
		$i=0;
		$nvl=1;
		$max = end($Arr)['nivel'];
		$min = $Arr[0]['nivel'];
		$org = [];
		
		
	}

	function recursiveCheck($Arr, $org, $n, $parent){
		
	}
?>