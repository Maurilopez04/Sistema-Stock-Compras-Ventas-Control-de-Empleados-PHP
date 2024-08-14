<?php
require "config/start.php";

// Obtener la lista de empleados para el dropdown
$sql = "SELECT id, nombre FROM empleados";
$stmt = $pdo->query($sql);
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario si se envía
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

    // Redirigir de vuelta a la página de transacciones del empleado seleccionado
    header("Location: verTransacciones.php?empleado_id=" . $empleado_id);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="mt-4">Registrar Transacción a Empleados</h1>
    <!-- Formulario para agregar transacción -->
    <form action="addTransaccion.php" method="post" class="mt-10">
        <div class="form-group">
            <label for="empleado_id">Empleado</label>
            <select name="empleado_id" id="empleado_id" class="form-control" required>
                <?php foreach ($empleados as $empleado): ?>
                    <option value="<?= htmlspecialchars($empleado['id']) ?>">
                        <?= htmlspecialchars($empleado['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tipo_transaccion">Tipo de Transacción</label>
            <select name="tipo_transaccion" id="tipo_transaccion" class="form-control" required>
                <option value="adelanto">Adelanto</option>
                <option value="bono">Bono</option>
                <option value="descuento">Descuento</option>
                <option value="pago_final">Pago Final</option>
            </select>
        </div>
        <div class="form-group">
            <label for="monto">Monto</label>
            <input type="number" name="monto" id="monto" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
        </div>
        <button type="submit" name="addTransaccion" class="btn btn-success mt-3">Agregar Transacción</button>
    </form>
</main>
<?php include 'components/footer.php'?>
</body>
</html>
