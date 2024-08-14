<?php
require "config/start.php";

if (!isset($_GET['empleado_id']) || !is_numeric($_GET['empleado_id'])) {
    die("ID de empleado no válido.");
}

$empleado_id = $_GET['empleado_id'];

// Obtener información del empleado
$stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = :empleado_id");
$stmt->execute([':empleado_id' => $empleado_id]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener las transacciones del empleado
$stmt = $pdo->prepare("SELECT * FROM transacciones_empleados WHERE empleado_id = :empleado_id ORDER BY fecha DESC");
$stmt->execute([':empleado_id' => $empleado_id]);
$transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular sueldo neto
function calcularSueldoNeto($empleado_id, $pdo) {
    $stmt = $pdo->prepare("SELECT sueldo FROM empleados WHERE id = :empleado_id");
    $stmt->execute([':empleado_id' => $empleado_id]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);
    $sueldo_base = $empleado['sueldo'];

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

    $sueldo_neto = $sueldo_base + $total_bonos - $total_adelantos - $total_descuentos;
    return $sueldo_neto;
}

$sueldo_neto = calcularSueldoNeto($empleado_id, $pdo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Transacciones de <?= htmlspecialchars($empleado['nombre']) ?></title>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h1 class="mt-4">Transacciones de <?= htmlspecialchars($empleado['nombre']) ?></h1>
        <p><strong>Sueldo Neto:</strong> <?= htmlspecialchars(number_format($sueldo_neto,0)) ?></p>
        <!-- Formulario para agregar una nueva transacción -->
        <form action="storeTransaccion.php" method="post">
            <input type="hidden" name="empleado_id" value="<?= htmlspecialchars($empleado_id) ?>">
            <div class="mb-3">
                <label for="tipo_transaccion" class="form-label">Tipo de Transacción</label>
                <select name="tipo_transaccion" id="tipo_transaccion" class="form-select">
                    <option value="adelanto">Adelanto</option>
                    <option value="bono">Bono</option>
                    <option value="descuento">Descuento</option>
                    <option value="pago_final">Pago Final</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="monto" class="form-label">Monto</label>
                <input type="number" name="monto" id="monto" class="form-control" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
            </div>
            <button type="submit" name="addTransaccion" class="btn btn-success">Agregar Transacción</button>
        </form>
        <hr>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transacciones as $transaccion): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaccion['id']) ?></td>
                        <td><?= htmlspecialchars($transaccion['tipo_transaccion']) ?></td>
                        <td><?= htmlspecialchars(number_format($transaccion['monto'],0)) ?></td>
                        <td><?= htmlspecialchars($transaccion['fecha']) ?></td>
                        <td><?= htmlspecialchars($transaccion['descripcion']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


                </main>
    <?php include 'components/footer.php'?>
</body>
</html>