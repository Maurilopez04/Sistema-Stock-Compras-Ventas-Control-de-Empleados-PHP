<?php
require 'config/start.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];
    $precios = $_POST['precios'];  // Asegúrate de capturar los precios

    // Validar los datos de entrada
    if (empty($cliente_id) || empty($productos) || empty($cantidades) || count($productos) !== count($cantidades)) {
        die('Datos de entrada inválidos');
    }

    // Registrar la venta y actualizar el stock
    $pdo->beginTransaction();

    try {
        foreach ($productos as $index => $producto_id) {
            $cantidad = $cantidades[$index];
            $precio = $precios[$index];  // Usa el precio modificado por el usuario

            // Insertar en la tabla de ventas
            $stmt = $pdo->prepare("INSERT INTO ventas (cliente_id, producto_id, cantidad, precio) VALUES (:cliente_id, :producto_id, :cantidad, :precio)");
            $stmt->execute([
                'cliente_id' => $cliente_id,
                'producto_id' => $producto_id,
                'cantidad' => $cantidad,
                'precio' => $precio
            ]);

            // Actualizar el stock del producto
            $stmt = $pdo->prepare("UPDATE productos SET cantidad = cantidad - :cantidad WHERE id = :producto_id");
            $stmt->execute([
                'cantidad' => $cantidad,
                'producto_id' => $producto_id
            ]);

            // Registrar el movimiento de stock
            $stmt = $pdo->prepare("INSERT INTO movimientos_stock (producto_id, cantidad, tipo) VALUES (:producto_id, :cantidad, 'salida')");
            $stmt->execute([
                'producto_id' => $producto_id,
                'cantidad' => $cantidad
            ]);
        }

        $pdo->commit();
        header('Location: registro_ventas.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die('Error al procesar la venta: ' . $e->getMessage());
    }
} else {
    die('Método de solicitud no válido');
}
?>
