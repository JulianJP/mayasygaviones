<?php

	include '../modelo/mygr_model.php';

	$nickname = $_POST["nickname"];
	$password = $_POST["pass"];

	$db_usuarios = new Database;

	$row = $db_usuarios->ValidarUsu($nickname,$password);

	if ($row) 
	{
		$usuario = mysqli_fetch_array($row);

		session_start();

		$_SESSION['NICKNAME'] = $nickname;
		$_SESSION['TIPO'] = $usuario['tipo'];
		$_SESSION['NOMBRE'] = $usuario['nombre'];
		$_SESSION['APELLIDO'] = $usuario['apellido1'];

		header( "Status: 301 Moved Permanently", false, 301);

		switch ($_SESSION['TIPO']) 
		{
			case 'S':
				header("Location: ../vista/sec.php");
				break;
			case 'G':
				header("Location: ../vista/ger.php");
				break;
			case 'A':
				header("Location: ../vista/admin.php");
				break;

			default:
				header("Location: ../vista/login.php");
				exit();
				break;
		}
	}
	else
	{
		echo "El Usuario No Existe";
	}



?>