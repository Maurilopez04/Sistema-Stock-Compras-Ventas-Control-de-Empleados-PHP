<?php require "config/start.php";


$id = $_GET['id'];
$sql = "DELETE FROM productos WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: productos.php");
exit;
?>
