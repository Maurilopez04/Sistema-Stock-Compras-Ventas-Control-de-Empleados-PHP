<?php
require "config/start.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM empleados WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}

$sql = "SELECT * FROM empleados";
$stmt = $pdo->query($sql);
$result = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h1 class="mt-4">Gestión de Empleados</h1>
        
        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Agregar Empleado</button>
        
        <table class="table table-striped">
    <thead>
        <tr>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Sueldo</th>
            <th>Puesto</th>
            <th>Número</th>
            <th>Correo</th>
            <th>Fecha de Contratación</th>
            <th>Casado</th>
            <th>Hijos</th>
            <th>Ubicación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['cedula']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars(number_format($row['sueldo'],0)) ?></td>
                <td><?= htmlspecialchars($row['puesto']) ?></td>
                <td><?= htmlspecialchars($row['numero']) ?></td>
                <td><?= htmlspecialchars($row['correo']) ?></td>
                <td><?= htmlspecialchars($row['fecha_contratacion']) ?></td>
                <td><?= $row['casado'] ? 'Sí' : 'No' ?></td>
                <td><?= htmlspecialchars($row['hijos']) ?></td>
                <td><?= htmlspecialchars($row['ubicacion']) ?></td>
                <td>
                    <!-- Botón para Editar -->
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" 
                            data-id="<?= htmlspecialchars($row['id']) ?>" data-cedula="<?= htmlspecialchars($row['cedula']) ?>" 
                            data-nombre="<?= htmlspecialchars($row['nombre']) ?>" data-sueldo="<?= htmlspecialchars($row['sueldo']) ?>"
                            data-puesto="<?= htmlspecialchars($row['puesto']) ?>" data-numero="<?= htmlspecialchars($row['numero']) ?>" 
                            data-correo="<?= htmlspecialchars($row['correo']) ?>" data-fecha_contratacion="<?= htmlspecialchars($row['fecha_contratacion']) ?>"
                            data-casado="<?= htmlspecialchars($row['casado']) ?>" data-hijos="<?= htmlspecialchars($row['hijos']) ?>" 
                            data-ubicacion="<?= htmlspecialchars($row['ubicacion']) ?>">
                        Editar
                    </button>
                    <!-- Formulario para Eliminar -->
                    <form method="post" style="display:inline-block">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                    <!-- Enlace para Ver Transacciones -->
                    <a href="verTransacciones.php?empleado_id=<?= $row['id'] ?>" class="btn btn-info btn-sm my-2"> Transacciones</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    </main>

    <!-- Modal Agregar Empleado -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="storeEmpleado.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Agregar Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" name="cedula" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="sueldo" class="form-label">Sueldo</label>
                            <input type="number" step="0.01" class="form-control" name="sueldo" required>
                        </div>
                        <div class="mb-3">
                            <label for="puesto" class="form-label">Puesto</label>
                            <input type="text" class="form-control" name="puesto" required>
                        </div>
                        <div class="mb-3">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_contratacion" class="form-label">Fecha de Contratación</label>
                            <input type="date" class="form-control" name="fecha_contratacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="casado" class="form-label">Casado</label>
                            <input type="checkbox" class="form-check-input" name="casado">
                        </div>
                        <div class="mb-3">
                            <label for="hijos" class="form-label">Número de Hijos</label>
                            <input type="number" class="form-control" name="hijos" required>
                        </div>
                        <div class="mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" name="ubicacion" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="add" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Empleado -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="updateEmpleado.php">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel">Editar Empleado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" name="cedula" id="edit-cedula" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="edit-nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-sueldo" class="form-label">Sueldo</label>
                            <input type="number" step="0.01" class="form-control" name="sueldo" id="edit-sueldo" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-puesto" class="form-label">Puesto</label>
                            <input type="text" class="form-control" name="puesto" id="edit-puesto" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-numero" class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" id="edit-numero" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" name="correo" id="edit-correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-fecha_contratacion" class="form-label">Fecha de Contratación</label>
                            <input type="date" class="form-control" name="fecha_contratacion" id="edit-fecha_contratacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-casado" class="form-label">Casado</label>
                            <input type="checkbox" class="form-check-input" name="casado" id="edit-casado">
                        </div>
                        <div class="mb-3">
                            <label for="edit-hijos" class="form-label">Número de Hijos</label>
                            <input type="number" class="form-control" name="hijos" id="edit-hijos" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" name="ubicacion" id="edit-ubicacion" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="edit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var editEmployeeModal = document.getElementById('editEmployeeModal');
        editEmployeeModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('edit-id').value = button.getAttribute('data-id');
            document.getElementById('edit-cedula').value = button.getAttribute('data-cedula');
            document.getElementById('edit-nombre').value = button.getAttribute('data-nombre');
            document.getElementById('edit-sueldo').value = button.getAttribute('data-sueldo');
            document.getElementById('edit-puesto').value = button.getAttribute('data-puesto');
            document.getElementById('edit-numero').value = button.getAttribute('data-numero');
            document.getElementById('edit-correo').value = button.getAttribute('data-correo');
            document.getElementById('edit-fecha_contratacion').value = button.getAttribute('data-fecha_contratacion');
            document.getElementById('edit-casado').checked = button.getAttribute('data-casado') == 1;
            document.getElementById('edit-hijos').value = button.getAttribute('data-hijos');
            document.getElementById('edit-ubicacion').value = button.getAttribute('data-ubicacion');
        });
    </script>
</body>
</html>
