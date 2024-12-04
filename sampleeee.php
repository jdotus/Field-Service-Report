<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('c:/xampp/htdocs/FPDF/fpdf.php');

include ('dbcon.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Leave empty to remove the header
    }

    // Page footer
    function Footer()
    {
        // Leave footer blank for this document
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AddPage('P', 'Letter'); // Full Letter size (8.5 x 11)
$pdf->SetFont('Arial', '', 8.5);

// Set starting point at 5.6 inches (142.24 mm) from the top
$startX = 10;
$startY = 54.8; // 5.6 inches in mm
$lineHeight = 6; // Adjust line height


$info = "SELECT * FROM fsr_tbl WHERE id = '{$_GET['id']}'";
$infoQuery =mysqli_query($con, $info);

while($row = mysqli_fetch_array($infoQuery)) {

    // Add form fields
    $pdf->SetXY($startX, $startY);
    $pdf->Cell(30, 5.3, "Customer's Name:");
    $pdf->Cell(100, $lineHeight,'                       ' . $row['customer_name'], 0, 0);
    $pdf->Cell(20, $lineHeight, "Tel No.:");
    $pdf->Cell(35, $lineHeight, '                  ' . $row['tel_no']);
    $pdf->Ln($lineHeight);
    $pdf->Cell(20, $lineHeight, "Address:");
    $pdf->Cell(10, $lineHeight, '                  ' . $row['address']);
    
    $pdf->Ln($lineHeight);
    // Align Model No., Serial No., and Meter Reading on the same row
    $pdf->Cell(20, $lineHeight, "Model No.:");
    $pdf->Cell(35, $lineHeight, '             ' . $row['model_no']);
    $pdf->Cell(35, $lineHeight, "Serial No.:");
    $pdf->Cell(35, $lineHeight, '               ' . $row['serial_no']);
    $pdf->Cell(35, $lineHeight, "Meter Reading:");
    $pdf->Cell(0, $lineHeight,  '              ' . $row['meter_reading']);
    
    $pdf->Ln(5.7);
    // Adjust complaints to be inline with the label
    $pdf->Cell(35, 5.7, "Customer's complaints:");
    $pdf->Cell(0, 5.7, "" . $row['customer_complaints']);
    
    $pdf->Ln( 5.7);
    // Adjust details of repair to be inline with the label
    $pdf->Cell(30, $lineHeight, "Details of Repair:");
    $pdf->Cell(0, $lineHeight, "" . $row['detail_report']);
    $pdf->Ln(5.7);
    $pdf->Cell(0, 5.7, "______________________________________________________");
    
    $pdf->Ln( 5.7);
    // Adjust comments to be inline with the label
    $pdf->Cell(40, $lineHeight, "Customer's Comments:");
    $pdf->Cell(0, $lineHeight, "" . $row['customer_comment']);
    
    $pdf->Ln( 5.7);
    $pdf->Cell(50, $lineHeight, "Technician's Recommendations:");
    $pdf->Cell(0, $lineHeight, "" . $row['tech_recommendation']);
    
    $pdf->Ln( 5);
    $pdf->Cell(60, $lineHeight, "Certified that repairs have been completed to customer's satisfaction and approval:");
    
    $pdf->Ln($lineHeight * 1.5);
    $pdf->Cell(60, $lineHeight, "Customer's Signature & Printed Name", 0, 0, 'L');
    $pdf->Cell(120, $lineHeight, "Service Technician", 0, 0, 'R');
    
    
    $pdf->Ln($lineHeight * 1.75);
    // $pdf->Cell(80, $lineHeight, "Time In: _______________");
    // $pdf->Cell(50, $lineHeight, "Time Out: _______________");
    
    // Define the width of each section
    $widthLeft = 110;  // Width for "Time In"
    $widthRight = 70; // Width for "Time Out"
    // $lineHeight = 10; // Adjust line height as needed
    
    // Time In section
    $pdf->Cell($widthLeft / 2, $lineHeight, "Time In:", 0, 0, 'R'); // Label on the left
    $pdf->Cell($widthLeft / 2, $lineHeight, '' . $row['time_in'], 0, 0, 'C'); // Centered underline
    
    // Time Out section
    $pdf->Cell(10, $lineHeight, "Time Out:", 0, 0, 'L'); // Label on the left
    $pdf->Cell(50, $lineHeight, '' . $row['time_out'], 0, 1, 'C'); // Centered underline and move to next line
    
    
    // Output the PDF
    $pdf->Output('I', 'Field Service Report.pdf');
}
?>
