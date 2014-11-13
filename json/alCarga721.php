<?php
    session_start();
	
	date_default_timezone_set('America/Mexico_City');
	include 'alUnidadesLeeArchivo.php';
	iudsrfyuiewdyfiews
	error_reporting(E_ALL);
    $a = array();
	$a['successTitle'] = "Manejador de Archivos";
	
	if(isset($_FILES))
	{
		if($_FILES["rhEmpleadosPathPhotoFld"]["error"] > 0)
		{
			$a['success'] = false;
			$a['message'] = $_FILES["rhEmpleadosPathPhotoFld"]["error"];
		}
		else
		{
			$temp_file_name = $_FILES['rhEmpleadosPathPhotoFld']['tmp_name']; 
			$original_file_name = $_FILES['rhEmpleadosPathPhotoFld']['name'];
		  
			// Find file extention 
			$ext = explode ('.', $original_file_name); 
			$ext = $ext [count ($ext) - 1]; 
		 	
			// Remove the extention from the original file name 
			$file_name = str_replace ($ext, '', $original_file_name); 
		 	
		  	$new_name = $_SERVER['DOCUMENT_ROOT'] . "../../carbookBck/Temp/" . $file_name . $ext;
		  
			if (move_uploaded_file($temp_file_name, $new_name))
			{
				if (!file_exists($new_name))
				{
					$a['success'] = false;
					$a['errorMessage'] = "Error al procesar el archivo " . $new_name;
				}
				else
				{
					$a['success'] = true;
					$a['successMessage'] = "Archivo Cargado";
					$a['imagen'] = $file_name . $ext;
					$a['root'] = leerXLS($new_name);
				//}
				}
		 	}
		 	else
		 	{ 
				$a['success'] = false;
				$a['errorMessage'] = "No se pudo mover el archivo " . $temp_file_name;
			}	
		}
	}
	else
	{
		$a['success'] = false;
		$a['errorMessage'] = "Error FILES NOT SET";
	}
	
	echo json_encode($a);
?>