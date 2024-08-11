<?php
require 'config/start.php';

$q = $_GET['q'] ?? '';

// Obtener clientes que coincidan con la búsqueda
$sql = "SELECT id, nombre FROM clientes WHERE nombre LIKE :q LIMIT 10";
$stmt = $pdo->prepare($sql);
$stmt->execute(['q' => "%$q%"]);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formatear los resultados para Select2
$results = array_map(function($cliente) {
    return [
        'id' => $cliente['id'],
        'text' => $cliente['nombre']
    ];
}, $clientes);

echo json_encode(['results' => $results]);
?>
