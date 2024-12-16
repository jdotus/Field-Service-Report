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
       $this->Image('logo.png', 10, 5, 30); // Replace 'logo.png' with your actual logo path

       // Company title
       $this->SetFont('Arial', 'B', 12);
       $this->SetXY(60, 8); 
       $this->Cell(110, 5, 'OTUS COPY SYSTEMS, INC.', 0, 1, 'L');

       // Address and contact details
       $this->SetFont('Arial', '', 8);
       $this->SetXY(60, 12);
       $this->MultiCell(110, 3.5, "10th Flr. MG Tower, #75 Shaw Blvd., \n Brgy. Daang Bakal, Mandaluyong City \n Telephone No.: 631-9454 Loc. 106/111 \n Fax No.: 535-8731", 0, 'L');

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

        // $this->SetFont('Arial', '', 10);
        // $this->SetXY(152, 35);
        // $this->Cell(25, 5, 'Date: ' . $formattedDate, 0, 1, 'L');

        $this->SetFont('Arial', 'B', 10);
        $this->SetXY(152, 35);
        $this->Cell(15, 5, 'Date: ', 0, 0, 'L');

        // Switch back to normal font for the date value
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, $formattedDate, 0, 1, 'L');
        $this->Ln(3); // Adjust the vertical spacing between date and No. label

       // Set font to bold for the "No." label
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(155, 5, 'No.:', 0, 0, 'R');
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(0, 5, 'MEOW', 0, 1, 'L');

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
$pdf->SetFont('Arial', '', 10);

// Set starting point at 5.6 inches (142.24 mm) from the top 
$startX = 10;
$startY = 54.8; // 5.6 inches in mm
$lineHeight = 6; // Adjust line height


$info = "SELECT * FROM fsr_tbl WHERE id = '{$_GET['id']}'";
$infoQuery =mysqli_query($con, $info);

while($row = mysqli_fetch_array($infoQuery)) {

    // Add form fields
    $pdf->SetXY($startX, $startY);
    $pdf->Cell(10, 5.3, " ");

    // Set bold for "Customer Name:"
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(35, $lineHeight, 'Customer Name: ', 0, 0);

    // Switch back to normal for the value
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, $lineHeight, $row['customer_name']);
    $pdf->Ln($lineHeight);

    $pdf->Cell(10, $lineHeight, " ");


    // Set bold for "Tel. No."
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(25, $lineHeight, 'Tel. No.: ', 0, 0);

    // Switch back to normal for the value
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, $lineHeight, $row['tel_no']);
    $pdf->Ln($lineHeight);



    // Bold for "Address:"
    $pdf->Cell(10, $lineHeight, " ");
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(25, $lineHeight, 'Address: ');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, $lineHeight, $row['address']);
    $pdf->Ln($lineHeight);


    $pdf->Cell(10, $lineHeight, " ");
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(25, $lineHeight, 'Model No.: ');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(55, $lineHeight, $row['model_no']);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(20, $lineHeight, 'Serial No.: ');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(30, $lineHeight, $row['serial_no']);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, $lineHeight, 'Meter Reading: ');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, $lineHeight, $row['meter_reading']);
    $pdf->Ln(5.7);
    
    // Adjust complaints to be inline with the label
    $pdf->Cell(10, 5.7, " ");
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 5.7, "Customer Complaints: ");
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 5.7, $row['customer_complaints']);

    // $pdf->Ln( 5.7);
    // // Adjust details of repair to be inline with the label
    // $pdf->Cell(20, $lineHeight, " ");
    // $pdf->Cell(0, $lineHeight, "Details of Repair: " . $row['detail_report']);
    // $pdf->Ln(5.7);
    // $pdf->Cell(0, 5.7, "");

    $pdf->Ln(5.7);
    // Adjust details of repair to be inline with the label
    $pdf->Cell(10, $lineHeight, " ");
    
    // Define margins
    $leftMargin = 10; // Adjust as needed for your layout
    $rightMargin = 10;
    $pageWidth = $pdf->GetPageWidth();
    $availableWidth = $pageWidth - $leftMargin - $rightMargin - 20; // Available space for content
    
    // Fixed width for the label
    $labelWidth = 30; // Adjust label width as needed
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetX($leftMargin + 10);
    $pdf->Cell($labelWidth, $lineHeight, "Details of Repair:", 0, 0, 'L');

    // Switch back to normal font for the content
    $pdf->SetFont('Arial', '', 10);
    
    // Calculate the remaining width for the content
    $contentWidth = $availableWidth - $labelWidth;
    
    // Use MultiCell for the text content
    $x = $pdf->GetX(); // Save current X position (end of the label)
    $y = $pdf->GetY(); // Save current Y position
    $pdf->SetXY($x, $y); // Align content after the label
    $pdf->MultiCell($contentWidth, $lineHeight, utf8_decode($row['detail_report']), 0, 'L');
    
    // If there is additional content, position it correctly
    if (!empty($row['additional_text'])) {
        $pdf->SetX($leftMargin + 20 + $labelWidth); // Align additional content with the text
        $pdf->MultiCell($contentWidth, $lineHeight, utf8_decode($row['additional_text']), 0, 'L');
    }
    
    
    // Add final spacing
    // $pdf->Ln(5.7);
    // $pdf->Cell(0, 5.7, "");

    // $pdf->Ln( 5.7);
    // // Adjust comments to be inline with the label
    // $pdf->Cell(10, $lineHeight, " ");
    // $pdf->Cell(0, $lineHeight, "Customer's Comments:    " . $row['customer_comment']);

    $pdf->Ln(5.7); // Add a line break

