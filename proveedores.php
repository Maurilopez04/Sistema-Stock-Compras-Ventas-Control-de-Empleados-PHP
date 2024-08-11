<?php
require "config/start.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $contacto = $_POST['contacto'];
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    try {
        $pdo->beginTransaction();

        if ($id) {
            // Actualizar proveedor existente
            $sql = "UPDATE proveedores SET nombre = :nombre, contacto = :contacto WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombre' => $nombre, 'contacto' => $contacto, 'id' => $id]);
        } else {
            // Insertar nuevo proveedor
            $sql = "INSERT INTO proveedores (nombre, contacto) VALUES (:nombre, :contacto)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombre' => $nombre, 'contacto' => $contacto]);
        }

        $pdo->commit();
        header('Location: proveedores.php?success=1');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error al procesar la solicitud: " . $e->getMessage();
    }
}

// Obtener proveedores
$sql = "SELECT * FROM proveedores";
$proveedores = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'; ?>
    <link href="scripts/select2/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h1 class="my-4">Proveedores</h1>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalProveedor">Agregar Proveedor</button>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="alert alert-success">Proveedor procesado exitosamente.</div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proveedores as $proveedor): ?>
                    <tr>
                        <td><?= htmlspecialchars($proveedor['nombre']) ?></td>
                        <td><?= htmlspecialchars($proveedor['contacto']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalProveedor" 
                                data-id="<?= $proveedor['id'] ?>" 
                                data-nombre="<?= htmlspecialchars($proveedor['nombre']) ?>" 
                                data-contacto="<?= htmlspecialchars($proveedor['contacto']) ?>">
                                Editar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal Proveedor -->
        <div class="modal fade" id="modalProveedor" tabindex="-1" aria-labelledby="modalProveedorLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalProveedorLabel">Agregar / Editar Proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formProveedor" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="id" name="id">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="contacto" class="form-label">Contacto</label>
                                <input type="text" class="form-control" id="contacto" name="contacto" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>
    <?php include 'components/footer.php'; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var modalProveedor = document.getElementById('modalProveedor');
        modalProveedor.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nombre = button.getAttribute('data-nombre');
            var contacto = button.getAttribute('data-contacto');

            var modal = bootstrap.Modal.getInstance(modalProveedor);
            var form = document.getElementById('formProveedor');

            if (id) {
                // Editar
                form.action = 'proveedores.php';  // Cambia a la URL del archivo de procesamiento para edición
                document.getElementById('id').value = id;
                document.getElementById('nombre').value = nombre;
                document.getElementById('contacto').value = contacto;
                modal._element.querySelector('.modal-title').textContent = 'Editar Proveedor';
            } else {
                // Agregar
                form.action = 'proveedores.php';  // Cambia a la URL del archivo de procesamiento para agregar
                document.getElementById('formProveedor').reset();
                document.getElementById('id').value = '';
                modal._element.querySelector('.modal-title').textContent = 'Agregar Proveedor';
            }
        });
    });
    </script>
</body>
</html>
