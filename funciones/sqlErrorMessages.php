<?php
	function mysqlErrorMessages($errroNum){
		 switch ($errroNum) {
		 	case '1046':
		 		return "Error: ".$errroNum.", Base de datos no encontrada, copie este mensaje completo y favor de notificarlo con su administrador de sistema.";
		 		break;
		 	case '1054':
		 		//return "Error: ".$errroNum.", Columna invalida.";
		 	return "Error: ".$errroNum.", No se puede insertar o actualizar alguna de las columnas tiene algun caracter no aceptado, copie este mensaje completo y favor de notificarlo con su administrador de sistema.";
		 		break;
		 	case '1062':
		 		return "Error: ".$errroNum.", No puede insertar un registro que duplique una llave primaria.";
		 		break;
		 	case '1064':
		 		return "Error: ".$errroNum.", La sintaxis de la peticion es incorrecta, copie este mensaje completo y favor de notificarlo con su administrador de sistema.";
		 		break;
		 	case '1146':
		 		return "Error: ".$errroNum.", Una de las tablas a la que se hace referencia no existe, copie este mensaje completo y favor de notificarlo con su administrador de sistema.";
		 		break;
		 	case '1136':
		 		return "Error: ".$errroNum.", El número de columnas no corresponde al número en la línea, copie este mensaje completo y favor de notificarlo con su administrador de sistema.";
		 		break;
		 	case '1242':
		 		return "Error; ".$errroNum.", Error en Subconsultas.";
		 		break;
		 	case '1366':
		 		return "Error: ".$errroNum.", El Valor a insertar es incorrecto para la columna.";
		 		break;
		 	case '1451':
		 		return "Error: ".$errroNum.", No puede borrar o actualizar el registro porque tiene registros dependientes, copie este mensaje completo y favor de notificarlo con su administrador de sistema.";
		 		break;
		 	case '1452':
		 		return "Error: ".$errroNum.", No puede agregar o actualizar el registro cuando una llave foranea no existe, copie este mensaje completo y favor de notificarlo con su administrador de sistema.";
		 		break;
		 	case '1048':
		 		return "Error: ".$errroNum.", Alguna de las columnas no pueden ser NULLAS, copie este mensaje completo y favor de notificarlo con su administrador de sistema.";
		 		break;
		 	default:
		 		return "Error: ".$errroNum." No se reconoce, copie este mensaje completo y favor de notificarlo con su administrador de sistema.";
		 		break;
		 }
	}
?>