// Adjust comments to be inline with the label
    $pdf->Cell(10, $lineHeight, " "); // Add some padding on the left
    $pdf->SetFont('Arial', 'B', 10); // Set font to bold for the label
    $pdf->Cell(40, $lineHeight, "Customer's Comments: ", 0, 0);

    $pdf->SetFont('Arial', '', 10); // Reset font to regular for the comment
    $pdf->Cell(0, $lineHeight, $row['customer_comment'], 0, 1);

    // $pdf->Ln(5.7);
    $pdf->Cell(10, $lineHeight, " ");

    // Set bold font for the label
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(60, $lineHeight, "Technician's Recommendations: ", 0, 0);

    // Switch back to normal font for the content
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, $lineHeight, $row['tech_recommendation'], 0, 1);

    

    $pdf->Ln($lineHeight * 2); // Add spacing before the line

    // Set the left margin for both labels
    $leftMargin = 20; // Set the desired left margin in mm
    
    // Set font for the labels (bold)
    $pdf->SetFont('Arial', 'B', 10);
    
    // Create the cell for "Customer's Signature & Printed Name" with a top border
    $signatureLabel = "             Client's Name              ";
    $pdf->SetX($leftMargin); // Move the cursor to the left margin
    $pdf->Cell($pdf->GetStringWidth($signatureLabel), $lineHeight, $signatureLabel, 'T', 0, 'L'); // 'T' for top border
    
    // Create the cell for "Service Technician" with a top border
    $techLabel = "        Technician          ";
    $pdf->SetX($leftMargin + 130); // Adjust this to position it at the correct distance
    $pdf->Cell($pdf->GetStringWidth($techLabel), $lineHeight, $techLabel, 'T', 0, 'L'); // 'T' for top border
    
    $pdf->SetFont('Arial', '', 10); // Reset to normal font for further text
    
    


    $pdf->Ln($lineHeight * 2);
    // $pdf->Cell(80, $lineHeight, "Time In: _______________");
    // $pdf->Cell(50, $lineHeight, "Time Out: _______________");
    
    // Define the width of each section
    $widthLeft = 110;  // Width for "Time In"
    $widthRight = 70; // Width for "Time Out"
    // $lineHeight = 10; // Adjust line height as needed
    
    // Time In section
    // $pdf->Cell($widthLeft / 2, $lineHeight, " ", 0, 0, 'R'); // Label on the left
    // $pdf->Cell($widthLeft /2.5, $lineHeight, 'Time In:  ' . $row['time_in'], 0, 0, 'C'); // Centered underline
    
    // // Time Out section
    // $pdf->Cell(30, $lineHeight, " ", 0, 0, 'L'); // Label on the left
    // $pdf->Cell(50, $lineHeight, 'Time Out:  ' . $row['time_out'], 0, 1, 'C'); // Centered underline and move to next line
    
    // Time In section
$pdf->Cell($widthLeft / 2, $lineHeight, " ", 0, 0, 'R'); // Label on the left
$pdf->SetFont('Arial', 'B', 10); // Set font to bold for "Time In:"
$pdf->Cell(30, $lineHeight, 'Time In: ', 0, 0, 'C'); // Bold "Time In:"
$pdf->SetFont('Arial', '', 10); // Reset font to regular
$pdf->Cell($widthLeft / 2.5 - 30, $lineHeight, $row['time_in'], 0, 0, 'C'); // Normal font for time value

// Time Out section
$pdf->Cell(30, $lineHeight, " ", 0, 0, 'L'); // Label on the left
$pdf->SetFont('Arial', 'B', 10); // Set font to bold for "Time Out:"
$pdf->Cell(30, $lineHeight, 'Time Out: ', 0, 0, 'C'); // Bold "Time Out:"
$pdf->SetFont('Arial', '', 10); // Reset font to regular
$pdf->Cell(50 - 30, $lineHeight, $row['time_out'], 0, 1, 'C'); // Normal font for time value


    // Output the PDF
    $pdf->Output('I', 'Field Service Report.pdf');
}
?>
