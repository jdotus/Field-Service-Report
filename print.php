<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('c:/xampp/htdocs/FPDF/fpdf.php');

require('dbcon.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
       // Add logo (if needed)
    //    $this->Image('logo.png', 10, 5, 30); // Replace 'logo.png' with your actual logo path

       // Company title
       $this->SetFont('Arial', 'B', 12);
       $this->SetXY(50, 10); 
       $this->Cell(110, 5, ' ', 0, 1, 'C');

       // Address and contact details
       $this->SetFont('Arial', '', 10);
       $this->SetXY(50, 15);
       $this->MultiCell(110, 5, " ", 0, 'C');

    //    // Add the date at the exact position
    //    $this->SetFont('Arial', '', 10);
    //    $this->SetXY(152, 35); // Adjust to match the exact location for the date
    //    $this->Cell(25, 5, 'Date: ' . date('Y-m-d'), 0, 1, 'L');

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "fsr";

    $con = mysqli_connect($servername,$username,$password,$dbname);

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }


    $info = "SELECT * FROM fsr_tbl WHERE id = '{$_GET['id']}'";
    $infoQuery = mysqli_query($con, $info);

    if (!$infoQuery) {
        die("Error: " . mysqli_error($con));
    }

    while ($row = mysqli_fetch_array($infoQuery)) {
        // Access the date from the row and format it as needed
        $date = $row['date']; // Assuming the date field is named 'date' in your database
        $formattedDate = date('Y-m-d', strtotime($date));

        $this->SetFont('Arial', '', 10);
        $this->SetXY(152, 35);
        $this->Cell(25, 5, ' ' . $formattedDate, 0, 1, 'L');
    }

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
    $pdf->Cell(30, 5.3, " ");
    $pdf->Cell(100, $lineHeight,'' . $row['customer_name'], 0, 0);
    $pdf->Cell(20, $lineHeight, " ");
    $pdf->Cell(35, $lineHeight, '                  ' . $row['tel_no']);
    $pdf->Ln($lineHeight);
    $pdf->Cell(20, $lineHeight, " ");
    $pdf->Cell(10, $lineHeight, '                  ' . $row['address']);
    
    $pdf->Ln($lineHeight);
    // Align Model No., Serial No., and Meter Reading on the same row
    $pdf->Cell(20, $lineHeight, " ");
    $pdf->Cell(35, $lineHeight, '             ' . $row['model_no']);
    $pdf->Cell(35, $lineHeight, " ");
    $pdf->Cell(35, $lineHeight, '               ' . $row['serial_no']);
    $pdf->Cell(35, $lineHeight, " ");
    $pdf->Cell(0, $lineHeight,  '              ' . $row['meter_reading']);
    
    $pdf->Ln(5.7);
    // Adjust complaints to be inline with the label
    $pdf->Cell(35, 5.7, " ");
    $pdf->Cell(0, 5.7, "" . $row['customer_complaints']);
    
    $pdf->Ln( 5.7);
    // Adjust details of repair to be inline with the label
    $pdf->Cell(30, $lineHeight, " ");
    $pdf->Cell(0, $lineHeight, "" . $row['detail_report']);
    $pdf->Ln(5.7);
    $pdf->Cell(0, 5.7, "");
    
    $pdf->Ln( 5.7);
    // Adjust comments to be inline with the label
    $pdf->Cell(40, $lineHeight, " ");
    $pdf->Cell(0, $lineHeight, "" . $row['customer_comment']);
    
    $pdf->Ln( 5.7);
    $pdf->Cell(50, $lineHeight, " ");
    $pdf->Cell(0, $lineHeight, "" . $row['tech_recommendation']);
    
    $pdf->Ln( 5);
    $pdf->Cell(60, $lineHeight, " ");
    
    $pdf->Ln($lineHeight * 1.5);
    $pdf->Cell(60, $lineHeight, '' . $row['customer_name'], 0, 0, 'R');
    $pdf->Cell(120, $lineHeight, " ", 0, 0, 'R');
    
    
    $pdf->Ln($lineHeight * 1.75);
    // $pdf->Cell(80, $lineHeight, "Time In: _______________");
    // $pdf->Cell(50, $lineHeight, "Time Out: _______________");
    
    // Define the width of each section
    $widthLeft = 110;  // Width for "Time In"
    $widthRight = 70; // Width for "Time Out"
    // $lineHeight = 10; // Adjust line height as needed
    
    // Time In section
    $pdf->Cell($widthLeft / 2, $lineHeight, " ", 0, 0, 'R'); // Label on the left
    $pdf->Cell($widthLeft /2.5, $lineHeight, '' . $row['time_in'], 0, 0, 'C'); // Centered underline
    
    // Time Out section
    $pdf->Cell(30, $lineHeight, " ", 0, 0, 'L'); // Label on the left
    $pdf->Cell(50, $lineHeight, '' . $row['time_out'], 0, 1, 'C'); // Centered underline and move to next line
    
    
    // Output the PDF
    $pdf->Output('I', 'Field Service Report.pdf');
}
?>
