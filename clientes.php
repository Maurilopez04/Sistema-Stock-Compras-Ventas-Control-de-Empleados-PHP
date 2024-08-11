<?php
require "config/start.php";

$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Construir la consulta para contar los clientes
$sql_count = "SELECT COUNT(*) FROM clientes WHERE nombre LIKE ?";
$params_count = ["%$search%"];
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute($params_count);
$total_clients = $stmt_count->fetchColumn();
$total_pages = ceil($total_clients / $limit);

// Construir la consulta para obtener los clientes
$sql = "SELECT * FROM clientes WHERE nombre LIKE ? LIMIT $limit OFFSET $offset";
$params = ["%$search%"];
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="my-4">Clientes</h1>
    <div class="row mb-4">
        <div class="col-12 col-md-8">
            <form class="d-flex" method="get" action="clientes.php">
                <input class="form-control me-2" type="text" name="search" placeholder="Buscar clientes" value="<?= htmlspecialchars($search) ?>">
                <input class="btn btn-secondary" type="submit" value="Buscar">
            </form>
        </div>
        <div class="col-12 col-md-4 mt-2 mt-md-0 text-md-end">
            <button type="button" data-bs-toggle="modal" data-bs-target="#addClientModal" class="btn btn-success w-100 w-md-auto">Nuevo Cliente</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>CI/RUC</th>
                    <th>Fecha de Cumpleaños</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                    <td><?= htmlspecialchars($cliente['direccion']) ?></td>
                    <td><?= htmlspecialchars($cliente['ci_ruc']) ?></td>
                    <td><?= htmlspecialchars($cliente['fecha_cumple']) ?></td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editClientModal<?= $cliente['id'] ?>">Editar</button>
                            <button type="button" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#deleteClientModal<?= $cliente['id'] ?>">Eliminar</button>
                        </div>
                    </td>
                </tr>

                <!-- Modal para Editar Cliente -->
                <div class="modal fade" id="editClientModal<?= $cliente['id'] ?>" tabindex="-1" aria-labelledby="editClientModalLabel<?= $cliente['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editClientModalLabel<?= $cliente['id'] ?>">Editar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="updateClient.php" method="post">
                                    <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                                    <div class="mb-3">
                                        <label for="nombre<?= $cliente['id'] ?>" class="form-label">Nombre:</label>
                                        <input type="text" id="nombre<?= $cliente['id'] ?>" name="nombre" class="form-control" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email<?= $cliente['id'] ?>" class="form-label">Email:</label>
                                        <input type="email" id="email<?= $cliente['id'] ?>" name="email" class="form-control" value="<?= htmlspecialchars($cliente['email']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefono<?= $cliente['id'] ?>" class="form-label">Teléfono:</label>
                                        <input type="text" id="telefono<?= $cliente['id'] ?>" name="telefono" class="form-control" value="<?= htmlspecialchars($cliente['telefono']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="direccion<?= $cliente['id'] ?>" class="form-label">Dirección:</label>
                                        <textarea id="direccion<?= $cliente['id'] ?>" name="direccion" class="form-control"><?= htmlspecialchars($cliente['direccion']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ci_ruc<?= $cliente['id'] ?>" class="form-label">CI/RUC:</label>
                                        <input type="text" id="ci_ruc<?= $cliente['id'] ?>" name="ci_ruc" class="form-control" value="<?= htmlspecialchars($cliente['ci_ruc']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_cumple<?= $cliente['id'] ?>" class="form-label">Fecha de Cumpleaños:</label>
                                        <input type="date" id="fecha_cumple<?= $cliente['id'] ?>" name="fecha_cumple" class="form-control" value="<?= htmlspecialchars($cliente['fecha_cumple']) ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Eliminar Cliente -->
                <div class="modal fade" id="deleteClientModal<?= $cliente['id'] ?>" tabindex="-1" aria-labelledby="deleteClientModalLabel<?= $cliente['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteClientModalLabel<?= $cliente['id'] ?>">Confirmar Eliminación</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ¿Estás seguro de que deseas eliminar a este cliente?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <form action="deleteClient.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item"><a class="page-link" href="?search=<?= htmlspecialchars($search) ?>&page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul>
    </div>

    <!-- Modal para Agregar Cliente -->
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Agregar Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="storeClient.php" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección:</label>
                            <textarea id="direccion" name="direccion" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ci_ruc" class="form-label">CI/RUC:</label>
                            <input type="text" id="ci_ruc" name="ci_ruc" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_cumple" class="form-label">Fecha de Cumpleaños:</label>
                            <input type="date" id="fecha_cumple" name="fecha_cumple" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>



<?php include 'components/footer.php'?>
</body>
</html>