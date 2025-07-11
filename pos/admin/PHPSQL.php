<?php 
include_once("config/config.php");
require_once('partials/_head.php');

if (isset($_POST['qaa'])){
    header("location:orders_reports.php");
    $filename = "C:\Users\INTEL\Downloads\Christy's_Store_".date('Ymd').".csv";
    $file = fopen($filename, "r");

$row_count = 0;
while (($data = fgetcsv($file)) !== FALSE) {
    $row_count++;
    if ($row_count == 1) {
        continue; // skip the first row
    }
    $column1 = (int)$data[0];
    $column2 = $data[1];
    $column3 = $data[2];
    $column4 = $data[3];
    $column5 = $data[4];
    $column6 = $data[5];
    $column7 = $data[6];
    $time = $data[7];
    $date_format = 'Y-m-d H:i:s';
    $column8 = DateTime::createFromFormat($date_format, $time);

    // Use the MERGE statement to insert or update values based on id1
    $sql = "MERGE INTO transactions AS t
            USING (VALUES (?, ?, ?, ?, ?, ?, ?, ?)) AS s (id, id2, id3, id4, id5, id6, id7, id8)
                ON t.id = s.id
            WHEN MATCHED THEN
                UPDATE SET t.id2 = s.id2, t.id3 = s.id3, t.id4 = s.id4, t.id5 = s.id5, t.id6 = s.id6, t.id7 = s.id7, t.id8 = s.id8
            WHEN NOT MATCHED BY TARGET THEN
                INSERT (id, id2, id3, id4, id5, id6, id7, id8)
                VALUES (s.id, s.id2, s.id3, s.id4, s.id5, s.id6, s.id7, s.id8);";
    $params = array($column1, $column2, $column3 , $column4, $column5 , $column6, $column7 , $column8);
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    
    if (sqlsrv_execute($stmt) === TRUE) {
         $today = new DateTime();
         $startOfWeek = $today->modify('this week')->modify('Monday')->format('Y-m-d');
         $endOfWeek = $today->modify('this week +7 days')->format('Y-m-d');
         $startOfMonth = $today->format('Y-m-01');
         $endOfMonth = $today->modify('last day of this month')->format('Y-m-d');
         $startOfYear = $today->format('Y-01-01');
         $endOfYear = $today->format('Y-12-31');


         $select = "SELECT id, id2, id3, id4, id5, id6, id7, id8 FROM xample WHERE id8 >= ? AND id8 <= ? ";
         $params = array($startOfWeek, $endOfWeek);
         $stmt = sqlsrv_query($conn, $select, $params);

         while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
              $sql2 = "INSERT weekly (id, id2, id3, id4, id5, id6, id7, id8) VALUES (?,?,?,?,?,?,?,?)";
              $params = array($row['id'], $row['id2'], $row['id3'], $row['id4'], $row['id5'], $row['id6'], $row['id7'], $row['id8']);
              $insertStmt = sqlsrv_query($conn, $sql2, $params);

         }

         $select = "SELECT id, id2, id3, id4, id5, id6, id7, id8 FROM xample WHERE id8 >= ? AND id8 <= ? ";
         $params = array($startOfMonth, $endOfMonth);
         $stmt = sqlsrv_query($conn, $select, $params);

         while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
              $sql2 = "INSERT monthly (id, id2, id3, id4, id5, id6, id7, id8) VALUES (?,?,?,?,?,?,?,?)";
              $params = array($row['id'], $row['id2'], $row['id3'], $row['id4'], $row['id5'], $row['id6'], $row['id7'], $row['id8']);
              $insertStmt = sqlsrv_query($conn, $sql2, $params);

         }
         
         $select = "SELECT id, id2, id3, id4, id5, id6, id7, id8 FROM xample WHERE id8 >= ? AND id8 <= ? ";
         $params = array($startOfYear, $endOfYear);
         $stmt = sqlsrv_query($conn, $select, $params);

         while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
              $sql2 = "INSERT yearly (id, id2, id3, id4, id5, id6, id7, id8) VALUES (?,?,?,?,?,?,?,?)";
              $params = array($row['id'], $row['id2'], $row['id3'], $row['id4'], $row['id5'], $row['id6'], $row['id7'], $row['id8']);
              $insertStmt = sqlsrv_query($conn, $sql2, $params);

         }
         

    } else{
         echo "Error inserting row: " . print_r(sqlsrv_errors(), true);
         exit();
    }
    }
        fclose($file);
        exit();
    if (!$file) {
        die("Error opening file");
        exit();
    }
}


