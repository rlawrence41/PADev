<?php
require_once('tcpdf.php');

// Create a new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(5, 5, 5); // Small margins for label space
$pdf->SetAutoPageBreak(false); // Prevent page breaks

// Add a page
$pdf->AddPage();

// UPS Label Dimensions
$labelWidth = 101.6; // 4 inches in mm
$labelHeight = 152.4; // 6 inches in mm

// Draw label border for visual reference (optional)
$pdf->Rect(5, 5, $labelWidth, $labelHeight);

// Add UPS Logo (adjust path to your logo file)
$pdf->Image('ups_logo.png', 10, 10, 30, 10);

// Add Sender Address
$sender = "Sender Name\n123 Sender Street\nCity, State, ZIP";
$pdf->SetFont('helvetica', '', 10);
$pdf->SetXY(10, 25);
$pdf->MultiCell(90, 10, $sender, 0, 'L');

// Add Recipient Address
$recipient = "Recipient Name\n456 Recipient Ave\nCity, State, ZIP";
$pdf->SetXY(10, 45);
$pdf->MultiCell(90, 10, $recipient, 0, 'L');

// Add Tracking Number as Barcode
$trackingNumber = "1Z12345E1512345676"; // Example UPS tracking number
$pdf->SetFont('helvetica', '', 12);
$pdf->SetXY(10, 70);
$pdf->Cell(90, 10, "Tracking Number: $trackingNumber", 0, 1);

// Generate the Code 39 Barcode
$pdf->write1DBarcode($trackingNumber, 'C39', 10, 80, '', 20, 0.4, ['position' => '', 'align' => 'C', 'stretch' => false, 'fitwidth' => true, 'border' => true, 'padding' => 1], 'N');

// Add additional details like service type
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetXY(10, 110);
$pdf->Cell(90, 10, "UPS Ground", 0, 1, 'L');

// Save or output the label
$pdf->Output('UPS_Label.pdf', 'I'); // 'I' for inline display in the browser
?>
