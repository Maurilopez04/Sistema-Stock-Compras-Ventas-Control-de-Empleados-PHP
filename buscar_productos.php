<?php
require 'config/start.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : 'venta'; // Añadido parámetro 'type'

$precioColumn = $type === 'compra' ? 'costo' : 'precioMinorista'; // Ajustar el precio según el tipo

$sql = "SELECT id, nombre, $precioColumn AS precio FROM productos WHERE nombre LIKE :search LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => "%$search%"]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$results = [];
foreach ($productos as $producto) {
    $results[] = [
        'id' => $producto['id'] . '|' . $producto['precio'],  // Devuelve id|precio
        'text' => $producto['nombre']
    ];
}

echo json_encode(['results' => $results]);
?>


