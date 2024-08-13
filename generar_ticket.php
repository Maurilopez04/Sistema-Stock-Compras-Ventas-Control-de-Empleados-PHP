<?php
require 'config/start.php';

if (!isset($_GET['venta_id'])) {
    die('ID de venta no especificado');
}

$venta_id = $_GET['venta_id'];

// Obtener detalles de la venta
$sql = "SELECT ventas.*, clientes.nombre AS cliente_nombre
        FROM ventas
        JOIN clientes ON ventas.cliente_id = clientes.id
        WHERE ventas.id = :venta_id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['venta_id' => $venta_id]);
$venta = $stmt->fetch();

// Obtener detalles de los productos vendidos
$sql = "SELECT ventas_detalle.*, productos.nombre AS producto_nombre
        FROM ventas_detalle
        JOIN productos ON ventas_detalle.producto_id = productos.id
        WHERE ventas_detalle.venta_id = :venta_id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['venta_id' => $venta_id]);
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php' ?>
    <style>
        .ticket {
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #000;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .ticket h1 {
            font-size: 16px;
            text-align: center;
        }
        .ticket .details p {
            margin: 0;
        }
        .ticket table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .ticket th, .ticket td {
            border-bottom: 1px solid #000;
            padding: 5px 0;
            text-align: left;
        }
        .ticket .total {
            font-weight: bold;
            text-align: right;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #ticket, #ticket * {
                visibility: visible;
            }
            #ticket {
                position: absolute;
                left: 0;
                top: 0;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="ticket" id="ticket">
    <h1>Ticket de Venta</h1>
    <div class="details">
        <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['cliente_nombre']) ?></p>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($venta['fecha']) ?></p>
        <p><strong>Total:</strong> <?= htmlspecialchars(number_format($venta['total'], 2)) ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['producto_nombre']) ?></td>
                    <td><?= htmlspecialchars($producto['cantidad']) ?></td>
                    <td><?= htmlspecialchars(number_format($producto['precio'], 2)) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="total">
        <p><strong>Total a pagar:</strong> <?= htmlspecialchars(number_format($venta['total'], 2)) ?></p>
    </div>
</div>
<button class="print-button" onclick="window.print();">Imprimir Ticket</button>
</body>
</html>