?>


<?php
if (isset($_POST['sql'])) {

    if( $conn ) {
        // Check if the file was uploaded successfully
    if ($_FILES['excel-file']['error'] !== UPLOAD_ERR_OK) {
      header("Location: orders_reports.php?error=File error upload.");
    }

    // Check if the uploaded file is a CSV file
    $fileExt = pathinfo($_FILES['excel-file']['name'], PATHINFO_EXTENSION);
    if ($fileExt !== 'csv') {
        header("Location: orders_reports.php?error=Only CSV files are allowed");
        exit();
    }

      // Check if the file is empty or not
      if ($_FILES['excel-file']['size'] == 0) {
        header("Location: orders_reports.php?error=Empty file uploaded.");
        exit();
    }


    $file = $_FILES['excel-file']['tmp_name'];
  
    try {
      // Open the CSV file
      $handle = fopen($file, "r");
  
      // Read the header row
      $header = fgetcsv($handle);
  
      // Check if the header row is in the expected format
     if (count($header) != 11) {
        header("Location: orders_reports.php?error=Invalid excel file format.");
        exit();
    }
      // Read the remaining rows
      while (($row = fgetcsv($handle)) !== FALSE) {
        $time = $row[10];
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $time);
       if ($datetime === false) {
        $dateTime = DateTime::createFromFormat('d/m/Y H:i', $time);
        $column8 = $dateTime->format('Y-m-d H:i:s');
        } else {
            $column8 = $datetime->format('Y-m-d H:i:s');
        }
        $sql = "MERGE INTO transactions AS t
        USING (VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)) AS s (transaction_id, transaction_code, buyer, product_name, product_category, product_brand, product_price, product_quantity, product_total, staff, date_bought)
        ON t.transaction_id = s.transaction_id
        WHEN MATCHED THEN
        UPDATE SET t.transaction_code = s.transaction_code, t.buyer = s.buyer, t.product_name = s.product_name, t.product_category = s.product_category, t.product_brand = s.product_brand, t.product_price = s.product_price, t.product_quantity = s.product_quantity, t.product_total = s.product_total, t.staff = s.staff, t.date_bought = s.date_bought
        WHEN NOT MATCHED BY TARGET THEN
        INSERT (transaction_id, transaction_code, buyer, product_name, product_category, product_brand, product_price, product_quantity, product_total, staff, date_bought)
        VALUES (s.transaction_id, s.transaction_code, s.buyer, s.product_name, s.product_category, s.product_brand, s.product_price, s.product_quantity, s.product_total, s.staff, s.date_bought);";
        $params = array($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $column8);

        $insertStmt = sqlsrv_query($conn, $sql, $params);
  
        if ($insertStmt === false) {
          header("Location: orders_reports.php?error=".print_r(sqlsrv_errors(), true));
        }
      }
  
      // Close the file
      fclose($handle);
      header("Location: orders_reports.php?success=Excel file transported to database successfully");


    } catch (Exception $e) {
      header("Location: orders_reports.php?error=Error while reading Excel file");
    }
   
  } else{
      header("Location: orders_reports.php?error=SQL Database is not connected.");
        die( print_r( sqlsrv_errors(), true));
  }
    
  }
?>