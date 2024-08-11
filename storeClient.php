<?php
require "config/start.php";

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$ci_ruc = $_POST['ci_ruc'];
$fecha_cumple = $_POST['fecha_cumple'];

$sql = "INSERT INTO clientes (nombre, email, telefono, direccion, ci_ruc, fecha_cumple) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $email, $telefono, $direccion, $ci_ruc, $fecha_cumple]);

header("Location: clientes.php");
exit();
?>
