<?php
require "config/start.php";

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

$sql = "UPDATE marcas SET nombre = ?, descripcion = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $descripcion, $id]);

header("Location: marcas.php");