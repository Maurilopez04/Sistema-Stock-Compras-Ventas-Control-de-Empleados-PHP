<?php
require "config/start.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTransaccion'])) {
    $empleado_id = $_POST['empleado_id'];
    $tipo_transaccion = $_POST['tipo_transaccion'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO transacciones_empleados (empleado_id, tipo_transaccion, monto, fecha, descripcion) 
            VALUES (:empleado_id, :tipo_transaccion, :monto, :fecha, :descripcion)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':empleado_id' => $empleado_id,
        ':tipo_transaccion' => $tipo_transaccion,
        ':monto' => $monto,
        ':fecha' => $fecha,
        ':descripcion' => $descripcion
    ]);

    // Redirigir de vuelta a la página de transacciones del empleado
    header("Location: verTransacciones.php?empleado_id=" . urlencode($empleado_id));
    exit();
}
?>

