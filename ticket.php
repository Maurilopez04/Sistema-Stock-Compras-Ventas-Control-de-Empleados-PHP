<?php
require "config/start.php";

$venta_id = $_GET['venta_id'];

// Obtener los datos de la venta
$sql = "SELECT v.id, v.fecha, c.nombre AS cliente_nombre FROM ventas v JOIN clientes c ON v.cliente_id = c.id WHERE v.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$venta_id]);
$venta = $stmt->fetch();

// Obtener los productos de la venta
$sql = "SELECT p.nombre, v.cantidad, v.precio FROM ventas v JOIN productos p ON v.producto_id = p.id WHERE v.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$venta_id]);
$productos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<?php include 'components/head.php' ?>
    <title>Ticket de Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .ticket {
            max-width: 250px;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #000;
            font-size: 12px;
            text-align: center;
            background:white;
            color:black;
        }
        .ticket h1 {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .ticket p {
            margin: 0;
            padding: 0;
        }
        .ticket table {
            width: 100%;
            margin: 10px 0;
            border-collapse: collapse;
        }
        .ticket th, .ticket td {
            text-align: left;
            padding: 5px 0;
            border-bottom: 1px dashed #000;
        }
        .ticket .total {
            font-weight: bold;
            border-top: 2px solid #000;
            margin-top: 10px;
            padding-top: 10px;
        }
        .print-button {
            margin-top: 20px;
            display: inline-block;
            width:300px;
            max-width:300px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        .print-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include 'components/header.php' ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div id="ticket" class="ticket">
        <h1>Ticket de Venta</h1>
        <p><strong>Cliente:</strong> <?= htmlspecialchars($venta['cliente_nombre']) ?></p>
        <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_venta = 0;
                foreach ($productos as $producto) {
                    $total_producto = $producto['cantidad'] * $producto['precio'];
                    $total_venta += $total_producto;
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($producto['nombre']) . "</td>";
                    echo "<td>" . $producto['cantidad'] . "</td>";
                    echo "<td>$" . number_format($total_producto, 2) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <p class="total"><strong>Total:</strong> $<?= number_format($total_venta, 2) ?></p>
    </div>
    <button class="print-button btn btn-primary" onclick="printTicket()">Imprimir Ticket</button>
</main>

<script>
    function printTicket() {
        var ticketContent = document.getElementById('ticket').innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = ticketContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>

</body>
</html>
