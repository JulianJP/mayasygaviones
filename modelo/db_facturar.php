<?php
	// Conexion a bases de datos
		
		function dbconectar()
		{
			$conexion=mysqli_connect("localhost","root","123456","mygr");

			if (mysqli_connect_errno())
			{
				echo "Error no se pudo conectar la base de datos: " . mysqli_connect_error();
			}
			else
			{
				return $conexion;
			}

			mysqli_close();
		}

		

		

	/* Obtener Inventario

	********	$row = dbObtenerInventario();
	********
	******** 	while ($inventario = mysqli_fetch_array($row)) 
	******** 	{
	********		echo $inventario['nombre']." ".$inventario['direccion']." ".$inventario['nickname']."<br>";
	********	}
	*/



		

	// Actulizar Inventario Segun Facturaazion
	/*<?php
		// A two-dimensional array
		$cars = array
		   (
		   array("Volvo",100,96),
		   array("BMW",60,59),
		   array("Toyota",110,100)
		   );
		   
		echo $cars[0][0].": Ordered: ".$cars[0][1].". Sold: ".$cars[0][2]."<br>";
		echo $cars[1][0].": Ordered: ".$cars[1][1].". Sold: ".$cars[1][2]."<br>";
		echo $cars[2][0].": Ordered: ".$cars[2][1].". Sold: ".$cars[2][2]."<br>";
	?>*/

		function dbActInventario($ids)
		{
			for ($i=0; $i < count($ids); $i++) 
			{ 
				$query = "UPDATE inventario SET  cantidad = ".$ids[$i][1].", subtotal = ".$ids[$i][2]." WHERE id = ".$ids[$i][0];

				if (!mysqli_query(dbconectar(),$query))
				{
				  die('Error: ' . mysqli_error($dbconectar()));
				}		
			}
		}

