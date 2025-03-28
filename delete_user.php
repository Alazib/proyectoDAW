<?php
session_start();
require("database.php");
$con = conectar();

$id = $_GET['id'];
$sql = "DELETE FROM usuario WHERE id = $id";
mysqli_query($con, $sql);
header("Location: admin_page.php");
exit();
?>
