<?php
require "config/start.php";

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM marcas WHERE nombre LIKE ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(['%' . $search . '%']);
$marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="my-4">Marcas</h1>
    <div class="row mb-4">
        <div class="col-12 col-md-8">
            <form class="d-flex" method="get" action="marcas.php">
                <input class="form-control me-2" type="text" name="search" placeholder="Buscar marcas">
                <input class="btn btn-secondary" type="submit" value="Buscar">
            </form>
        </div>
        <div class="col-12 col-md-4 mt-2 mt-md-0 text-md-end">
            <button type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModal" class="btn btn-success w-100 w-md-auto">Nueva Marca</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($marcas as $marca): ?>
                <tr>
                    <td><?= $marca['nombre'] ?></td>
                    <td><?= $marca['descripcion'] ?></td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-id="<?= $marca['id'] ?>" data-nombre="<?= $marca['nombre'] ?>" data-descripcion="<?= $marca['descripcion'] ?>">Editar</button>
                            <button class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-id="<?= $marca['id'] ?>">Eliminar</button>
                            <a class="btn btn-warning ms-2" href="productos.php?marca=<?= $marca['id'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
</svg></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar categoría -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Agregar Nueva Marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm" action="storeMarca.php" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar categoría -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Editar Marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm" action="updateMarca.php" method="post">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label for="edit-nombre" class="form-label">Nombre:</label>
                            <input type="text" id="edit-nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-descripcion" class="form-label">Descripción:</label>
                            <textarea id="edit-descripcion" name="descripcion" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar categoría -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar esta marca?</p>
                </div>
                <div class="modal-footer">
                    <form id="deleteCategoryForm" action="deleteMarca.php" method="post">
                        <input type="hidden" id="delete-id" name="id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>


<script>
document.addEventListener('DOMContentLoaded', function () {
    var editCategoryModal = document.getElementById('editCategoryModal');
    editCategoryModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nombre = button.getAttribute('data-nombre');
        var descripcion = button.getAttribute('data-descripcion');

        var editForm = document.getElementById('editCategoryForm');
        var editId = document.getElementById('edit-id');
        var editNombre = document.getElementById('edit-nombre');
        var editDescripcion = document.getElementById('edit-descripcion');

        editId.value = id;
        editNombre.value = nombre;
        editDescripcion.value = descripcion;
    });

    var deleteCategoryModal = document.getElementById('deleteCategoryModal');
    deleteCategoryModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');

        var deleteForm = document.getElementById('deleteCategoryForm');
        var deleteId = document.getElementById('delete-id');

        deleteId.value = id;
    });
});

</script>
<?php include 'components/footer.php'?>
</body>
</html>
