<?php
require "config/start.php";

// Obtener todos los proveedores para la carga inicial en Select2
$sql = "SELECT id, nombre FROM proveedores";
$stmt = $pdo->query($sql);
$proveedores = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'; ?>
    <link href="scripts/select2/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="scripts/select2/select2.min.js"></script>
</head>
<body>

<?php include 'components/header.php'; ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="my-4">Registrar Compra</h1>
    <form method="post" action="procesar_compra.php">
        <div class="mb-3">
            <label for="proveedor" class="form-label">Proveedor</label>
            <select name="proveedor_id" id="proveedor" class="form-select proveedor-select" required>
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
                        <input type="number" name="precios[]" class="form-control precio-compra" placeholder="Precio de Compra" step="0.01" required>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger eliminar-producto">Eliminar</button>
                    </div>
                </div>
            </div>
            <button type="button" id="agregar-producto" class="btn btn-success mt-3">Agregar Producto</button>
        </div>

        <button type="submit" class="btn btn-primary">Registrar Compra</button>
    </form>
</main>
<?php include 'components/footer.php'; ?>
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
        minimumInputLength: 1,
        width: '100%'
    }).on('select2:select', function (e) {
        var data = e.params.data.id.split('|');
        var precio = data[1];
        $(this).closest('.producto-row').find('.precio-compra').val(Math.round(precio));
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

    initSelect2(newSelect, 'buscar_productos.php', 'compra');

    document.getElementById('productos-container').appendChild(productoRow);
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('agregar-producto').addEventListener('click', agregarProducto);

    document.getElementById('productos-container').addEventListener('click', function (event) {
        if (event.target.classList.contains('eliminar-producto')) {
            event.target.closest('.producto-row').remove();
        }
    });

    initSelect2($('#proveedor'), 'buscar_productos_proveedores.php'); // Inicializa Select2 para proveedores
    initSelect2($('.producto-select'), 'buscar_productos.php', 'compra'); // Inicializa Select2 para productos
});
</script>
</body>
</html>
