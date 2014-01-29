<?php

	require_once '../modelo/mygr_model.php';

	/****************************************************************************
	*****************************************************************************
	**********      RECOGEMOS LOS DATOS MANDADOS EN LA SECCION   ****************
	**********      SECRETARI@  EN EL DIALOGO DE BUSQUEDA        ****************
	*****************************************************************************
	****************************************************************************/

	$descrip = $_POST["descripcion"];

	/****************************************************************************
	*****************************************************************************
	**********      CREAMOS UNA NUEVA VARIABLE DE CLASE Databse  ****************
	**********      QUE ESTA EN mygr_model.php 		             ****************
	*****************************************************************************
	****************************************************************************/

	$db_facturacion = new Database;

	$result = $db_facturacion->ObtenerLike('*','inventario','detalle',$descrip);


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
		 		echo "<span>".$descripciones['Ref']."  |</span>";
		 		echo "<span>".$descripciones['detalle']."  </span>";
		 		echo "<input  name='seleccionado' type='radio' value='".$descripciones['Ref']."'>";
		 		echo "<input type='hidden' id='".$descripciones['Ref']."' name='cantidadInv' value='".$descripciones['cantidad']."'><br>";
		}
	} 
	else 
	{
		return 0;
	}
?>