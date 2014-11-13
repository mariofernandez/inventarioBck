<?php
	define("latin1UcChars", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ");
	define("latin1LcChars", "àáâãäåæçèéêëìíîïðñòóôõöøùúûüý");

	//Remplazar variables decimales vacías con un cero
	function replaceEmptyDec($dataStr){
		if($dataStr == ''){
			return 0;
		} else {
			return $dataStr;
		}
	}

	//Remplazar variables vacias con NULL
	function replaceEmptyNull($dataStr){
		if($dataStr == "" || $dataStr == "''"){
			return 'NULL';
		} else {
			return $dataStr;
		}
	}

	function trasformUppercase($array){
		$upArray = array();
		foreach ($array as $key => $value) {
			if (strpos($key, "ActionHdn")) {
				$upArray[$key] = $value;
			} else {
				$upArray[$key] = strtoupper(strtr($value, latin1LcChars, latin1UcChars));
			}
		}
		return $upArray;
	}
?>