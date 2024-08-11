<?php 
require "config/start.php";
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$sql_count = "SELECT COUNT(*) FROM productos WHERE nombre LIKE ?";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute(["%$search%"]);
$total_products = $stmt_count->fetchColumn();
$total_pages = ceil($total_products / $limit);

$sql = "SELECT p.*, c.nombre as categoria_nombre 
        FROM productos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.nombre LIKE ?
        LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$search%"]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="my-4">Mayorista</h1>
    <p>Este es un catalogo mayorista, eliminar o modificar productos tambien modifica el catalogo minorista.</p>
    <div class="row mb-4">
        <div class="col-12 col-md-8">
            <form class="d-flex" method="get" action="productos.php">
                <input class="form-control me-2" type="text" name="search" placeholder="Buscar productos" value="<?= htmlspecialchars($search) ?>">
                <input class="btn btn-secondary" type="submit" value="Buscar">
            </form>
        </div>
        <div class="col-12 col-md-4 mt-2 mt-md-0 text-md-end">
            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-success w-100 w-md-auto">Nuevo Producto <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
            </svg></button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Precio Mayorista</th>
                    <th>Cantidad</th>
                    <th class="d-none d-sm-table-cell">Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="h-100">
                <?php foreach ($productos as $producto): ?>
                <tr>
                    <td>
                        <?php if ($producto['imagen']): ?>
                        <img src="uploads/<?= $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>" class="img-fluid" style="width: 100px;">
                        <?php endif; ?>
                    </td>
                    <td><?= $producto['nombre'] ?></td>
                    <td><?= number_format($producto['precioMayorista'], 0); ?></td>
                    <td><?= $producto['cantidad'] ?></td>
                    <td class="d-none d-sm-table-cell"><?= $producto['categoria_nombre'] ?></td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <button class="btn btn-primary btn-sm col-md-4" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?= $producto['id'] ?>" data-nombre="<?= $producto['nombre'] ?>" data-descripcion="<?= $producto['descripcion'] ?>" data-costo="<?= $producto['costo'] ?>" data-precio-mayorista="<?= $producto['precioMayorista'] ?>" data-precio-minorista="<?= $producto['precioMinorista'] ?>" data-cantidad="<?= $producto['cantidad'] ?>" data-categoria-id="<?= $producto['categoria_id'] ?>" data-imagen="<?= $producto['imagen'] ?>">Editar</button>
                            <button class="btn btn-danger btn-sm ms-2 col-md-5" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $producto['id'] ?>">Eliminar</button>

                            <button class="btn btn-warning btn-sm ms-1 col-md-3" data-bs-toggle="modal" data-bs-target="#viewModal" data-nombre="<?= $producto['nombre'] ?>" data-descripcion="<?= $producto['descripcion'] ?>" data-costo="<?= $producto['costo'] ?>" data-precio-mayorista="<?= $producto['precioMayorista'] ?>" data-precio-minorista="<?= $producto['precioMinorista'] ?>" data-cantidad="<?= $producto['cantidad'] ?>" data-categoria-nombre="<?= $producto['categoria_nombre'] ?>" data-imagen="uploads/<?= $producto['imagen'] ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
  <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
  <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
