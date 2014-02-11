<?php
	session_start();
	date_default_timezone_set('America/Bogota');

	if (!isset($_SESSION['NICKNAME']) )
	{
		echo "Debe loguearse para poder ingresar <br><br>";
		include 'login.php';
	}
	else
	{
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Mallas Y Gaviones Risaralda</title>
		<link rel="stylesheet" href="../css/jquery-ui-1.10.3.custom.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="../css/desktop.css" type="text/css" media="screen" />
        <script type="text/javascript" src="../scripts/jquery-1.11.0.js"></script>
        <script type="text/javascript" src="../scripts/jquery-ui-1.10.3.custom.js"></script>
        <script type="text/javascript" src="../scripts/interaccionSec.js"></script>
	</head>
	<body>
		<div class='header'>
			<div style="float:left;padding: 0 5%"><img src="../multimedia/img/mygr.png" width="170px" height="100px"></div>
			<div class="bienvenido">
				Sistema de Facturacion e Inventariado <br>
				Bienvenido <?php echo $_SESSION['NOMBRE']?><br>
				Fecha : <?php echo date('d/m/Y')?>
			</div>
		</div>
		<div class="principal">
			<nav>
				<ul>
					<li><a href="#fact" id="Facturar">Facturar</a></li>
					<li><a href="#inv" id="Inventario">Inventario</a></li>
					<li><a href="#coti" id="Cotizar">Cotizacion</a></li>
					<li><a href="#repor" id="Reporte">Reporte</a></li>
					<li><a href="../index.php">Salir</a></li>
				</ul>
			</nav>
			<div id='find'><form action='../controlador/find.php' class='buscador'><input type'text' class='buscador' name='descripcion'></form><div id='encontrado'></div></div>
			<div class="contenedor"></div>
		</div>
	</body>
</html>
<?php
	}
?>