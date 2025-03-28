<?php
$host = "localhost";
$user = "root";
$password = "root";
$db_name = "proyectodaw";

$con;


//MANAGEMENT OF DATABASE
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

function cerrar_conexion($con){
	mysqli_close($con);
}

//-----------------------------------//


//GETTERS
function getRol($con, $id_rol){
	$stmt = mysqli_prepare($con, "select rol from rol where id_rol=?;");
	mysqli_stmt_bind_param($stmt, "i", $id_rol);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$rol = mysqli_fetch_assoc($result);
	return $rol; 
}

function getUser($con, $id_user){
	$stmt = mysqli_prepare($con, "select * from users where id_user=?;");
	mysqli_stmt_bind_param($stmt, "i", $id_user);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$user = mysqli_fetch_assoc($result);
	return $user; 
}

function getGender($con, $id_gender){
	$stmt = mysqli_prepare($con, "select * from gender where id_gender=?;");
	mysqli_stmt_bind_param($stmt, "i", $id_gender);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$gender = mysqli_fetch_assoc($result);
	return $gender; 
}

function getCountry($con, $id_country){
	$stmt = mysqli_prepare($con, "select * from country where id_country=?;");
	mysqli_stmt_bind_param($stmt, "i", $id_country);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$country = mysqli_fetch_assoc($result);
	return $country; 
}

function getAllGenders($con){
	$stmt = mysqli_prepare($con, "select * from gender;");
	mysqli_stmt_execute($stmt);
	$genders = mysqli_stmt_get_result($stmt);

	return $genders; 
}

function getAllRols($con){
	$stmt = mysqli_prepare($con, "select * from rol;");
	mysqli_stmt_execute($stmt);
	$rols = mysqli_stmt_get_result($stmt);

	return $rols; 
}

function getAllCountries($con){
	$stmt = mysqli_prepare($con, "select * from country;");
	mysqli_stmt_execute($stmt);
	$countries = mysqli_stmt_get_result($stmt);

	return $countries; 
}


