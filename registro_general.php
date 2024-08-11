<?php
require "config/start.php";

// Calcular total de compras
$sql = "SELECT SUM(cantidad * precio) AS total_compras FROM compras";
$total_compras = $pdo->query($sql)->fetchColumn();

// Calcular total de ventas
$sql = "SELECT SUM(cantidad * precio) AS total_ventas FROM ventas";
$total_ventas = $pdo->query($sql)->fetchColumn();

// Calcular ganancia o pérdida
$ganancia_perdida = $total_ventas - $total_compras;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="my-4">Registro General de Ganancias y Pérdidas</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Total Compras</th>
                <th>Total Ventas</th>
                <th>Ganancia/Pérdida</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= number_format($total_compras, 0) ?></td>
                <td><?= number_format($total_ventas, 0) ?></td>
                <td><?= number_format($ganancia_perdida, 0) ?></td>
            </tr>
        </tbody>
    </table>
</main>
<?php include 'components/footer.php'?>
</body>
</html>
