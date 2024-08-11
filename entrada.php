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
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container">
        <h1>Registrar Entrada</h1>
        <form method="post">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group my-3">
                        <label for="producto_id">Producto:</label>
                        <select name="producto_id" id="producto_id" class="form-control">
                            <?php
                            require 'config/start.php';
                            $sql_productos = "SELECT id, nombre FROM productos";
                            foreach ($pdo->query($sql_productos) as $producto) {
                                echo "<option value='{$producto['id']}'>{$producto['nombre']}</option>";
                            }
                            ?>
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
<?php include 'components/footer.php'?>
</body>
</html>
