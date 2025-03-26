<?php
$host = "localhost";
$user = "root";
$password = "root";
$db_name = "proyectodaw";

$con;

function conectar(){
	$con = mysqli_connect($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["password"]) or die("Error al conectar con la base de datos");
	//crear_bdd($con);
	mysqli_select_db($con, $GLOBALS["db_name"]);
	//crear_tabla_usuario($con);
	return $con;
	
}


function login($con, $alias, $password){
	$stmt = mysqli_prepare($con, "select * from users where alias=?;");
	mysqli_stmt_bind_param($stmt, "s", $alias);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if(mysqli_num_rows($result)>0){

		$datosUsuario = mysqli_fetch_array($result);
		if(password_verify($password, $datosUsuario['password_hash'])){
			return $datosUsuario;
		}
		return false;
	}

	return false;
}


function getRol($con, $id_rol){
	$stmt = mysqli_prepare($con, "select rol from rol where id_rol=?;");
	mysqli_stmt_bind_param($stmt, "i", $id_rol);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$rol = mysqli_fetch_array($result);
	return $rol; 
}

function getGender($con, $id_gender){
	$stmt = mysqli_prepare($con, "select gender from gender where id_gender=?;");
	mysqli_stmt_bind_param($stmt, "i", $id_gender);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$gender = mysqli_fetch_array($result);
	return $gender; 
}

function cerrar_conexion($con){
	mysqli_close($con);
}

// -------------- CÓDIGO LEGACY: LA BBDD, LAS TABLAS Y EL ADMIN YA ESTÁN CREADOS. NO ES NECESARIO CREARLAS/COMPROBAR CADA VEZ
//--------------- QUE SE INICIA LA APP.

// function crear_bdd($con){
// 	mysqli_query($con, "create database if not exists ".$GLOBALS["db_name"].";");
// }

// function crear_tabla_usuario($con){
// 	mysqli_query($con, "create table if not exists usuario(
// 	id int auto_increment primary key,
//     nombre varchar(100),
// 	username varchar(100),
// 	email varchar(100),
// 	pass varchar(255),
// 	tipo int);");
//     crear_admin($con);
// }

// function crear_admin($con){
// 	$resultado = existe_admin($con);
// 	if(mysqli_fetch_array($resultado)==0){
// 		$admin_name = "admin";
// 		$admin_user = "admin";
// 		$admin_email = "admin@admin.com";
// 		$password = password_hash($admin_name, PASSWORD_DEFAULT);
// 		$admin_type = 0;
// 		$stmt = mysqli_prepare($con, "insert into usuario(nombre, username, email, pass, tipo) values(?,?,?,?,?);");
// 		mysqli_stmt_bind_param($stmt, "ssssi", $admin_name, $admin_user, $admin_email, $password, $admin_type);
// 		mysqli_stmt_execute($stmt);
// 	}
// }

// function existe_admin($con){
// 	$result = mysqli_query($con, "select * from usuario where tipo=0");
// 	return $result;
// }