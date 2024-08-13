<?php
require 'config/start.php';

if (!isset($_GET['venta_id'])) {
    die(json_encode(['error' => 'ID de venta no especificado']));
}

$venta_id = $_GET['venta_id'];

// Obtener detalles de la venta
$sql = "SELECT ventas.total, ventas.fecha, clientes.nombre AS cliente_nombre
        FROM ventas
        JOIN clientes ON ventas.cliente_id = clientes.id
        WHERE ventas.id = :venta_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['venta_id' => $venta_id]);
$venta = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener detalles de los productos vendidos
$sql = "SELECT productos.nombre, ventas_detalle.cantidad, ventas_detalle.precio
        FROM ventas_detalle
        JOIN productos ON ventas_detalle.producto_id = productos.id
        WHERE ventas_detalle.venta_id = :venta_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['venta_id' => $venta_id]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$venta['productos'] = $productos;

// Devolver los datos como JSON
echo json_encode($venta);
?>
