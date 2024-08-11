<?php
require "config/start.php";

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

$sql = "INSERT INTO marcas (nombre, descripcion) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $descripcion]);

header("Location: marcas.php");
?>