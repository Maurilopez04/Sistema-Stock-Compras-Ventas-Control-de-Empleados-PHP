<?php
require 'config/start.php';
require 'fpdf/fpdf.php';

if (isset($_GET['venta_id'])) {
    $venta_id = $_GET['venta_id'];

    $sql = "SELECT ventas.*, productos.nombre AS producto_nombre, clientes.nombre AS cliente_nombre, clientes.email AS cliente_email, clientes.telefono AS cliente_telefono, clientes.ci_ruc  AS cliente_ruc
            FROM ventas 
            JOIN productos ON ventas.producto_id = productos.id 
            JOIN clientes ON ventas.cliente_id = clientes.id 
            WHERE ventas.id = :venta_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['venta_id' => $venta_id]);
    $venta = $stmt->fetch();

    if ($venta) {
        $empresa_nombre = "Bytech Paraguay";
        $empresa_direccion = "Calle Dr. Rebull 165";
        $empresa_telefono = "+595 983882017";
        $empresa_email = "bytechpy@gmail.com";

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Ticket de Venta', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Datos de la Empresa', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $empresa_nombre, 0, 1);
        $pdf->Cell(0, 10, $empresa_direccion, 0, 1);
        $pdf->Cell(0, 10, $empresa_telefono, 0, 1);
        $pdf->Cell(0, 10, $empresa_email, 0, 1);
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Datos del Cliente', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Nombre: ' . $venta['cliente_nombre'], 0, 1);
        $pdf->Cell(0, 10, 'Email: ' . $venta['cliente_email'], 0, 1);
        $pdf->Cell(0, 10, 'Telefono: ' . $venta['cliente_telefono'], 0, 1);
        $pdf->Cell(0, 10, 'CI/RUC: ' . $venta['cliente_ruc'], 0, 1);
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Detalles de la Venta', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Producto: ' . $venta['producto_nombre'], 0, 1);
        $pdf->Cell(0, 10, 'Cantidad: ' . $venta['cantidad'], 0, 1);
        $pdf->Cell(0, 10, 'Precio: Gs. ' . number_format($venta['precio'], 0), 0, 1);
        $pdf->Cell(0, 10, 'Fecha: ' . $venta['fecha'], 0, 1);
        $pdf->Ln(10);

        $pdf->Output('I', 'ticket_venta.pdf');
    } else {
        echo "Venta no encontrada.";
    }
} else {
    echo "ID de venta no proporcionado.";
}
?>
