<?php
require "config/start.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];
    $precioMayorista = $_POST['precioMayorista'];
    $precioMinorista = $_POST['precioMinorista'];
    $cantidad = $_POST['cantidad'];
    $categoria_id = $_POST['categoria_id'];
    $marca_id = $_POST['marca_id'];

    $imagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        $imagenTmpName = $_FILES['imagen']['tmp_name'];
        $imagenName = basename($_FILES['imagen']['name']);
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . $imagenName;

        if (move_uploaded_file($imagenTmpName, $uploadFile)) {
            $imagen = $imagenName;
        }
    }

    $sql = "INSERT INTO productos (nombre, descripcion, costo, precioMayorista, precioMinorista, cantidad, categoria_id, marca_id, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $descripcion, $costo, $precioMayorista, $precioMinorista, $cantidad, $categoria_id, $marca_id, $imagen]);

    header("Location: productos.php");
    exit;
}
?>