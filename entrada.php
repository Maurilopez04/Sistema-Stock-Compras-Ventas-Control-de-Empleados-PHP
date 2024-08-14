<?php
require 'config/start.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    $sql = "INSERT INTO movimientos_stock (producto_id, cantidad, tipo) VALUES (?, ?, 'entrada')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$producto_id, $cantidad]);

    // Actualizar cantidad en productos
    $sql_update = "UPDATE productos SET cantidad = cantidad + ? WHERE id = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$cantidad, $producto_id]);

    header('Location: movimientos.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
    <link href="scripts/select2/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="scripts/select2/select2.min.js"></script>
    <style>
/* Estilos básicos para Select2 */
.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem; /* Bordes redondeados como en Bootstrap */
    height: 38px;
    padding: 0.375rem 0.75rem !important; /* Espaciado interno similar a Bootstrap */
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #495057;
    line-height: 1.5; /* Asegura que el texto se alinee correctamente */
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
}

/* Oculta el segundo span dentro de cualquier .col-md-4 */
.col-md-4 > .select2-container:nth-of-type(2) {
    display: none !important;
}

/* Estilos para modo oscuro */
[data-bs-theme="dark"] .select2-container--default .select2-selection--single {
    background-color: #212529; /* Fondo oscuro */
    border: 1px solid #495057; /* Borde gris oscuro */
}

[data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #e9ecef; /* Texto claro */
}

[data-bs-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__arrow {
    background-color: #495057; /* Fondo de la flecha */
}

[data-bs-theme="dark"] .select2-dropdown {
    background-color: #343a40; /* Fondo oscuro del dropdown */
    color: #e9ecef; /* Texto claro del dropdown */
}

[data-bs-theme="dark"] .select2-results__option--highlighted {
    background-color: #495057; /* Opción seleccionada en modo oscuro */
    color: #fff; /* Texto de la opción seleccionada */
}
</style>

</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container">
        <h1 class="mt-4">Registrar Entrada</h1>
        <form method="post">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group my-3">
                        <label for="producto_id">Producto:</label>
                        <select name="producto_id" id="producto_id" class="form-control producto-select">
                            <!-- Opciones serán cargadas por Select2 -->
                        </select>
                    </div>
                    <div class="form-group my-3">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar Entrada</button>
                </div>
            </div>
        </form>
    </div>
</main>
<script>
function initSelect2(element, url) {
    element.select2({
        ajax: {
            url: url,
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
        placeholder: 'Selecciona un producto',
        minimumInputLength: 2,
        width: '100%'
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initSelect2($('#producto_id'), 'buscar_productos.php');
});
</script>

<?php include 'components/footer.php'?>
</body>
</html>
