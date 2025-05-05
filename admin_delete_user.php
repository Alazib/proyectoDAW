<?php
session_start();
require("database.php");
$con = conectar();

$id = $_GET['id'];
$sql = "DELETE FROM users WHERE id_user = $id";
mysqli_query($con, $sql);
header("Location: admin_dashboard.php");
exit();
