<?php
require "config/start.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

$sql = "SELECT ventas.*, productos.nombre AS producto_nombre, clientes.nombre AS cliente_nombre 
        FROM ventas 
        JOIN productos ON ventas.producto_id = productos.id
        JOIN clientes ON ventas.cliente_id = clientes.id
        WHERE (productos.nombre LIKE :search OR clientes.nombre LIKE :search)";

if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $sql .= " AND ventas.fecha BETWEEN :fecha_inicio AND :fecha_fin";
}

$sql .= " ORDER BY ventas.fecha DESC";

$stmt = $pdo->prepare($sql);
$params = ['search' => "%$search%"];
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $params['fecha_inicio'] = $fecha_inicio;
    $params['fecha_fin'] = $fecha_fin;
}
$stmt->execute($params);
$ventas = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="my-4">Registro de Ventas</h1>
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Buscar por producto o cliente" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($fecha_inicio) ?>" placeholder="Fecha Inicio">
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($fecha_fin) ?>" placeholder="Fecha Fin">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </div>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?= htmlspecialchars($venta['cliente_nombre']) ?></td>
                    <td><?= htmlspecialchars($venta['producto_nombre']) ?></td>
                    <td><?= htmlspecialchars($venta['cantidad']) ?></td>
                    <td><?= htmlspecialchars(number_format($venta['precio'], 0)) ?></td>
                    <td><?= htmlspecialchars($venta['fecha']) ?></td>
                    <td>
                        <a href="generar_ticket.php?venta_id=<?= $venta['id'] ?>" class="btn btn-primary">Generar Ticket</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include 'components/footer.php'?>
</body>
</html>
