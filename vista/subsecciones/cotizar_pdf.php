<?php
	session_start();
	require('../../pdf/fpdf.php');
	require('../../modelo/mygr_model.php');
	
	class PDF extends FPDF
	{
		private $valoresAdd;

		function DatosAdicionales($data)
		{
			for ($i=0; $i < count($data); $i++) 
			{ 
				$this->valoresAdd[$i] = $data[$i];
			}			
		}

		// Cabecera de página
		function Header()
		{
		    // Logo
		    $this->Image('../../multimedia/img/superior_mallas.png',10,10,40);
		    // Arial bold 15
		    $this->SetFont('Arial','B',15);
		    //Nos movemos  a la derecha
		    $this->Cell(48);
		    // Título
		    $this->Cell(80,10,'Mallas y Gaviones Risaralda','0LTR',0,'C');
		    $this->SetFont('','',12);
		    //****Factura de venta
		    $this->SetFillColor(122, 122, 122);
		    $this->SetTextColor(255, 204, 51);
		    $this->Cell(5);
		    $this->Cell(49,5,'Cotizacion Numero: '.$this->valoresAdd[0],1,2,'L',true);
		    $this->Cell(49,2,'',0,1);
		    //Nit de la empresa
		    $this->SetTextColor(0, 0, 0);
		    $this->SetFont('','',10);
		    $this->Cell(48);
		    $this->Cell(80,5,'NIT - 124511454-8','0LR',0,'C');
		    //****Fecha de elaboracion
		    $this->Cell(5);
		    $this->Cell(49,5,'Elaborado el : '.$this->valoresAdd[1],'0TLR',1,'L');
		    //Direccion de la empresa
		    $this->Cell(48);
		    $this->Cell(80,5,'Carrera 5a. Bis No. 38 - 37','0RL',0,'C');
		    //*****Nombre de quien lo elaboro
		    $this->Cell(5);
		    $this->Cell(49,5,'Por : '.$_SESSION['NOMBRE'].' '.$_SESSION['APELLIDO'],'0LRB',1,'L');
		    //Telefono De La Empresa
		    $this->Cell(48);
		    $this->Cell(80,5,'PBX: 3369217 - 3366413','0BLR',0,'C');
		    // Salto de línea
		    $this->Ln(10);
		}

		// Pie de página
		function Footer()
		{
		    // Posición: a 1,5 cm del final
		    $this->SetY(-15);
		    // Arial italic 8
		    $this->SetFont('Arial','I',8);
		    // Número de página
		    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
	}

	/***************************************************************
	****************************************************************
	*********	Establecemos todo lo relacionado 			********
	*********	a la fecha en la que se realiza la factura 	********
	****************************************************************
	***************************************************************/

			date_default_timezone_set('America/Bogota');

			$fecha = date("Y-m-d");

			$hora = date("H:i:s");

	/***************************************************************************
	****************************************************************************
	*********	Creamos los objetos para las consultas de la base 	************
	*********	de datos y de la hoja de PDF						************
	****************************************************************************
	***************************************************************************/

	$facturar = new Database();
	$mipdf = new PDF('P','mm','Letter');


	/***************************************************************************
	****************************************************************************
	********	Realizamos una consulta para obtener el numero 	****************
	********	del consecutivo de la base de datos y la 		****************
	********	guardamos en una variable 						****************
	****************************************************************************
	***************************************************************************/

	$ConsultaConsecutivo = $facturar->obtenerDesde('Numero','cotizar_consecutivo');
	$ResultConsecutivo = mysqli_fetch_array($ConsultaConsecutivo);
	$NewConsecutivo = ($ResultConsecutivo['Numero'] + 1);

	//Enviamos el consecutivo para agregarlo en la cabecera y fecha de Colombia

	$datosxAdd[0] =  $NewConsecutivo;
	$datosxAdd[1] =  $fecha;

	$mipdf -> DatosAdicionales($datosxAdd);

	// Creación del objeto de la clase heredada
	$mipdf -> addPage();

	/***************************************************************************
	****************************************************************************
	*******	Formateamos la cabecera de la descripcion con 			************
	*******	los sig valores: 										************
	*******		 													************
	*******		Tipo de letra 	=>	 Courier en negrilla			************
	*******		Tamño de letra 	=> 	 10 mm 							************
	*******		Color de letra 	=> 	 Blanco							************
	*******		Color de celda  => 	 Gris Claro (125, 125 ,125)		************
	*******															************
	****************************************************************************
	***************************************************************************/

	$mipdf -> SetFont( 'Courier' , 'B' , 10 );
	$mipdf -> SetTextColor( 255 , 255 , 255);
	$mipdf -> SetFillColor( 125, 125, 125);
	$mipdf -> Cell(5);

	// Creamos la cabecera de la descripcion

	$cabeceraT = array(
		13	=> "Ref" ,
		55	=> "Detalle" ,
		30 	=> "Cantidad", 
		29	=> "Vlr Unitario", 
		10	=> "Iva" , 
		37	=> "Subtotal"
	);

	foreach ($cabeceraT as $key => $value) 
	{
		$mipdf -> Cell ($key , 8 , $value , 1 , 0 , 'C' , true);
	}

	// Salto de linea de 10 milimetros
	$mipdf -> Ln(10);

	/***************************************************************************
	****************************************************************************
	*******		Formateamos la descripcion con los sig valores:		************
	*******		 													************
	*******		Tipo de letra 	=>	 Times Normal					************
	*******		Tamño de letra 	=> 	 10 mm 							************
	*******		Color de letra 	=> 	 Negro							************
	*******		Color de celda  => 	 Blanco valor en Cell() Nulo	************
	*******															************
	****************************************************************************
	***************************************************************************/

	$mipdf -> SetFont( 'Times', '',10);
	$mipdf -> SetTextColor( 0 , 0 , 0);

	//cargamos los datos de la factura
	$consulta = $facturar->dbObtenerDetalleCotizar($NewConsecutivo);

	$subtotalFactura = 0;
	while ($datos = mysqli_fetch_array($consulta) )
	{
		$ref = $datos ['Ref'];
		$detalle = $datos ['detalle'];
		$medida = $datos ['medida'];
		$cantidad = $datos ['Cantidad'];
		$subtotalFactura = $subtotalFactura + ($datos['valor_unit'] * $cantidad);

		/*******************************************************************
		********************************************************************
		**************												********
		**************	Damos un formato monetario a las variables  ********
		**************												********
		********************************************************************
		*******************************************************************/
		
		$valor = "$".number_format($datos['valor_unit'],0,',', '.');
		$subtotal = "$".number_format(($datos['valor_unit']*$cantidad),0,',', '.');

		/*******************************************************************
		*******************************************************************/
		$mipdf -> Cell(5);
		$mipdf -> Cell( 13, 10 , $ref , 1, 0, 'C');
		$mipdf -> Cell( 55, 10 , $detalle , 1, 0, 'L');
		$mipdf -> Cell( 30, 10 , $cantidad." ".$medida, 1, 0, 'C');
		$mipdf -> Cell( 29, 10 , $valor, 1, 0, 'C');
		$mipdf -> Cell( 10, 10 , '16%', 1, 0, 'C');
		$mipdf -> Cell( 37, 10 , $subtotal , 1, 0, 'C');
		$mipdf -> Ln( 10);
	}

	$subtotalFactura = "$".number_format($subtotalFactura,0,',', '.');

	//salto de linea de 10 milimetros para el pie de pagina
	$mipdf -> Ln(3);

	/***********************************************************************
	************************************************************************
	***********		Creamos pie de factura con la informacion 		********
	***********		de el comprador y con la informacion total 		********
	***********		de la factura 									********
	************************************************************************
	***********************************************************************/

		/***************************************************************************
		****************************************************************************
		*******		Formateamos info del comprador con los sig : 		************
		*******		 													************
		*******		Tipo de letra 	=>	 Arial Normal					************
		*******		Tamño de letra 	=> 	 8 mm 							************
		*******		Color de letra 	=> 	 Negro							************
		*******		Color de celda  => 	 Blanco							************
		*******															************
		****************************************************************************
		***************************************************************************/

		$mipdf -> SetFont( 'Arial' , '' , 8 );

	$mipdf -> Cell(5);
	$mipdf -> Cell(75, 2, '' ,'0LTR',0);
	$mipdf -> Cell(95, 2, '' ,'0TR',1);
	$mipdf -> Cell(5);
	$mipdf -> Cell(25,5,'Cliente:','0L',0);
	$mipdf -> Cell(50,5,'Julian Alberto Patiño','0R',0);
	$mipdf->SetFont('Arial','B',12);
	$mipdf -> Cell(5);
	$mipdf -> Cell(40,5,'Forma De Pago',0,0);
	$mipdf -> Cell(50,5,'Efectivo','0R',1);
	$mipdf -> Cell(5);
	$mipdf->SetFont('Arial','I',8);
	$mipdf -> Cell(25,5,'Nit/Documento:','0L',0);
	$mipdf -> Cell(50,5,'1128904058','0R',0);
	$mipdf->SetFont('Arial','B',12);
	$mipdf -> Cell(5);
	$mipdf -> Cell(40,5,'Subtotal:',0,0);
	$mipdf -> Cell(50,5,$subtotalFactura,'0R',1);
	$mipdf -> Cell(5);
	$mipdf->SetFont('Arial','I',8);
	$mipdf -> Cell(25,5,'Direccion:','0L',0);
	$mipdf -> Cell(50,5,'Mz 25 Cs 34 Villa del Campo','0R',0);
	$mipdf->SetFont('Arial','B',12);
	$mipdf -> Cell(5);
	$mipdf -> Cell(40,5,'I.V.A:',0,0);
	$mipdf -> Cell(50,5,'$5.000','0R',1);
	$mipdf -> Cell(5);
	$mipdf->SetFont('Arial','I',8);
	$mipdf -> Cell(25,5,'Telefono:','0L',0);
	$mipdf -> Cell(50,5,'3322660','0R',0);
	$mipdf->SetFont('Arial','B',12);
	$mipdf -> Cell(5);
	$mipdf -> Cell(40,5,'Total a Pagar:',0,0);
	$mipdf -> Cell(50,5,$subtotalFactura,'0R',1);
	$mipdf -> Cell(5);
	$mipdf->SetFont('Arial','I',8);
	$mipdf -> Cell(25,5,'Celular:','0LB',0);
	$mipdf -> Cell(50,5,'3127473361','0RB',0);
	$mipdf -> Cell(5,5,'','0B');
	$mipdf->SetFont('Arial','I',8);
	$mipdf -> Cell(40,5,'','0B',0);
	$mipdf -> Cell(50,5,'','0RB',1);

	$DataFacturaTotal = array(
		'NumCotiza' 	=> $NewConsecutivo, 
		'FechaCotiza'	=> $fecha,
		'HoraCotiza'	=> $hora,
		'Subtotal'		=> $subtotalFactura,
		'iva'			=> 0,
		'Descuento'		=> 0,
		'Total'			=> $subtotalFactura,
		'IdTercero'		=> '1128904058'
	);
	$facturar -> ActualizarConsecutivoCotizar($NewConsecutivo,$fecha);
	$facturar -> dbAddCotizar($DataFacturaTotal);	 

	$mipdf -> Output();
?>