<?php
require 'config/start.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación de entrada
    $proveedor_id = trim($_POST['proveedor_id']);
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];
    $precios = $_POST['precios'];

    // Validar si el proveedor existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM proveedores WHERE id = :proveedor_id");
    $stmt->execute(['proveedor_id' => $proveedor_id]);
    if ($stmt->fetchColumn() == 0) {
        die('Error: Proveedor no encontrado.');
    }

    // Validar que todos los productos y cantidades sean válidos
    foreach ($productos as $index => $producto_id) {
        $cantidad = (int)$cantidades[$index];
        $precio = (float)$precios[$index];

        if ($cantidad <= 0) {
            die('Error: La cantidad debe ser mayor que cero.');
        }

        if ($precio <= 0) {
            die('Error: El precio debe ser mayor que cero.');
        }

        // Validar si el producto existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE id = :producto_id");
        $stmt->execute(['producto_id' => $producto_id]);
        if ($stmt->fetchColumn() == 0) {
            die('Error: Producto no encontrado.');
        }
    }

    try {
        $pdo->beginTransaction();

        // Insertar en la tabla de compras
        foreach ($productos as $index => $producto_id) {
            $cantidad = (int)$cantidades[$index];
            $precio = (float)$precios[$index];

            // Insertar en la tabla de compras
            $sql = "INSERT INTO compras (proveedor_id, producto_id, cantidad, precio) VALUES (:proveedor_id, :producto_id, :cantidad, :precio)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['proveedor_id' => $proveedor_id, 'producto_id' => $producto_id, 'cantidad' => $cantidad, 'precio' => $precio]);

            // Actualizar el stock
            $sql = "UPDATE productos SET cantidad = cantidad + :cantidad WHERE id = :producto_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['cantidad' => $cantidad, 'producto_id' => $producto_id]);

            // Registrar el movimiento de stock
            $sql = "INSERT INTO movimientos_stock (producto_id, cantidad, tipo) VALUES (:producto_id, :cantidad, 'entrada')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['producto_id' => $producto_id, 'cantidad' => $cantidad]);
        }

        $pdo->commit();
        header('Location: compras.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die('Error al procesar la compra: ' . $e->getMessage());
    }
}
?>
