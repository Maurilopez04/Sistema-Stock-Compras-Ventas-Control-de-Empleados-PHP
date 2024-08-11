<?php
require "config/start.php";

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$ci_ruc = $_POST['ci_ruc'];
$fecha_cumple = $_POST['fecha_cumple'];

$sql = "UPDATE clientes SET nombre = ?, email = ?, telefono = ?, direccion = ?, ci_ruc = ?, fecha_cumple = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $email, $telefono, $direccion, $ci_ruc, $fecha_cumple, $id]);

header("Location: clientes.php");
exit();
?>
