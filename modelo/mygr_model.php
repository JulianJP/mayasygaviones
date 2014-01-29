<?php
	// Conexion a bases de datos

	Class Database 
	{
		/**************************************************************************
		***************************************************************************
		****************	SE CREAN LAS VARIABLES NECESARIAS 		***************
		****************	PARA LA CONEXION A LA BASE DE DATOS 	***************
		***************************************************************************
		**************************************************************************/

		private $servidor;
		private $user;
		private $pass;
		private $database;
		private $conexion;

		/**************************************************************************
		***************************************************************************
		****************	SE ASIGNAN LOS VALORES A VARIABLES 		***************
		****************	DE NUESTRA BASE DE DATOS 			 	***************
		***************************************************************************
		**************************************************************************/

		function __construct()
		{
			$this->servidor = "localhost";
			$this->user 	= "root";
			$this->pass 	= "123456";
			$this->database = "mygr";
		}

		/**************************************************************************
		***************************************************************************
		****************	CONECTAMOS A LA BASE DE DATOS 			***************
		****************											***************
		***************************************************************************
		**************************************************************************/
		function conectar()
		{
			$this->conexion = mysqli_connect($this->servidor,$this->user,$this->pass,$this->database);

			return $this->conexion;

			mysqli_close($this->conexion);
		}
			

		/**************************************************************************
		***************************************************************************
		****************	OBTENEMOS LOS DATOS QUE NOS PIDEN		***************
		****************	DESDE LA TABLA QUE ES REQUERIADA		***************
		***************************************************************************
		**************************************************************************/

		public function obtenerDesde($valores,$tabla)
		{
			$query = "SELECT ".$valores." FROM ".$tabla;

			$result =  mysqli_query($this->conectar(),$query);

			return $result;

			mysqli_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	OBTENEMOS LOS DATOS QUE NOS PIDEN		***************
		****************	DESDE LA TABLA QUE ES REQUERIADA		***************
		****************	CUANDO EL VALOR DE LA LLAVE 			***************
		****************	PRIMARIA DADA ES IGUAL A EL VALOR DADO 	***************
		***************************************************************************
		**************************************************************************/

		public function ObtenerLike($valores,$tabla,$nomPrimary,$id)
		{
			$query = "SELECT ".$valores." FROM ".$tabla." WHERE ".$nomPrimary." LIKE '".$id."%'";

			$result =  mysqli_query($this->conectar(),$query);

			return $result;

			mysqli_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	OBTENEMOS LOS DATOS QUE NOS PIDEN		***************
		****************	DESDE LA TABLA QUE ES REQUERIADA		***************
		****************	CUANDO EL VALOR DE LA LLAVE 			***************
		****************	PRIMARIA DADA ES IGUAL A EL VALOR DADO 	***************
		***************************************************************************
		**************************************************************************/

		public function ObtenerDesdeCuando($valores,$tabla,$nomPrimary,$id)
		{
			$query = "SELECT ".$valores." FROM ".$tabla." WHERE ".$nomPrimary." = '".$id."'";

			$result =  mysqli_query($this->conectar(),$query);

			return $result;

			mysqli_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	VALIDA UN USUARIO SEGUN EL USUARIO		***************
		****************	Y CONTRASEÑA DADA 						***************
		***************************************************************************
		**************************************************************************/

		function ValidarUsu($nickname,$password)
		{

			$query = "SELECT tipo , nombre, apellido1 FROM usuarios WHERE nickname LIKE '".$nickname."' AND password LIKE '".$password."'";

			$result = mysqli_query($this->conectar(),$query);

			return $result;

			mysql_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	OBTENER EL CONSECUTIVO DE LA FACTURA	***************
		***************************************************************************
		**************************************************************************/

		function ObtenerConsecutivo()
		{
			$query = "SELECT Numero FROM factura_consecutivo";

			$result = mysqli_query($this->conectar(),$query);

			while ($consecutivo = mysqli_fetch_array($result)) 
			{
				return $consecutivo['Numero'];
			}

			mysql_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	OBTENER EL CONSECUTIVO DE LA FACTURA	***************
		***************************************************************************
		**************************************************************************/

		function ObtenerConsecutivoCotizar()
		{
			$query = "SELECT Numero FROM cotizar_consecutivo";

			$result = mysqli_query($this->conectar(),$query);

			while ($consecutivo = mysqli_fetch_array($result)) 
			{
				return $consecutivo['Numero'];
			}

			mysql_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	ACTUALIZA EL CONSECUTIVO DE LA FACTURA	***************
		***************************************************************************
		**************************************************************************/

		function ActualizarConsecutivo($nuevo,$fecha)
		{
			$query = "UPDATE factura_consecutivo SET Numero = ".$nuevo.", Fecha = '".date("Y-m-d")."' WHERE Numero = ".($nuevo - 1);

			$result = mysqli_query($this->conectar(),$query);

			return $result;

			mysql_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	ACTUALIZA EL INVENTARIO 				***************
		***************************************************************************
		**************************************************************************/

		function ActualizarCantidad($nuevo,$ref)
		{
			$query = "UPDATE inventario SET cantidad = '".$nuevo."' WHERE Ref = '".$ref."';";

			mysqli_query($this->conectar(),$query);

		}

		/**************************************************************************
		***************************************************************************
		****************	OBTENER EL VALOR DE UN PRODUCTO 		***************
		****************	SEGUN LA REFERENCIA DADA 				***************
		***************************************************************************
		**************************************************************************/

		function ObtenerVlrUnit($ref)
		{
			$query = "SELECT valor_unit FROM inventario WHERE Ref = '".$ref."'";

			echo "<br>".$query;

			$result = mysqli_query($this->conectar(),$query);

			while ($vlr = mysqli_fetch_array($result)) 
			{

				return $vlr['valor_unit'];
			}

			mysql_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	OBTENER EL DETALLE DE LA FACTURA 		***************
		****************	QUE ACTUALMENTE ESTA SIENDO USADA		***************
		***************************************************************************
		**************************************************************************/

		function dbObtenerDetalle($consecutivo)
		{
			$query = "SELECT factura_detalle. * , inventario.medida, inventario.valor_unit, inventario.detalle FROM factura_detalle INNER JOIN inventario ON factura_detalle.Ref = inventario.Ref WHERE factura_detalle.Num_Factura = '".$consecutivo."'";

			$result = mysqli_query($this->conectar(),$query);

			return $result;

			mysql_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	OBTENER EL DETALLE DE LA FACTURA 		***************
		****************	QUE ACTUALMENTE ESTA SIENDO USADA		***************
		***************************************************************************
		**************************************************************************/

		function dbObtenerDetalleCotizar($consecutivo)
		{
			$query = "SELECT cotizar_detalle. * , inventario.medida, inventario.valor_unit, inventario.detalle FROM cotizar_detalle INNER JOIN inventario ON cotizar_detalle.Ref = inventario.Ref WHERE cotizar_detalle.Num_Factura = '".$consecutivo."'";

			$result = mysqli_query($this->conectar(),$query);

			return $result;

			mysql_free_result($result);
		}

		/**************************************************************************
		***************************************************************************
		****************	AGREGAR LOS VALORES MANDADOS DESDE 		***************
		****************	UN ARREGLO HECO POR EL FORMULARIO		***************
		***************************************************************************
		**************************************************************************/

		function dbAddProducto($data)
		{
			$query = "INSERT INTO factura_detalle VALUES (".$data['Num_Factura'].", '".$data['Ref']."', ".$data['Cantidad'].")";

			mysqli_query($this->conectar(),$query);

		}

		/**************************************************************************
		***************************************************************************
		****************	AGREGAR LOS VALORES MANDADOS DESDE 		***************
		****************	UN ARREGLO HECO POR EL FORMULARIO		***************
		***************************************************************************
		**************************************************************************/

		function dbAddCotizar($data)
		{
			$query = "INSERT INTO cotizar_detalle VALUES (".$data['Num_Cotizar'].", '".$data['Ref']."', ".$data['Cantidad'].")";

			mysqli_query($this->conectar(),$query);

		}

		function dbAddFactura($data)
		{
			$query = "INSERT INTO factura VALUES (".$data['NumFactura'].", '".$data['FechaFactura']."', '".$data['HoraFactura']."', '".$data['Subtotal']."', '".$data['iva']."', '".$data['Descuento']."', '".$data['Total']."', '".$data['IdTercero']."')";

			mysqli_query($this->conectar(),$query);

		}

		function Prueba()
		{
			$prueba = array(
				'1', 
				'2', 
				'3', 
				'4', 
				'5', 
				'6', 
				'7', 
				'8'
			);

			return $prueba;
		}

	}
	/* Obtener Usuarios

	********	$row = dbobtenerusuarios();
	********
	******** 	while ($usuarios = mysqli_fetch_array($row)) 
	******** 	{
	********		echo $usuarios['nombre']." ".$usuarios['direccion']." ".$usuarios['nickname']."<br>";
	********	}
	*/

	// Validar Usuario

		

	// Agregar Usuarios

		function dbAddUsu()
		{
			$nickname = "";

			$password = "x";

			$query = "INSERT INTO usuarios (id, nombre, apellido1, apellido2, direccion, telefono, nickname, password, tipo)
							VALUES (".$usuario['nombre'].",".$usuario['apellido1'].",".$usuario['apellido2'].",".$usuario['direccion'].",".$usuario['telefono'].",".$nickname.",".$password.",".$usuario['tipo'].")";

			if (!mysqli_query(dbconectar(),$query))
			  {
			  die('Error: ' . mysqli_error($dbconectar()));
			  }
			$sms = "Usuario Agregado Correctamente";

			return $sms;
		}

	// Borrar Usuarios

		function dbDelUsu($id)
		{
			$query = "DELETE FROM usuarios WHERE id LIKE ".$id;

			if (mysqli_connect_errno())
			  {
			  echo "Failed to connect to MySQL: " . mysqli_connect_error();
			  }
		 	mysqli_query(dbconectar(),$query);
		}

	// Actualizar Usuarios

		function dbUpUsu($id)
		{
			$query = "UPDATE usuarios SET  nombre =".$UpUsuario['nombre'].", apellido1 = ".$UpUsuario['apellido1'].", apellido2 =".$UpUsuario['apellido2'].", direccion =".$UpUsuario['direccion'].", telefono =".$UpUsuario['telefono'].", password=".$UpUsuario['pass'].", tipo=".$UpUsuario['tipo']."WHERE id = ".$id;

			if (!mysqli_query(dbconectar(),$query))
			  {
			  die('Error: ' . mysqli_error($dbconectar()));
			  }
			$sms = "Usuario Agregado Correctamente";

			return $sms;
		}
?>