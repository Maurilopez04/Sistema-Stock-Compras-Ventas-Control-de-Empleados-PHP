<?php
require "config/start.php";

// Obtener todos los clientes para la carga inicial en Select2
$sql = "SELECT id, nombre FROM clientes";
$stmt = $pdo->query($sql);
$clientes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php' ?>
    <link href="scripts/select2/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="scripts/select2/select2.min.js"></script>
    <style>
        /* Estilo general para select2 */
.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #ced4da;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #495057;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
}
/* Selecciona y oculta el segundo span dentro de cualquier .col-md-4 */
.col-md-4 > .select2-container:nth-of-type(2) {
    display: none !important;
}
/* Estilos para modo oscuro */
[data-bs-theme="dark"] .select2-container--default .select2-selection--single {
    background-color: transparent; /* Fondo oscuro */
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

<?php include 'components/header.php' ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="my-4">Registrar Venta</h1>
    <form method="post" action="procesar_venta.php">
        <div class="mb-3">
            <label for="cliente" class="form-label">Cliente</label>
            <select name="cliente_id" id="cliente" class="form-select cliente-select" required>
                <!-- Las opciones serán cargadas por Select2 -->
            </select>
        </div>

        <div class="mb-3">
            <label for="producto" class="form-label">Productos</label>
            <div id="productos-container">
            <div class="row mb-2 producto-row">
    <div class="col-md-4">
        <select name="productos[]" class="form-select producto-select" required>
            <option value="">Selecciona un producto</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="number" name="cantidades[]" class="form-control" placeholder="Cantidad" required>
    </div>
    <div class="col-md-3">
        <input type="number" name="precios[]" class="form-control precio-venta" placeholder="Precio de Venta" step="0.01" required>
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-danger eliminar-producto">Eliminar</button>
    </div>
</div>

            </div>
            <button type="button" id="agregar-producto" class="btn btn-success mt-3">Agregar Producto</button>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Venta</button>
    </form>
</main>
<?php include 'components/footer.php' ?>
<script>
function initSelect2(element, url, type) {
    element.select2({
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    type: type // Pasar el tipo a la solicitud
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            }
        },
        placeholder: 'Selecciona una opción',
        minimumInputLength: 2,
        width: '100%'
    }).on('select2:select', function (e) {
        var data = e.params.data.id.split('|');
        var precio = data[1];
        $(this).closest('.producto-row').find('.precio-venta').val(Math.round(precio));
    });
}

function agregarProducto() {
    const productoRow = document.querySelector('.producto-row').cloneNode(true);

    $(productoRow).find('input').val(''); // Limpia los valores de los inputs clonados

    const newSelect = $(productoRow).find('.producto-select');
    if (newSelect.hasClass("select2-hidden-accessible")) {
        newSelect.select2('destroy');
    }
    newSelect.empty(); // Limpia las opciones previas

    initSelect2(newSelect, 'buscar_productos.php', 'venta'); // Añadir 'venta' como tipo

    document.getElementById('productos-container').appendChild(productoRow);
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('agregar-producto').addEventListener('click', agregarProducto);

    document.getElementById('productos-container').addEventListener('click', function (event) {
        if (event.target.classList.contains('eliminar-producto')) {
            event.target.closest('.producto-row').remove();
        }
    });

    // Llamar a initSelect2 con tipo 'venta'
    initSelect2($('#cliente'), 'buscar_clientes.php', 'venta');
    $('.producto-select').each(function () {
        initSelect2($(this), 'buscar_productos.php', 'venta');
    });
});
</script>

</body>
</html>

