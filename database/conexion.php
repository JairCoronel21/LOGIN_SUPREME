<?php
  //Conexiona a mysql
	
	$mysqli=new mysqli("localhost","root","1234","sistema_ticket"); //servidor, usuario de base de datos, contraseña del usuario, nombre de base de datos
	
	if(mysqli_connect_errno()){
		echo 'Conexion Fallida : ', mysqli_connect_error();
		exit();
	}
	
?>  