<?php
while (ob_get_level()) ob_end_clean();
ob_start();

require_once "../../../controladores/ventas.controlador.php";
require_once "../../../modelos/ventas.modelo.php";
require_once "../../../controladores/escribanos.controlador.php";
require_once "../../../modelos/escribanos.modelo.php";
require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";
require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";
require_once "../../../controladores/empresa.controlador.php";
require_once "../../../modelos/empresa.modelo.php";
require_once "../../../extensiones/fpdf/fpdf.php";

class PDF extends FPDF {
	function Header() {}
	function Footer() {}
}

class imprimirFactura {

	public $id;

	public function traerImpresionFactura() {
		$itemVenta = "id";
		$valorVenta = $this->id;
		$venta = ControladorVentas::ctrMostrarVentas($itemVenta, $valorVenta);
		$productos = json_decode($venta["productos"], true);
		
		$cliente = ControladorEscribanos::ctrMostrarEscribanos("id", $venta["id_cliente"]);
		$vendedor = ControladorUsuarios::ctrMostrarUsuarios("id", $venta["id_vendedor"]);
		$empresa = ControladorEmpresa::ctrMostrarEmpresa("id", 1);
		
		$pdf = new PDF();
        
		$pdf->AddPage();

		// Encabezado con imagen y datos de empresa
		$pdf->Image("../../../vistas/img/plantilla/logo-blanco-bloque.png", 10, 10, 80);
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY(100, 12);
		$pdf->Cell(100,5,"CUIT: " . utf8_decode($empresa['cuit']),0,1);
		$pdf->SetX(100);
		$pdf->Cell(100,5,utf8_decode("Dirección: ") . utf8_decode($empresa['direccion']),0,1);
		$pdf->SetX(100);
		$pdf->Cell(100,5,utf8_decode("Teléfono: ") . utf8_decode($empresa['telefono']),0,1);
		$pdf->SetTextColor(220,50,50);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY(150, 10);
		$pdf->MultiCell(50,5,utf8_decode("Pendiente\nde\nFacturación"),0,'C');
		$pdf->SetTextColor(0);
		$pdf->Ln(10);

		// Título
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(205,25,"FACTURA PROVISORIA",0,1,'C');
		$pdf->Ln(1);

		// Datos cliente
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(30,6,"Cliente:",0,0);
		$pdf->Cell(130,6,utf8_decode($venta['nombre']),0,0);
		$pdf->Cell(15,6,"Fecha:",0,0);
		$pdf->Cell(20,6,$venta['fecha'],0,1);

		$pdf->Cell(30,6,"Vendedor:",0,0);
		$pdf->Cell(160,6,utf8_decode($vendedor['nombre']),0,1);

		$pdf->Ln(5);
		$pdf->SetFont('Arial','B',10);
		$pdf->SetFillColor(230,230,230);
		$pdf->Cell(80,6,"Producto",1,0,'C',true);
		$pdf->Cell(30,6,"Cantidad",1,0,'C',true);
		$pdf->Cell(40,6,"Valor Unit.",1,0,'C',true);
		$pdf->Cell(40,6,"Valor Total",1,1,'C',true);

		$pdf->SetFont('Arial','',10);
		foreach ($productos as $item) {
			$pdf->Cell(80,6,utf8_decode($item['descripcion']),1);
			$pdf->Cell(30,6,$item['cantidad'],1,0,'C');
			$pdf->Cell(40,6,"$" . number_format($item['precio'],2),1,0,'R');
			$pdf->Cell(40,6,"$" . number_format($item['total'],2),1,1,'R');
		}

		$pdf->Ln(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(150,0,"", "T", 1);
		$pdf->Cell(150,6,"Total:",0,0,'R');
		$pdf->Cell(40,6,"$" . number_format($venta['total'],2),0,1,'R');

		$pdf->Ln(10);
		$pdf->SetFont('Arial','I',9);
		$pdf->SetTextColor(100,100,100);
		$pdf->Cell(190,6,utf8_decode("Devolver el original de este comprobante para retirar la factura electrónica"),0,1,'C');
		$pdf->SetTextColor(0);

        $pdf->SetXY(10, 148);
        $pdf->Cell(190,50,"", "T", 1);

        // Encabezado con imagen y datos de empresa
		$pdf->Image("../../../vistas/img/plantilla/logo-blanco-bloque.png", 10, 160, 80);
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY(100, 162);
		$pdf->Cell(100,5,"CUIT: " . utf8_decode($empresa['cuit']),0,1);
		$pdf->SetX(100);
		$pdf->Cell(100,5,utf8_decode("Dirección: ") . utf8_decode($empresa['direccion']),0,1);
		$pdf->SetX(100);
		$pdf->Cell(100,5,utf8_decode("Teléfono: ") . utf8_decode($empresa['telefono']),0,1);
		$pdf->SetTextColor(220,50,50);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY(150, 160);
		$pdf->MultiCell(50,5,utf8_decode("Pendiente\nde\nFacturación"),0,'C');
		$pdf->SetTextColor(0);
		$pdf->Ln(10);

		// Título
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(205,25,"FACTURA PROVISORIA",0,1,'C');
		$pdf->Ln(1);

		// Datos cliente
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(30,6,"Cliente:",0,0);
		$pdf->Cell(130,6,utf8_decode($venta['nombre']),0,0);
		$pdf->Cell(15,6,"Fecha:",0,0);
		$pdf->Cell(20,6,$venta['fecha'],0,1);

		$pdf->Cell(30,6,"Vendedor:",0,0);
		$pdf->Cell(160,6,utf8_decode($vendedor['nombre']),0,1);

		$pdf->Ln(5);
		$pdf->SetFont('Arial','B',10);
		$pdf->SetFillColor(230,230,230);
		$pdf->Cell(80,6,"Producto",1,0,'C',true);
		$pdf->Cell(30,6,"Cantidad",1,0,'C',true);
		$pdf->Cell(40,6,"Valor Unit.",1,0,'C',true);
		$pdf->Cell(40,6,"Valor Total",1,1,'C',true);

		$pdf->SetFont('Arial','',10);
		foreach ($productos as $item) {
			$pdf->Cell(80,6,utf8_decode($item['descripcion']),1);
			$pdf->Cell(30,6,$item['cantidad'],1,0,'C');
			$pdf->Cell(40,6,"$" . number_format($item['precio'],2),1,0,'R');
			$pdf->Cell(40,6,"$" . number_format($item['total'],2),1,1,'R');
		}

		$pdf->Ln(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(150,0,"", "T", 1);
		$pdf->Cell(150,6,"Total:",0,0,'R');
		$pdf->Cell(40,6,"$" . number_format($venta['total'],2),0,1,'R');

		$pdf->Ln(10);
		$pdf->SetFont('Arial','I',9);
		$pdf->SetTextColor(100,100,100);
		$pdf->Cell(190,6,utf8_decode("Devolver el original de este comprobante para retirar la factura electrónica"),0,1,'C');
		$pdf->SetTextColor(0);

		$pdf->Output();
	}
}

$factura = new imprimirFactura();
$factura->id = $_GET["id"];
$factura->traerImpresionFactura();

ob_end_flush();
?>
