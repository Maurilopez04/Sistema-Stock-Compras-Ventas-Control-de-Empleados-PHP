<?php
require 'config/start.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';

$sql = "SELECT id, nombre FROM proveedores WHERE nombre LIKE :search";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$results = [];
foreach ($proveedores as $proveedor) {
    $results[] = [
        'id' => $proveedor['id'],
        'text' => $proveedor['nombre']
    ];
}

echo json_encode(['results' => $results]);
?>
