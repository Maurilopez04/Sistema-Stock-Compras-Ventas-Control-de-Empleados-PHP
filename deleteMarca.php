<?php
require "config/start.php";

// ID de la marca a eliminar
$id = $_POST['id'];

// ID de la marca por defecto para reasignar productos
$defaultMarcaId = 1; // Reemplaza este valor con el ID de tu marca por defecto

try {
    // Iniciar una transacción
    $pdo->beginTransaction();

    // Primero, actualizamos los productos para que apunten a la marca por defecto
    $sqlUpdateProducts = "UPDATE productos SET marca_id = ? WHERE marca_id = ?";
    $stmtUpdate = $pdo->prepare($sqlUpdateProducts);
    $stmtUpdate->execute([$defaultMarcaId, $id]);

    // Ahora, eliminamos la marca
    $sqlDeleteMarca = "DELETE FROM marcas WHERE id = ?";
    $stmtDelete = $pdo->prepare($sqlDeleteMarca);
    $stmtDelete->execute([$id]);

    // Confirmar la transacción
    $pdo->commit();

    header("Location: marcas.php");
} catch (Exception $e) {
    // Revertir la transacción si hay un error
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
