<?php
    include ('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Service Report</title>
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</head>
    <body>
        <!-- Modal content -->
    <div class="modal-contents">
                <!-- Close Button -->


                <h3 class="title">Field Service Report</h3>

                <div class="containers">
                <?php   
                    if(isset(($_POST['submit']))) {
                        $date=date("Y-m-d",strtotime($_POST["date"]));
                        $timeIn = mysqli_real_escape_string($con, $_POST["time_in"]);
                        $timeOut = mysqli_real_escape_string($con, $_POST["time_out"]);

                        $timeIn12hrFormat = date ('h:i A', strtotime($timeIn));
                        $timeOut12hrFormat = date ('h:i A', strtotime($timeOut));

                        $customerName = mysqli_real_escape_string($con, $_POST["customer_name"]);
                        $address = mysqli_real_escape_string($con, $_POST["address"]);
                        $telNo = mysqli_real_escape_string($con, $_POST["tel_no"]);
                    
                        $modelNo = mysqli_real_escape_string($con, $_POST["model_no"]);
                        $serailNo = mysqli_real_escape_string($con, $_POST["serial_no"]);
                        $meterReading = mysqli_real_escape_string($con, $_POST["meter_reading"]);

                        $customer_complaints = mysqli_real_escape_string($con, $_POST['customer_complaints']);
                        $detailRepair = mysqli_real_escape_string($con, $_POST["detail_repair"]);
                        $customerComment = mysqli_real_escape_string($con, $_POST["customer_comment"]);
                        $recommendation = mysqli_real_escape_string($con, $_POST["recommendation"]);

                        $firstStatement = $con->prepare("INSERT INTO fsr_tbl (date, time_in, time_out, customer_name, address, tel_no, model_no, serial_no, meter_reading, detail_report, customer_comment, tech_recommendation, customer_complaints) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                        if ($firstStatement === false) {
                            die("Prepare() failed: " .htmlspecialchars($con->error));
                        }

                        $firstStatement->bind_param("sssssssssssss", $date, $timeIn12hrFormat, $timeOut12hrFormat, $customerName, $address, $telNo, $modelNo, $serailNo, $meterReading, $detailRepair, $customerComment, $recommendation, $customer_complaints);

                        if($firstStatement->execute()) {
                            $id = $con->insert_id;

                            echo "<div id='alerta' class='alert alert-success'>FSR Added Successfully. <a id='linkClick'>Click </a> here to Print Invoice </div> ";
                        }else {
                            echo "<div class='alert alert-danger'>Error Inserting, Info :" . $firstStatement->error . " </div> ";
                        }

                        $firstStatement->close();
                    }
                ?>

                    <form action="index.php" method="post" autocomplete="off" id="myForms">
                        
                        <div class="technician">
                            <label class="tech_label">Technician: </label>
                            <p class="tech_name">JOHN DAVID S. CABAL</p>
                        </div>

                        <div class="display-flex">
                            <div class="left-side">
                                
                                <label for="sidate">Date</label><br>
                                <input type="date" id="date" name="date" 
                                    value="<?php echo date('Y-m-d'); ?>" 
                                    max="<?php echo date('Y-m-d'); ?>" 
                                    required>

                                <label for="time_in">Time In: </label>
                                <input type="time" id="time_in" name="time_in" required>

                                <label for="time_out">Time Out: </label>
                                <input type="time" id="time_out" name="time_out" required>
                                
                            </div>

                            <div class="right-side">
                                <label for="customer_name">Customer Name:</label>
                                <input type="text" id="customer_name" name="customer_name" required>
                                
                                <label for="address">Address: </label>
                                <input type="text" id="address" name="address" required>

                                <label for="tel_no">Tel. No.: </label>
                                <input type="text" inputmode="numeric" id="tel_no" name="tel_no" maxlength="11" default="0" required>
                            </div>

                            <div class="next">
                                <label for="model_no">Model No.:</label>
                                <input type="text" id="model_no" name="model_no" required>
                                
                                <label for="serial_no">Serial No.: </label>
                                <input type="text" id="serial_no" name="serial_no" required>

                                <label for="meter_reading">Meter Reading: </label>
                                <input type="number" id="meter_reading" name="meter_reading" required>
                            </div>
                        </div>
                        
                        <div class="detail_report">
                            <label for="customer_complaints">Customer complaints: </label>
                            <input type="text" id="customer_complaints"  maxlength="92" name="customer_complaints" required>

                            <label for="detail_repair">Details of Repair: </label>
                            <input type="text" id="detail_repair" name="detail_repair" required>

                            <label for="customer_comment">Customer's Comments: </label>
                            <input type="text" id="customer_comment" maxlength="92"  name="customer_comment" required>
                            
                            <label for="recommendation">Technician's Recommendations: </label>
                            <input type="text" id="recommendation" maxlength="92" name="recommendation" required>
                        </div>

                       
                        <div class="inputContainer">
                            <button class="submit" type="submit" name="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div> 
        </div>

        <script>

            $(document).ready(function() {
            var phpValue = "<?php echo $id; ?>";

            $('#linkClick').attr('href', 'print.php?id=' + phpValue);
            $('#linkClick').attr('target', '_blank');

            $("#linkClick").click(function(event) {
                // event.preventDefault();
                // No need for setTimeout here, as the link will open in a new tab

                setTimeout(function() {
                    $('#alerta').hide();
                    window.location.href = 'index.php';
                }, 1000);
            });
            });
            const input = document.getElementById("tel_no");

            input.addEventListener("input", function () {  
                this.value = this.value.replace(/[^0-9.-]/g, '');
            });

            // Get the filtered value after input:
            input.addEventListener("input", function () {
            const filteredValue = this.value;
            console.log(filteredValue); // This will log the filtered value
            // Use the filteredValue as needed, e.g., to send it to the server
        });
        </script>
    </body>
</html>