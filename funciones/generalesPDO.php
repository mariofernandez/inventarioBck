<?php
    function fn_ejecuta_query($ps_sql) {
		set_time_limit(0);
        $_SESSION['error_sql'] = "";
        $_SESSION['sql_debug'] = $ps_sql;
		
		$usuario = $_SESSION['usuario'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $modulo = $_SESSION['modulo'];
        $database = "tracomexdb";
        $host = "localhost";


        $link = new PDO("mysql:host=".$host.";dbname=".$database, "root", "toor") or die ('I cannot connect to the database');
        $result = $link->prepare($ps_sql);
        $result->execute();

        $lines = $result->fetchAll(PDO::FETCH_ASSOC);
  		
        if ($result->errorInfo()[1] == 0){
            $error_msg = "";
        }else{
            $error_msg = $result->errorInfo()[1] . ": " . $result->errorInfo()[2];
        }
		 
        $_SESSION['error_sql'] = $error_msg;
        $_SESSION['error_sql_debug'] = $error_msg;

        return $lines;
    }
?>