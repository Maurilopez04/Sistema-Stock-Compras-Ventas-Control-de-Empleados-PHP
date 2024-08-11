<?php
require 'config/start.php';



// Obtener datos del usuario logueado
$sql = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Actualizar datos del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar contraseñas
    if ($password && $password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        // Preparar consulta para actualizar datos
        $sql = "UPDATE usuarios SET nombre = :nombre, email = :email" . ($password ? ", password = :password" : "") . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $params = [
            ':nombre' => $nombre,
            ':email' => $email,
            ':id' => $_SESSION['user_id']
        ];
        if ($password) {
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        $stmt->execute($params);

        // Actualizar datos del usuario en la sesión
        $user['nombre'] = $nombre;
        $user['email'] = $email;
        $success = 'Datos actualizados con éxito.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'; ?>
    <title>Configuración</title>
</head>
<body>
<?php include 'components/header.php'; ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container">
        <h1 class="my-4">Datos de usuario</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($user['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña Nueva</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</main>
<?php include 'components/footer.php'; ?>
</body>
</html>