</svg></button>
                        </div>
                        
                    </td>
                </tr>
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
    <!-- Modales -->
    <!-- Modal de Agregar Nuevo Producto -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="store.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="costo" class="form-label">Costo:</label>
                            <input type="text" id="costo" name="costo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="precioMayorista" class="form-label">Precio Mayorista:</label>
                            <input type="text" id="precioMayorista" name="precioMayorista" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="precioMinorista" class="form-label">Precio Minorista:</label>
                            <input type="text" id="precioMinorista" name="precioMinorista" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="text" id="cantidad" name="cantidad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoría:</label>
                            <select id="categoria_id" name="categoria_id" class="form-select">
                                <?php
                                $stmt = $pdo->query("SELECT id, nombre FROM categorias");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=\"{$row['id']}\">{$row['nombre']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen:</label>
                            <input type="file" id="imagen" name="imagen" class="form-control">
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
    <!-- Modal de Editar Producto -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="update.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-nombre" class="form-label">Nombre:</label>
                            <input type="text" id="edit-nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-descripcion" class="form-label">Descripción:</label>
                            <textarea id="edit-descripcion" name="descripcion" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-costo" class="form-label">Costo:</label>
                            <input type="text" id="edit-costo" name="costo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-precioMayorista" class="form-label">Precio Mayorista:</label>
                            <input type="text" id="edit-precioMayorista" name="precioMayorista" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-precioMinorista" class="form-label">Precio Minorista:</label>
                            <input type="text" id="edit-precioMinorista" name="precioMinorista" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-cantidad" class="form-label">Cantidad:</label>
                            <input type="text" id="edit-cantidad" name="cantidad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-categoria_id" class="form-label">Categoría:</label>
                            <select id="edit-categoria_id" name="categoria_id" class="form-select">
                                <?php
                                $stmt = $pdo->query("SELECT id, nombre FROM categorias");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=\"{$row['id']}\">{$row['nombre']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-imagen" class="form-label">Imagen:</label>
                            <input type="file" id="edit-imagen" name="imagen" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Ver Producto -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Ver Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="view-imagen" src="" alt="Imagen del Producto" class="img-fluid">
                        </div>
                        <div class="col-md-8">
                            <h4 id="view-nombre"></h4>
                            <p id="view-descripcion"></p>
                            <p><strong>Costo:</strong> <span id="view-costo"></span></p>
                            <p><strong>Precio Mayorista:</strong> <span id="view-precioMayorista"></span></p>
                            <p><strong>Precio Minorista:</strong> <span id="view-precioMinorista"></span></p>
                            <p><strong>Cantidad:</strong> <span id="view-cantidad"></span></p>
                            <p><strong>Categoría:</strong> <span id="view-categoria_nombre"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este producto?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="confirmDeleteBtn" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nombre = button.getAttribute('data-nombre');
        var descripcion = button.getAttribute('data-descripcion');
        var costo = button.getAttribute('data-costo');
        var precioMayorista = button.getAttribute('data-precio-mayorista');
        var precioMinorista = button.getAttribute('data-precio-minorista');
        var cantidad = button.getAttribute('data-cantidad');
        var categoriaId = button.getAttribute('data-categoria-id');
        var imagen = button.getAttribute('data-imagen');

        var modalTitle = editModal.querySelector('.modal-title');
        var editForm = document.getElementById('editForm');
        var editId = document.getElementById('edit-id');
        var editNombre = document.getElementById('edit-nombre');
        var editDescripcion = document.getElementById('edit-descripcion');
        var editCosto = document.getElementById('edit-costo');
        var editPrecioMayorista = document.getElementById('edit-precioMayorista');
        var editPrecioMinorista = document.getElementById('edit-precioMinorista');
        var editCantidad = document.getElementById('edit-cantidad');
        var editCategoriaId = document.getElementById('edit-categoria_id');
        var editImagen = document.getElementById('edit-imagen');

        editId.value = id;
        editNombre.value = nombre;
        editDescripcion.value = descripcion;
        editCosto.value = costo;
        editPrecioMayorista.value = precioMayorista;
        editPrecioMinorista.value = precioMinorista;
        editCantidad.value = cantidad;
        editCategoriaId.value = categoriaId;
        editImagen.value = imagen;

        modalTitle.textContent = 'Editar Producto ' + nombre;
    });

    var viewModal = document.getElementById('viewModal');
    viewModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var nombre = button.getAttribute('data-nombre');
        var descripcion = button.getAttribute('data-descripcion');
        var costo = button.getAttribute('data-costo');
        var precioMayorista = button.getAttribute('data-precio-mayorista');
        var precioMinorista = button.getAttribute('data-precio-minorista');
        var cantidad = button.getAttribute('data-cantidad');
        var categoriaNombre = button.getAttribute('data-categoria-nombre');
        var imagen = button.getAttribute('data-imagen');

        var viewNombre = document.getElementById('view-nombre');
        var viewDescripcion = document.getElementById('view-descripcion');
        var viewCosto = document.getElementById('view-costo');
        var viewPrecioMayorista = document.getElementById('view-precioMayorista');
        var viewPrecioMinorista = document.getElementById('view-precioMinorista');
        var viewCantidad = document.getElementById('view-cantidad');
        var viewCategoriaNombre = document.getElementById('view-categoria_nombre');
        var viewImagen = document.getElementById('view-imagen');

        viewNombre.textContent = nombre;
        viewDescripcion.textContent = descripcion;
        viewCosto.textContent = costo;
        viewPrecioMayorista.textContent = precioMayorista;
        viewPrecioMinorista.textContent = precioMinorista;
        viewCantidad.textContent = cantidad;
        viewCategoriaNombre.textContent = categoriaNombre;
        viewImagen.src = imagen;
    });
});
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');

        var confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        confirmDeleteBtn.href = 'delete.php?id=' + id;
    });
});

</script>
<?php include 'components/footer.php'?>
</body>
</html>
