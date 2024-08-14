<?php
require "config/start.php";

if (!isset($_GET['empleado_id']) || !is_numeric($_GET['empleado_id'])) {
    die("ID de empleado no válido.");
}

$empleado_id = $_GET['empleado_id'];

function calcularSueldoNeto($empleado_id, $pdo) {
    // Obtener el sueldo base del empleado
    $stmt = $pdo->prepare("SELECT sueldo FROM empleados WHERE id = :empleado_id");
    $stmt->execute([':empleado_id' => $empleado_id]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);
    $sueldo_base = $empleado['sueldo'];

    // Calcular adelantos, bonos, descuentos
    $stmt = $pdo->prepare("SELECT tipo_transaccion, SUM(monto) AS total FROM transacciones_empleados WHERE empleado_id = :empleado_id GROUP BY tipo_transaccion");
    $stmt->execute([':empleado_id' => $empleado_id]);
    $transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_adelantos = 0;
    $total_bonos = 0;
    $total_descuentos = 0;

    foreach ($transacciones as $transaccion) {
        switch ($transaccion['tipo_transaccion']) {
            case 'adelanto':
                $total_adelantos += $transaccion['total'];
                break;
            case 'bono':
                $total_bonos += $transaccion['total'];
                break;
            case 'descuento':
                $total_descuentos += $transaccion['total'];
                break;
        }
    }

    // Calcular sueldo neto
    $sueldo_neto = $sueldo_base + $total_bonos - $total_adelantos - $total_descuentos;
    return $sueldo_neto;
}

// Mostrar el sueldo neto
$sueldo_neto = calcularSueldoNeto($empleado_id, $pdo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sueldo Neto de Empleado</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Sueldo Neto de Empleado</h1>
        <p><strong>ID del Empleado:</strong> <?= htmlspecialchars($empleado_id) ?></p>
        <p><strong>Sueldo Neto:</strong> <?= htmlspecialchars($sueldo_neto) ?></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>
