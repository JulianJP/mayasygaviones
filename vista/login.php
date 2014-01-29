<?php	
		session_start();
		session_unset();
		session_set_cookie_params (10,"/");
?>
<html>
	<head>
		<title>Mayas Y Gaviones De Risaralda</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="../css/jquery-ui-1.10.3.custom.css" type="text/css" media="screen" />
        <script type="text/javascript" src="../scripts/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../scripts/jquery-ui-1.10.3.custom.js"></script>
        <script type="text/javascript">
        	//***********************************************************************************
			//***********************************************************************************
			//*******    RUTINA QUE SE EJECUTA AL CARGARSE LA PAGINA WEB     ********************
			//***********************************************************************************
			//***********************************************************************************

			var pagina;
			pagina=$(document);
			pagina.ready(inicializar);

			function inicializar () 
			{
				$("#login").dialog({title: "Login"});	  	
			}
        </script>
        <style type="text/css">
        	body
        	{
        		background: url('../multimedia/img/ui-bg_diagonals-thick_20_666666_40x40.png') rgba(0,0,0,0.1);
        	}
        </style>
	</head>
	<body>
		<div id="login">
			<form name="login" action="../controlador/validar.php" method="post">
				<label>Nombre De Usuario</label>
				<input type="text" name="nickname">
				<br>
				<label>Contraseña</label>
				<input type="password" name="pass">
				<input type="submit" value="Ingresar">
			</form>
		</div>
	</body>
</html>
