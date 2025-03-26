<?php
session_start();

$id_user = $_SESSION['logged_user'];

echo var_dump($id_user);







?>