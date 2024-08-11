<?php
require 'config/start.php';

$search = $_GET['search'] ?? '';
$type = $_GET['type'] ?? '';  // Filtro de tipo
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Ajustamos la consulta de conteo para incluir el filtro de tipo
$sql_count = "SELECT COUNT(*) 
              FROM movimientos_stock 
              WHERE producto_id IN (SELECT id FROM productos WHERE nombre LIKE :search)
              AND (:type = '' OR tipo = :type)";
$stmt_count = $pdo->prepare($sql_count);
$params_count = [
    ':search' => "%$search%",
    ':type' => $type
];
$stmt_count->execute($params_count);
$total_movimientos = $stmt_count->fetchColumn();
$total_pages = ceil($total_movimientos / $limit);

// Ajustamos la consulta para incluir el filtro de tipo
$sql = "SELECT m.*, p.nombre as producto_nombre 
        FROM movimientos_stock m 
        JOIN productos p ON m.producto_id = p.id 
        WHERE p.nombre LIKE :search
        AND (:type = '' OR tipo = :type)
        ORDER BY m.fecha DESC
        LIMIT $limit OFFSET $offset";  // LIMIT y OFFSET directamente en la consulta
$stmt = $pdo->prepare($sql);
$params = [
    ':search' => "%$search%",
    ':type' => $type
];
$stmt->execute($params);
$movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container">
        <h1 class="my-4">Movimientos de Stock</h1>
        
        <form method="get" action="movimientos.php" class="mb-4">
            <div class="form-row align-items-center row">
                <div class="col-sm-12 col-md-6 mb-2 mb-md-0">
                    <input type="text" name="search" class="form-control" placeholder="Buscar producto" value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-sm-12 col-md-4 mb-2 mb-md-0">
                    <select name="type" class="form-control">
                        <option value="">Tipo</option>
                        <option value="entrada" <?= $type === 'entrada' ? 'selected' : '' ?>>Entradas</option>
                        <option value="salida" <?= $type === 'salida' ? 'selected' : '' ?>>Salidas</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Buscar</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($movimientos)): ?>
                    <tr>
                        <td colspan="4" class="text-center">No se encontraron movimientos</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($movimientos as $movimiento): ?>
                    <tr>
                        <td><?= htmlspecialchars($movimiento['producto_nombre']) ?></td>
                        <td><?= htmlspecialchars($movimiento['cantidad']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($movimiento['tipo'])) ?></td>
                        <td><?= htmlspecialchars($movimiento['fecha']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= htmlspecialchars($search) ?>&type=<?= htmlspecialchars($type) ?>&page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i === (int)$page ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= htmlspecialchars($search) ?>&type=<?= htmlspecialchars($type) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?search=<?= htmlspecialchars($search) ?>&type=<?= htmlspecialchars($type) ?>&page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</main>
<?php include 'components/footer.php'?>
</body>
</html>

