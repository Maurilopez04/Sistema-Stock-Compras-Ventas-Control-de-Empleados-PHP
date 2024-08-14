<?php
require "config/start.php";

// Obtener la lista de empleados para el filtro
$sql = "SELECT id, nombre FROM empleados";
$stmt = $pdo->query($sql);
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener los tipos de transacción para el filtro
$tipos_transaccion = [
    'adelanto' => 'Adelanto',
    'bono' => 'Bono',
    'descuento' => 'Descuento',
    'pago_final' => 'Pago Final'
];

// Obtener las transacciones basadas en los filtros
$empleado_id = isset($_GET['empleado_id']) && is_numeric($_GET['empleado_id']) ? $_GET['empleado_id'] : null;
$tipo_transaccion = isset($_GET['tipo_transaccion']) ? $_GET['tipo_transaccion'] : null;
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

$sql = "SELECT * FROM transacciones_empleados WHERE 1=1";
$params = [];

if ($empleado_id) {
    $sql .= " AND empleado_id = :empleado_id";
    $params[':empleado_id'] = $empleado_id;
}

if ($tipo_transaccion) {
    $sql .= " AND tipo_transaccion = :tipo_transaccion";
    $params[':tipo_transaccion'] = $tipo_transaccion;
}

if ($fecha_inicio) {
    $sql .= " AND fecha >= :fecha_inicio";
    $params[':fecha_inicio'] = $fecha_inicio;
}

if ($fecha_fin) {
    $sql .= " AND fecha <= :fecha_fin";
    $params[':fecha_fin'] = $fecha_fin;
}

$sql .= " ORDER BY fecha DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="mt-4">Todas las Transacciones</h1>

    <!-- Filtros -->
    <form action="verTodasTransacciones.php" method="get" class="mb-4">
        <div class="form-group">
            <label for="empleado_id">Filtrar por Empleado</label>
            <select name="empleado_id" id="empleado_id" class="form-control">
                <option value="">Todos</option>
                <?php foreach ($empleados as $empleado): ?>
                    <option value="<?= htmlspecialchars($empleado['id']) ?>" <?= $empleado_id == $empleado['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($empleado['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tipo_transaccion">Filtrar por Tipo de Transacción</label>
            <select name="tipo_transaccion" id="tipo_transaccion" class="form-control">
                <option value="">Todos</option>
                <?php foreach ($tipos_transaccion as $key => $value): ?>
                    <option value="<?= htmlspecialchars($key) ?>" <?= $tipo_transaccion == $key ? 'selected' : '' ?>>
                        <?= htmlspecialchars($value) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="fecha_inicio">Fecha Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?= htmlspecialchars($fecha_inicio) ?>">
        </div>

        <div class="form-group">
            <label for="fecha_fin">Fecha Fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?= htmlspecialchars($fecha_fin) ?>">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
    </form>

    <!-- Tabla de transacciones -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Empleado</th>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transacciones as $transaccion): ?>
                <?php
                // Obtener el nombre del empleado
                $stmt = $pdo->prepare("SELECT nombre FROM empleados WHERE id = :empleado_id");
                $stmt->execute([':empleado_id' => $transaccion['empleado_id']]);
                $empleado = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <tr>
                    <td><?= htmlspecialchars($transaccion['id']) ?></td>
                    <td><?= htmlspecialchars($empleado['nombre']) ?></td>
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
