<?php
require 'config/start.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        // Validar el formato del email y la longitud de la contraseña
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6) {
            // Verificar si el email ya existe
            $sql_check = "SELECT id FROM usuarios WHERE email = :email";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([':email' => $email]);
            
            if ($stmt_check->rowCount() === 0) {
                // Insertar el nuevo usuario en la base de datos
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_insert = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->execute([
                    ':nombre' => $nombre,
                    ':email' => $email,
                    ':password' => $hashed_password
                ]);
                echo "Usuario registrado con éxito.";
            } else {
                echo "El email ya está registrado.";
            }
        } else {
            echo "Email inválido o contraseña demasiado corta.";
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
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
    <div class="container mt-5">
        <h1 class="mb-4">Registrar Nuevo Usuario</h1>
        <form method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>
</main>
<?php include 'components/footer.php'?>
</body>
</html>
