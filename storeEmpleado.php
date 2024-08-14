<?php
require "config/start.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $sueldo = $_POST['sueldo'];
    $puesto = $_POST['puesto'];
    $numero = $_POST['numero'];
    $correo = $_POST['correo'];
    $fecha_contratacion = $_POST['fecha_contratacion'];
    $casado = isset($_POST['casado']) ? 1 : 0;
    $hijos = $_POST['hijos'];
    $ubicacion = $_POST['ubicacion'];

    $sql = "INSERT INTO empleados (cedula, nombre, sueldo, puesto, numero, correo, fecha_contratacion, casado, hijos, ubicacion) 
            VALUES (:cedula, :nombre, :sueldo, :puesto, :numero, :correo, :fecha_contratacion, :casado, :hijos, :ubicacion)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cedula' => $cedula,
        ':nombre' => $nombre,
        ':sueldo' => $sueldo,
        ':puesto' => $puesto,
        ':numero' => $numero,
        ':correo' => $correo,
        ':fecha_contratacion' => $fecha_contratacion,
        ':casado' => $casado,
        ':hijos' => $hijos,
        ':ubicacion' => $ubicacion
    ]);


    header("Location: empleados.php");
    exit();
}
?>
