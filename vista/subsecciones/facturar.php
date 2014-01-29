<table border="3px">
	<thead>
		<?php
			session_start();

			require_once '../../modelo/mygr_model.php';

			$db_facturacion = new Database;

			/***************************************************************
			****************************************************************
			*********	Establecemos todo lo relacionado 			********
			*********	a la fecha en la que se realiza la factura 	********
			****************************************************************
			****************************************************************

			date_default_timezone_set('America/Bogota');

			$fecha = date("Y-m-d");

			$hora = date("H:i:s");

			/**************************************************************
			***************************************************************

			echo "<tr>";
				echo "<th>Factura No.".$consecutivo."</th>";
				echo "<th>Fecha de Elaboracion</th>";
				echo "<td>".$fecha." ".$hora."</td>";
			echo "</tr>";
			****************************************************************/
		?>
		<tr>
			<th>Ref</th>
			<th>Detalle</th>
			<th>Cantidad</th>
			<th>Valor Unitario</th>
			<th>Subtotal</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$result = $db_facturacion->dbObtenerDetalle();
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
	?>
	</tbody>
	<tfoot border="0">
		<form name="factura" action="../controlador/factura.php" method="post">
			<td><input type="text" name = "ref"></td>
			<td><input type="text" name = "detalle"></td>
			<td><input type="text" name = "cantidad"></td>
			<td><input type="submit" value="Agregar"></td>
			<td><input type="submit" formaction="subsecciones/factura_pdf.php" value="Generar Recibo" target="_blank"></td>
		</form>
	</tfoot>
</table>