<?php
require "config/start.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $contacto = $_POST['contacto'];
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    try {
        $pdo->beginTransaction();

        if ($id) {
            // Actualizar proveedor existente
            $sql = "UPDATE proveedores SET nombre = :nombre, contacto = :contacto WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombre' => $nombre, 'contacto' => $contacto, 'id' => $id]);
        } else {
            // Insertar nuevo proveedor
            $sql = "INSERT INTO proveedores (nombre, contacto) VALUES (:nombre, :contacto)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombre' => $nombre, 'contacto' => $contacto]);
        }

        $pdo->commit();
        header('Location: proveedores.php?success=1');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error al procesar la solicitud: " . $e->getMessage();
        header('Location: proveedores.php?error=' . urlencode($error));
        exit();
    }
}
?>

