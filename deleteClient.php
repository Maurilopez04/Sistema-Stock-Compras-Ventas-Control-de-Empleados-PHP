<?php
require "config/start.php";

$id = $_POST['id'];

$sql = "DELETE FROM clientes WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: clientes.php");
exit();
?>
