<?php
	//Usuarios
	function getUsuariosSuccessMsg(){
		return 'Usuario Creado Correctamente';
	}
	function getUsuariosUpdMsg(){
		return 'Usuario Actualizado Correctamente';
	}
	function getCambiarContraseniaSuccessMsg(){
		return 'Contrase&ntilde;a Actualizada Correctamente';
	}
	function getCambiarContraseniaErrorMsg(){
		return 'Contrase&ntilde;a Actual Incorrecta';
	}	
	function getCambiarContraseniaRepeatMsg(){
		return 'Contrase&ntilde;a Nueva Usada Anteriormente.<br>Elige Una Nueva';
	}
	function getUsuariosModulosSuccessMsg(){
		return 'Permisos Asignados Correctamente';
	}
	function getUsuariosModulosDltMsg(){
		return 'Permisos Eliminados Correctamente';
	}

	//Panel de Control
	function getSisWallpaperSuccessMsg(){
		return 'Fondo de Pantalla Actualizado';
	}
	function getSisThemeSuccessMsg(){
		return 'Tema Actualizado';
	}
	function getSisDesktopSuccessMsg(){
		return 'Iconos Actualizados';
	}

	// Generales
	function getRequerido(){
		return 'Este campo es requerido...';
	}
	function getErrorRequeridos(){
		return 'Falto llenar campos requeridos';
	}
	function getMsgTitulo(){
		return 'Resultado de la Solicitud';
	}

	//Bancos
	function getBancosUpdateMsg(){
		return 'Banco Actualizado Correctamente';
	}
	function getBancosDuplicateMsg(){
		return 'El Banco Ingresado ya Existe';
	}
	function getBancosSuccessMsg(){
		return 'Banco Guardado Correctamente.';
	}

	//Rutas
	function getRutasSuccessMsg(){
		return 'Ruta Creada Correctamente';
	}

	function getRutasUpdateMsg(){
		return 'Ruta Actualizada Correctamente';
	}
	
	//Compañias
	function getCiaSuccesMsg()
	{
		return 'Compa&ntilde;&iacute;a Guardada Correctamente.';
	}
	function getCiaUpdtMsg()
	{
		return 'Compa&ntilde;&iacute;a Actualizada Correctamente.';
	}
	function getCiaDelMsg()
	{
		return 'Compa&ntilde;&iacute;a Eliminada Correctamente.';
	}
	function getCiaDelErrorMsg()
	{
		return 'Es necesario seleccionar una Compa&ntilde;&iacute;a ...';
	}
	function getMsgCiaAsig()
	{
		return 'Compa&ntilde;&iacute;a(s) Asignada(s).';
	}


	//Plazas
	function getPlazaSuccessMsg() {
		return 'Plaza Creada Correctamente.';
	}
	function getPlazaUpdMsg() {
		return 'Plaza Actualizada Correctamente.';
	}

	//Paises
	function getPaisSuccessMsg(){
		return 'Pais Creado Correctamente';
	}
	function getPaisUpdateMsg(){
		return 'Pais Actualizado Correctamente';
	}
	function getPaisDeleteMsg(){
		return 'Pais Borrado Correctamente';
	}
	function getPaisDuplicateMsg(){
		return 'Pais Ya Existente';
	}

	//Estados
	function getEstadoSuccessMsg(){
		return 'Estado Creado Correctamente';
	}
	function getEstadoFailedMsg(){
		return 'Error(es)';
	}
	function getEstadoUpdateMsg(){
		return 'Estado Actualizado Correctamente';
	}
	function getEstadoDeleteMsg(){
		return 'Estado Borrado Correctamente';
	}
	function getEstadoDuplicateMsg(){
		return 'Estado Ya Existente';
	}

	//Municipios
	function getMunicipioSuccessMsg(){
		return 'Municipio Creado Correctamente';
	}
	function getMunicipioFailedMsg(){
		return 'Error(es)';
	}
	function getMunicipioUpdateMsg(){
		return 'Municipio Actualizado Correctamente';
	}
	function getMunicipioDeleteMsg(){
		return 'Municipio Borrado Correctamente';
	}
	function getMunicipioDuplicateMsg(){
		return 'Municipio Ya Existente';
	}

	//Colonia
	function getColoniaSuccessMsg(){
		return 'Colonia Creada Correctamente';
	}
	function getColoniaFailedMsg(){
		return 'Error(es)';
	}
	function getColoniaUpdateMsg(){
		return 'Colonia Actualizada Correctamente';
	}
	function getColoniaDeleteMsg(){
		return 'Colonia Borrada Correctamente';
	}
	function getColoniaDuplicateMsg(){
		return 'Colonia Ya Existente';
	}

	//Regiones
	function getRegionSuccessMsg(){
		return 'Region Creada Correctamente';
	}
	function getRegionUpdateMsg(){
		return 'Region Actualizada Correctamente';
	}
	function getRegionDeleteMsg(){
		return 'Region Borrada Correctamente';
	}

	//Generales
	function getGeneralesSuccessMsg(){
		return 'Registro Creado Correctamente';
	}
	function getGeneralesUpdateMsg(){
		return 'Registro Actualizado Correctamente';
	}
	function getGeneralesDeleteMsg(){
		return 'Registro Borrado Correctamente';
	}
	function getGeneralesFailedMsg(){
		return 'Error(es) en Registro(s): <br> ';
	}

	//Tractores
	function getTractoresSuccessMsg(){
		return 'Tractor Creado Correctamente';
	}
	function getTractoresUpdateMsg(){
		return 'Tractor Actualizado Correctamente';
	}
	function getTractoresDeleteMsg(){
		return 'Tractor Borrado Correctamente';
	}
	//--Errores Tractores
	//Error No 1062
	function getTractoresDuplicateMsg(){
		return 'Existe otro tractor con el mismo No. serie o placas';
	}
	function getBloquearTractorMsg(){
		return 'Tractor Bloqueado Correctamente';
	}
	function getTractoresLiberarMsg(){
		return 'Tractor Liberado Correctamente';
	}

	//Choferes
	function getChoferesSuccessMsg(){
		return 'Chofer Creado Correctamente';
	}
	function getChoferesUpdateMsg(){
		return 'Chofer Actualizado Correctamente';
	}
	function getChoferesDeleteMsg(){
		return 'Chofer Borrado Correctamente';
	}
	//--Errores Choferes
	//Error No 1062
	function getChoferesDuplicateMsg(){
		return 'Existe otro chofer con la misma clave o licencia';
	}

	//Tarifas
	function getTarifasSuccessMsg(){
		return 'Tarifa Creada Correctamente';
	}
	function getTarifasUpdateMsg(){
		return 'Tarifa Actualizada Correctamente';
	}
	function getTarifasDeleteMsg(){
		return 'Tarifa Borrada Correctamente';
	}

	//Distribuidores
	function getDistribuidoresSuccessMsg(){
		return 'Distribuidor Creado Correctamente';
	}
	function getDistribuidoresUpdateMsg(){
		return 'Distribuidor Actualizado Correctamente';
	}
	function getDistribuidoresDeleteMsg(){
		return 'Distribuidor Borrado Correctamente';
	}
	function getDistribuidoresUpdateNoDirectionUpdMsg(){
		return 'Distribuidor Actualizado Correctamente.<br>No hubo direcciones a actualizar.';	
	}

	//Distribuidores Especiales
	function getDistEspecialSuccessMsg($tipo){
		switch ($tipo) {
			case 'DE':
				return 'Destino Especial Creado Correctamente';
				break;
			case 'CD':
				return 'Centro de Distribucion Creado Correctamente';
				break;
			case 'PA':
				return 'Patio Creado Correctamente';
				break;
			default:
				return '';
				break;
		}
	}
	function getDistEspecialUpdMsg($tipo){
		switch ($tipo) {
			case 'DE':
				return 'Destino Especial Actualizado Correctamente';
				break;
			case 'CD':
				return 'Centro de Distribucion Actualizado Correctamente';
				break;
			case 'PA':
				return 'Patio Actualizado Correctamente';
				break;
			default:
				return '';
				break;
		}
	}

	//Marcas por Centro de Distribucion
	function getAsignarMarcaSuccess(){
		return "Marca Asignada Correctamente";
	}

	//Direcciones
	function getDireccionesSuccessMsg(){
			return 'Direccion(es) Creada(s) Correctamente';
	}

	function getDireccionesUpdateMsg(){
			return 'Direccion(es) Actualizada(s) Correctamente';
	}

	function getDireccionesDeleteMsg(){
			return 'Direccion(es) Borrada(s) Correctamente';
	}

	function getDireccionesFailedMsg(){
		return 'Error(es) en Registro(s): <br> ';
	}

	//--Errores Distribuidores
	//Error No 1062
	function getDistribuidoresDuplicateMsg(){
		return 'Centro Distribucion Duplicado';
	}

	//Colores
	function getColoresSuccessMsg(){
		return 'Color Creado Correctamente';
	}
	function getColoresUpdateMsg(){
		return 'Color Actualizado Correctamente';
	}
	function getColoresDeleteMsg(){
		return 'Color Borrado Correctamente';
	}
	//--Errores Colores
	//Error No 1062
	function getColoresDuplicateMsg(){
		return 'Esta Marca ya esta asignada a este color';
	}

	//Gastos Tractor
	function getGastosTractorSuccessMsg(){
		return 'Gasto de Tractor Creado Correctamente';
	}
	function getGastosTractorUpdateMsg(){
		return 'Gasto de Tractor Actualizado Correctamente';
	}
	function getGastosTractorDeleteMsg(){
		return 'Gasto de Tractor Borrado Correctamente';
	}

	//Marcas Unidades
	function getMarcasUnidadesSuccessMsg(){
		return 'Marca Creada Correctamente';
	}
	function getMarcasUnidadesUpdateMsg(){
		return 'Marca Actualizada Correctamente';
	}
	function getMarcasUnidadesDeleteMsg(){
		return 'Marca Borrada Correctamente';
	}

	//Clasificacion Marca
	function getClasificacionMarcaSuccessMsg(){
		return 'Clasificacion de la Marca Creada Correctamente';
	}
	function getClasificacionMarcaUpdateMsg(){
		return 'Clasificacion de la Marca Actualizada Correctamente';
	}
	function getClasificacionMarcaDeleteMsg(){
		return 'Clasificacion de la Marca Borrada Correctamente';
	}

	//Conceptos Centros
	function getConceptosCentrosSuccessMsg(){
		return 'Concepto de Distribuidor Creado Correctamente';
	}
	function getConceptosCentrosUpdateMsg(){
		return 'Concepto de Distribuidor Actualizado Correctamente';
	}
	function getConceptosCentrosDeleteMsg(){
		return 'Concepto de Distribuidor Borrado Correctamente';
	}
	//Error No 1062
	function getConceptosCentrosDuplicateMsg(){
		return 'Este Distribuidor ya tiene un concepto igual';
	}

	//Simbolos Unidades
	function getSimbolosUnidadesSuccessMsg(){
		return 'Simbolo de Unidad Creado Correctamente';
	}
	function getSimbolosUnidadesUpdateMsg(){
		return 'Simbolo de Unidad Actualizado Correctamente';
	}
	function getSimbolosUnidadesDeleteMsg(){
		return 'Simbolo de Unidad Borrado Correctamente';
	}

	//Conceptos
	function getConceptosSuccessMsg(){
		return 'Concepto Creado Correctamente';
	}
	function getConceptosUpdateMsg(){
		return 'Concepto Actualizado Correctamente';
	}
	function getConceptosDeleteMsg(){
		return 'Concepto Borrado Correctamente';
	}

	//Unidades
	function getUnidadesSuccessMsg(){
		return 'Unidad Creada Correctamente';
	}
	function getUnidadesMasivoSuccessMsg(){
		return 'Unidades Creadas Correctamente';
	}
	function getUnidadesUpdMsg(){
		return 'Unidad Actualizada Correctamente';
	}
	function getUnidadesNotUpdMsg(){
		return 'No Hubo Nada Que Actualizar a la Unidad';
	}
	function getHistoricoUnidad(){
		return 'Historico de la Unidad Agregado';
	}
	function getUnidadesLiberarMsg(){
		return 'Unidad Liberada Correctamente';
	}
	function getUnidadesBloquearMsg(){
		return 'Unidad Bloqueada Correctamente';
	}
	function getUnidadesTrap003Msg(){
		return 'Cambio de Estatus De La Unidad Correcto';
	}
	function getUnidadesDetenidasSuccessMsg(){
		return 'Unidades Detenidas Correctamente';
	}
	function getUnidadesDetencionDistribuidor($distribuidor){
		return 'Unidades del Distribuidor '.$distribuidor.' Detenidas Correctamente';
	}
	function getUnidadesDetencionSimbolo($simbolo){
		return 'Unidades con el Simbolo '.$simbolo.' Detenidas Correctamente';
	}
	function getUnidadesDetencionVin($vin){
		return 'Unidad con el VIN '.$vin.' Detenida Correctamente';
	}
	function getUnidadesLiberarDetencionDistribuidor($distribuidor){
		return 'Unidades del Distribuidor '.$distribuidor.' Liberadas Correctamente';
	}
	function getUnidadesLiberarDetencionSimbolo($simbolo){
		return 'Unidades con el Simbolo '.$simbolo.' Liberadas Correctamente';
	}
	function getUnidadesLiberarDetencionVin($vin){
		return 'Unidad con el VIN '.$simbolo.' Liberada Correctamente';
	}
	function getHoldUnidadesMessage($ok, $fail){
		return 'Correctos: '.$ok.'<br>'.'Fallos: '.$fail;
	}
	function getUnidadesQuitarHold($cantVin){
		if ($cantVin > 1) {
			return 'Unidades Liberadas Correctamente';
		} else {
			return 'Unidad Liberada Correctamente';
		}
	}
	function getInsertarEntradaPatio(){
		return 'Generacion de Movimientos Correcta';
	}
	function getUnidadesUpdObservaciones(){
		return 'Observaciones Actualizadas Correctamente';
	}

	//Grupos
	function getGrupoSuccessMsg(){
		return 'Grupo Creado Correctamente';
	}
	function getGrupoMissingDataMsg(){
		return 'Faltan datos necesarios para la creacion del grupo';
	}
	function getGrupoFailedClasifMsg(){
		return 'Error al insertar con las siguientes Clasificaciones: ';
	}
	function getGrupoFailedDistMsg(){
		return 'Error al insertar con los siguientes Distribuidores: ';	
	}
	function getGrupoLugaresSuccessMsg(){
		return 'Lugares para el Grupo Creados Correctamente';
	}
	function getGrupoLugaresFailedMsg(){
		return 'Error al insertar los siguientes lugares: ';
	}

	//Localizacion Patios
	function getLocalizacionSuccessMsg(){
		return 'Unidades Colocadas Correctamente.';
	}
	function getLocalizacionFailedMsg(){
		return 'No se dieron localizacion a las siguientes unidades: ';
	}
	function getLocalizacionPatiosSuccessMsg($fila, $lugar){
		return 'La unidad ha sido colocada en <br>Fila: '.$fila."<br>Lugar: ".$lugar;
	}
	function getLocalizacionPatiosDeleteMsg(){
		return 'La unidad ha sido retirada del patio.';
	}
	function getLocalizacionPatiosUpdMsg(){
		return 'Localizaciones Actualizadas Correctamente. ';
	}
	function getLocalizacionPatiosUpdFailedMsg(){
		return 'Error al Actualizar: ';
	}

	//Clasificación - Tarifa
	function getClasificacionTarifaSuccessMsg(){
		return 'Relacion entre Clasificacion y Tarifa Guardada Correctamente';
	}

	//Destinos Especiales
	function getDestinosEspecialesSuccessMsg(){
		return 'Destino Especial Creado Correctamente. ';
	}
	function getDestinosEspecialesFailedMsg(){
		return 'Error al insertar los siguientes VIN: ';
	}
	function getDestinosEspecialesUpdMsg(){
		return 'Destino Especial Actualizado Correctamente.';
	}
	function getDestinosEspecialesDltMsg(){
		return 'Destinos Especial Eliminado Correctamente.';
	}

	//Impuestos
	function getImpuestosSuccessMsg(){
		return 'Impuesto Creado Correctamente';
	}
	function getImpuestosUpdMsg(){
		return 'Impuesto Actualizado Correctamente';
	}

	//Series
	function getSeriesSuccessMsg(){
		return 'Serie Creada Correctamente';
	}
	function getSeriesUpdateMsg(){
		return 'Serie Actualizada Correctamente';
	}

	//Cuentas Contables
	function getCuentasSuccessMsg(){
		return 'Cuenta Creada Correctamente';
	}
	function getCuentasUpdMsg(){
		return 'Cuenta Modificada Correctamente';
	}

	//Cuentas Bancarias
	function getCuentasBancariasSuccessMsg(){
		return 'Cuenta Bancaria Creada Correctamente';
	}

	function getCuentasBancariasUpdMsg(){
		return 'Cuenta Bancaria Actualizada Correctamente';
	}

	//Previajes
	function getPreViajeSuccessMsg(){
		return 'PreViaje Creado Correctamente';
	}
	//Viajes Vacíos
	function getGastosViajeVacioSuccessMsg(){
		return 'Viaje Vacio Creado Correctamente';
	}
	//Gastos Viaje
	function getGastosViajeTractorSuccessMsg(){
		return 'Gastos del Viaje Agregados Correctamente';
	}
	function getConceptosNoExist(){
		return 'Concepto de Gasto No Existe';
	}
	function getGastosCanceladosSuccessMsg(){
		return 'Gastos Cancelados Correctamente';
	}
	///Viajes
	//Talones
	function getUnidadTalonSuccessMsg(){
		return 'Unidades Agregadas al Talon Correctamente';
	}
	function getUnidadTalonUpdateMsg(){
		return 'Unidades del Talon Actualizadas Correctamente';
	}
	function getUnidadTalonDltMsg(){
		return 'Unidades Borarradas del Talon Correctamente';
	}
	function getEntregaTalonSuccessMsg(){
		return 'Talon Entregado Correctamente';
	}
	function getTalonesViajeUpdMsg(){
		return 'Talones Actualizados Correctamente';
	} 
	//Cancelacion de Viajes
	function getViajeCanceladoSuccessMsg($tipoCancelacion){
		switch ($tipoCancelacion) {
			case 'VV':
				return 'Previaje Cancelado Correctamente';
				break;
			case 'VG':
				return 'Viaje con Gastos Cancelado Correctamente';
				break;
			case 'VA':
				return 'Viaje Asignado Cancelado Correctamente';
				break;
		}
	}
	//Cancelacion de unidades
	function getTalonCanceladoSuccessMsg(){
		return 'Talon Cancelado Correctamente';
	}
	function getCancelarUnidadSuccessMsg(){
		return 'Unidad Cancelada Correctamente';
	}
	//Comprobacion Viaje
	function getComprobacionViajeSuccessMsg(){
		return 'Recepción Exitosa';
	}

	//Daños
	function getDanosSuccessMsg(){
		return 'Da&ntilde;o Creado Correctamente';
	}
	function getDanosUpdMsg(){
		return 'Da&ntilde Actualizado Correctamente';
	}

	//Daños Vics
	//function getDanosVicsSuccessMsg(){
	//	return 'Da&ntilde;os Creados Correctamente.';
	//}
	//function getDanosVicsFailedMsg(){
	//	return 'Error al crear los siguientes da&ntilde;os: ';
	//}

	//Productos
	function getProductosSuccessMsg(){
		return 'Producto(s) Creado(s) Correctamente';
	}
	function getProductosUpdMsg(){
		return 'Producto(s) Actualizado(s) Correctamente';
	}
	function getProductosFailedMsg(){
		return 'Error(es)';
	}
	function getProductosDeleteMsg(){
		return 'Producto(s) Elimindo(s) Correctamente';
	}	

	//Companias
	function getCompaniasSuccessMsg(){
		return 'Compa&ntilde;ia(s) Creada(s) Correctamente';
	}
	function getCompaniasUpdMsg(){
		return 'Compa&ntilde;ia(s) Actualizada(s) Correctamente';
	}
	function getCompaniasFailedMsg(){
		return 'Error(es) en la(s) Compa&ntilde;&iacute;a(s): <br> ';
	}
	function getCompaniasDeleteMsg(){
		return 'Compa&ntilde;ia(s) Eliminda(s) Correctamente';
	}
	//Ventas
	function getVentasSuccessMsg(){
		return 'Venta Aplicada Correctamente';
	}
	function getVEntasUpdMsg(){
		return 'Venta Actualizada Correctamente';
	}
	function getVentasFailedMsg(){
		return 'Error(es) en la(s) Venta(s) Codigo(s) de barra: <br> ';
	}
	function getVentasDeleteMsg(){
		return 'Venta(s) Eliminda(s) Correctamente';
	}
		
?>
