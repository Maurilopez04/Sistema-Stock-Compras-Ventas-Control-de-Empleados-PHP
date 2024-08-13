<?php
require "config/start.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

$sql = "SELECT ventas.id AS venta_id, ventas.total, ventas.fecha, clientes.nombre AS cliente_nombre
        FROM ventas
        JOIN clientes ON ventas.cliente_id = clientes.id
        WHERE (clientes.nombre LIKE :search)";

if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $sql .= " AND ventas.fecha BETWEEN :fecha_inicio AND :fecha_fin";
}

$sql .= " ORDER BY ventas.fecha DESC";

$stmt = $pdo->prepare($sql);
$params = ['search' => "%$search%"];
if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $params['fecha_inicio'] = $fecha_inicio;
    $params['fecha_fin'] = $fecha_fin;
}
$stmt->execute($params);
$ventas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head.php'?>
    <style>
        .ticket {
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #000;
            font-family: Arial, sans-serif;
        }
        .ticket h1 {
            font-size: 16px;
            text-align: center;
        }
        .ticket .details p {
            margin: 0;
            font-size: 14px;
        }
        .ticket table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .ticket th, .ticket td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 14px;
        }
        .print-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'components/header.php'?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h1 class="my-4">Registro de Ventas</h1>
    <form method="get" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Buscar por cliente" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha_inicio" class="form-control" value="<?= htmlspecialchars($fecha_inicio) ?>" placeholder="Fecha Inicio">
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha_fin" class="form-control" value="<?= htmlspecialchars($fecha_fin) ?>" placeholder="Fecha Fin">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </div>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?= htmlspecialchars($venta['cliente_nombre']) ?></td>
                    <td><?= htmlspecialchars(number_format($venta['total'], 0)) ?></td>
                    <td><?= htmlspecialchars($venta['fecha']) ?></td>
                    <td>
                        <button class="btn btn-primary" onclick="generateTicketPDF(<?= $venta['venta_id'] ?>)">Generar Ticket</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script>
    async function generateTicketPDF(ventaId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        const response = await fetch('get_ticket_data.php?venta_id=' + ventaId);
        const data = await response.json();

        // Title
        doc.setFontSize(20);
        doc.setFont('helvetica', 'bold');
        doc.text('Ticket de Venta', 30, 10);

        // Client details
        doc.setFontSize(16);
        doc.setFont('helvetica', 'normal');
        doc.text(`Cliente: ${data.cliente_nombre}`, 10, 20);
        doc.text(`Fecha: ${data.fecha}`, 10, 30);
        doc.text(`Total: GS. ${data.total}`, 10, 40);

        // Draw a line
        doc.setLineWidth(0.5);
        doc.line(0, 45, 220, 45);

        // Product details
        doc.setFontSize(16);
        let yOffset = 50;
        yOffset += 10;

        data.productos.forEach((producto) => {
            if (yOffset > doc.internal.pageSize.height - 20) {
                doc.addPage();
                yOffset = 10;
            }
            doc.setFont('helvetica', 'bold');
            doc.text(`${producto.nombre}`, 10, yOffset);
            yOffset += 10; 
            doc.setFont('helvetica', 'normal');
            doc.text(`Cantidad: ${producto.cantidad} | Gs. ${producto.precio}`, 10, yOffset);
            yOffset += 12;

        });

        // Footer
        doc.setFontSize(18);
        doc.text('Gracias por su compra!', 10, yOffset + 10);

        doc.save(`Ticket_${ventaId}.pdf`);
    }
</script>



<?php include 'components/footer.php'?>
</body>
</html>
