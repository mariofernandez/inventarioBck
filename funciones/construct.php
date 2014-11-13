<?php
	function fn_construct($ps_texto, $ps_columna, $pi_tipo_dato)
	{
		// Tipo de dato 0 - numerico, 1 - String, 2 - Fecha

		global $gb_error_filtro;
		global $gi_simbolo;
		global $gs_simbolo;
		global $gs_condicion;

		global $gi_ini1;
		global $gi_long1;
		global $gi_ini2;
		global $gi_long2;

		$gb_error_filtro = 0;
		$gi_simbolo = 0;
		$gs_simbolo = "";
		$gs_condicion = "";
		
		$gi_ini1  = 0;
		$gi_long1 = 0;
		$gi_ini2  = 0;
		$gi_long2 = 0;		

		$ls_construct = "";
		$li_posini = 0;
		$li_posfin = 0;
		$li_long = 0;
		$lb_continua = 1;
	
		if ($gb_error_filtro == 0)
		{
			$ps_texto = trim($ps_texto);
			if ($lb_continua && strlen($ps_texto) == 0)
			{
			   $ls_construct = "";
			   $lb_continua = 0;
			}
			if ($lb_continua && inStr(1, strtoupper($ps_texto), "NULL") > 0)
			{
				if (inStr(1, strtoupper($ps_texto), "NOT") > 0)
					$ls_construct = $ps_columna + " is not null";
				else
					$ls_construct = $ps_columna + " is null";
				$lb_continua = 0;
			}
			if ($lb_continua)
			{
			   $gs_condicion = "";
			   $gb_error_filtro = 0;
			   
			   if ($pi_tipo_dato == 1 || $pi_tipo_dato == 2)
				  $gs_delimitador = "'";
			   else
				  $gs_delimitador = "";
				
			   sb_val_sim_al_inicio("<>", $ps_texto, 1);
			   sb_val_sim_al_inicio("!=", $ps_texto, 2);
			   sb_val_sim_al_inicio(">=", $ps_texto, 3);
			   sb_val_sim_al_inicio("<=", $ps_texto, 4);
				
			   if ($gi_simbolo == 0)
			   {
					sb_val_sim_al_inicio("=", $ps_texto, 5);
					sb_val_sim_al_inicio(">", $ps_texto, 6);
					sb_val_sim_al_inicio("<", $ps_texto, 7);
				}

			   sb_val_sim_en_medio("..", $ps_texto, 9);
				
			   sb_val_sim_varios("|", $ps_texto, 10);
				
			   sb_val_existe("%", $ps_texto, 11, $pi_tipo_dato);
			   //Call sb_val_existe("?", ps_texto, 12, pi_tipo_dato)

			   if ($gb_error_filtro <> 1)
			   {
				  if ($gi_simbolo == 0)
				  {
					  if ($pi_tipo_dato == 2)
					  {
						 $ps_texto = fn_formatea_fecha($ps_texto, 1, "/");
						 $ps_texto = fn_format_Dis_Nat($ps_texto);
					  }
					  $gs_condicion = $ps_columna . " = " . $gs_delimitador . $ps_texto . $gs_delimitador;
				   }       
                  if ($gi_simbolo >= 1 && $gi_simbolo <= 4)
                  {
                      if ($pi_tipo_dato == 2)
                      {
                         $gs_texto1 = substr($ps_texto, 2);
                         $gs_texto1 = fn_formatea_fecha($gs_texto1, 1, "/");
                         $gs_texto1 = fn_format_Dis_Nat($gs_texto1);
                         $ps_texto = substr(ps_texto, 0, 2) . $gs_texto1;
                      }
                      $gs_condicion = $ps_columna . " " . $gs_simbolo . " " . $gs_delimitador . substr($ps_texto, 2) . $gs_delimitador;
                   }                 
                  if ($gi_simbolo >= 5 && $gi_simbolo <= 7)
                  {
                      if ($pi_tipo_dato == 2)
                      {
                         $gs_texto1 = substr($ps_texto, 1);
                         $gs_texto1 = fn_formatea_fecha($gs_texto1, 1, "/");
                         $gs_texto1 = fn_format_Dis_Nat($gs_texto1);
                         $ps_texto = substr($ps_texto, 0, 1) . $gs_texto1;
                      }
                      $gs_condicion = $ps_columna . " " . $gs_simbolo . " " . $gs_delimitador . substr($ps_texto, 1) . $gs_delimitador;
                  }               
                  if ($gi_simbolo >= 8 && $gi_simbolo <= 9)
                  {
                        if  ($pi_tipo_dato == 2)
                        {
                             $gs_texto1 = substr($ps_texto, $gi_ini1, $gi_long1);
                             $gs_texto1 = fn_formatea_fecha($gs_texto1, 1, "/");
                             $gs_texto1 = fn_format_Dis_Nat($gs_texto1);
                               
                             $gs_texto2 = substr($ps_texto, $gi_ini2, $gi_long2);
                             $gs_texto2 = fn_formatea_fecha($gs_texto2, 1, "/");
                             $gs_texto2 = fn_format_Dis_Nat($gs_texto2);
                               
                             $gs_condicion = $ps_columna . " BETWEEN " . $gs_delimitador . $gs_texto1 . $gs_delimitador . " AND " . $gs_delimitador . $gs_texto2 . $gs_delimitador;
                        }
                        else
                            $gs_condicion = $ps_columna . " BETWEEN " . $gs_delimitador . substr($ps_texto, $gi_ini1, $gi_long1) . $gs_delimitador . " AND " . $gs_delimitador . substr($ps_texto, $gi_ini2, $gi_long2) . $gs_delimitador;
                    }                      
                  if ($gi_simbolo == 10)
                  {
                          $li_posini = 1;
                          $li_posfin = 1;
                          $li_long = 0;
                          
                          While (inStr($li_posini, $ps_texto, "|") > 0)
                          {
                                $li_posfin = inStr($li_posini, $ps_texto, "|");
                                $li_long = $li_posfin - $li_posini;
                                
                                if ($li_posini > 1)
                                   $gs_condicion = $gs_condicion . " OR ";
                                
                                if ($pi_tipo_dato == 2)
                                {
                                   $gs_texto1 = substr($ps_texto, $li_posini - 1, $li_long);
                                   $gs_texto1 = fn_formatea_fecha($gs_texto1, 1, "/");
                                   $gs_texto1 = fn_format_Dis_Nat($gs_texto1);
                                   $gs_condicion = $gs_condicion . " " . $ps_columna + " = " . $gs_delimitador . $gs_texto1 . $gs_delimitador;
                                }
                                else
                                   $gs_condicion = $gs_condicion . " " . $ps_columna . " = " . $gs_delimitador . substr($ps_texto, $li_posini - 1, $li_long) . $gs_delimitador;
                                
                                $li_posini = $li_posfin + 1;
                          }               
                          if ($li_posini > 1)
                             $gs_condicion = $gs_condicion . " OR ";
                            
                          if ($pi_tipo_dato == 2)
                          {
                             $gs_texto1 = substr($ps_texto, $li_posini);
                             $gs_texto1 = fn_formatea_fecha($gs_texto1, 1, "/");
                             $gs_texto1 = fn_format_Dis_Nat($gs_texto1);
                             $ps_texto = $gs_texto1;
                             $gs_condicion = " (" . $gs_condicion . " " . $ps_columna . " = " . $gs_delimitador . $ps_texto . $gs_delimitador . ") ";
                          }
                          else
                             $gs_condicion = " (" . $gs_condicion . " " . $ps_columna . " = " . $gs_delimitador . substr($ps_texto, $li_posini - 1) . $gs_delimitador . ") ";
                    }
                                            
                    if ($gi_simbolo >= 11 && $gi_simbolo <= 12)
                    {
                            $gs_condicion = $ps_columna . " " . $gs_simbolo . " " . $gs_delimitador . $ps_texto . $gs_delimitador;
                    }

					$ls_construct = $gs_condicion;
			   }
			   else
				  $ls_construct = "";
			}
		}
		return $ls_construct;
	}
	
	function sb_val_sim_al_inicio($ps_operando, $ps_cadena, $pi_simbolo)
	{
		global $gb_error_filtro;
		global $gi_simbolo;
		global $gs_simbolo;
		global $gs_condicion;

		global $gi_ini1;
		global $gi_long1;
		global $gi_ini2;
		global $gi_long2;

		$li_pos = 0;
		
		$li_pos = inStr(1, $ps_cadena, $ps_operando);
		if ($li_pos <> 0)
		{
			if ((inStr($li_pos + 1, $ps_cadena, $ps_operando) == 0) && $li_pos == 1)
			{
				if ($gi_simbolo == 0)
				{
				   $gi_simbolo = $pi_simbolo;
				   $gs_simbolo = $ps_operando;
				}
				else
				  $gb_error_filtro = 1;
			}
			else
				$gb_error_filtro = 1;
		}
	}

	function sb_val_sim_en_medio($ps_operando, $ps_cadena, $pi_simbolo)
	{
		global $gb_error_filtro;
		global $gi_simbolo;
		global $gs_simbolo;
		global $gs_condicion;

		global $gi_ini1;
		global $gi_long1;
		global $gi_ini2;
		global $gi_long2;

		$li_pos = 0;
		
		$li_pos = inStr(1, $ps_cadena, $ps_operando);
		if ($li_pos <> 0)
		{ 
			if ((inStr($li_pos + 1, $ps_cadena, $ps_operando) == 0) && ($li_pos > 1 && $li_pos < strlen($ps_cadena)))
			{
				if ($gi_simbolo == 0)
				{
					$gi_simbolo = $pi_simbolo;
					$gs_simbolo = $ps_operando;
					$gi_ini1 = 0;
					$gi_long1 = $li_pos - 1;
					$gi_ini2 = $li_pos + strlen($ps_operando) - 1;
					$gi_long2 = strlen($ps_cadena) - $li_pos + strlen($ps_operando);
				}
				else
					$gb_error_filtro = 1;
			}
			else
				$gb_error_filtro = 1;
		}
	}

	function sb_val_sim_varios($ps_operando, $ps_cadena, $pi_simbolo)
	{
		global $gb_error_filtro;
		global $gi_simbolo;
		global $gs_simbolo;
		global $gs_condicion;

		global $gi_ini1;
		global $gi_long1;
		global $gi_ini2;
		global $gi_long2;

		$li_pos = 0;
		
		$li_pos = InStr(1, $ps_cadena, $ps_operando);
		if ($li_pos > 1 && $li_pos < strlen($ps_cadena))
		{
			if (inStr(strlen($ps_cadena) - strlen($ps_operando) + 1, $ps_cadena, $ps_operando) == 0)
			{
				if ($gi_simbolo == 0)
				{
					$gi_simbolo = $pi_simbolo;
					$gs_simbolo = $ps_operando;
				}
				else				
					$gb_error_filtro = 1;
			}
			else
				$gb_error_filtro = 1;
		}
		else
			if ($li_pos <> 0)
			   $gb_error_filtro = 1;
	}	

	function sb_val_existe($ps_operando, $ps_cadena, $pi_simbolo, $pi_tipo_dato)
	{
		global $gb_error_filtro;
		global $gi_simbolo;
		global $gs_simbolo;
		global $gs_condicion;

		global $gi_ini1;
		global $gi_long1;
		global $gi_ini2;
		global $gi_long2;

		$li_pos = 0;

		$li_pos = inStr(1, $ps_cadena, $ps_operando);
		if ($li_pos <> 0)
		{
			if ($gi_simbolo == 0 && $pi_tipo_dato <> 0)
			{
			   $gi_simbolo = $pi_simbolo;
			   $gs_simbolo = "LIKE";
		   }
		   else
			   $gb_error_filtro = 1;
		}
	}
	
	function inStr($pi_posini, $ps_texto, $ps_cadena)
	{
		$long = strlen($ps_cadena);
		for ($i = $pi_posini - 1; $i < strlen($ps_texto); $i++)
		{
			if (trim(substr($ps_texto, $i, $long)) == trim($ps_cadena))
			{
		  		return $i + 1;
			}
		}
		return 0; 
	}  
	
	function fn_concatena_condicion($ps_where, $ps_condicion)
	{
    	if ($ps_condicion <> "")
		{
       		if ($ps_where == "") 
          		$ps_where = "WHERE ";
       		else
          		$ps_where = $ps_where . " AND ";
       		$ps_where = $ps_where . $ps_condicion;
		}    
		return $ps_where;
	}

	function fn_formatea_fecha($ps_texto, $pi_tipo, $ps_delimitador)
	{
		return $ps_texto;
	}

	function fn_format_Dis_Nat($ps_texto)
	{
		return $ps_texto;
	}	
	
?>