<?php
	session_start();

	require_once '../modelo/mygr_model.php';

	/****************************************************************************
	*****************************************************************************
	**********      CREAMOS UNA NUEVA VARIABLE DE CLASE Databse  ****************
	**********      QUE ESTA EN mygr_model.php 		             ****************
	*****************************************************************************
	****************************************************************************/

	$db_facturacion = new Database;

	/****************************************************************************
	*****************************************************************************
	**********      RECOGEMOS LOS DATOS MANDADOS EN LA SECCION   ****************
	**********      SECRETARI@  EN EL MENU COTIZAR               ****************
	*****************************************************************************
	****************************************************************************/

	$ref = $_POST["ref"];
	$cantidad = $_POST["cantidad"];

	/****************************************************************************
	*****************************************************************************
	**********      CARGAMOS LOS DATOS NECESARIOS PARA LLENAR    ****************
	**********      LA TABLA factura_detalle DESDE LA TABLA      ****************
	**********      inventario Y  factura_consecutivo en MySQL   ****************
	*****************************************************************************
	****************************************************************************/

	$consecutivo = $db_facturacion->ObtenerConsecutivoCotizar();
	$consecutivo = $consecutivo + 1;


	/****************************************************************************
	*****************************************************************************
	**********  CONSTRUIMOS UN ARREGLO CON LOS DATOS NECESARIOS  ****************
	**********  PARA INSERTAR EN LA TABLA factura_detalle Y LO   ****************
	**********  		ENVIAMOS A modelo/db_facturar.php 		 ****************
	*****************************************************************************
	****************************************************************************/

	$datos = array
	(
		'Num_Cotizar' => $consecutivo,
		'Ref'  => $ref,
		'Cantidad' => $cantidad
	);

	$db_facturacion->dbAddCotizar($datos);

	$result = $db_facturacion->dbObtenerDetalleCotizar($consecutivo);


	/****************************************************************************
	*****************************************************************************
	*************	ENVIAMOS A JQUERY LA RESPUESTA DADA POR LA BASE *************
	*************	DE DATOS YA FORMATEADO 							*************
	*****************************************************************************
	****************************************************************************/

	if (isset($result)) 
	{
		while ($descripciones = mysqli_fetch_array($result) )
		{
			echo "<tr>";
		 		echo "<td align='center'>".$descripciones['Ref']."</td> ";
		 		echo "<td>".$descripciones['detalle']."</td> ";
		 		echo "<td align='center'>".$descripciones[2]." ".$descripciones[3]."</td> ";
		 		echo "<td align='right'>$".number_format($descripciones['valor_unit'],0,',', '.')."</td> ";
		 		echo "<td align='right'>$".number_format(($descripciones['valor_unit']*$descripciones[2]),0,',', '.')."</td> ";
		 	echo "</tr>";
		}
	} 
	else 
	{
		return 0;
	}
?>