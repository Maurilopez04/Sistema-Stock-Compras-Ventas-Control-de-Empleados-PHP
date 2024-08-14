<?php
require "config/start.php";
$sql = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
    <style>
.custom-icon {
    font-size: 40px;
    padding: 0;
    margin-right:25px;
}
</style>

</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 " >
    <h1 class="my-4">Inicio</h1>
    <div class="tab-content rounded-bottom">
    <div class="tab-pane p-0 active preview">
        <div class="row g-3 wrap">
            <div class="col-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="card text-white bg-secondary bg-gradient">
                    <div class="card-body">
                        <div class="text-white text-opacity-75 text-end">
                            <i class="bi bi-person-bounding-box icon-xxl custom-icon"></i>
                        </div>
                        <div class="fs-4 fw-semibold"><?= htmlspecialchars($user['nombre']) ?></div>
                        <div class="small text-white text-opacity-75 text-uppercase fw-semibold text-truncate">Administrador</div>

                    </div>
                </div>
            </div>
            <!-- /.col-->
            <div class="col-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="card text-white bg-success bg-gradient">
                    <div class="card-body">
                        <div class="text-white text-opacity-75 text-end">
                            <i class="bi bi-person-check icon-xxl custom-icon"></i>
                        </div>
                        <div class="fs-4 fw-semibold">94</div>
                        <div class="small text-white text-opacity-75 text-uppercase fw-semibold text-truncate">Clientes Registrados</div>
                    </div>
                </div>
            </div>
            <!-- /.col-->
            <div class="col-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="card text-white bg-warning bg-gradient">
                    <div class="card-body">
                        <div class="text-white text-opacity-75 text-end">
                            <i class="bi bi-basket custom-icon"></i>
                        </div>
                        <div class="fs-4 fw-semibold">128</div>
                        <div class="small text-white text-opacity-75 text-uppercase fw-semibold text-truncate">Productos</div>
                       
                    </div>
                </div>
            </div>
            <!-- /.col-->
            <div class="col-6 col-lg-4 col-xl-3 col-xxl-3">
                <div class="card text-white bg-primary bg-gradient">
                    <div class="card-body">
                        <div class="text-white text-opacity-75 text-end">
                        <i class="bi bi-bag custom-icon"></i>
                        </div>
                        <div class="fs-4 fw-semibold">11</div>
                        <div class="small text-white text-opacity-75 text-uppercase fw-semibold text-truncate">Ventas Totales</div>
                        
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row.g-4-->
    </div>
</div>
</main>

<?php include 'components/footer.php'?>
</body>
</html>
