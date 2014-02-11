<?php
	session_start();

	require_once '../modelo/mygr_model.php';

	$db_inventario = new Database;

	$result = $db_inventario->obtenerDesde('*','inventario');
	while ($descripciones = mysqli_fetch_array($result) )
	{
		echo "<tr>";
	 		echo "<td align='center'>".$descripciones[0]."</td> ";
	 		echo "<td>".$descripciones[1]."</td> ";
	 		echo "<td align='center'>".$descripciones[3]." ".$descripciones[2]."</td> ";
	 		echo "<td align='right'>$".number_format($descripciones[4],0,',', '.')."</td> ";
	 		echo "<td align='right'>$".number_format($descripciones['subtotal'],0,',', '.')."</td> ";
	 	echo "</tr>";
	}
?>