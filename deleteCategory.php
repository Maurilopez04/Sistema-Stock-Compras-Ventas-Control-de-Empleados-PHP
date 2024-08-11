<?php
require "config/start.php";

// ID de la categoría a eliminar
$id = $_POST['id'];

// ID de la categoría por defecto para reasignar productos
$defaultCategoryId = 4; // Reemplaza este valor con el ID de tu categoría por defecto

try {
    // Iniciar una transacción
    $pdo->beginTransaction();

    // Primero, actualizamos los productos para que apunten a la categoría por defecto
    $sqlUpdateProducts = "UPDATE productos SET categoria_id = ? WHERE categoria_id = ?";
    $stmtUpdate = $pdo->prepare($sqlUpdateProducts);
    $stmtUpdate->execute([$defaultCategoryId, $id]);

    // Ahora, eliminamos la categoría
    $sqlDeleteCategory = "DELETE FROM categorias WHERE id = ?";
    $stmtDelete = $pdo->prepare($sqlDeleteCategory);
    $stmtDelete->execute([$id]);

    // Confirmar la transacción
    $pdo->commit();

    header("Location: categorias.php");
} catch (Exception $e) {
    // Revertir la transacción si hay un error
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
