<?php
require "config/start.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Buscar por nombre del proveedor o producto
$sql = "SELECT compras.*, productos.nombre AS producto_nombre, proveedores.nombre AS proveedor_nombre 
        FROM compras 
        JOIN productos ON compras.producto_id = productos.id
        JOIN proveedores ON compras.proveedor_id = proveedores.id
        WHERE (productos.nombre LIKE :search OR proveedores.nombre LIKE :search)";

if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $sql .= " AND compras.fecha BETWEEN :fecha_inicio AND :fecha_fin";
}

$sql .= " ORDER BY compras.fecha DESC";

$stmt = $pdo->prepare($sql);
$params = ['search' => "%$search%"];
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $params['fecha_inicio'] = $fecha_inicio;
    $params['fecha_fin'] = $fecha_fin;
}
$stmt->execute($params);
$compras = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
    <link href="scripts/select2/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="scripts/select2/select2.min.js"></script>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="my-4">Registro de Compras</h1>
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" id="search" name="search" class="form-control" placeholder="Buscar por producto o proveedor" value="<?= htmlspecialchars($search) ?>">
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
                <th>Proveedor</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($compras as $compra): ?>
                <tr>
                    <td><?= htmlspecialchars($compra['proveedor_nombre']) ?></td>
                    <td><?= htmlspecialchars($compra['producto_nombre']) ?></td>
                    <td><?= htmlspecialchars($compra['cantidad']) ?></td>
                    <td><?= htmlspecialchars(number_format($compra['precio'], 2)) ?></td>
                    <td><?= htmlspecialchars($compra['fecha']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include 'components/footer.php'?>
<script>
$(document).ready(function() {
    $('#search').select2({
        placeholder: 'Buscar por producto o proveedor',
        ajax: {
            url: 'buscar_productos_proveedores.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            }
        },
        minimumInputLength: 1,
        width: '100%'
    }).on('select2:select', function (e) {
        var data = e.params.data;
        if (data.id.includes('|')) {
            // Esta es una búsqueda por producto
            $('#search').val(data.text);
        } else {
            // Esta es una búsqueda por proveedor
            $('#search').val(data.text);
        }
    });
});
</script>
</body>
</html>

